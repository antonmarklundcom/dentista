<?php
/** @var array $g */
$G_crumbs = $g['slug'] === 'consultorios-odontologicos'
    ? [['Inicio', '/'], [$g['nav'] ?? $g['h1'], guide_url($g['slug'])]]
    : [['Inicio', '/'], ['Salud dental', '/salud-dental/'], [$g['nav'] ?? $g['h1'], guide_url($g['slug'])]];
$G_svc = services()[$g['service'] ?? ''] ?? null;
$G_wa = $G_svc['wa'] ?? 'Hola, vengo de dentista.com.py — leí la guía sobre ' . $g['keyword'] . ' y quiero consultar.';
render([
    'title' => $g['title'],
    'description' => $g['description'],
    'wa' => $G_wa,
    'bodyClass' => 'page-guide',
    'schema' => [
        breadcrumb_schema($G_crumbs),
        faq_schema($g['faq']),
        [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $g['h1'],
            'description' => $g['description'],
            'inLanguage' => 'es-PY',
            'dateModified' => $g['updated'] ?? date('Y-m-d'),
            'author' => ['@type' => 'Organization', 'name' => site()['name']],
            'publisher' => ['@type' => 'Organization', 'name' => site()['name'], 'logo' => ['@type' => 'ImageObject', 'url' => abs_url('/assets/img/favicon.svg')]],
            'mainEntityOfPage' => abs_url(guide_url($g['slug'])),
        ],
    ],
    'body' => function () use ($g, $G_crumbs, $G_svc, $G_wa) { ?>

<section class="hero hero--inner hero--guide">
  <div class="container narrow">
    <?php crumbs($G_crumbs); ?>
    <p class="eyebrow">Guía · Actualizada <?= e(date('d/m/Y', strtotime($g['updated'] ?? 'now'))) ?></p>
    <h1><?= e($g['h1']) ?></h1>
    <p class="lead"><?= e($g['lead']) ?></p>
  </div>
</section>

<div class="container article">
  <aside class="article__side">
    <?php toc_block($g['sections']); ?>
  </aside>
  <div class="article__main prose">
    <?php sections_block_anchored($g['sections']); ?>
    <?php if ($G_svc): ?>
    <div class="card card--accent inline-cta">
      <h3><?= e($G_svc['name']) ?> en Asunción</h3>
      <p><?= e($G_svc['card']) ?></p>
      <div class="btn-row"><a class="btn btn--wa-light" href="<?= e(wa_link($G_wa)) ?>" data-wa data-ev-loc="guide-inline" rel="noopener" target="_blank"><?php require ROOT . '/inc/icon-wa.php'; ?>Consultá por WhatsApp</a>
      <a class="btn btn--ghost-light" href="/servicios/<?= e($G_svc['slug']) ?>/">Ver tratamiento</a></div>
    </div>
    <?php endif; ?>
    <?php faq_block($g['faq']); ?>
    <p class="disclaimer">Esta guía es informativa y no reemplaza la evaluación de un odontólogo. Cada caso se define en la consulta.</p>
  </div>
</div>

<section class="section section--tint">
  <div class="container form-section">
    <div>
      <p class="eyebrow">Coordiná tu consulta</p>
      <h2>¿Querés que te vea un odontólogo?</h2>
      <p>Dejanos tus datos y te escribimos por WhatsApp para coordinar día, hora y profesional en Asunción o el Gran Asunción. El presupuesto no tiene costo.</p>
    </div>
    <?php $F_service = $g['service'] ?? ''; $F_source = 'guia:' . $g['slug']; require ROOT . '/inc/lead-form.php'; ?>
  </div>
</section>
<?php }]);
