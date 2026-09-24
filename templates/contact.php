<?php
$C_crumbs = [['Inicio', '/'], ['Contacto', '/contacto/']];
$C_wa = 'Hola, vengo de dentista.com.py — quiero coordinar una consulta odontológica.';
render([
    'title' => 'Contacto: pedí turno con un dentista | Dentista.com.py',
    'description' => 'Pedí turno con un dentista en Asunción y Gran Asunción. Escribinos por WhatsApp o dejanos tus datos y coordinamos tu consulta. Presupuesto sin costo.',
    'wa' => $C_wa,
    'bodyClass' => 'page-contact',
    'schema' => [breadcrumb_schema($C_crumbs), org_schema()],
    'body' => function () use ($C_crumbs, $C_wa) { ?>
<section class="hero hero--inner">
  <div class="container hero__grid">
    <div class="hero__copy">
      <?php crumbs($C_crumbs); ?>
      <?= eyebrow('Contacto', true) ?>
      <h1>Contanos qué necesitás</h1>
      <p class="lead">La forma más rápida es WhatsApp. Si preferís que te escribamos nosotros, dejanos tus datos y te contactamos para coordinar día, hora y profesional.</p>
      <div class="wa-card">
        <h2 class="h3">WhatsApp</h2>
        <p>Es por donde respondemos más rápido.</p>
        <?= wa_btn($C_wa, 'Escribinos por WhatsApp', 'contact', 'btn btn--white') ?>
        <?php if (cfg('phone_display')): ?><p class="micro"><?= e(cfg('phone_display')) ?></p><?php endif; ?>
      </div>
      <?php ticks_inline(['Presupuesto sin costo', 'Te pasamos los datos del profesional antes de confirmar', 'No pedimos pagos ni datos de tarjeta por acá'], 'ticks-inline--stack'); ?>
    </div>
    <div class="hero__form"><?php $F_source = 'contacto'; require ROOT . '/inc/lead-form.php'; ?></div>
  </div>
</section>
<?php partner_band(); ?>
<?php }]);
