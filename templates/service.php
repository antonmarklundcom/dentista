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
    'body' => function () use ($s, $S_crumbs) { ?>

<section class="hero hero--inner">
  <div class="container hero__grid">
    <div class="hero__copy">
      <?php crumbs($S_crumbs); ?>
      <h1><?= e($s['h1']) ?></h1>
      <p class="lead"><?= e($s['lead']) ?></p>
      <?php if (!empty($s['highlights'])): ?><ul class="chips"><?php foreach ($s['highlights'] as $h): ?><li><?= e($h) ?></li><?php endforeach; ?></ul><?php endif; ?>
      <div class="btn-row">
        <a class="btn btn--wa" href="<?= e(wa_link($s['wa'])) ?>" data-wa data-ev-loc="service-hero" rel="noopener" target="_blank"><?php require ROOT . '/inc/icon-wa.php'; ?>Consultá por WhatsApp</a>
      </div>
      <p class="micro">Presupuesto sin costo. No se empieza nada hasta que vos digas que sí.</p>
    </div>
    <div class="hero__form">
      <?php $F_service = $s['slug']; $F_source = 'servicio:' . $s['slug']; $F_title = 'Pedí tu turno para ' . mb_strtolower($s['nav']); require ROOT . '/inc/lead-form.php'; ?>
    </div>
  </div>
</section>

<?php promise_band(); ?>

<div class="container article">
  <aside class="article__side"><?php toc_block($s['sections']); ?></aside>
  <div class="article__main prose">
    <?php sections_block_anchored($s['sections']); ?>
    <?php if (!empty($s['steps'])) steps_block($s['steps'], 'Cómo es la primera consulta de ' . mb_strtolower($s['nav'])); ?>
    <?php faq_block($s['faq'], 'Preguntas frecuentes sobre ' . $s['keyword']); ?>
  </div>
</div>

<?php if (!empty($s['guides'])): ?>
<section class="section section--tint">
  <div class="container">
    <div class="section-head"><p class="eyebrow">Para leer antes</p><h2>Guías relacionadas</h2></div>
    <div class="guide-grid">
      <?php foreach ($s['guides'] as $gs): if (!isset(guides()[$gs])) continue; $G = guides()[$gs]; ?>
      <a class="card card--hair guide-card" href="<?= e(guide_url($gs)) ?>"><h3><?= e($G['h1']) ?></h3><p><?= e($G['card'] ?? mb_strimwidth($G['lead'], 0, 170, '…')) ?></p><span class="svc-card__more">Leer guía →</span></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container">
    <div class="section-head"><p class="eyebrow">También te puede interesar</p><h2>Otros tratamientos</h2></div>
    <?php service_cards($s['related'] ?? []); ?>
    <p class="zone-inline">Coordinamos <?= e(mb_strtolower($s['nav'])) ?> en <a href="/">Asunción</a><?php foreach (zones() as $zs => $zz): ?>, <a href="/zonas/<?= e($zs) ?>/"><?= e($zz['name']) ?></a><?php endforeach; ?>.</p>
  </div>
</section>

<?php cta_band('¿Querés consultar por ' . mb_strtolower($s['nav']) . '?', 'Contanos tu caso y te confirmamos disponibilidad y los datos del profesional.', $s['wa'], 'service-cta'); ?>
<?php }]);
