<?php
/*
 * Front controller. .htaccess manda acá toda URL que no sea un archivo real.
 * Local: php -S localhost:8080 index.php
 */
require_once __DIR__ . '/inc/app.php';

$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

if (PHP_SAPI === 'cli-server' && $path !== '/') {
    if (preg_match('#^/(inc|content|leads|templates|docs)/|^/(config|KEYWORDS|README)#', $path)) { http_response_code(403); exit('Forbidden'); }
    if (is_file(__DIR__ . $path)) return false;
}

if ($path === '/sitemap.xml') { require ROOT . '/templates/sitemap.php'; exit; }
if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo cfg('noindex') ? "User-agent: *\nDisallow: /\n" : "User-agent: *\nDisallow: /gracias/\nDisallow: /lp/\nDisallow: /enviar.php\n\nSitemap: " . abs_url('/sitemap.xml') . "\n";
    exit;
}

// Barra final obligatoria: una sola URL por página.
if (!str_ends_with($path, '/') && !str_contains(basename($path), '.')) {
    $q = $_SERVER['QUERY_STRING'] ?? '';
    header('Location: ' . $path . '/' . ($q !== '' ? "?$q" : ''), true, 301);
    exit;
}

$seg = array_values(array_filter(explode('/', $path), 'strlen'));
$one = $seg[0] ?? '';
$two = $seg[1] ?? null;
if (count($seg) > 2) { not_found(); exit; }

switch (true) {
    case $one === '':                                   require ROOT . '/templates/home.php'; break;
    case $one === 'servicios' && $two === null:         $hub = 'servicios'; require ROOT . '/templates/hub.php'; break;
    case $one === 'servicios' && isset(services()[$two]): $s = services()[$two]; require ROOT . '/templates/service.php'; break;
    case $one === 'zonas' && $two === null:             $hub = 'zonas'; require ROOT . '/templates/hub.php'; break;
    case $one === 'zonas' && isset(zones()[$two]):      $zslug = $two; $z = zones()[$two]; require ROOT . '/templates/zone.php'; break;
    case $one === 'guias' && $two === null:             $hub = 'guias'; require ROOT . '/templates/hub.php'; break;
    case $one === 'guias' && isset(guides()[$two]):     $g = guides()[$two]; require ROOT . '/templates/guide.php'; break;
    case $one === 'lp' && isset(services()[$two]):      $s = services()[$two]; require ROOT . '/templates/landing.php'; break;
    case $one === 'para-odontologos' && $two === null:  require ROOT . '/templates/partner.php'; break;
    case $one === 'contacto' && $two === null:          require ROOT . '/templates/contact.php'; break;
    case $one === 'privacidad' && $two === null:        require ROOT . '/templates/privacy.php'; break;
    case $one === 'gracias' && $two === null:           require ROOT . '/templates/thanks.php'; break;
    default:                                            not_found();
}
