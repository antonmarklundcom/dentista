<?php
/**
 * dentista.com.py — formulärhandler (BUILD-SPEC §8).
 *
 * Webbläsaren pratar ALDRIG med VenderCRM. Formuläret postar hit, och den här
 * filen postar vidare med X-Api-Key. Nyckeln finns aldrig i sidkällan.
 *
 * Stage 1: VENDERCRM_URL och VENDERCRM_API_KEY saknas ännu. Handlern är färdig
 * och loggar varje submit till leads.log tills nyckeln finns.
 * Ingen mailto:, ingen Formspree, ingen tredjepartsendpoint.
 */
declare(strict_types=1);

const THANK_YOU   = '/gracias.html';
const ERROR_BACK  = '/index.html?error=telefono#contacto';
const SITE_SLUG   = 'dentista-com-py';
const LEAD_LOG    = __DIR__ . '/leads.log';

/* ── 1. Honeypot — svara som om allt gick bra, posta ingenting. ── */
if (!empty($_POST['website'])) {
    header('Location: ' . THANK_YOU);
    exit;
}

/* ── 3. Telefon är identiteten. Lokalt format (0981 123 456) normaliseras i CRM:et. ── */
$phone = trim((string)($_POST['telefono'] ?? ''));
if ($phone === '' || mb_strlen($phone) < 6 || mb_strlen($phone) > 30) {
    header('Location: ' . ERROR_BACK);
    exit;
}

/* ── 6. Attribution: first-touch-cookien som vc-attribution.js skriver. ── */
$attr = [];
if (!empty($_COOKIE['vc_attr'])) {
    $decoded = json_decode((string)$_COOKIE['vc_attr'], true);
    if (is_array($decoded)) {
        $attr = $decoded;
    }
}

/* ── 2. Stabil idempotensnyckel: samma nummer inom samma timme = samma submit. ── */
$idempotencyKey = hash('sha256', $phone . '|' . gmdate('Y-m-d-H'));

/* Extrafält som är värda att ha på tidslinjen (cotizadorn kan skicka dem). */
$fields = [];
foreach (['tratamiento', 'zona', 'horario'] as $key) {
    $value = trim((string)($_POST[$key] ?? ''));
    if ($value !== '') {
        $fields[$key] = mb_substr($value, 0, 200);
    }
}

$payload = [
    'phone'           => $phone,
    'name'            => $_POST['nombre']  ?? null,
    // 'email' skickas medvetet inte: fältet finns inte i 3-fältsformuläret och
    // ett tomt '' ger 422 i stället för att ignoreras.
    'message'         => $_POST['mensaje'] ?? null,
    'source'          => $_POST['form_id'] ?? ('site:' . SITE_SLUG),
    'page_url'        => $attr['landing_page'] ?? null,
    'referrer'        => $attr['referrer']     ?? null,
    'utm_source'      => $attr['utm_source']   ?? null,
    'utm_medium'      => $attr['utm_medium']   ?? null,
    'utm_campaign'    => $attr['utm_campaign'] ?? null,
    'utm_term'        => $attr['utm_term']     ?? null,
    'utm_content'     => $attr['utm_content']  ?? null,
    'gclid'           => $attr['gclid']        ?? null,
    'fbclid'          => $attr['fbclid']       ?? null,
    'idempotency_key' => $idempotencyKey,
];

// Aldrig pipeline / stage / owner / tag — routing ligger på site-posten i CRM:et.
$payload = array_filter($payload, static fn($v) => $v !== null && $v !== '');
if ($fields !== []) {
    $payload['fields'] = $fields;
}

/**
 * Nyckeln: env först, annars en include utanför public_html.
 * getenv() som returnerar false är den vanligaste orsaken till tysta 401:or.
 */
$crmUrl = getenv('VENDERCRM_URL') ?: '';
$apiKey = getenv('VENDERCRM_API_KEY') ?: '';

if ($crmUrl === '' || $apiKey === '') {
    $private = dirname(__DIR__) . '/private/vendercrm.php';
    if (is_readable($private)) {
        $config = require $private;
        $crmUrl = $crmUrl !== '' ? $crmUrl : (string)($config['url']     ?? '');
        $apiKey = $apiKey !== '' ? $apiKey : (string)($config['api_key'] ?? '');
    }
}

if ($crmUrl === '' || $apiKey === '') {
    /* Stage 1 — inget CRM konfigurerat ännu. Logga leadet, tappa det aldrig. */
    @file_put_contents(
        LEAD_LOG,
        gmdate('c') . ' PENDING_CRM ' . json_encode($payload, JSON_UNESCAPED_UNICODE) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
    error_log('VenderCRM no configurado (VENDERCRM_URL / VENDERCRM_API_KEY). Lead escrito en leads.log');
    header('Location: ' . THANK_YOU);
    exit;
}

/* ── 5. Blockera aldrig besökaren. Timeout 10s, logga fel, tacka alltid. ── */
$status   = 0;
$response = '';
$curlErr  = '';

try {
    $ch = curl_init(rtrim($crmUrl, '/') . '/api/v1/leads');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'X-Api-Key: ' . $apiKey,
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
    ]);
    $response = (string)curl_exec($ch);
    $status   = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);
} catch (Throwable $e) {
    $curlErr = $e->getMessage();
}

/* 201 = skapad, 200 = idempotent replay. Allt annat loggas med kroppen, som
   namnger fältet vid 422 och skiljer 401 (nyckel) från 403 (sajt/abonnemang). */
if ($status !== 201 && $status !== 200) {
    error_log(sprintf('VenderCRM lead failed [%d] %s %s', $status, $response, $curlErr));
    @file_put_contents(
        LEAD_LOG,
        gmdate('c') . ' FAILED_' . $status . ' ' . json_encode($payload, JSON_UNESCAPED_UNICODE) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

header('Location: ' . THANK_YOU);
exit;
