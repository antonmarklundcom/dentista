<?php
header('Content-Type: application/xml; charset=utf-8');
$SM = [['/', '1.0'], ['/servicios/', '0.9'], ['/zonas/', '0.6'], ['/salud-dental/', '0.7'], ['/para-odontologos/', '0.7'], ['/contacto/', '0.5'], ['/privacidad/', '0.2']];
foreach (services() as $s) $SM[] = ['/servicios/' . $s['slug'] . '/', '0.9'];
foreach (zones() as $slug => $z) $SM[] = ['/zonas/' . $slug . '/', '0.7'];
foreach (guides() as $g) $SM[] = [guide_url($g['slug']), $g['slug'] === 'consultorios-odontologicos' ? '0.9' : '0.6'];
$lastmod = date('Y-m-d', max(array_map('filemtime', glob(ROOT . '/content/{*,*/*}.php', GLOB_BRACE))));
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($SM as [$p, $pr]) echo '  <url><loc>' . e(abs_url($p)) . "</loc><lastmod>$lastmod</lastmod><priority>$pr</priority></url>\n";
echo "</urlset>\n";
