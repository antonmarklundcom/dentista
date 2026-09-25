<?php
/** @var array $g */
$G_choose = in_array($g['slug'], TOP_GUIDES, true);
$G_crumbs = $G_choose
    ? [['Inicio', '/'], [$g['nav'] ?? $g['h1'], guide_url($g['slug'])]]
    : [['Inicio', '/'], ['Salud dental', '/salud-dental/'], [$g['nav'] ?? $g['h1'], guide_url($g['slug'])]];
$G_svc = services()[$g['service'] ?? ''] ?? null;
$G_wa = $G_svc['wa'] ?? 'Hola, vengo de dentista.com.py — leí la guía sobre ' . $g['keyword'] . ' y quiero consultar.';
$G_updated = $g['updated'] ?? date('Y-m-d');
render([
    'title' => $g['title'],
    'description' => $g['description'],
    'wa' => $G_wa,
    'bodyClass' => 'page-guide' . ($G_choose ? ' page-choose' : ''),
    'ogType' => 'article',
    'schema' => [
        breadcrumb_schema($G_crumbs),
        faq_schema($g['faq']),
        [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $g['h1'],
            'description' => $g['description'],
            'inLanguage' => 'es-PY',
            'dateModified' => $G_updated,
            'author' => ['@type' => 'Organization', 'name' => site()['name']],
            'publisher' => ['@type' => 'Organization', 'name' => site()['name'], 'logo' => ['@type' => 'ImageObject', 'url' => abs_url('/assets/img/favicon.svg')]],
            'mainEntityOfPage' => abs_url(guide_url($g['slug'])),
        ],
    ],
    'body' => function () use ($g, $G_crumbs, $G_svc, $G_wa, $G_choose, $G_updated) {
        $G_min = reading_minutes($g);
        $G_ts = strtotime($G_updated) ?: time();
        ob_start(); edu_diagram($g['slug']); $G_svg = ob_get_clean();
        $G_after = $G_svg !== '' ? [0 => function () use ($G_svg) { echo $G_svg; }] : [];
        // The "cómo elegir" page: its first section list becomes the hero checklist.
        $G_check = $G_choose ? ($g['sections'][0]['list'] ?? []) : [];
        $G_more = array_filter(edu_guides(), fn($o) => $o['slug'] !== $g['slug'] && $G_svc && ($o['service'] ?? '') === $G_svc['slug']);
        if (count($G_more) < 3) $G_more += array_diff_key(edu_guides(), [$g['slug'] => 1], $G_more);
        $G_more = array_slice($G_more, 0, 3);
        ?>

<section class="hero hero--inner hero--guide">
  <div class="container<?= $G_check ? ' guide-hero' : '' ?>">
    <div class="hero__narrow">
      <?php crumbs($G_crumbs); ?>
      <p class="eyebrow"><span class="eyebrow__icon"><?= guide_icon($g['slug']) ?></span><?= e($g['eyebrow'] ?? ($G_choose ? 'Cómo elegir' : 'Guía de salud dental')) ?></p>
      <h1><?= e($g['h1']) ?></h1>
      <p class="lead"><?= e($g['lead']) ?></p>
      <p class="meta"><span>Actualizada <time datetime="<?= e(date('Y-m-d', $G_ts)) ?>"><?= e(date('d/m/Y', $G_ts)) ?></time></span><span><?= $G_min ?> min de lectura</span><?php if ($G_svc): ?><span>Tratamiento: <a href="/servicios/<?= e($G_svc['slug']) ?>/"><?= e($G_svc['nav']) ?></a></span><?php endif; ?></p>
    </div>
    <?php if ($G_check): ?>
    <aside class="checklist" aria-labelledby="ck-h">
      <p class="checklist__t" id="ck-h">Lo que conviene mirar</p>
      <ol><?php foreach ($G_check as $c): ?><li><?= e($c) ?></li><?php endforeach; ?></ol>
      <a class="btn btn--accent btn--block" href="#pedir-turno" data-scroll-form>Coordiná con un matriculado<?= arrow() ?></a>
    </aside>
    <?php endif; ?>
  </div>
</section>

<div class="container article">
  <aside class="article__side">
    <?php toc_block($g['sections']); ?>
  </aside>
  <div class="article__main prose">
    <?php sections_block_anchored($g['sections'], $G_after); ?>
    <?php if ($G_svc): ?>
    <div class="inline-cta">
      <span class="inline-cta__icon"><?= service_icon($G_svc['slug']) ?></span>
      <div>
        <h2 class="h3"><?= e($G_svc['name']) ?> en Asunción</h2>
        <p><?= e($G_svc['card']) ?></p>
        <div class="btn-row"><?= wa_btn($G_wa, 'Consultá por WhatsApp', 'guide-inline', 'btn btn--white') ?>
        <a class="btn btn--outline-light" href="/servicios/<?= e($G_svc['slug']) ?>/">Ver <?= e(mb_strtolower($G_svc['nav'])) ?></a></div>
      </div>
    </div>
    <?php endif; ?>
    <?php faq_block($g['faq']); ?>
    <p class="disclaimer">Esta guía es informativa y no reemplaza la evaluación de un odontólogo. Cada caso se define en la consulta.</p>
  </div>
</div>

<?php if ($G_choose): ?>
<section class="section section--line" aria-labelledby="gz-h">
  <div class="container zones">
    <div>
      <?= eyebrow('Zonas') ?>
      <h2 id="gz-h"><?= $g['slug'] === 'consultorios-odontologicos' ? 'Consultorios' : 'Odontólogos' ?> en Asunción y <em>Gran Asunción.</em></h2>
      <p class="section-lead">No publicamos listas de clínicas. Nos decís dónde te queda cómodo y coordinamos con un odontólogo matriculado de la red, con los datos del profesional antes de confirmar.</p>
      <?php zone_links(); ?>
    </div>
    <?php zone_map(); ?>
  </div>
</section>
<section class="section section--tint" aria-labelledby="gt-h">
  <div class="container">
    <div class="section-head section-head--link"><div><?= eyebrow('Tratamientos') ?><h2 id="gt-h">¿Ya sabés qué necesitás?</h2></div><a class="link-arrow" href="/servicios/">Ver todos<?= arrow() ?></a></div>
    <?php service_cards(array_slice(array_keys(services()), 0, $g['slug'] === 'odontologia' ? 6 : 3)); ?>
  </div>
</section>
<?php elseif ($G_more): ?>
<section class="section section--line" aria-labelledby="gm-h">
  <div class="container">
    <div class="section-head section-head--link"><div><?= eyebrow('Seguí leyendo') ?><h2 id="gm-h">Otras guías de salud dental</h2></div><a class="link-arrow" href="/salud-dental/">Todas las guías<?= arrow() ?></a></div>
    <?php guide_cards($G_more); ?>
  </div>
</section>
<?php endif; ?>

<section class="section section--tint">
  <div class="container form-section">
    <div>
      <?= eyebrow('Coordiná tu consulta') ?>
      <h2>¿Querés que te vea un odontólogo?</h2>
      <p class="section-lead">Dejanos tus datos y te escribimos por WhatsApp para coordinar día, hora y profesional en Asunción o el Gran Asunción. El presupuesto no tiene costo.</p>
      <?php ticks_inline(['Presupuesto sin costo', 'Odontólogos matriculados', 'Vos decidís antes de empezar'], 'ticks-inline--stack'); ?>
    </div>
    <?php $F_service = $g['service'] ?? ''; $F_source = 'guia:' . $g['slug']; require ROOT . '/inc/lead-form.php'; ?>
  </div>
</section>
<?php }]);
