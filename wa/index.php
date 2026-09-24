<?php
/*
 * /wa/?t=<texto>&l=<ubicación del botón>
 * Registra el clic de WhatsApp en leads/wa-clicks-YYYY-MM.csv (panel /admin/)
 * y redirige a wa.me con el número fijo del sitio (sin open redirect).
 */
require_once dirname(__DIR__) . '/inc/app.php';
require_once dirname(__DIR__) . '/inc/crm.php';

$text = mb_substr(trim((string)($_GET['t'] ?? '')), 0, 500) ?: 'Hola, vengo de dentista.com.py — quiero coordinar una consulta odontológica.';
$loc  = preg_replace('/[^a-z0-9:_-]/i', '', mb_substr((string)($_GET['l'] ?? ''), 0, 40));
$ua   = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');

if ($ua !== '' && !preg_match('/bot|crawl|spider|slurp|preview|facebookexternalhit/i', $ua)) {
    $attr = vcrm_attribution();
    $ref  = (string)parse_url((string)($_SERVER['HTTP_REFERER'] ?? ''), PHP_URL_PATH);
    // El servicio va entre paréntesis en el texto prellenado: "(brackets)".
    preg_match('/\(([a-z0-9-]+)\)/', $text, $m);
    $row = [date('Y-m-d H:i:s'), $ref, $loc, $m[1] ?? '', $attr['utm_source'] ?? '', $attr['utm_campaign'] ?? '', mb_substr($text, 0, 160)];
    $file = ROOT . '/leads/wa-clicks-' . date('Y-m') . '.csv';
    $new  = !is_file($file);
    if ($fh = @fopen($file, 'a')) {
        flock($fh, LOCK_EX);
        if ($new) fputcsv($fh, ['date', 'page', 'button', 'service', 'utm_source', 'utm_campaign', 'text']);
        fputcsv($fh, array_map(fn($v) => preg_match('/^(=|@|[+-](?![0-9]))/', (string)$v) ? "'" . $v : $v, $row));
        flock($fh, LOCK_UN);
        fclose($fh);
    }
}

header('Cache-Control: no-store');
header('X-Robots-Tag: noindex, nofollow');
header('Location: https://wa.me/' . preg_replace('/\D/', '', (string)cfg('whatsapp')) . '?text=' . rawurlencode($text), true, 302);
