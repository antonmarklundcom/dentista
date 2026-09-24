<?php
/* Bloques reutilizables. Cada función imprime HTML; las *_schema devuelven arrays JSON-LD. */

function breadcrumb_schema(array $items): array
{
    $list = [];
    foreach (array_values($items) as $i => [$name, $path]) {
        $list[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $name, 'item' => abs_url($path)];
    }
    return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $list];
}

function faq_schema(array $faq): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn($f) => [
            '@type' => 'Question', 'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ], $faq),
    ];
}

function org_schema(): array
{
    $s = site();
    return array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $s['name'],
        'url' => base_url() . '/',
        'logo' => abs_url('/assets/img/favicon.svg'),
        'areaServed' => [['@type' => 'City', 'name' => 'Asunción'], ['@type' => 'AdministrativeArea', 'name' => 'Departamento Central']],
        'contactPoint' => ['@type' => 'ContactPoint', 'contactType' => 'customer service', 'telephone' => '+' . preg_replace('/\D/', '', (string)cfg('whatsapp')), 'availableLanguage' => 'es'],
        'sameAs' => array_values(array_filter([$s['instagram'], $s['facebook']])) ?: null,
    ]);
}

function crumbs(array $items): void
{
    echo '<nav class="crumbs" aria-label="Migas de pan"><ol>';
    $last = count($items) - 1;
    foreach (array_values($items) as $i => [$name, $path]) {
        echo $i === $last ? '<li aria-current="page">' . e($name) . '</li>' : '<li><a href="' . e($path) . '">' . e($name) . '</a></li>';
    }
    echo '</ol></nav>';
}

function promise_band(): void
{
    echo '<section class="ribbon grain" aria-label="Lo que te prometemos"><div class="ribbon__track">';
    foreach (site()['promises'] as $p) echo '<span>' . e($p) . '</span>';
    echo '</div></section>';
}

function faq_block(array $faq, string $title = 'Preguntas frecuentes'): void
{
    if (!$faq) return;
    echo '<div class="faq">' . ($title !== '' ? '<h2>' . e($title) . '</h2>' : '');
    foreach ($faq as $f) {
        echo '<details class="faq__item"><summary>' . e($f['q']) . '</summary><div class="faq__a"><p>' . e($f['a']) . '</p></div></details>';
    }
    echo '</div>';
}

function toc_block(array $sections): void
{
    $h = array_filter(array_column($sections, 'h2'));
    if (count($h) < 3) return;
    echo '<nav class="toc card card--hair" aria-label="En esta página"><p class="eyebrow">En esta página</p><ol>';
    foreach ($h as $i => $t) echo '<li><a href="#s' . $i . '">' . e($t) . '</a></li>';
    echo '</ol></nav>';
}

function sections_block_anchored(array $sections): void
{
    foreach ($sections as $i => $sec) {
        echo '<section class="prose-section" id="s' . $i . '">';
        if (!empty($sec['h2'])) echo '<h2>' . e($sec['h2']) . '</h2>';
        foreach ($sec['p'] ?? [] as $p) echo '<p>' . e($p) . '</p>';
        if (!empty($sec['list'])) {
            echo '<ul class="ticks">';
            foreach ($sec['list'] as $li) echo '<li>' . e($li) . '</li>';
            echo '</ul>';
        }
        echo '</section>';
    }
}

function service_cards(?array $only = null, string $variant = ''): void
{
    $list = services();
    if ($only !== null) $list = array_intersect_key($list, array_flip($only));
    echo '<div class="svc-grid ' . e($variant) . '">';
    foreach ($list as $s) {
        $feature = $variant === 'is-home' && $s['order'] === 1;
        echo '<a class="svc-card card ' . ($feature ? 'card--ink svc-card--feature' : 'card--hair') . '" href="/servicios/' . e($s['slug']) . '/">'
           . '<span class="svc-card__n">' . sprintf('%02d', $s['order']) . '</span>'
           . '<h3>' . e($s['name']) . '</h3><p>' . e($s['card']) . '</p>'
           . '<span class="svc-card__more">Ver tratamiento <span aria-hidden="true">→</span></span></a>';
    }
    echo '</div>';
}

function steps_block(array $steps, string $title = 'Cómo es tu primera consulta'): void
{
    echo '<div class="steps"><h2>' . e($title) . '</h2><ol class="steps__list">';
    foreach ($steps as $i => $st) echo '<li><span class="steps__n">' . ($i + 1) . '</span><p>' . e($st) . '</p></li>';
    echo '</ol></div>';
}

function cta_band(string $h, string $p, string $wa, string $loc): void
{
    echo '<section class="cta-band grain"><div class="container cta-band__row"><div><h2>' . e($h) . '</h2><p>' . e($p) . '</p></div>'
       . '<div class="btn-row"><a class="btn btn--primary" href="#pedir-turno" data-scroll-form>Pedí tu turno</a>'
       . '<a class="btn btn--wa-light" href="' . e(wa_link($wa)) . '" data-wa data-ev-loc="' . e($loc) . '" rel="noopener" target="_blank">';
    require ROOT . '/inc/icon-wa.php';
    echo 'Escribinos por WhatsApp</a></div></div></section>';
}

function partner_band(): void
{ ?>
<section class="section partner-band">
  <div class="container partner-band__row">
    <div>
      <p class="eyebrow">¿Sos odontólogo?</p>
      <h2>Sumá tu consultorio a la red y recibí pacientes de tu zona</h2>
      <p>Miles de personas en Paraguay buscan cada mes “dentista”, “brackets” o “limpieza dental” en Google. Dentista.com.py capta esas búsquedas y deriva los pacientes a odontólogos matriculados de su zona.</p>
    </div>
    <a class="btn btn--ghost" href="/para-odontologos/">Quiero recibir pacientes</a>
  </div>
</section>
<?php }
