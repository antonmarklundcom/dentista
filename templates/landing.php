<?php
/*
 * Landing de Google Ads: /lp/<servicio>/. noindex, sin menú, el formulario
 * arriba de todo en móvil. URL final de los anuncios, con UTM:
 *   https://dentista.com.py/lp/brackets/?utm_source=google&utm_medium=cpc&utm_campaign=brackets
 * @var array $s
 */
render([
    'title' => $s['lp']['h1'] . ' | Dentista.com.py',
    'description' => $s['description'],
    'canonical' => abs_url('/servicios/' . $s['slug'] . '/'),
    'noindex' => true,
    'lp' => true,
    'wa' => $s['wa'],
    'bodyClass' => 'page-lp',
    'body' => function () use ($s) { $LP_img = img($s['slug'], 'hero__photo', '(min-width: 1024px) 560px, 100vw'); ?>
<section class="hero hero--lp">
  <div class="container hero__grid">
    <div class="hero__copy">
      <?= eyebrow('Asunción y Gran Asunción', true) ?>
      <h1><?= e($s['lp']['h1']) ?></h1>
      <p class="lead"><?= e($s['lp']['sub']) ?></p>
      <ul class="ticks ticks--lg lp-bullets">
        <?php foreach ($s['lp']['bullets'] as $b): ?><li><?= e($b) ?></li><?php endforeach; ?>
      </ul>
    </div>
    <div class="hero__form">
      <?php $F_service = $s['slug']; $F_source = 'lp:' . $s['slug']; $F_title = 'Pedí tu evaluación'; $F_variant = 'compact';
            $F_only = array_merge([$s['slug']], $s['related'] ?? []); require ROOT . '/inc/lead-form.php'; ?>
      <?php ticks_inline(['Odontólogos matriculados', 'El horario que te sirva'], 'ticks-inline--center'); ?>
    </div>
    <?php if ($LP_img): ?><div class="hero__media has-img"><?= $LP_img ?></div><?php endif; ?>
  </div>
</section>

<section class="section section--line" aria-labelledby="why-h">
  <div class="container">
    <h2 id="why-h">Por qué pedirlo <em>por acá</em></h2>
    <ul class="why">
      <li><span class="features__i features__i--sand"><?= icon('idcard') ?></span><div><h3>Sabés quién te atiende</h3><p>Te pasamos nombre, matrícula y consultorio del profesional antes de confirmar.</p></div></li>
      <li><span class="features__i features__i--sand"><?= icon('doc') ?></span><div><h3>Presupuesto sin costo</h3><p>No se empieza nada hasta que vos digas que sí.</p></div></li>
      <li><span class="features__i features__i--sand"><?= icon('pin') ?></span><div><h3>Cerca tuyo, en tu horario</h3><p>Asunción y Gran Asunción. Coordinamos el horario que te sirva.</p></div></li>
    </ul>
  </div>
</section>

<section class="section section--tint" aria-labelledby="lph-h">
  <div class="container">
    <h2 id="lph-h">Cómo funciona</h2>
    <?php how_block([
        ['Contanos qué necesitás', 'Tratamiento, zona y para cuándo.'],
        ['Te escribimos', 'Por WhatsApp, con horarios.'],
        ['Conocés al profesional', 'Datos y matrícula antes de ir.'],
        ['Vos decidís', 'Presupuesto sin costo.'],
    ], 'how--compact'); ?>
  </div>
</section>

<?php before_after_block($s['slug'], 'Antes y después'); ?>

<section class="section" aria-label="Preguntas frecuentes">
  <div class="container narrow">
    <?php faq_block(array_slice($s['faq'], 0, 4), 'Preguntas frecuentes', true); ?>
  </div>
</section>

<?php cta_band('Empezá con un presupuesto', 'Contanos tu caso y te confirmamos disponibilidad y los datos del profesional.', $s['wa'], 'lp-cta', 'sin costo.', 'Pedí tu presupuesto'); ?>
<?php }]);
