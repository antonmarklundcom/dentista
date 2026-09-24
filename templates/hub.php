<?php
/** @var string $hub servicios|zonas|guias */
$HUB = [
    'servicios' => ['Tratamientos', 'Tratamientos dentales en Asunción | Dentista.com.py', 'Brackets, prótesis, limpieza dental, blanqueamiento, implantes, muela del juicio y odontopediatría en Asunción. Coordinamos tu consulta. Presupuesto sin costo.', 'Tratamientos dentales en Asunción', 'Elegí el tratamiento que te interesa para ver cómo es, qué conviene saber antes y cómo es la primera consulta. Si no sabés qué necesitás, pedí una revisión general.'],
    'zonas'     => ['Zonas', 'Dentista en Gran Asunción: zonas | Dentista.com.py', 'Coordinamos consultas odontológicas en Asunción, San Lorenzo, Luque, Lambaré, Fernando de la Mora, Capiatá y Mariano Roque Alonso. Escribinos por WhatsApp.', 'Dentista en el Gran Asunción', 'Coordinamos tu consulta lo más cerca posible de donde vivís o trabajás. Elegí tu ciudad.'],
    'guias'     => ['Salud dental', 'Salud dental: guías claras | Dentista.com.py', 'Guías claras de salud dental: sarro, placa bacteriana, gingivitis, dolor de muelas y más. Qué es, cuándo consultar y qué preguntar al odontólogo en Asunción.', 'Salud dental: guías claras antes de la consulta', 'Información clara para llegar a la consulta sabiendo qué preguntar. Ninguna guía reemplaza la evaluación de un odontólogo.'],
][$hub];
[$HB_label, $HB_title, $HB_desc, $HB_h1, $HB_lead] = $HUB;
$HB_crumbs = [['Inicio', '/'], [$HB_label, $hub === 'guias' ? '/salud-dental/' : "/$hub/"]];
render([
    'title' => $HB_title,
    'description' => $HB_desc,
    'bodyClass' => 'page-hub page-hub--' . $hub,
    'schema' => [breadcrumb_schema($HB_crumbs)],
    'body' => function () use ($hub, $HB_crumbs, $HB_h1, $HB_lead, $HB_label) { ?>
<section class="hero hero--inner hero--hub">
  <div class="container"><div class="hero__narrow">
    <?php crumbs($HB_crumbs); ?>
    <?= eyebrow($HB_label, true) ?>
    <h1><?= e($HB_h1) ?></h1>
    <p class="lead"><?= e($HB_lead) ?></p>
  </div></div>
</section>
<section class="section section--flush">
  <div class="container">
  <?php if ($hub === 'servicios'): bento_services(isset(guides()['dolor-de-muelas']) ? guide_url('dolor-de-muelas') : '/contacto/'); ?>
  <?php elseif ($hub === 'zonas'): ?>
    <div class="zones">
      <div>
        <h2>Elegí tu ciudad</h2>
        <?php zone_links(); ?>
      </div>
      <?php zone_map(); ?>
    </div>
    <div class="card-grid card-grid--zones">
      <?php foreach (zones() as $zs => $zz): ?>
      <article class="card card--link"><span class="card__icon"><?= icon('pin') ?></span><h3><a class="stretch" href="/zonas/<?= e($zs) ?>/"><?= e($zz['name']) ?></a></h3><p><?= e(mb_strimwidth($zz['lead'], 0, 150, '…')) ?></p><span class="link-arrow">Ver zona<?= arrow() ?></span></article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <?php guide_cards(edu_guides()); ?>
    <?php if (isset(guides()['consultorios-odontologicos'])): $CG = guides()['consultorios-odontologicos']; ?>
    <a class="feature-link" href="<?= e(guide_url('consultorios-odontologicos')) ?>">
      <span class="features__i features__i--sand"><?= icon('clinic') ?></span>
      <span><span class="feature-link__k">Cómo elegir</span><span class="feature-link__t"><?= e($CG['h1']) ?></span><span class="feature-link__p"><?= e($CG['card'] ?? '') ?></span></span>
      <?= arrow() ?>
    </a>
    <?php endif; ?>
  <?php endif; ?>
  </div>
</section>
<?php if ($hub === 'servicios') before_after_block(null, 'Así puede cambiar una sonrisa', 'section section--line'); ?>
<section class="section section--tint">
  <div class="container form-section">
    <div>
      <?= eyebrow('Coordiná tu consulta') ?>
      <h2>¿No sabés por dónde empezar?</h2>
      <p class="section-lead">Pedí una revisión general. El odontólogo te revisa, te explica qué encontró y te pasa el presupuesto sin costo. Después decidís vos.</p>
      <?php ticks_inline(['Presupuesto sin costo', 'Odontólogos matriculados', 'Vos decidís antes de empezar'], 'ticks-inline--stack'); ?>
    </div>
    <?php $F_source = 'hub:' . $hub; require ROOT . '/inc/lead-form.php'; ?>
  </div>
</section>
<?php partner_band(); ?>
<?php }]);
