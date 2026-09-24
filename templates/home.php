<?php
$H_faq = [
    ['q' => '¿Dentista.com.py es una clínica?', 'a' => 'No. Coordinamos tu consulta con odontólogos matriculados independientes de Asunción y Gran Asunción. La atención la hace el profesional, en su consultorio.'],
    ['q' => '¿Tiene costo pedir el turno?', 'a' => 'No. Pedir el turno no tiene costo y el presupuesto tampoco. El odontólogo te evalúa y te pasa el presupuesto por escrito; vos decidís si seguís.'],
    ['q' => '¿Cuánto sale el tratamiento?', 'a' => 'Depende de lo que encuentren cuando te revisen, así que no publicamos precios: un número puesto antes de verte la boca no te sirve para decidir. El presupuesto se te pasa en la consulta, por escrito y sin costo, y recién ahí decidís si seguís.'],
    ['q' => '¿Cómo sé quién me va a atender?', 'a' => 'Antes de confirmar te pasamos por WhatsApp el nombre del profesional, su matrícula y la dirección del consultorio.'],
    ['q' => '¿Cómo hago para sacar turno con un dentista?', 'a' => 'Completá el formulario o escribinos por WhatsApp contando qué necesitás y en qué zona estás. Te respondemos con la disponibilidad, coordinamos día y horario y te confirmamos los datos del profesional. No hace falta llamar ni ir hasta el consultorio para sacar el turno.'],
    ['q' => '¿Qué hacen con mis datos?', 'a' => 'Los compartimos solo con el odontólogo que coordina tu consulta y solo si aceptás. Podés pedir que los borremos cuando quieras.'],
    ['q' => '¿Y si tengo dolor ahora mismo?', 'a' => 'Escribinos igual y contanos hace cuánto te duele, si tenés la cara hinchada y si estás tomando algo. Buscamos el turno más cercano que haya. Si tenés la cara muy hinchada, fiebre o te cuesta tragar o respirar, andá directo a una guardia.'],
    ['q' => '¿Coordinan consultas para chicos?', 'a' => 'Sí, coordinamos consultas de odontopediatría. La primera visita suele ser corta y sirve para que el chico conozca el consultorio y se anime. Si viene con miedo, avisanos antes y lo tenemos en cuenta al coordinar el turno.'],
];
$H_wa = 'Hola, vengo de dentista.com.py — quiero coordinar una consulta odontológica.';

render([
    'title' => 'Dentista en Asunción: pedí tu turno sin costo',
    'description' => 'Dentista en Asunción y Gran Asunción: coordinamos tu consulta con odontólogos matriculados. Brackets, limpieza, implantes. Presupuesto sin costo.',
    'canonical' => base_url() . '/',
    'wa' => $H_wa,
    'bodyClass' => 'page-home',
    'schema' => [org_schema(), faq_schema($H_faq)],
    'body' => function () use ($H_faq, $H_wa) { $H_img = img('hero', 'hero__photo', '(min-width: 1024px) 620px, 100vw', true); ?>

<section class="hero hero--home">
  <div class="container hero__grid">
    <div class="hero__copy">
      <?= eyebrow('Dentistas en Asunción y Gran Asunción', true) ?>
      <h1 class="display">Tu dentista en Asunción, <em>coordinado</em> por WhatsApp.</h1>
      <p class="lead">Contanos qué tratamiento buscás y dónde estás. Te conectamos con un odontólogo matriculado de tu zona, con presupuesto sin costo y en el horario que te sirva.</p>
      <div class="btn-row">
        <?= wa_btn($H_wa, 'Escribinos por WhatsApp', 'hero', 'btn btn--ink btn--lg') ?>
        <a class="link-arrow" href="#como-funciona">Ver cómo funciona<?= arrow() ?></a>
      </div>
      <?php ticks_inline(['Presupuesto sin costo', 'Odontólogos matriculados', 'Vos decidís antes de empezar']); ?>
    </div>
    <div class="hero__media<?= $H_img ? ' has-img' : '' ?>">
      <?php if ($H_img): echo $H_img; else: ?>
      <svg class="odontogram" viewBox="0 0 616 104" fill="none" role="img" aria-label="Odontograma ilustrativo con la pieza 26 señalada">
        <defs><path id="odt" d="M7 2C3.5 2 1.5 5 1.5 10c0 6 2.5 9 3.5 15 .8 5 1.5 11 4 11s2.5-7 6-7 3.5 7 6 7 3.2-6 4-11c1-6 3.5-9 3.5-15 0-5-2-8-5.5-8-3 0-4.5 2-8 2S10 2 7 2z"/></defs>
        <rect x="436" y="0" width="154" height="24" rx="12" fill="#14241E"/>
        <text x="513" y="16" text-anchor="middle" font-size="12" font-weight="500" fill="#F7F4ED">“me molesta esta”</text>
        <path d="M513 24v8" stroke="#14241E" stroke-width="1.4"/>
        <g stroke="#14241E" stroke-width="1.3" stroke-linejoin="round"><?php foreach ([4,42,80,118,156,194,232,270,308,346,384,422,460,536,574] as $x): ?><use href="#odt" x="<?= $x ?>" y="36"/><?php endforeach; ?></g>
        <use href="#odt" x="498" y="36" fill="#E7B8A3" stroke="#A44C29" stroke-width="1.6" stroke-linejoin="round"/>
        <path d="M304 32v58" stroke="#A39A88" stroke-width="1" stroke-dasharray="3 3"/>
        <g font-size="11" fill="#4A5A53" text-anchor="middle"><?php foreach ([18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28] as $i => $n): ?><text x="<?= 19 + $i * 38 ?>" y="98"<?= $n === 26 ? ' fill="#A44C29" font-weight="600"' : '' ?>><?= $n ?></text><?php endforeach; ?></g>
      </svg>
      <?php endif; ?>
    </div>
    <div class="hero__form">
      <?php $F_source = 'home-hero'; require ROOT . '/inc/lead-form.php'; ?>
    </div>
  </div>
</section>

<?php promise_band(); ?>

<section class="section" id="tratamientos" aria-labelledby="trat-h">
  <div class="container">
    <div class="section-head section-head--split">
      <div><?= eyebrow('Tratamientos') ?><h2 id="trat-h">¿Qué necesitás <em>resolver?</em></h2></div>
      <p>Elegí un tratamiento y te coordinamos una consulta con un odontólogo matriculado que lo hace todos los días, cerca tuyo.</p>
    </div>
    <?php bento_services('#urgencias'); ?>
  </div>
</section>

<?php urgency_block(); ?>

<section class="section" id="como-funciona" aria-labelledby="how-h">
  <div class="container">
    <?= eyebrow('Cómo funciona') ?>
    <h2 id="how-h">Cuatro pasos. <em>Vos decidís</em> en cada uno.</h2>
    <?php how_block(how_patient()); ?>
  </div>
</section>

<?php trust_block(); ?>

<?php before_after_block(null, 'Así puede cambiar una sonrisa'); ?>

<?php $H_g = array_slice(edu_guides(), 0, 4); if ($H_g): ?>
<section class="section" id="guias" aria-labelledby="guias-h">
  <div class="container">
    <div class="section-head section-head--link">
      <div><?= eyebrow('Salud dental') ?><h2 id="guias-h">Entendé qué te pasa <em>antes de la consulta.</em></h2></div>
      <a class="link-arrow" href="/salud-dental/">Ver todas las guías<?= arrow() ?></a>
    </div>
    <?php guide_cards($H_g); ?>
  </div>
</section>
<?php endif; ?>

<section class="section section--line" id="zonas" aria-labelledby="zonas-h">
  <div class="container zones">
    <div>
      <?= eyebrow('Zonas') ?>
      <h2 id="zonas-h">Odontólogos en Asunción y <em>Gran Asunción.</em></h2>
      <p class="section-lead">Decinos dónde te queda más cómodo —cerca de tu casa o del trabajo— y buscamos un consultorio ahí.</p>
      <?php zone_links(); ?>
    </div>
    <?php zone_map(); ?>
  </div>
</section>

<?php if (img_exists('familia')): ?>
<section class="photo-band" aria-hidden="true"><?= img('familia', 'photo-band__img', '100vw') ?></section>
<?php endif; ?>

<section class="section section--tint" id="preguntas" aria-labelledby="faq-h">
  <div class="container faq-split">
    <div>
      <?= eyebrow('Preguntas') ?>
      <h2 id="faq-h">Lo que todos nos preguntan</h2>
      <p class="section-lead">¿Te quedó otra duda? Escribinos y te respondemos por WhatsApp.</p>
      <?= wa_btn('Hola, vengo de dentista.com.py — tengo una pregunta: ', 'Preguntar por WhatsApp', 'faq') ?>
    </div>
    <?php faq_block($H_faq, '', true); ?>
  </div>
</section>

<?php cta_band('Contanos qué necesitás.', 'Presupuesto sin costo · Respondemos por WhatsApp · Asunción y Gran Asunción', $H_wa, 'cta-band', 'Del resto nos encargamos.'); ?>
<?php partner_band(); ?>
<?php }]);
