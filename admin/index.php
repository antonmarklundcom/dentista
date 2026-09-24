<?php
/*
 * /admin/ — private lead panel.
 * Password: bcrypt hash in config.php → 'admin_password_hash' (empty = panel off).
 * Reads leads/leads-YYYY-MM.csv and leads/wa-clicks-YYYY-MM.csv. Can resend a
 * lead to VenderCRM when the first send failed.
 */
declare(strict_types=1);
require_once dirname(__DIR__) . '/inc/app.php';
require_once dirname(__DIR__) . '/inc/crm.php';

session_name('dp_admin');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Strict', 'secure' => !empty($_SERVER['HTTPS']), 'path' => '/admin/']);
session_start();

header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
header('X-Frame-Options: DENY');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; form-action 'self'; frame-ancestors 'none'");

$hash = (string)cfg('admin_password_hash');
$csrf = $_SESSION['csrf'] ??= bin2hex(random_bytes(16));
$post = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
$okCsrf = $post && hash_equals($csrf, (string)($_POST['csrf'] ?? ''));
$LEADS = ROOT . '/leads';

/* ---------- login throttle (per IP, file based) ---------- */
function throttle_file(): string { return ROOT . '/leads/.admin-throttle-' . substr(hash('sha256', (string)($_SERVER['REMOTE_ADDR'] ?? '')), 0, 16); }
function throttle_count(): int
{
    $f = throttle_file();
    if (!is_file($f)) return 0;
    [$n, $t] = array_map('intval', explode('|', (string)file_get_contents($f)) + [0, 0]);
    return $t > time() - 900 ? $n : 0;
}
function throttle_hit(): void { @file_put_contents(throttle_file(), (throttle_count() + 1) . '|' . time()); }

$error = '';
if ($post && ($_POST['action'] ?? '') === 'login') {
    if (!$okCsrf) $error = 'La sesión expiró. Probá de nuevo.';
    elseif (throttle_count() >= 5) $error = 'Demasiados intentos. Esperá 15 minutos.';
    elseif ($hash !== '' && password_verify((string)($_POST['password'] ?? ''), $hash)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = time();
        @unlink(throttle_file());
        header('Location: /admin/', true, 303); exit;
    } else { throttle_hit(); $error = 'Clave incorrecta.'; }
}
if ($post && ($_POST['action'] ?? '') === 'logout' && $okCsrf) {
    $_SESSION = []; session_destroy();
    header('Location: /admin/', true, 303); exit;
}
$authed = $hash !== '' && !empty($_SESSION['admin']) && $_SESSION['admin'] > time() - 8 * 3600;

/* ---------- data helpers ---------- */
function csv_rows(string $file): array
{
    if (!is_file($file) || !($fh = fopen($file, 'r'))) return [];
    $head = fgetcsv($fh) ?: [];
    $rows = [];
    while (($r = fgetcsv($fh)) !== false) {
        if (count($r) === count($head)) $rows[] = array_combine($head, $r);
    }
    fclose($fh);
    return $rows;
}
function months(string $dir): array
{
    $m = [];
    foreach (glob($dir . '/{leads,wa-clicks}-*.csv', GLOB_BRACE) as $f) {
        if (preg_match('/-(\d{4}-\d{2})\.csv$/', $f, $x)) $m[$x[1]] = true;
    }
    krsort($m);
    return array_keys($m) ?: [date('Y-m')];
}

$month = preg_match('/^\d{4}-\d{2}$/', (string)($_GET['m'] ?? '')) ? $_GET['m'] : (months($LEADS)[0] ?? date('Y-m'));
$leadFile = "$LEADS/leads-$month.csv";

/* ---------- actions that need login ---------- */
$flash = '';
if ($authed && ($_GET['download'] ?? '') !== '') {
    $f = ($_GET['download'] === 'wa' ? "$LEADS/wa-clicks-$month.csv" : $leadFile);
    if (is_file($f)) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . basename($f) . '"');
        readfile($f); exit;
    }
}
if ($authed && $post && ($_POST['action'] ?? '') === 'resend' && $okCsrf) {
    $ref = (string)($_POST['ref'] ?? '');
    $rows = csv_rows($leadFile);
    foreach ($rows as $i => $r) {
        if ($r['ref'] !== $ref) continue;
        $msg = ($r['type'] === 'partner' ? 'Odontólogo quiere sumarse a la red.' : 'Paciente pide turno.')
             . "\nTratamiento: {$r['service']}\nZona: {$r['zone']}\nUrgencia: {$r['urgency']}\nRef: {$r['ref']} (reenviado desde el panel)";
        [$st, $body] = vcrm_send([
            'phone' => $r['phone'], 'name' => $r['name'], 'email' => $r['email'] ?: null, 'message' => $msg,
            'source' => $r['type'] . ':' . ($r['source'] ?: 'web'),
            'utm_source' => $r['utm_source'] ?: null, 'utm_campaign' => $r['utm_campaign'] ?: null,
            'idempotency_key' => hash('sha256', 'resend|' . $r['ref']),
            'fields' => ['ref' => $r['ref'], 'tipo' => $r['type'], 'nivel' => $r['tier']],
        ]);
        $rows[$i]['crm_status'] = vcrm_key() === '' ? 'off' : ($st === 0 ? 'error' : (string)$st);
        if ($fh = fopen($leadFile, 'c+')) {
            flock($fh, LOCK_EX); ftruncate($fh, 0); rewind($fh);
            fputcsv($fh, array_keys($rows[0]));
            foreach ($rows as $row) fputcsv($fh, $row);
            flock($fh, LOCK_UN); fclose($fh);
        }
        $flash = in_array($st, [200, 201], true) ? "Lead $ref enviado al CRM." : "El CRM respondió $st: " . mb_substr($body, 0, 160);
        break;
    }
}

/* ---------- view data ---------- */
$tab  = ($_GET['tab'] ?? '') === 'wa' ? 'wa' : 'leads';
$type = in_array($_GET['type'] ?? '', ['paciente', 'partner'], true) ? $_GET['type'] : '';
$q    = mb_strtolower(trim((string)($_GET['q'] ?? '')));

$leads = array_reverse(csv_rows($leadFile));
$clicks = csv_rows("$LEADS/wa-clicks-$month.csv");
$stats = [
    'total'    => count($leads),
    'paciente' => count(array_filter($leads, fn($r) => $r['type'] === 'paciente')),
    'partner'  => count(array_filter($leads, fn($r) => $r['type'] === 'partner')),
    'crmfail'  => count(array_filter($leads, fn($r) => !in_array($r['crm_status'] ?? '', ['200', '201'], true))),
    'clicks'   => count($clicks),
    'value'    => array_sum(array_map(fn($r) => (int)($r['value_pyg'] ?? 0), $leads)),
];
$shown = array_filter($leads, function ($r) use ($type, $q) {
    if ($type !== '' && $r['type'] !== $type) return false;
    return $q === '' || str_contains(mb_strtolower(implode(' ', $r)), $q);
});
$by = function (array $rows, string $k): array {
    $c = [];
    foreach ($rows as $r) { $v = $r[$k] !== '' ? $r[$k] : '—'; $c[$v] = ($c[$v] ?? 0) + 1; }
    arsort($c);
    return $c;
};
$qs = fn(array $over) => '?' . http_build_query(array_filter(array_merge(['m' => $month, 'tab' => $tab, 'type' => $type, 'q' => $q], $over), fn($v) => $v !== ''));
?><!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Panel · Dentista.com.py</title>
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<style>
:root{--bg:#F7F4ED;--ink:#14241E;--mut:#56645E;--line:rgba(20,36,30,.14);--acc:#A44C29;--ok:#1F6B45;--bad:#A3261B;--card:#fff}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font:15px/1.5 system-ui,-apple-system,"Segoe UI",sans-serif}
a{color:var(--acc)}header{display:flex;gap:1rem;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--line);background:#fff;flex-wrap:wrap}
header b{font-size:17px}header b span{color:var(--acc)}main{padding:20px;max-width:1400px;margin:0 auto}
.tabs,.filters{display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;margin:0 0 16px}
.tabs a,.btn{display:inline-flex;align-items:center;min-height:38px;padding:0 14px;border-radius:999px;border:1px solid var(--line);background:#fff;color:var(--ink);text-decoration:none;font:inherit;cursor:pointer}
.tabs a[aria-current]{background:var(--ink);color:#fff;border-color:var(--ink)}.btn--acc{background:var(--acc);border-color:var(--acc);color:#fff}
select,input[type=search],input[type=password]{min-height:38px;padding:0 10px;border:1px solid var(--line);border-radius:8px;font:inherit;background:#fff}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px;margin-bottom:18px}
.card{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:12px 14px}.card small{color:var(--mut);display:block}.card strong{font-size:22px}
.wrap{overflow-x:auto;background:#fff;border:1px solid var(--line);border-radius:12px}table{border-collapse:collapse;width:100%;font-size:13.5px}
th,td{text-align:left;padding:8px 10px;border-bottom:1px solid var(--line);vertical-align:top;white-space:nowrap}th{background:#FBFAF6;font-weight:600;position:sticky;top:0}
td.msg{white-space:normal;min-width:180px}.pill{display:inline-block;padding:1px 8px;border-radius:999px;font-size:12px;background:#EFEADF}
.ok{color:var(--ok);font-weight:600}.bad{color:var(--bad);font-weight:600}.flash{background:#fff;border:1px solid var(--line);border-left:0;padding:10px 14px;border-radius:10px;margin-bottom:14px}
.login{max-width:360px;margin:12vh auto;background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px}.login h1{margin:0 0 6px;font-size:22px}
.login label{display:block;margin:16px 0 6px;font-weight:600}.login input{width:100%}.err{color:var(--bad)}.two{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:800px){.two{grid-template-columns:1fr}}
</style>
</head>
<body>
<?php if ($hash === ''): ?>
<div class="login"><h1>Panel desactivado</h1>
<p>Para activarlo, generá un hash de tu clave y ponelo en <code>config.php</code> del servidor como <code>'admin_password_hash'</code>:</p>
<pre style="white-space:pre-wrap;background:#F7F4ED;padding:10px;border-radius:8px">php -r "echo password_hash('TU-CLAVE', PASSWORD_DEFAULT), PHP_EOL;"</pre></div>
<?php elseif (!$authed): ?>
<form class="login" method="post" action="/admin/">
  <h1>Dentista<span style="color:var(--acc)">.com.py</span></h1>
  <p style="color:var(--mut);margin:0">Panel de leads</p>
  <?php if ($error): ?><p class="err" role="alert"><?= e($error) ?></p><?php endif; ?>
  <label for="pw">Clave</label>
  <input id="pw" type="password" name="password" required autocomplete="current-password" autofocus>
  <input type="hidden" name="action" value="login"><input type="hidden" name="csrf" value="<?= e($csrf) ?>">
  <p><button class="btn btn--acc" type="submit" style="width:100%;justify-content:center">Entrar</button></p>
</form>
<?php else: ?>
<header>
  <b>Dentista<span>.com.py</span> · Panel</b>
  <form method="post" action="/admin/" style="display:flex;gap:.5rem;align-items:center">
    <span style="color:var(--mut)">CRM: <?= vcrm_key() !== '' ? '<span class="ok">conectado</span>' : '<span class="bad">sin clave</span>' ?></span>
    <input type="hidden" name="action" value="logout"><input type="hidden" name="csrf" value="<?= e($csrf) ?>">
    <button class="btn" type="submit">Salir</button>
  </form>
</header>
<main>
  <?php if ($flash): ?><p class="flash" role="status"><?= e($flash) ?></p><?php endif; ?>
  <div class="cards">
    <div class="card"><small>Leads <?= e($month) ?></small><strong><?= $stats['total'] ?></strong></div>
    <div class="card"><small>Pacientes</small><strong><?= $stats['paciente'] ?></strong></div>
    <div class="card"><small>Odontólogos</small><strong><?= $stats['partner'] ?></strong></div>
    <div class="card"><small>Clics WhatsApp</small><strong><?= $stats['clicks'] ?></strong></div>
    <div class="card"><small>Valor estimado</small><strong><?= number_format($stats['value'], 0, ',', '.') ?> ₲</strong></div>
    <div class="card"><small>Sin llegar al CRM</small><strong class="<?= $stats['crmfail'] ? 'bad' : 'ok' ?>"><?= $stats['crmfail'] ?></strong></div>
  </div>

  <nav class="tabs" aria-label="Vistas">
    <a href="<?= e($qs(['tab' => ''])) ?>"<?= $tab === 'leads' ? ' aria-current="page"' : '' ?>>Leads</a>
    <a href="<?= e($qs(['tab' => 'wa'])) ?>"<?= $tab === 'wa' ? ' aria-current="page"' : '' ?>>Clics de WhatsApp</a>
    <a class="btn" href="<?= e($qs(['download' => $tab === 'wa' ? 'wa' : 'leads'])) ?>">Descargar CSV</a>
  </nav>

  <form class="filters" method="get" action="/admin/">
    <input type="hidden" name="tab" value="<?= e($tab === 'wa' ? 'wa' : '') ?>">
    <label>Mes <select name="m"><?php foreach (months($LEADS) as $mm): ?><option<?= $mm === $month ? ' selected' : '' ?>><?= e($mm) ?></option><?php endforeach; ?></select></label>
    <?php if ($tab === 'leads'): ?>
    <label>Tipo <select name="type"><option value="">Todos</option><option value="paciente"<?= $type === 'paciente' ? ' selected' : '' ?>>Pacientes</option><option value="partner"<?= $type === 'partner' ? ' selected' : '' ?>>Odontólogos</option></select></label>
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar nombre, teléfono, ref…" aria-label="Buscar">
    <?php endif; ?>
    <button class="btn" type="submit">Ver</button>
  </form>

  <?php if ($tab === 'leads'): ?>
  <div class="wrap"><table>
    <thead><tr><th>Fecha</th><th>Ref</th><th>Tipo</th><th>Nombre</th><th>WhatsApp</th><th>Tratamiento / especialidades</th><th>Zona</th><th>Cuándo</th><th>Nivel</th><th>Origen</th><th>UTM</th><th>CRM</th></tr></thead>
    <tbody>
    <?php if (!$shown): ?><tr><td colspan="12" style="color:var(--mut)">Sin leads para este filtro.</td></tr><?php endif; ?>
    <?php foreach ($shown as $r): $ok = in_array($r['crm_status'] ?? '', ['200', '201'], true); ?>
      <tr>
        <td><?= e(substr($r['date'], 5, 11)) ?></td>
        <td><?= e($r['ref']) ?></td>
        <td><span class="pill"><?= $r['type'] === 'partner' ? 'odontólogo' : 'paciente' ?></span></td>
        <td><?= e($r['name']) ?><?= $r['clinic'] ? '<br><small>' . e($r['clinic']) . '</small>' : '' ?></td>
        <td><a href="https://wa.me/<?= e(ltrim($r['phone'], '+')) ?>" target="_blank" rel="noopener"><?= e($r['phone']) ?></a></td>
        <td class="msg"><?= e(str_replace('|', ', ', $r['service'])) ?></td>
        <td><?= e($r['zone']) ?></td>
        <td><?= e($r['urgency']) ?></td>
        <td><?= e($r['tier']) ?></td>
        <td><?= e($r['source']) ?></td>
        <td><?= e(trim($r['utm_source'] . ' ' . $r['utm_campaign'])) ?></td>
        <td><?php if ($ok): ?><span class="ok">✓</span><?php else: ?>
          <form method="post" action="<?= e($qs([])) ?>" style="margin:0"><span class="bad"><?= e($r['crm_status'] ?: '—') ?></span>
          <input type="hidden" name="action" value="resend"><input type="hidden" name="ref" value="<?= e($r['ref']) ?>"><input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <button class="btn" type="submit" style="min-height:28px;padding:0 10px;margin-left:6px">Reenviar</button></form>
        <?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php else: ?>
  <div class="two">
    <div class="wrap"><table><thead><tr><th>Tratamiento</th><th>Clics</th></tr></thead><tbody>
      <?php foreach ($by($clicks, 'service') as $k => $n): ?><tr><td><?= e($k) ?></td><td><?= $n ?></td></tr><?php endforeach; ?>
      <?php if (!$clicks): ?><tr><td colspan="2" style="color:var(--mut)">Sin clics este mes.</td></tr><?php endif; ?>
    </tbody></table></div>
    <div class="wrap"><table><thead><tr><th>Página</th><th>Clics</th></tr></thead><tbody>
      <?php foreach (array_slice($by($clicks, 'page'), 0, 30, true) as $k => $n): ?><tr><td><?= e($k) ?></td><td><?= $n ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
    <div class="wrap"><table><thead><tr><th>Botón</th><th>Clics</th></tr></thead><tbody>
      <?php foreach ($by($clicks, 'button') as $k => $n): ?><tr><td><?= e($k) ?></td><td><?= $n ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
    <div class="wrap"><table><thead><tr><th>Origen (utm_source)</th><th>Clics</th></tr></thead><tbody>
      <?php foreach ($by($clicks, 'utm_source') as $k => $n): ?><tr><td><?= e($k) ?></td><td><?= $n ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
  </div>
  <?php endif; ?>
</main>
<?php endif; ?>
</body>
</html>
