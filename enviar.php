<?php
/*
 * Recibe los formularios (paciente y odontólogo). Por cada lead:
 *   1. lo guarda en leads/leads-YYYY-MM.csv (siempre, aunque falle lo demás)
 *   2. manda un email a cfg('email_to') si está configurado
 *   3. hace POST JSON a cfg('webhook_url') si está configurado (VenderCRM, Zapier…)
 * y redirige a /gracias/, donde se dispara la conversión de Google Ads una sola vez.
 */
require_once __DIR__ . '/inc/app.php';
session_start();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { header('Location: /', true, 303); exit; }

$in = fn(string $k, int $max = 200) => trim(mb_substr(strip_tags((string)($_POST[$k] ?? '')), 0, $max));

$type = $in('type') === 'partner' ? 'partner' : 'paciente';
$back = $type === 'partner' ? '/para-odontologos/' : '/contacto/';

// Honeypot y envíos demasiado rápidos: se descartan en silencio.
if ($in('website') !== '') { header('Location: /gracias/', true, 303); exit; }

// Límite simple: 5 envíos cada 10 minutos por sesión.
$_SESSION['sends'] = array_filter($_SESSION['sends'] ?? [], fn($t) => $t > time() - 600);
if (count($_SESSION['sends']) >= 5) { header('Location: ' . $back . '?error=limite#pedir-turno', true, 303); exit; }

$phone = preg_replace('/[^\d+]/', '', $in('phone', 30));
$name  = $in('name', 80);
if ($name === '' || strlen(preg_replace('/\D/', '', $phone)) < 6 || empty($_POST['consent'])) {
    header('Location: ' . $back . '?error=datos#pedir-turno', true, 303); exit;
}

$services = services();
$service  = $in('service', 40);
if ($type === 'paciente' && !isset($services[$service])) $service = 'revision';
$tier  = $type === 'partner' ? 'P' : ($services[$service]['tier'] ?? 'C');
$value = lead_value($service, $type);
$ref   = strtoupper(($type === 'partner' ? 'OD-' : 'DP-') . substr(bin2hex(random_bytes(3)), 0, 5));

$lead = [
    'ref'          => $ref,
    'date'         => date('Y-m-d H:i:s'),
    'type'         => $type,
    'name'         => $name,
    'phone'        => $phone,
    'email'        => filter_var($in('email', 120), FILTER_VALIDATE_EMAIL) ?: '',
    'service'      => $type === 'partner' ? implode('|', array_map('strip_tags', array_slice((array)($_POST['specialties'] ?? []), 0, 12))) : $service,
    'zone'         => $in('zone', 40),
    'urgency'      => $in('urgency', 20),
    'tier'         => $tier,
    'value_pyg'    => $value,
    'clinic'       => $in('clinic', 120),
    'license'      => $in('license', 40),
    'capacity'     => $in('capacity', 40),
    'message'      => $in('message', 1000),
    'source'       => $in('source', 60),
    'page'         => $in('page', 120),
    'gclid'        => $in('gclid', 200),
    'gbraid'       => $in('gbraid', 200),
    'wbraid'       => $in('wbraid', 200),
    'utm_source'   => $in('utm_source', 80),
    'utm_medium'   => $in('utm_medium', 80),
    'utm_campaign' => $in('utm_campaign', 120),
    'utm_term'     => $in('utm_term', 120),
    'utm_content'  => $in('utm_content', 120),
    'ip'           => $_SERVER['REMOTE_ADDR'] ?? '',
];

// 1. CSV (una fila por lead, un archivo por mes).
$dir = ROOT . '/leads';
if (!is_dir($dir)) @mkdir($dir, 0750, true);
$file = $dir . '/leads-' . date('Y-m') . '.csv';
$new  = !is_file($file);
if ($fh = @fopen($file, 'a')) {
    flock($fh, LOCK_EX);
    if ($new) fputcsv($fh, array_keys($lead));
    // Evita fórmulas al abrir el CSV en Excel/Sheets.
    fputcsv($fh, array_map(fn($v) => preg_match('/^[=+\-@]/', (string)$v) ? "'" . $v : $v, $lead));
    flock($fh, LOCK_UN);
    fclose($fh);
}

// 2. Email.
if ($to = cfg('email_to')) {
    $label = $type === 'partner' ? 'ODONTÓLOGO' : 'PACIENTE ' . $tier;
    $subject = "[$label] $name — " . ($lead['service'] ?: '-') . " — $ref";
    $body = '';
    foreach ($lead as $k => $v) if ($v !== '' && $v !== null) $body .= str_pad($k, 14) . ": $v\n";
    $body .= "\nWhatsApp: https://wa.me/" . ltrim(preg_replace('/^0/', '595', $phone), '+') . "\n";
    @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", [
        'From: Dentista.com.py <' . cfg('email_from') . '>',
        'Content-Type: text/plain; charset=UTF-8',
    ]));
}

// 3. Webhook / CRM.
if (($url = cfg('webhook_url')) && function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($lead + ['site' => 'dentista.com.py'], JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => array_filter(['Content-Type: application/json', cfg('webhook_key') ? 'X-API-Key: ' . cfg('webhook_key') : null]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 6,
    ]);
    curl_exec($ch);
    curl_close($ch);
}

$_SESSION['sends'][] = time();
$_SESSION['conversion'] = ['ref' => $ref, 'type' => $type, 'service' => $service, 'value' => $value, 'phone' => $phone, 'email' => $lead['email'], 'name' => $name];
header('Location: /gracias/', true, 303);
