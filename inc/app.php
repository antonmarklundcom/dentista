<?php
declare(strict_types=1);

defined('ROOT') || define('ROOT', dirname(__DIR__));

$GLOBALS['CFG'] = array_merge(
    require ROOT . '/config.example.php',
    is_file(ROOT . '/config.php') ? (require ROOT . '/config.php') : []
);

function cfg(string $k, $default = null) { return $GLOBALS['CFG'][$k] ?? $default; }

function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

function base_url(): string
{
    $u = cfg('site_url') ?: ('https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
    return rtrim($u, '/');
}

function abs_url(string $path): string { return base_url() . $path; }

function wa_link(string $text): string
{
    return 'https://wa.me/' . preg_replace('/\D/', '', (string)cfg('whatsapp')) . '?text=' . rawurlencode($text);
}

function site(): array
{
    static $s;
    return $s ??= require ROOT . '/content/site.php';
}

/** Loads content/<dir>/*.php records, keyed by slug and sorted by 'order'. */
function records(string $dir): array
{
    static $cache = [];
    if (isset($cache[$dir])) return $cache[$dir];
    $out = [];
    foreach (glob(ROOT . "/content/$dir/*.php") as $f) {
        $r = require $f;
        $r['slug'] = basename($f, '.php');
        $out[$r['slug']] = $r;
    }
    uasort($out, fn($a, $b) => ($a['order'] ?? 99) <=> ($b['order'] ?? 99));
    return $cache[$dir] = $out;
}

require_once __DIR__ . '/parts.php';

function services(): array { return records('servicios'); }
function guides(): array   { return records('guias'); }
function zones(): array
{
    static $z;
    return $z ??= require ROOT . '/content/zonas.php';
}

/** Lead value for Ads conversion value, in PYG. */
function lead_value(?string $service, string $type = 'paciente'): int
{
    $s = site();
    if ($type === 'partner') return $s['leadValues']['partner'];
    $tier = services()[$service]['tier'] ?? 'C';
    return $s['leadValues'][$tier] ?? $s['leadValues']['C'];
}

/** Renders a full page: $page = [title, description, canonical, noindex, schema[], body(callable), bodyClass, lp]. */
function render(array $page): void
{
    extract(['page' => $page]);
    require ROOT . '/inc/layout.php';
}

function not_found(): void
{
    http_response_code(404);
    render([
        'title' => 'Página no encontrada — Dentista.com.py',
        'description' => 'La página que buscás no existe. Volvé al inicio o escribinos por WhatsApp y coordinamos tu consulta odontológica.',
        'noindex' => true,
        'body' => function () { ?>
<section class="section"><div class="container narrow">
  <p class="eyebrow">Error 404</p>
  <h1>Esta página no existe</h1>
  <p class="lead">Puede que el enlace esté mal escrito o que la página se haya movido.</p>
  <p class="btn-row"><a class="btn btn--primary" href="/">Ir al inicio</a>
  <a class="btn btn--ghost" href="/servicios/">Ver tratamientos</a></p>
</div></section>
<?php }]);
}
