<?php
/*
 * Recibe los formularios (paciente y odontólogo). Por cada lead:
 *   1. lo manda a VenderCRM (contacto + negocio en el pipeline del sitio)
 *   2. lo guarda en leads/leads-YYYY-MM.csv con el resultado del CRM (respaldo, panel /admin/)
 *   3. manda un email a cfg('email_to') si está configurado
 * y redirige a /gracias/. Si el CRM falla, el visitante no se entera: queda en el CSV.
 */
require_once __DIR__ . '/inc/app.php';
require_once __DIR__ . '/inc/crm.php';
session_start();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { header('Location: /', true, 303); exit; }

$in = fn(string $k, int $max = 200) => trim(mb_substr(strip_tags((string)($_POST[$k] ?? '')), 0, $max));

$type = $in('type') === 'partner' ? 'partner' : 'paciente';
$back = $type === 'partner' ? '/para-odontologos/' : '/contacto/';

// Honeypot: el bot ve "éxito" y no se manda nada.
if ($in('website') !== '') { header('Location: /gracias/', true, 303); exit; }

// Límite simple: 5 envíos cada 10 minutos por sesión.
$_SESSION['sends'] = array_filter($_SESSION['sends'] ?? [], fn($t) => $t > time() - 600);
if (count($_SESSION['sends']) >= 5) { header('Location: ' . $back . '?error=limite#pedir-turno', true, 303); exit; }

$phone = phone_e164($in('phone', 30));
$name  = $in('name', 80);
if ($name === '' || strlen(preg_replace('/\D/', '', $phone)) < 9 || empty($_POST['consent'])) {
    header('Location: ' . $back . '?error=datos#pedir-turno', true, 303); exit;
}

$services = services();
$zones    = zones();
$service  = $in('service', 40);
if ($type === 'paciente' && !isset($services[$service])) $service = 'revision';
$tier  = $type === 'partner' ? 'P' : ($services[$service]['tier'] ?? 'C');
$value = lead_value($service, $type);
$ref   = strtoupper(($type === 'partner' ? 'OD-' : 'DP-') . substr(bin2hex(random_bytes(3)), 0, 5));
$attr  = vcrm_attribution();
$zone  = $in('zone', 40);
$urg   = $in('urgency', 20);
$specs = array_slice(array_map(fn($s) => mb_substr(strip_tags((string)$s), 0, 40), (array)($_POST['specialties'] ?? [])), 0, 12);

$serviceName = $type === 'partner' ? implode(', ', $specs) : ($services[$service]['name'] ?? 'Revisión general');
$zoneName    = $zone === 'asuncion' ? 'Asunción' : ($zones[$zone]['name'] ?? ($zone === 'otra' ? 'Otra ciudad' : $zone));
$urgName     = site()['urgency'][$urg] ?? '';
$utm = fn(string $k) => $in($k, 120) ?: ($attr[$k] ?? '');

$lead = [
    'ref'          => $ref,
    'date'         => date('Y-m-d H:i:s'),
    'type'         => $type,
    'name'         => $name,
    'phone'        => $phone,
    'email'        => filter_var($in('email', 120), FILTER_VALIDATE_EMAIL) ?: '',
    'service'      => $type === 'partner' ? implode('|', $specs) : $service,
    'zone'         => $zone,
    'urgency'      => $urg,
    'tier'         => $tier,
    'value_pyg'    => $value,
    'clinic'       => $in('clinic', 120),
    'license'      => $in('license', 40),
    'capacity'     => $in('capacity', 40),
    'message'      => $in('message', 1000),
    'source'       => $in('source', 60),
    'page'         => $in('page', 120),
    'utm_source'   => $utm('utm_source'),
    'utm_medium'   => $utm('utm_medium'),
    'utm_campaign' => $utm('utm_campaign'),
    'utm_term'     => $utm('utm_term'),
    'utm_content'  => $utm('utm_content'),
    'crm_status'   => '',
    'ip'           => $_SERVER['REMOTE_ADDR'] ?? '',
];

// 1. VenderCRM.
$summary = $type === 'partner'
    ? "Odontólogo quiere sumarse a la red.\nConsultorio: {$lead['clinic']}\nZona: $zoneName\nEspecialidades: $serviceName\nPacientes nuevos/mes: {$lead['capacity']}\nMatrícula: " . ($lead['license'] ?: '-')
    : "Paciente pide turno.\nTratamiento: $serviceName\nZona: $zoneName\nPara cuándo: $urgName";
if ($lead['message'] !== '') $summary .= "\nMensaje: " . $lead['message'];
$summary .= "\nRef: $ref";

[$crmStatus, $crmBody] = vcrm_send([
    'phone'           => $phone,
    'name'            => $name,
    'email'           => $lead['email'] ?: null,
    'message'         => $summary,
    'source'          => ($type === 'partner' ? 'partner' : 'paciente') . ':' . ($lead['source'] ?: 'web'),
    'page_url'        => $attr['landing_page'] ?? abs_url($lead['page'] ?: '/'),
    'referrer'        => $attr['referrer'] ?? null,
    'utm_source'      => $lead['utm_source'] ?: null,
    'utm_medium'      => $lead['utm_medium'] ?: null,
    'utm_campaign'    => $lead['utm_campaign'] ?: null,
    'utm_term'        => $lead['utm_term'] ?: null,
    'utm_content'     => $lead['utm_content'] ?: null,
    'fbclid'          => $attr['fbclid'] ?? null,
    'idempotency_key' => hash('sha256', $type . '|' . $phone . '|' . gmdate('Y-m-d-H')),
    'fields'          => array_filter([
        'tipo'        => $type,
        'ref'         => $ref,
        'tratamiento' => $serviceName,
        'zona'        => $zoneName,
        'urgencia'    => $urgName,
        'nivel'       => $tier,
        'valor_pyg'   => (string)$value,
        'consultorio' => $lead['clinic'],
        'matricula'   => $lead['license'],
        'capacidad'   => $lead['capacity'],
        'pagina'      => $lead['page'],
    ], fn($v) => $v !== ''),
]);
$lead['crm_status'] = vcrm_key() === '' ? 'off' : ($crmStatus === 0 ? 'error' : (string)$crmStatus);

// 2. CSV (una fila por lead, un archivo por mes).
$dir = ROOT . '/leads';
if (!is_dir($dir)) @mkdir($dir, 0750, true);
$file = $dir . '/leads-' . date('Y-m') . '.csv';
$new  = !is_file($file);
if ($fh = @fopen($file, 'a')) {
    flock($fh, LOCK_EX);
    if ($new) fputcsv($fh, array_keys($lead));
    // Evita fórmulas al abrir el CSV en Excel/Sheets.
    fputcsv($fh, array_map(fn($v) => preg_match('/^(=|@|[+-](?![0-9]))/', (string)$v) ? "'" . $v : $v, $lead));
    flock($fh, LOCK_UN);
    fclose($fh);
}

// 3. Email.
if ($to = cfg('email_to')) {
    $label = $type === 'partner' ? 'ODONTÓLOGO' : 'PACIENTE ' . $tier;
    $subject = "[$label] $name — $serviceName — $ref";
    $body = $summary . "\n\nWhatsApp: https://wa.me/" . ltrim($phone, '+') . "\nCRM: " . $lead['crm_status'] . "\n";
    @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", [
        'From: Dentista.com.py <' . cfg('email_from') . '>',
        'Content-Type: text/plain; charset=UTF-8',
    ]));
}

$_SESSION['sends'][] = time();
$_SESSION['thanks'] = ['ref' => $ref, 'type' => $type, 'service' => $service, 'name' => $name];
header('Location: /gracias/', true, 303);
