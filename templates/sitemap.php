<?php
header('Content-Type: application/xml; charset=utf-8');
// [path, priority, image keys shown on that page]
$SM = [['/', '1.0', ['hero', 'familia']], ['/servicios/', '0.9', []], ['/zonas/', '0.6', ['adulto-mayor', 'pareja']], ['/salud-dental/', '0.7', []],
       ['/para-odontologos/', '0.7', ['odontologa', 'consultorio']], ['/contacto/', '0.5', []], ['/privacidad/', '0.2', []]];
foreach (services() as $s) $SM[] = ['/servicios/' . $s['slug'] . '/', '0.9', [$s['slug']]];
foreach (zones() as $slug => $z) $SM[] = ['/zonas/' . $slug . '/', '0.7', []];
foreach (guides() as $g) $SM[] = [guide_url($g['slug']), in_array($g['slug'], TOP_GUIDES, true) ? '0.9' : '0.6', []];
$lastmod = date('Y-m-d', max(array_map('filemtime', glob(ROOT . '/content/{*,*/*}.php', GLOB_BRACE))));
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
   . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
foreach ($SM as [$p, $pr, $imgs]) {
    echo '  <url><loc>' . e(abs_url($p)) . "</loc><lastmod>$lastmod</lastmod><priority>$pr</priority>";
    // Before/after simulations: all pairs on / and /servicios/, matching pairs on each service page.
    $files = array_map(fn($k) => img_exists($k) ? images()['items'][$k]['file'] : null, $imgs);
    foreach (images()['beforeAfter'] ?? [] as $ba) {
        if (in_array($p, ['/', '/servicios/'], true) || $p === '/servicios/' . ($ba['service'] ?? '') . '/') {
            foreach (['before', 'after'] as $side) {
                if (is_file(ROOT . '/assets/img/' . $ba[$side]['file'] . '-640.webp')) $files[] = $ba[$side]['file'];
            }
        }
    }
    foreach (array_filter($files) as $file) {
        $f = '/assets/img/' . $file;
        echo '<image:image><image:loc>' . e(abs_url(is_file(ROOT . "$f-1280.webp") ? "$f-1280.webp" : "$f-640.webp")) . '</image:loc></image:image>';
    }
    echo "</url>\n";
}
echo "</urlset>\n";
