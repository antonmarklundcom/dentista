<?php
/** @var string $hub servicios|zonas|guias */
$HUB = [
    'servicios' => ['Tratamientos', 'Tratamientos dentales en Asunción | Dentista.com.py', 'Brackets, limpieza dental, blanqueamiento, implantes, muela del juicio y odontopediatría en Asunción. Coordinamos tu consulta. Presupuesto sin costo.', 'Tratamientos dentales en Asunción', 'Elegí el tratamiento que te interesa para ver cómo es, qué conviene saber antes y cómo es la primera consulta. Si no sabés qué necesitás, pedí una revisión general.'],
    'zonas'     => ['Zonas', 'Dentista en Gran Asunción: zonas | Dentista.com.py', 'Coordinamos consultas odontológicas en Asunción, San Lorenzo, Luque, Lambaré, Fernando de la Mora, Capiatá y Mariano Roque Alonso. Escribinos por WhatsApp.', 'Dentista en el Gran Asunción', 'Coordinamos tu consulta lo más cerca posible de donde vivís o trabajás. Elegí tu ciudad.'],
    'guias'     => ['Salud dental', 'Salud dental: guías claras | Dentista.com.py', 'Guías claras sobre salud dental: curetaje, dientes postizos, dolor de muelas y más. Qué es, cuándo consultar y qué preguntar al odontólogo en Asunción.', 'Guías de salud dental', 'Información clara para llegar a la consulta sabiendo qué preguntar. Ninguna guía reemplaza la evaluación de un odontólogo.'],
][$hub];
[$HB_label, $HB_title, $HB_desc, $HB_h1, $HB_lead] = $HUB;
$HB_crumbs = [['Inicio', '/'], [$HB_label, $hub === 'guias' ? '/salud-dental/' : "/$hub/"]];
render([
    'title' => $HB_title,
    'description' => $HB_desc,
    'schema' => [breadcrumb_schema($HB_crumbs)],
    'body' => function () use ($hub, $HB_crumbs, $HB_h1, $HB_lead) { ?>
<section class="hero hero--inner">
  <div class="container narrow">
    <?php crumbs($HB_crumbs); ?>
    <h1><?= e($HB_h1) ?></h1>
    <p class="lead"><?= e($HB_lead) ?></p>
  </div>
</section>
<section class="section section--flush">
  <div class="container">
  <?php if ($hub === 'servicios'): service_cards(null, 'is-home'); ?>
  <?php elseif ($hub === 'zonas'): ?>
    <div class="guide-grid">
      <a class="card card--ink guide-card" href="/"><h3>Asunción</h3><p>Coordinamos consultas en toda la capital, cerca de tu casa o de tu trabajo.</p><span class="svc-card__more">Ver →</span></a>
      <?php foreach (zones() as $zs => $zz): ?>
      <a class="card card--hair guide-card" href="/zonas/<?= e($zs) ?>/"><h3><?= e($zz['name']) ?></h3><p><?= e(mb_strimwidth($zz['lead'], 0, 150, '…')) ?></p><span class="svc-card__more">Ver zona →</span></a>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="guide-grid">
      <?php foreach (edu_guides() as $G): ?>
      <a class="card card--hair guide-card" href="<?= e(guide_url($G['slug'])) ?>"><h3><?= e($G['h1']) ?></h3><p><?= e($G['card'] ?? mb_strimwidth($G['lead'], 0, 170, '…')) ?></p><span class="svc-card__more">Leer guía →</span></a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  </div>
</section>
<section class="section section--tint">
  <div class="container form-section">
    <div>
      <p class="eyebrow">Coordiná tu consulta</p>
      <h2>¿No sabés por dónde empezar?</h2>
      <p>Pedí una revisión general. El odontólogo te revisa, te explica qué encontró y te pasa el presupuesto sin costo. Después decidís vos.</p>
    </div>
    <?php $F_source = 'hub:' . $hub; require ROOT . '/inc/lead-form.php'; ?>
  </div>
</section>
<?php partner_band(); ?>
<?php }]);
