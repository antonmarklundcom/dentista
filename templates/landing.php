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
    'body' => function () use ($s) { ?>
<section class="hero hero--lp">
  <div class="container hero__grid">
    <div class="hero__copy">
      <p class="eyebrow">Asunción · Gran Asunción</p>
      <h1><?= e($s['lp']['h1']) ?></h1>
      <p class="lead"><?= e($s['lp']['sub']) ?></p>
      <ul class="ticks ticks--lg">
        <?php foreach ($s['lp']['bullets'] as $b): ?><li><?= e($b) ?></li><?php endforeach; ?>
      </ul>
      <div class="btn-row">
        <a class="btn btn--wa" href="<?= e(wa_link($s['wa'])) ?>" data-wa data-ev-loc="lp-hero" rel="noopener" target="_blank"><?php require ROOT . '/inc/icon-wa.php'; ?>Prefiero WhatsApp</a>
      </div>
    </div>
    <div class="hero__form">
      <?php $F_service = $s['slug']; $F_source = 'lp:' . $s['slug']; $F_title = 'Pedí tu evaluación'; require ROOT . '/inc/lead-form.php'; ?>
    </div>
  </div>
</section>
<?php promise_band(); ?>
<section class="section">
  <div class="container split">
    <div class="split__sticky">
      <p class="eyebrow"><?= e($s['name']) ?></p>
      <h2>Qué pasa después de dejar tus datos</h2>
      <p>Te escribimos por WhatsApp, coordinamos el turno y te pasamos los datos del odontólogo matriculado antes de confirmar.</p>
      <a class="btn btn--primary" href="#pedir-turno" data-scroll-form>Pedí tu turno</a>
    </div>
    <ol class="rail">
      <?php foreach ($s['steps'] as $i => $st): ?><li><span class="rail__n"><?= sprintf('%02d', $i + 1) ?></span><p><?= e($st) ?></p></li><?php endforeach; ?>
    </ol>
  </div>
</section>
<section class="section section--tint">
  <div class="container narrow">
    <?php faq_block(array_slice($s['faq'], 0, 4)); ?>
  </div>
</section>
<?php cta_band('Pedí tu evaluación hoy.', 'Presupuesto sin costo. No se empieza nada hasta que vos digas que sí.', $s['wa'], 'lp-cta'); ?>
<?php }]);
