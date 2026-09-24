<?php
/** @var array $z @var string $zslug */
$Z_crumbs = [['Inicio', '/'], ['Zonas', '/zonas/'], [$z['name'], '/zonas/' . $zslug . '/']];
$Z_wa = 'Hola, vengo de dentista.com.py — estoy en ' . $z['name'] . ' y quiero coordinar una consulta odontológica.';
render([
    'title' => $z['title'],
    'description' => $z['description'],
    'wa' => $Z_wa,
    'bodyClass' => 'page-zone',
    'schema' => [
        breadcrumb_schema($Z_crumbs),
        faq_schema($z['faq'] ?? []),
        [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => 'Coordinación de consultas odontológicas en ' . $z['name'],
            'serviceType' => 'Odontología',
            'areaServed' => ['@type' => 'City', 'name' => $z['name']],
            'provider' => ['@type' => 'Organization', 'name' => site()['name'], 'url' => base_url() . '/'],
        ],
    ],
    'body' => function () use ($z, $zslug, $Z_crumbs, $Z_wa) { ?>

<section class="hero hero--inner">
  <div class="container hero__grid">
    <div class="hero__copy">
      <?php crumbs($Z_crumbs); ?>
      <p class="eyebrow"><span class="eyebrow__icon"><?= icon('pin') ?></span><?= e($z['name']) ?> · Departamento Central</p>
      <h1><?= e($z['h1']) ?></h1>
      <p class="lead"><?= e($z['lead']) ?></p>
      <div class="btn-row">
        <?= wa_btn($Z_wa, 'Escribinos por WhatsApp', 'zone-hero', 'btn btn--ink btn--lg') ?>
      </div>
      <?php ticks_inline(['Presupuesto sin costo', 'Odontólogos matriculados', 'El horario que te sirva']); ?>
    </div>
    <div class="hero__form">
      <?php $F_zone = $zslug; $F_source = 'zona:' . $zslug; $F_title = 'Pedí tu turno en ' . $z['name']; require ROOT . '/inc/lead-form.php'; ?>
    </div>
  </div>
</section>

<?php promise_band(); ?>

<div class="container article">
  <aside class="article__side">
    <?php toc_block($z['body']); ?>
  </aside>
  <div class="article__main prose">
    <?php sections_block_anchored($z['body']); ?>
    <?php faq_block($z['faq'] ?? []); ?>
  </div>
</div>

<section class="section section--tint">
  <div class="container">
    <div class="section-head section-head--link"><div><?= eyebrow('Tratamientos') ?><h2>Qué podés consultar desde <?= e($z['name']) ?></h2></div><a class="link-arrow" href="/servicios/">Ver todos<?= arrow() ?></a></div>
    <?php service_cards(); ?>
    <p class="zone-inline"><?php if (!empty($z['nearby'])): ?>Cerca de <?= e($z['name']) ?> también coordinamos en <?= e(implode(', ', $z['nearby'])) ?> y <a href="/">Asunción</a>. <?php endif; ?>Ver <a href="/zonas/">todas las zonas</a>.</p>
  </div>
</section>

<?php cta_band('Tu consulta en ' . $z['name'] . ',', 'Contanos qué necesitás y te confirmamos disponibilidad por WhatsApp.', $Z_wa, 'zone-cta', 'coordinada hoy.'); ?>
<?php }]);
