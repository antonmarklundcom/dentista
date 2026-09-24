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

/* ---------------------------------------------------------------- icons */

/** Inline line icon (32×32 grid). Accent strokes use class "ia". */
function icon(string $k, string $class = 'ico'): string
{
    static $I = [
        'tooth'    => '<path d="M10 4c-3.5 0-6 2.8-6 7 0 4.5 2 7 3 11 .8 3.3 1.3 6 3.2 6 2 0 2-5 5.8-5s3.8 5 5.8 5c1.9 0 2.4-2.7 3.2-6 1-4 3-6.5 3-11 0-4.2-2.5-7-6-7-2.6 0-3.6 1.6-6 1.6S12.6 4 10 4z"/>',
        'tartar'   => '<path d="M10 4c-3.5 0-6 2.8-6 7 0 4.5 2 7 3 11 .8 3.3 1.3 6 3.2 6 2 0 2-5 5.8-5s3.8 5 5.8 5c1.9 0 2.4-2.7 3.2-6 1-4 3-6.5 3-11 0-4.2-2.5-7-6-7-2.6 0-3.6 1.6-6 1.6S12.6 4 10 4z"/><path class="ia" d="M9 20c2 1 3.5 1 5 0M18 20c2 1 3.5 1 5 0"/>',
        'toothache'=> '<path d="M10 4c-3.5 0-6 2.8-6 7 0 4.5 2 7 3 11 .8 3.3 1.3 6 3.2 6 2 0 2-5 5.8-5s3.8 5 5.8 5c1.9 0 2.4-2.7 3.2-6 1-4 3-6.5 3-11 0-4.2-2.5-7-6-7-2.6 0-3.6 1.6-6 1.6S12.6 4 10 4z"/><path class="ia" d="M17 9l-3 5h4l-3 5"/>',
        'plaque'   => '<path d="M10 4c-3.5 0-6 2.8-6 7 0 4.5 2 7 3 11 .8 3.3 1.3 6 3.2 6 2 0 2-5 5.8-5s3.8 5 5.8 5c1.9 0 2.4-2.7 3.2-6 1-4 3-6.5 3-11 0-4.2-2.5-7-6-7-2.6 0-3.6 1.6-6 1.6S12.6 4 10 4z"/><path class="ia" d="M10 11h.01M14 14h.01M19 11h.01M22 15h.01M12 17h.01"/>',
        'breath'   => '<path d="M4 12h14a4 4 0 1 0-4-4"/><path d="M4 18h19a4 4 0 1 1-4 4"/><path class="ia" d="M4 24h7"/>',
        'braces'   => '<rect x="4" y="9" width="7" height="15" rx="3"/><rect x="12.5" y="8" width="7" height="16" rx="3"/><rect x="21" y="9" width="7" height="15" rx="3"/><path class="ia" d="M2 16.5c9-2.5 19-2.5 28 0"/>',
        'selflig'  => '<rect x="7" y="7" width="18" height="18" rx="4"/><path class="ia" d="M3 16h26M11 11.5h10"/>',
        'aligner'  => '<path d="M4 12c0-3 2-5 5-5h14c3 0 5 2 5 5v4c0 5-4 9-12 9S4 21 4 16z"/><path class="ia" d="M9 12v4M14 11v5M18 11v5M23 12v4"/>',
        'denture'  => '<path d="M5 22c0-9 5-15 11-15s11 6 11 15"/><path class="ia" d="M9 19v4M12.5 15v5M16 13.5v5M19.5 15v5M23 19v4"/>',
        'bridge'   => '<path d="M3 12c0-3 1.5-5 3.5-5S10 9 10 12v4c0 5-1 10-2.2 10S6.3 22 6 22s-.6 4-1.6 4S3 21 3 16zM22 12c0-3 1.5-5 3.5-5S29 9 29 12v4c0 5-1 10-2.2 10S25.3 22 25 22s-.6 4-1.6 4S22 21 22 16z"/><path class="ia" d="M12.5 9.5c0-1.6 1.2-2.5 3.5-2.5s3.5.9 3.5 2.5v5c0 1.8-1.5 3-3.5 3s-3.5-1.2-3.5-3zM10 12h2.5M19.5 12H22"/>',
        'clean'    => '<path d="M5 27L18 14"/><path d="M17 10l5 5 5-5-5-5z"/><path class="ia" d="M20 5l-2-2M24 9l2-2M27 12l2-2"/>',
        'whiten'   => '<path d="M16 4v7M16 21v7M4 16h7M21 16h7"/><path class="ia" d="M8.5 8.5l3.5 3.5M20 20l3.5 3.5M23.5 8.5L20 12M12 20l-3.5 3.5"/>',
        'moon'     => '<path d="M24 20.5A11 11 0 0 1 11.5 5 11 11 0 1 0 24 20.5z"/><path class="ia" d="M21 5h5l-5 6h5"/>',
        'gum'      => '<path class="ia" d="M3 11c3 2.5 5.5 2.5 8 0s5-2.5 8 0 5.5 2.5 8 0"/><path d="M9 13v5c0 5 1.3 10 3.2 10 1.6 0 1.5-5 3.8-5s2.2 5 3.8 5c1.9 0 3.2-5 3.2-10v-5"/>',
        'wisdom'   => '<path d="M8 6c-3 0-5 2.5-5 6 0 4 1.7 6 2.5 9.5.7 3 1.1 5.5 2.8 5.5 1.7 0 1.7-4.5 4.7-4.5s3 4.5 4.7 4.5c1.7 0 2.1-2.5 2.8-5.5.8-3.5 2.5-5.5 2.5-9.5 0-3.5-2-6-5-6-2.2 0-3 1.4-5 1.4S10.2 6 8 6z"/><circle class="ia" cx="25" cy="8" r="4.5"/><path class="ia" d="M25 6v4M23 8h4"/>',
        'implant'  => '<path d="M9 4h14c1 0 1.6 1 1.3 2l-1.8 6H9.5L7.7 6C7.4 5 8 4 9 4z"/><path class="ia" d="M16 12v16M12 15.5h8M12.5 19.5h7M13 23.5h6"/>',
        'kid'      => '<circle cx="16" cy="16" r="12"/><path class="ia" d="M10.5 18.5c1.5 2.3 3.4 3.5 5.5 3.5s4-1.2 5.5-3.5"/><path d="M12 13h.01M20 13h.01" stroke-width="2.6"/>',
        'bolt'     => '<path class="ia" d="M18 3L7 18h8l-2 11 11-15h-8z"/>',
        'doc'      => '<path d="M8 4h12l6 6v18H8z"/><path d="M20 4v6h6M12 17h10M12 22h7"/>',
        'chat'     => '<path d="M16 4a12 12 0 0 0-10.3 18.2L4 28l6-1.6A12 12 0 1 0 16 4z"/><path d="M11 14h10M11 18.5h6"/>',
        'pin'      => '<path d="M16 29s9-8.2 9-15.5a9 9 0 1 0-18 0C7 20.8 16 29 16 29z"/><circle cx="16" cy="13.5" r="3.2"/>',
        'calendar' => '<rect x="4" y="7" width="24" height="21" rx="3"/><path d="M4 13h24M11 4v6M21 4v6M11 19l3 3 6-6"/>',
        'shield'   => '<path d="M16 3l10 4v8c0 7-4.5 11.5-10 14-5.5-2.5-10-7-10-14V7z"/><path class="ia" d="M11.5 16l3 3 6-6.5"/>',
        'idcard'   => '<rect x="4" y="6" width="24" height="20" rx="3"/><circle cx="12" cy="15" r="3"/><path class="ia" d="M7.5 22c1-2.5 2.7-3.5 4.5-3.5s3.5 1 4.5 3.5M19 13h5M19 17.5h4"/>',
        'lock'     => '<rect x="7" y="14" width="18" height="14" rx="3"/><path d="M11 14v-3a5 5 0 0 1 10 0v3"/><path class="ia" d="M16 19.5v3"/>',
        'user'     => '<circle cx="16" cy="11" r="5"/><path d="M6 27c1.5-5 5.5-8 10-8s8.5 3 10 8"/>',
        'clock'    => '<circle cx="16" cy="17" r="11"/><path d="M16 11v6l4 3M13 3h6"/>',
        'clinic'   => '<path d="M5 28V12l11-7 11 7v16z"/><path class="ia" d="M16 14v8M12 18h8"/>',
        'check'    => '<path d="M6 17l6.4 6L26 9"/>',
        'arrow'    => '<path d="M6 16h20M18 8l8 8-8 8"/>',
    ];
    return '<svg class="' . e($class) . '" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . ($I[$k] ?? $I['tooth']) . '</svg>';
}

function arrow(): string { return icon('arrow', 'ico ico--arrow'); }

function wa_icon(): string
{
    return '<svg class="ico-wa" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path d="M12 2.5a9.5 9.5 0 0 0-8.1 14.4L2.5 21.5l4.7-1.3A9.5 9.5 0 1 0 12 2.5z" fill="#25D366"/><path d="M9 7.3c.3-.3.8-.3 1 .1l1 1.6c.2.3.1.7-.1 1l-.6.6c.6 1.2 1.5 2.1 2.7 2.7l.6-.6c.3-.3.7-.3 1-.1l1.6 1c.4.2.4.7.1 1l-.8.8c-.5.5-1.3.7-2 .4-2.3-.9-4.1-2.7-5-5-.3-.7-.1-1.5.4-2z" fill="#fff"/></svg>';
}

/** WhatsApp button/link. Always through /wa/ (logs the click); site.js adds &l=<loc>. */
function wa_btn(string $text, string $label, string $loc, string $class = 'btn btn--ink'): string
{
    return '<a class="' . e($class) . '" href="' . e(wa_link($text)) . '" data-wa data-ev-loc="' . e($loc) . '" rel="nofollow noopener" target="_blank">' . wa_icon() . '<span>' . e($label) . '</span></a>';
}

function service_icon(string $slug): string
{
    $map = [
        'brackets' => 'braces', 'protesis-dental' => 'denture', 'profilaxis-dental' => 'clean', 'blanqueamiento-dental' => 'whiten',
        'brackets-autoligables' => 'selflig', 'alineadores-transparentes' => 'aligner', 'bruxismo' => 'moon', 'encias-inflamadas' => 'gum',
        'muela-del-juicio' => 'wisdom', 'implantes-dentales' => 'implant', 'puente-dental' => 'bridge', 'odontopediatria' => 'kid',
    ];
    return icon($map[$slug] ?? 'tooth');
}

function guide_icon(string $slug): string
{
    $map = [
        'sarro-dental' => 'tartar', 'dolor-de-muelas' => 'toothache', 'placa-bacteriana' => 'plaque', 'mal-aliento' => 'breath',
        'gingivitis' => 'gum', 'curetaje-dental' => 'clean', 'dientes-postizos' => 'denture', 'consultorios-odontologicos' => 'clinic',
    ];
    return icon($map[$slug] ?? 'tooth');
}

/** Short one-line blurb for compact cards. */
function service_blurb(array $s): string
{
    $map = [
        'protesis-dental' => 'Fijas o removibles, para recuperar las piezas que faltan.',
        'profilaxis-dental' => 'Profilaxis y limpieza de sarro para encías sanas.',
        'blanqueamiento-dental' => 'Blanqueo dental con evaluación previa del profesional.',
        'brackets-autoligables' => 'Brackets sin ligaduras elásticas, metálicos o estéticos.',
        'alineadores-transparentes' => 'Ortodoncia removible y casi invisible, si tu caso lo permite.',
        'bruxismo' => 'Placas de descanso si apretás o rechinás los dientes.',
        'encias-inflamadas' => 'Encías inflamadas o que sangran: gingivitis y periodoncia.',
        'muela-del-juicio' => 'Evaluación y extracción de terceros molares.',
        'implantes-dentales' => 'Implantes dentales para reemplazar piezas perdidas.',
        'puente-dental' => 'Un puente fijo para reemplazar una o más piezas.',
        'odontopediatria' => 'Consultas para chicos, con paciencia y buen trato.',
    ];
    if (isset($map[$s['slug']])) return $map[$s['slug']];
    $first = preg_split('/(?<=\.)\s/u', (string)($s['card'] ?? ''))[0] ?? '';
    return mb_strimwidth($first, 0, 90, '…');
}

/* ---------------------------------------------------------------- small blocks */

function crumbs(array $items): void
{
    echo '<nav class="crumbs" aria-label="Migas de pan"><ol>';
    $last = count($items) - 1;
    foreach (array_values($items) as $i => [$name, $path]) {
        echo $i === $last ? '<li aria-current="page">' . e($name) . '</li>' : '<li><a href="' . e($path) . '">' . e($name) . '</a></li>';
    }
    echo '</ol></nav>';
}

function eyebrow(string $text, bool $dot = false): string
{
    return '<p class="eyebrow">' . ($dot ? '<span class="eyebrow__dot" aria-hidden="true"></span>' : '') . e($text) . '</p>';
}

function ticks_inline(array $items, string $class = ''): void
{
    echo '<ul class="ticks-inline ' . e($class) . '">';
    foreach ($items as $t) echo '<li>' . icon('check') . e($t) . '</li>';
    echo '</ul>';
}

function promise_band(): void
{
    $icons = ['doc', 'chat', 'pin', 'calendar'];
    $subs  = ['Te evalúan y decidís sin compromiso.', 'Sin llamadas ni formularios eternos.', 'Cerca de tu casa o de tu trabajo.', 'Coordinamos según tu agenda.'];
    $p = site()['promises'];
    $withSubs = count($p) === count($subs);
    echo '<section class="promise" aria-label="Lo que te prometemos"><ul class="container promise__list">';
    foreach (array_values($p) as $i => $t) {
        echo '<li>' . icon($icons[$i % 4]) . '<div><p class="promise__t">' . e($t) . '</p>'
           . ($withSubs ? '<p class="promise__s">' . e($subs[$i]) . '</p>' : '') . '</div></li>';
    }
    echo '</ul></section>';
}

function section_head(string $eyebrow, string $h2Html, string $p = '', string $class = ''): void
{
    echo '<div class="section-head ' . e($class) . '"><div>' . eyebrow($eyebrow) . '<h2>' . $h2Html . '</h2></div>'
       . ($p !== '' ? '<p>' . e($p) . '</p>' : '') . '</div>';
}

function faq_block(array $faq, string $title = 'Preguntas frecuentes', bool $firstOpen = false): void
{
    if (!$faq) return;
    echo '<div class="faq">' . ($title !== '' ? '<h2 class="faq__title">' . e($title) . '</h2>' : '');
    foreach (array_values($faq) as $i => $f) {
        echo '<details class="faq__item"' . ($firstOpen && $i === 0 ? ' open' : '') . '><summary>' . e($f['q']) . '<span class="faq__plus" aria-hidden="true"></span></summary><div class="faq__a"><p>' . e($f['a']) . '</p></div></details>';
    }
    echo '</div>';
}

function toc_block(array $sections): void
{
    $h = array_filter(array_column($sections, 'h2'));
    if (count($h) < 3) return;
    echo '<nav class="toc" aria-label="En esta página"><p class="toc__t">En esta página</p><ol>';
    foreach ($h as $i => $t) echo '<li><a href="#s' . $i . '">' . e($t) . '</a></li>';
    echo '</ol></nav>';
}

/** Prose sections with anchors. $after[i] = callable printed after section i. */
function sections_block_anchored(array $sections, array $after = []): void
{
    foreach (array_values($sections) as $i => $sec) {
        echo '<section class="prose-section" id="s' . $i . '">';
        if (!empty($sec['h2'])) echo '<h2>' . e($sec['h2']) . '</h2>';
        foreach ($sec['p'] ?? [] as $p) echo '<p>' . e($p) . '</p>';
        if (!empty($sec['list'])) {
            echo '<ul class="ticks">';
            foreach ($sec['list'] as $li) echo '<li>' . e($li) . '</li>';
            echo '</ul>';
        }
        echo '</section>';
        if (isset($after[$i])) ($after[$i])();
    }
}

/** Approximate reading time in minutes (200 wpm). */
function reading_minutes(array $r): int
{
    $txt = ($r['lead'] ?? '') . ' ';
    foreach ($r['sections'] ?? [] as $s) $txt .= ($s['h2'] ?? '') . ' ' . implode(' ', $s['p'] ?? []) . ' ' . implode(' ', $s['list'] ?? []) . ' ';
    foreach ($r['faq'] ?? [] as $f) $txt .= $f['q'] . ' ' . $f['a'] . ' ';
    return max(1, (int)ceil(count(preg_split('/\s+/u', trim($txt))) / 200));
}

/* ---------------------------------------------------------------- treatment grids */

/** Bento grid of every service in services() order. First = feature, second = wide, rest = compact. */
function bento_services(string $urgHref = '#urgencias', bool $pick = true): void
{
    $list = array_values(services());
    $n = count($list);
    $rest = max(0, $n - 2);
    // Final "¿No sabés?" card fills the remaining columns of the last row (4-col grid).
    $used = ($n >= 1 ? 4 : 0) + ($n >= 2 ? 2 : 0) + $rest + 1;
    $span = 4 - ($used % 4);
    $urgWide = ($rest % 2) === 0; // 2-col grids: keep the last row full.
    echo '<div class="bento">';
    foreach ($list as $i => $s) {
        $href = '/servicios/' . $s['slug'] . '/';
        $img = img($s['slug'], 'bento__img', $i === 0 ? '(min-width: 1024px) 600px, 100vw' : '(min-width: 1024px) 300px, 50vw');
        if ($i === 0) {
            echo '<article class="bento__card bento__card--feature' . ($img ? ' has-img' : '') . '">' . ($img ?: bento_art($s['slug'], true))
               . '<div class="bento__body"><span class="pill pill--light">' . e($s['nav']) . '</span>'
               . '<h3><a href="' . e($href) . '">' . e($s['name']) . '</a></h3><p>' . e($s['card']) . '</p>';
            if (!empty($s['highlights'])) {
                echo '<ul class="bento__chips">';
                foreach ($s['highlights'] as $h) echo '<li>' . e($h) . '</li>';
                echo '</ul>';
            }
            echo '</div><div class="bento__actions"><a class="btn btn--light" href="#pedir-turno" data-scroll-form' . ($pick ? ' data-pick="' . e($s['slug']) . '"' : '') . '>Consultar por ' . e(mb_strtolower($s['nav'])) . arrow() . '</a>'
               . '<a class="bento__more" href="' . e($href) . '">Ver el tratamiento</a></div></article>';
        } elseif ($i === 1) {
            echo '<article class="bento__card bento__card--wide"><div class="bento__body"><h3><a class="stretch" href="' . e($href) . '">' . e($s['name']) . '</a></h3>'
               . '<p>' . e(service_blurb($s)) . '</p><span class="link-arrow">Ver ' . e(mb_strtolower($s['nav'])) . arrow() . '</span></div>'
               . ($img ?: bento_art($s['slug'], false)) . '</article>';
        } else {
            echo '<article class="bento__card">' . ($img ? '<span class="bento__avatar">' . $img . '</span>' : '<span class="bento__icon">' . service_icon($s['slug']) . '</span>')
               . '<div class="bento__body"><h3><a class="stretch" href="' . e($href) . '">' . e($s['nav']) . '</a></h3><p>' . e(service_blurb($s)) . '</p>'
               . '<span class="link-arrow link-arrow--sm">Ver tratamiento</span></div></article>';
        }
    }
    echo '<article class="bento__card' . ($urgWide ? ' bento__card--urg-wide' : '') . '"><span class="bento__icon">' . icon('bolt') . '</span>'
       . '<div class="bento__body"><h3><a class="stretch" href="' . e($urgHref) . '">Urgencias</a></h3><p>Dolor de muelas, fracturas o infecciones.</p><span class="link-arrow link-arrow--sm">Tengo dolor ahora</span></div></article>';
    echo '<article class="bento__card bento__card--cta bento--span-' . $span . '"><div class="bento__body"><h3>¿No sabés qué necesitás?</h3><p>Contanos qué te pasa y te orientamos hacia el profesional indicado.</p></div>'
       . wa_btn('Hola, vengo de dentista.com.py — no sé qué tratamiento necesito, quiero que me orienten.', 'Contanos por WhatsApp', 'bento-nosabes') . '</article>';
    echo '</div>';
}

/** Typographic/SVG art used when a service photo is missing. */
function bento_art(string $slug, bool $feature): string
{
    if ($feature) {
        return '<svg class="bento__art bento__art--feature" viewBox="0 0 236 130" fill="none" aria-hidden="true" focusable="false"><g stroke="currentColor" stroke-opacity=".75" stroke-width="1.5">'
            . '<rect x="10" y="34" width="32" height="70" rx="12"/><rect x="47" y="26" width="32" height="74" rx="12"/><rect x="84" y="22" width="32" height="78" rx="12"/><rect x="121" y="22" width="32" height="78" rx="12"/><rect x="158" y="26" width="32" height="74" rx="12"/><rect x="195" y="34" width="32" height="70" rx="12"/></g>'
            . '<path d="M4 68C60 52 176 52 232 68" stroke="#D49075" stroke-width="2.2" stroke-linecap="round"/><g fill="#D49075"><rect x="19" y="57" width="14" height="14" rx="3"/><rect x="56" y="52" width="14" height="14" rx="3"/><rect x="93" y="50" width="14" height="14" rx="3"/><rect x="130" y="50" width="14" height="14" rx="3"/><rect x="167" y="52" width="14" height="14" rx="3"/><rect x="204" y="57" width="14" height="14" rx="3"/></g></svg>';
    }
    return '<svg class="bento__art" viewBox="0 0 170 140" fill="none" aria-hidden="true" focusable="false"><path d="M6 40h158" stroke="#14241E" stroke-width="1.5" stroke-dasharray="4 4"/>'
        . '<path d="M14 40c0-12 6-20 18-20s18 8 18 20v20c0 18-5 40-11 40-5 0-5-16-7-16s-2 16-7 16c-6 0-11-22-11-40z" stroke="#14241E" stroke-width="1.5" stroke-linejoin="round"/>'
        . '<path d="M120 40c0-12 6-20 18-20s18 8 18 20v20c0 18-5 40-11 40-5 0-5-16-7-16s-2 16-7 16c-6 0-11-22-11-40z" stroke="#14241E" stroke-width="1.5" stroke-linejoin="round"/>'
        . '<path d="M60 30c0-8 6-12 25-12s25 4 25 12v28c0 10-10 16-25 16S60 68 60 58z" fill="#fff" stroke="#A44C29" stroke-width="1.8"/><path d="M50 44h10M110 44h10" stroke="#A44C29" stroke-width="1.8" stroke-linecap="round"/></svg>';
}

/** Simple card grid (related services, zone pages). */
function service_cards(?array $only = null, string $variant = ''): void
{
    $list = services();
    if ($only !== null) {
        $list = [];
        foreach ($only as $slug) if (isset(services()[$slug])) $list[$slug] = services()[$slug];
    }
    if (!$list) return;
    echo '<div class="card-grid ' . e($variant) . '">';
    foreach ($list as $s) {
        $img = img($s['slug'], 'card__img', '(min-width: 1024px) 380px, 100vw');
        echo '<article class="card card--link' . ($img ? ' has-img' : '') . '">' . $img
           . ($img ? '' : '<span class="card__icon">' . service_icon($s['slug']) . '</span>')
           . '<h3><a class="stretch" href="/servicios/' . e($s['slug']) . '/">' . e($s['name']) . '</a></h3>'
           . '<p>' . e($s['card']) . '</p><span class="link-arrow">Ver tratamiento' . arrow() . '</span></article>';
    }
    echo '</div>';
}

function guide_cards(array $guides, bool $withService = true): void
{
    if (!$guides) return;
    echo '<div class="card-grid card-grid--guides">';
    foreach ($guides as $g) {
        $svc = services()[$g['service'] ?? ''] ?? null;
        echo '<article class="card card--link card--guide"><span class="card__icon card__icon--tile">' . guide_icon($g['slug']) . '</span>'
           . '<p class="card__kicker">' . e($g['nav'] ?? $g['keyword']) . '</p>'
           . '<h3><a class="stretch" href="' . e(guide_url($g['slug'])) . '">' . e($g['h1']) . '</a></h3>'
           . '<p>' . e($g['card'] ?? mb_strimwidth($g['lead'], 0, 170, '…')) . '</p>'
           . '<span class="card__foot">' . ($withService && $svc ? 'Guía · ' . e($svc['nav']) : 'Leer guía') . arrow() . '</span></article>';
    }
    echo '</div>';
}

function steps_block(array $steps, string $title = 'Cómo es tu primera consulta'): void
{
    echo '<div class="steps"><h2>' . e($title) . '</h2><ol class="steps__list">';
    foreach ($steps as $i => $st) echo '<li><span class="steps__n">' . sprintf('%02d', $i + 1) . '</span><p>' . e($st) . '</p></li>';
    echo '</ol></div>';
}

/** Four-step "how it works" row. $items = [[title, text], ...] */
function how_block(array $items, string $class = ''): void
{
    echo '<ol class="how ' . e($class) . '">';
    foreach (array_values($items) as $i => [$t, $p]) echo '<li><span class="how__n">' . sprintf('%02d', $i + 1) . '</span><div><h3>' . e($t) . '</h3><p>' . e($p) . '</p></div></li>';
    echo '</ol>';
}

function how_patient(): array
{
    return [
        ['Contanos qué necesitás', 'Elegí el tratamiento, tu zona y para cuándo. Te lleva un minuto.'],
        ['Te escribimos por WhatsApp', 'Confirmamos los detalles y buscamos un odontólogo matriculado cerca tuyo, en el horario que te sirva.'],
        ['Conocés al profesional', 'Te pasamos sus datos antes de confirmar: nombre, matrícula y dirección del consultorio.'],
        ['Vos decidís', 'Presupuesto sin costo. No se empieza nada hasta que vos digas que sí.'],
    ];
}

/* ---------------------------------------------------------------- bands */

function urgency_block(): void
{ ?>
<section class="urgency" id="urgencias" aria-labelledby="urg-h">
  <div class="container urgency__grid">
    <div>
      <p class="eyebrow eyebrow--light"><?= icon('bolt', 'ico ico--sm') ?>Urgencias</p>
      <h2 id="urg-h">¿Tenés dolor ahora?</h2>
      <p class="urgency__lead">No esperes a que se pase solo. Escribinos por WhatsApp qué te duele y desde cuándo: buscamos un odontólogo de tu zona con lugar lo antes posible.</p>
      <div class="urgency__cta">
        <?= wa_btn('Hola, vengo de dentista.com.py — tengo dolor y necesito un turno lo antes posible.', 'Escribinos ahora por WhatsApp', 'urgencias', 'btn btn--white btn--lg') ?>
        <span class="urgency__or">o elegí «<?= e(site()['urgency']['hoy'] ?? 'Hoy') ?>» en el formulario</span>
      </div>
    </div>
    <aside class="urgency__aside" aria-labelledby="urg-wait">
      <h3 id="urg-wait">Mientras esperás</h3>
      <ol>
        <li>Enjuagá con agua tibia con sal.</li>
        <li>Frío por fuera de la mejilla, de a 15 minutos.</li>
        <li>No pongas aspirina sobre la encía.</li>
      </ol>
      <p>Si tenés la cara hinchada, fiebre o te cuesta tragar o respirar, andá directo a una guardia.</p>
    </aside>
  </div>
</section>
<?php }

function trust_block(): void
{ ?>
<section class="section section--tint trust" aria-labelledby="trust-h">
  <div class="container trust__grid">
    <div>
      <?= eyebrow('Transparencia') ?>
      <h2 id="trust-h">Sabés quién te atiende <em>antes de ir.</em></h2>
      <p class="section-lead">No somos una clínica: coordinamos tu consulta con odontólogos matriculados independientes. Por eso te mostramos todo antes de que confirmes.</p>
      <ul class="features">
        <li><span class="features__i"><?= icon('shield') ?></span><div><h3>Matrícula verificada</h3><p>Antes de sumar a un odontólogo a la red, verificamos su matrícula profesional.</p></div></li>
        <li><span class="features__i"><?= icon('idcard') ?></span><div><h3>Datos del profesional antes de confirmar</h3><p>Nombre, matrícula y dirección del consultorio, por WhatsApp.</p></div></li>
        <li><span class="features__i"><?= icon('lock') ?></span><div><h3>Tus datos, cuidados</h3><p>Solo los compartimos con el odontólogo que coordina tu consulta, y solo con tu permiso.</p></div></li>
      </ul>
    </div>
    <figure class="chat">
      <div class="chat__phone">
        <div class="chat__head"><span class="chat__avatar"><?= icon('tooth') ?></span><div><p class="chat__name">Dentista.com.py</p><p class="chat__sub">WhatsApp</p></div></div>
        <div class="chat__body">
          <p class="chat__msg">Hola, encontramos turno para tu limpieza en San Lorenzo. Estos son los datos del profesional:</p>
          <div class="chat__card">
            <div class="chat__photo">Foto del profesional</div>
            <dl><dt>Profesional</dt><dd>[Nombre y apellido]</dd><dt>Matrícula</dt><dd>N.º [verificada]</dd><dt>Consultorio</dt><dd>San Lorenzo, [dirección]</dd><dt>Horarios</dt><dd>[día y hora]</dd></dl>
          </div>
          <p class="chat__msg chat__msg--me">El jueves me sirve.</p>
          <p class="chat__msg">Perfecto. El presupuesto es sin costo y no se empieza nada hasta que vos digas que sí.</p>
        </div>
      </div>
      <figcaption>Ejemplo ilustrativo de la conversación por WhatsApp.</figcaption>
    </figure>
  </div>
</section>
<?php }

/** Dark closing band. $h is plain text; $em (optional) renders on a second line in italics. */
function cta_band(string $h, string $p, string $wa, string $loc, string $em = '', string $btn = 'Pedí tu turno'): void
{
    echo '<section class="cta-band"><div class="container cta-band__inner"><h2>' . e($h) . ($em !== '' ? '<br><em>' . e($em) . '</em>' : '') . '</h2>'
       . '<p>' . e($p) . '</p><div class="cta-band__btns"><a class="btn btn--light btn--lg" href="#pedir-turno" data-scroll-form>' . e($btn) . arrow() . '</a>'
       . wa_btn($wa, 'Escribinos por WhatsApp', $loc, 'btn btn--outline-light btn--lg') . '</div></div></section>';
}

function partner_band(): void
{ ?>
<section class="partner-band" aria-labelledby="pb-h">
  <div class="container partner-band__grid">
    <div>
      <?= eyebrow('Para odontólogos') ?>
      <h2 id="pb-h">¿Sos odontólogo? <em>Recibí pacientes de tu zona.</em></h2>
    </div>
    <div>
      <p>Pacientes que ya eligieron tratamiento, cerca de tu consultorio, directo a tu WhatsApp.</p>
      <a class="btn btn--ink" href="/para-odontologos/">Sumate a la red<?= arrow() ?></a>
    </div>
  </div>
</section>
<?php }

function zone_links(bool $withOther = true): void
{
    echo '<ul class="zone-list"><li><a href="/">Asunción' . arrow() . '</a></li>';
    foreach (zones() as $zs => $zz) echo '<li><a href="/zonas/' . e($zs) . '/">' . e($zz['name']) . arrow() . '</a></li>';
    if ($withOther) echo '<li><a class="zone-list__other" href="' . e(wa_link('Hola, vengo de dentista.com.py — estoy fuera del Gran Asunción, ¿pueden coordinar una consulta?')) . '" data-wa data-ev-loc="zonas-otra" rel="nofollow noopener" target="_blank">¿Otra ciudad? Consultanos' . arrow() . '</a></li>';
    echo '</ul>';
}

function zone_map(): void
{ ?>
<figure class="map">
  <svg viewBox="0 0 520 440" fill="none" role="img" aria-labelledby="map-t"><title id="map-t">Mapa ilustrativo de Asunción y las ciudades del Gran Asunción donde coordinamos consultas</title>
    <path d="M70 -10C112 60 58 122 94 184S70 300 118 362 108 424 138 450" stroke="#A9BDB6" stroke-width="16" stroke-linecap="round"/>
    <text x="46" y="286" transform="rotate(-80 46 286)" font-size="11" letter-spacing="2" fill="#4A5A53">RÍO PARAGUAY</text>
    <g stroke="#C9C1B1" stroke-width="1.2" stroke-dasharray="4 5"><path d="M175 185L225 85M175 185L345 120M175 185L285 225M285 225L375 265M375 265L465 315M175 185L165 300"/></g>
    <g fill="#14241E"><circle cx="225" cy="85" r="6"/><circle cx="345" cy="120" r="6"/><circle cx="285" cy="225" r="6"/><circle cx="375" cy="265" r="6"/><circle cx="165" cy="300" r="6"/><circle cx="465" cy="315" r="6"/></g>
    <g stroke="#14241E" stroke-opacity=".25" stroke-width="1.2"><circle cx="225" cy="85" r="13"/><circle cx="345" cy="120" r="13"/><circle cx="285" cy="225" r="13"/><circle cx="375" cy="265" r="13"/><circle cx="165" cy="300" r="13"/><circle cx="465" cy="315" r="13"/></g>
    <circle cx="175" cy="185" r="9" fill="#A44C29"/><circle cx="175" cy="185" r="20" stroke="#A44C29" stroke-opacity=".4" stroke-width="1.4"/>
    <g font-size="14" font-weight="600" fill="#14241E" text-anchor="middle">
      <text x="175" y="155" class="map__city">Asunción</text><text x="225" y="62">Mariano Roque Alonso</text><text x="345" y="100">Luque</text>
      <text x="285" y="255">Fernando de la Mora</text><text x="400" y="294">San Lorenzo</text><text x="165" y="330">Lambaré</text><text x="465" y="345">Capiatá</text>
    </g>
  </svg>
  <figcaption>Mapa ilustrativo, no a escala.</figcaption>
</figure>
<?php }

/* ---------------------------------------------------------------- before / after */

function ba_picture(array $m, string $class): string
{
    $b = '/assets/img/' . $m['file'];
    $set = fn(string $ext) => implode(', ', array_filter(["$b-640.$ext 640w", is_file(ROOT . "$b-1280.$ext") ? "$b-1280.$ext 1280w" : null]));
    $sizes = '(min-width: 1024px) 560px, 100vw';
    return '<picture class="' . e($class) . '">'
        . (is_file(ROOT . "$b-640.avif") ? '<source type="image/avif" srcset="' . e($set('avif')) . '" sizes="' . $sizes . '">' : '')
        . '<source type="image/webp" srcset="' . e($set('webp')) . '" sizes="' . $sizes . '">'
        . '<img src="' . e("$b-640.webp") . '" alt="' . e($m['alt']) . '" width="1280" height="960" loading="lazy" decoding="async"></picture>';
}

/**
 * Antes/después: only pairs whose two files exist (assets/img/<file>-640.webp).
 * $service filters by pair.service. Prints nothing when no pair qualifies.
 */
function before_after_block(?string $service = null, string $title = 'Así puede cambiar una sonrisa', string $class = 'section'): void
{
    $ok = fn(array $m) => !empty($m['file']) && is_file(ROOT . '/assets/img/' . $m['file'] . '-640.webp');
    $pairs = array_values(array_filter(images()['beforeAfter'] ?? [], fn($p) =>
        ($service === null || ($p['service'] ?? '') === $service) && $ok($p['before'] ?? []) && $ok($p['after'] ?? [])));
    if (!$pairs) return;
    $caption = images()['caption'] ?? 'Simulación ilustrativa — no es un paciente real.';
    echo '<section class="' . e($class) . ' ba-section" aria-labelledby="ba-h"><div class="container">';
    echo '<div class="section-head"><div>' . eyebrow('Antes y después') . '<h2 id="ba-h">' . e($title) . '</h2></div><p>Deslizá para comparar. Son simulaciones para que te hagas una idea; el resultado real lo define el odontólogo en tu caso.</p></div>';
    echo '<div class="ba-grid' . (count($pairs) === 1 ? ' ba-grid--one' : '') . '">';
    foreach ($pairs as $i => $p) {
        $id = 'ba-' . e($p['key'] ?? (string)$i);
        echo '<figure class="ba" style="--pos:50%"><div class="ba__frame">'
           . ba_picture($p['after'], 'ba__after')
           . '<div class="ba__before">' . ba_picture($p['before'], '') . '</div>'
           . '<span class="ba__tag ba__tag--l" aria-hidden="true">Antes</span><span class="ba__tag ba__tag--r" aria-hidden="true">Después</span>'
           . '<span class="ba__handle" aria-hidden="true"></span>'
           . '<input class="ba__range" type="range" min="0" max="100" value="50" id="' . $id . '" aria-label="Comparar antes y después: ' . e($p['label']) . '" aria-valuetext="50% antes">'
           . '</div><figcaption><strong>' . e($p['label']) . '</strong> · ' . e($caption) . '</figcaption></figure>';
    }
    echo '</div></div></section>';
}

/* ---------------------------------------------------------------- educational diagrams */

/** Small labelled SVG diagram for a guide/service slug. Prints nothing if none. */
function edu_diagram(string $slug): void
{
    // Tooth outline centred on 0,0 (crown on top, roots below). Gum is drawn over the roots.
    $tooth = 'M-26 -40c-12 0-20 10-20 26 0 16 6 26 9 42 3 14 5 26 12 26 7 0 7-18 25-18s18 18 25 18c7 0 9-12 12-26 3-16 9-26 9-42 0-16-8-26-20-26-10 0-13 6-26 6s-16-6-26-6z';
    $gum = fn(string $fill = '#E7B8A3', int $top = 24) => '<path d="M-62 ' . ($top + 6) . 'c14-8 26-8 36-2 10 5 16 6 26 6s16-1 26-6c10-6 22-6 36 2v' . (70 - $top) . 'h-124z" fill="' . $fill . '"/>';
    $d = [
        'sarro-dental' => ['Cómo la placa se convierte en sarro', 'Diagrama en tres pasos: la placa blanda se acumula sobre el diente junto a la encía, la saliva la mineraliza y se endurece como sarro; ese sarro solo lo saca el odontólogo.',
            function () use ($tooth, $gum) {
                $labels = [['1. Placa blanda', 'Se va con el cepillo'], ['2. Placa acumulada', 'La saliva la mineraliza'], ['3. Sarro', 'Solo lo saca el odontólogo']];
                foreach ([0, 1, 2] as $i) {
                    $x = 90 + $i * 180;
                    echo '<g transform="translate(' . $x . ' 78)"><path d="' . $tooth . '" fill="#fff" stroke="#14241E" stroke-width="2"/>' . $gum();
                    if ($i === 0) echo '<g fill="#D49075"><circle cx="-20" cy="8" r="3"/><circle cx="4" cy="-6" r="2.5"/><circle cx="22" cy="12" r="3"/></g>';
                    if ($i === 1) echo '<path d="M-38 22c10 5 24 7 38 7s28-2 38-7" fill="none" stroke="#D49075" stroke-width="7" stroke-linecap="round"/><g fill="#D49075"><circle cx="-16" cy="4" r="3"/><circle cx="16" cy="0" r="3"/></g>';
                    if ($i === 2) echo '<path d="M-40 20c10 6 26 9 40 9s30-3 40-9" fill="none" stroke="#A44C29" stroke-width="11" stroke-linecap="round"/>';
                    echo '</g><text x="' . $x . '" y="182" text-anchor="middle" font-weight="600">' . e($labels[$i][0]) . '</text><text x="' . $x . '" y="202" text-anchor="middle" class="d-muted">' . e($labels[$i][1]) . '</text>';
                    if ($i < 2) echo '<path d="M' . ($x + 70) . ' 70h36m-8-6 8 6-8 6" stroke="#A44C29" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>';
                }
            }],
        'placa-bacteriana' => ['Qué es la placa bacteriana', 'Diagrama: una película pegajosa de bacterias cubre el diente, sobre todo junto al borde de la encía; el cepillado y el hilo dental la retiran antes de que se endurezca.',
            function () use ($tooth, $gum) {
                echo '<g transform="translate(170 86)"><path d="' . $tooth . '" fill="#fff" stroke="#14241E" stroke-width="2"/>' . $gum()
                   . '<path d="M-44 -6c-1 12 1 22 5 30M44 -6c1 12-1 22-5 30" fill="none" stroke="#D49075" stroke-width="7" stroke-linecap="round"/>'
                   . '<g fill="#A44C29"><circle cx="-43" cy="4" r="2.5"/><circle cx="-41" cy="16" r="2.5"/><circle cx="43" cy="8" r="2.5"/><circle cx="41" cy="20" r="2.5"/></g></g>'
                   . '<path d="M222 84h66" stroke="#14241E" stroke-width="1.2"/><text x="298" y="80" font-weight="600">Placa (biofilm)</text><text x="298" y="98" class="d-muted">bacterias + restos de comida</text>'
                   . '<path d="M232 118h56" stroke="#14241E" stroke-width="1.2"/><text x="298" y="122" font-weight="600">Borde de la encía</text><text x="298" y="140" class="d-muted">donde más se acumula</text>'
                   . '<text x="298" y="184" font-weight="600" fill="#A44C29">Cepillo + hilo dental</text><text x="298" y="202" class="d-muted">la retiran si es a diario</text>';
            }],
        'gingivitis' => ['Encía sana vs. encía inflamada', 'Diagrama comparativo: a la izquierda una encía sana, rosada y firme que abraza el diente; a la derecha una encía inflamada, roja e hinchada, con placa en el borde, que sangra al cepillar.',
            function () use ($tooth, $gum) {
                foreach ([[140, false], [400, true]] as [$x, $bad]) {
                    echo '<g transform="translate(' . $x . ' 78)"><path d="' . $tooth . '" fill="#fff" stroke="#14241E" stroke-width="2"/>'
                       . ($bad ? '<path d="M-38 16c10 5 24 7 38 7s28-2 38-7" fill="none" stroke="#D49075" stroke-width="6" stroke-linecap="round"/>' . $gum('#C2603A', 12) . '<path d="M-10 34v10M8 36v8" stroke="#7A2E12" stroke-width="2" stroke-linecap="round"/>'
                               : $gum('#E7B8A3', 22))
                       . '</g><text x="' . $x . '" y="182" text-anchor="middle" font-weight="600">' . ($bad ? 'Encía inflamada' : 'Encía sana') . '</text>'
                       . '<text x="' . $x . '" y="202" text-anchor="middle" class="d-muted">' . ($bad ? 'roja, hinchada, sangra' : 'rosada, firme, no sangra') . '</text>';
                }
            }],
        'bruxismo' => ['Desgaste por bruxismo', 'Diagrama comparativo: a la izquierda una muela con sus cúspides normales; a la derecha una muela con la superficie aplanada y desgastada por apretar o rechinar los dientes.',
            function () {
                $roots  = 'C46 2 40 18 38 36C36 52 32 64 26 64C20 64 20 48 8 48L-8 48C-20 48 -20 64 -26 64C-32 64 -36 52 -38 36C-40 18 -46 2 -44 -14Z';
                $normal = 'M-44 -14C-44 -30 -26 -34 -22 -22C-18 -34 -4 -34 0 -22C4 -34 18 -34 22 -22C26 -34 44 -30 44 -14' . $roots;
                $worn   = 'M-44 -4L44 -4' . str_replace('C46 2', 'C45 6', $roots);
                $worn   = str_replace('-44 -14Z', '-44 -4Z', $worn);
                foreach ([[140, $normal, 'Muela normal', 'cúspides marcadas', false], [400, $worn, 'Muela desgastada', 'superficie plana, más sensible', true]] as [$x, $p, $t, $sub, $w]) {
                    echo '<g transform="translate(' . $x . ' 78)"><path d="' . $p . '" fill="#fff" stroke="#14241E" stroke-width="2" stroke-linejoin="round"/>'
                       . ($w ? '<path d="M-46 -4h92" stroke="#A44C29" stroke-width="4" stroke-linecap="round"/><path d="M-38 -26l8 8M0 -32v12M38 -26l-8 8" stroke="#A44C29" stroke-width="2" stroke-linecap="round"/>' : '')
                       . '</g><text x="' . $x . '" y="182" text-anchor="middle" font-weight="600">' . e($t) . '</text><text x="' . $x . '" y="202" text-anchor="middle" class="d-muted">' . e($sub) . '</text>';
                }
            }],
    ];
    if (!isset($d[$slug])) return;
    [$title, $label, $draw] = $d[$slug];
    $id = 'dg-' . $slug;
    echo '<figure class="diagram"><svg viewBox="0 0 540 220" role="img" aria-labelledby="' . $id . '-t ' . $id . '-d" font-size="13" fill="#14241E"><title id="' . $id . '-t">' . e($title) . '</title><desc id="' . $id . '-d">' . e($label) . '</desc>';
    $draw();
    echo '</svg><figcaption>' . e($title) . '. Esquema ilustrativo.</figcaption></figure>';
}
