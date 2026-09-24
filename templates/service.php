<?php
/** @var array $s */
$S_crumbs = [['Inicio', '/'], ['Tratamientos', '/servicios/'], [$s['nav'], '/servicios/' . $s['slug'] . '/']];
render([
    'title' => $s['title'],
    'description' => $s['description'],
    'wa' => $s['wa'],
    'bodyClass' => 'page-service',
    'schema' => [
        breadcrumb_schema($S_crumbs),
        faq_schema($s['faq']),
        [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $s['name'],
            'serviceType' => $s['keyword'],
            'description' => $s['description'],
            'areaServed' => ['@type' => 'City', 'name' => 'Asunción'],
            'provider' => ['@type' => 'Organization', 'name' => site()['name'], 'url' => base_url() . '/'],
            'url' => abs_url('/servicios/' . $s['slug'] . '/'),
        ],
    ],
    'body' => function () use ($s, $S_crumbs) {
        $S_img = img($s['slug'], 'hero__photo', '(min-width: 1024px) 620px, 100vw', true);
        // Guides: the ones listed on the service + any guide that points to this service.
        $S_guides = [];
        foreach ($s['guides'] ?? [] as $gs) if (isset(guides()[$gs])) $S_guides[$gs] = guides()[$gs];
        foreach (edu_guides() as $gs => $G) if (($G['service'] ?? '') === $s['slug']) $S_guides[$gs] = $G;
        $S_related = array_values(array_filter($s['related'] ?? [], fn($r) => isset(services()[$r]) && $r !== $s['slug']));
        foreach (array_keys(services()) as $r) { if (count($S_related) >= 3) break; if ($r !== $s['slug'] && !in_array($r, $S_related, true)) $S_related[] = $r; }
        $S_diagram = [];
        ob_start(); edu_diagram($s['slug']); $S_svg = ob_get_clean();
        if ($S_svg !== '') $S_diagram[0] = function () use ($S_svg) { echo $S_svg; };
        ?>

<section class="hero hero--inner">
  <div class="container hero__grid">
    <div class="hero__copy">
      <?php crumbs($S_crumbs); ?>
      <p class="eyebrow"><span class="eyebrow__icon"><?= service_icon($s['slug']) ?></span><?= e($s['nav']) ?> · Asunción y Gran Asunción</p>
      <h1><?= e($s['h1']) ?></h1>
      <p class="lead"><?= e($s['lead']) ?></p>
      <?php if (!empty($s['highlights'])) ticks_inline($s['highlights'], 'ticks-inline--stack'); ?>
      <div class="btn-row">
        <?= wa_btn($s['wa'], 'Consultá por WhatsApp', 'service-hero', 'btn btn--ink btn--lg') ?>
      </div>
      <p class="micro">Presupuesto sin costo. No se empieza nada hasta que vos digas que sí.</p>
    </div>
    <?php if ($S_img): ?><div class="hero__media has-img"><?= $S_img ?></div><?php endif; ?>
    <div class="hero__form">
      <?php $F_service = $s['slug']; $F_source = 'servicio:' . $s['slug']; $F_title = 'Pedí tu turno'; require ROOT . '/inc/lead-form.php'; ?>
    </div>
  </div>
</section>

<?php promise_band(); ?>

<div class="container article">
  <aside class="article__side">
    <?php toc_block($s['sections']); ?>
    <div class="side-cta">
      <p class="side-cta__t">¿Querés consultar por <?= e(mb_strtolower($s['nav'])) ?>?</p>
      <?= wa_btn($s['wa'], 'Escribinos', 'service-side', 'btn btn--ink btn--sm btn--block') ?>
    </div>
  </aside>
  <div class="article__main prose">
    <?php sections_block_anchored($s['sections'], $S_diagram); ?>
    <?php if (!empty($s['steps'])) steps_block($s['steps'], 'Cómo es la primera consulta de ' . mb_strtolower($s['nav'])); ?>
    <?php faq_block($s['faq'], 'Preguntas frecuentes sobre ' . $s['keyword']); ?>
    <p class="disclaimer">La información es orientativa y no reemplaza la evaluación de un odontólogo. Cada caso se define en la consulta.</p>
  </div>
</div>

<?php before_after_block($s['slug'], 'Antes y después: ' . mb_strtolower($s['nav']), 'section section--tint'); ?>

<?php if ($S_guides): ?>
<section class="section section--line" aria-labelledby="sg-h">
  <div class="container">
    <div class="section-head section-head--link"><div><?= eyebrow('Para leer antes') ?><h2 id="sg-h">Guías sobre <?= e(mb_strtolower($s['nav'])) ?></h2></div>
      <a class="link-arrow" href="/salud-dental/">Todas las guías<?= arrow() ?></a></div>
    <?php guide_cards(array_slice($S_guides, 0, 3), false); ?>
  </div>
</section>
<?php endif; ?>

<section class="section section--tint" aria-labelledby="rel-h">
  <div class="container">
    <div class="section-head section-head--link"><div><?= eyebrow('También te puede interesar') ?><h2 id="rel-h">Otros tratamientos</h2></div>
      <a class="link-arrow" href="/servicios/">Ver todos los tratamientos<?= arrow() ?></a></div>
    <?php service_cards($S_related); ?>
    <p class="zone-inline">Coordinamos <?= e(mb_strtolower($s['nav'])) ?> en <a href="/">Asunción</a><?php foreach (zones() as $zs => $zz): ?>, <a href="/zonas/<?= e($zs) ?>/"><?= e($zz['name']) ?></a><?php endforeach; ?>.</p>
  </div>
</section>

<?php cta_band('¿Querés consultar por ' . mb_strtolower($s['nav']) . '?', 'Contanos tu caso y te confirmamos disponibilidad y los datos del profesional.', $s['wa'], 'service-cta'); ?>
<?php }]);
