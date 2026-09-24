<?php
$H_faq = [
    ['q' => '¿El profesional está matriculado?', 'a' => 'Sí. Trabajamos únicamente con odontólogos matriculados, y te pasamos los datos del profesional antes de confirmar la consulta.'],
    ['q' => '¿Cuánto sale el tratamiento?', 'a' => 'Depende de lo que encuentren cuando te revisen, así que no publicamos precios: un número puesto antes de verte la boca no te sirve para decidir. El presupuesto se te pasa en la consulta, por escrito y sin costo, y recién ahí decidís si seguís.'],
    ['q' => '¿Cómo hago para sacar turno con un dentista?', 'a' => 'Completá el formulario o escribinos por WhatsApp contando qué necesitás y en qué zona estás. Te respondemos con la disponibilidad, coordinamos día y horario y te confirmamos los datos del profesional. No hace falta llamar ni ir hasta el consultorio para sacar el turno.'],
    ['q' => 'Tengo dolor de muela ahora. ¿Qué hago?', 'a' => 'Escribinos igual y contanos hace cuánto te duele, si tenés la cara hinchada y si estás tomando algo. Buscamos el turno más cercano que haya y te confirmamos el mismo día si conseguimos lugar. Si tenés la cara muy hinchada, fiebre o te cuesta tragar o respirar, andá directo a una guardia.'],
    ['q' => '¿Coordinan consultas para chicos?', 'a' => 'Sí, coordinamos consultas de odontopediatría. La primera visita suele ser corta y sirve para que el chico conozca el consultorio y se anime. Si viene con miedo, avisanos antes y lo tenemos en cuenta al coordinar el turno.'],
    ['q' => '¿Hay un dentista cerca de mí?', 'a' => 'Coordinamos consultas en Asunción y todo el Gran Asunción: Luque, San Lorenzo, Fernando de la Mora, Lambaré, Capiatá y Mariano Roque Alonso. Decinos en qué zona vivís o trabajás y buscamos el turno lo más cerca posible.'],
];
$H_wa = 'Hola, vengo de dentista.com.py — quiero coordinar una consulta odontológica.';

render([
    'title' => 'Dentista en Asunción: pedí tu turno sin costo',
    'description' => 'Dentista en Asunción y Gran Asunción: coordinamos tu consulta con odontólogos matriculados. Brackets, limpieza, implantes. Presupuesto sin costo.',
    'canonical' => base_url() . '/',
    'wa' => $H_wa,
    'bodyClass' => 'page-home',
    'schema' => [org_schema(), faq_schema($H_faq)],
    'body' => function () use ($H_faq, $H_wa) { ?>

<section class="hero">
  <div class="container hero__grid">
    <div class="hero__copy">
      <p class="eyebrow">Asunción · Gran Asunción</p>
      <h1>Dentista en Asunción y Gran Asunción</h1>
      <p class="lead">Contanos qué necesitás y coordinamos tu consulta odontológica con odontólogos matriculados en Asunción y el Gran Asunción. Te respondemos por WhatsApp y el presupuesto no tiene costo.</p>
      <ul class="chips">
        <li>Brackets y ortodoncia</li><li>Limpieza dental</li><li>Implantes</li><li>Muela del juicio</li><li>Niños</li>
      </ul>
      <div class="btn-row">
        <a class="btn btn--wa" href="<?= e(wa_link($H_wa)) ?>" data-wa data-ev-loc="hero" rel="noopener" target="_blank"><?php require ROOT . '/inc/icon-wa.php'; ?>Escribinos por WhatsApp</a>
        <a class="btn btn--ghost" href="/servicios/">Ver tratamientos</a>
      </div>
      <p class="micro">Sin compromiso. Contanos tu caso y te decimos cómo seguir.</p>
    </div>
    <div class="hero__form">
      <?php $F_source = 'home-hero'; require ROOT . '/inc/lead-form.php'; ?>
    </div>
  </div>
</section>

<?php promise_band(); ?>

<section class="section" id="tratamientos">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">Tratamientos</p>
      <h2>Los tratamientos que más nos consultan</h2>
      <p>Contanos cuál te interesa y coordinamos la consulta. Si todavía no sabés qué necesitás, escribinos igual: primero se revisa y recién después se decide el tratamiento.</p>
    </div>
    <?php service_cards(null, 'is-home'); ?>
  </div>
</section>

<section class="section section--tint">
  <div class="container split">
    <div class="split__sticky">
      <p class="eyebrow">Tu primera consulta</p>
      <h2>Así funciona, en cuatro pasos</h2>
      <p>Sin llamadas, sin filas y sin sorpresas: sabés quién te atiende y cuánto sale antes de empezar cualquier tratamiento.</p>
      <a class="btn btn--primary" href="#pedir-turno" data-scroll-form>Pedí tu turno</a>
    </div>
    <ol class="rail">
      <li><span class="rail__n">01</span><h3>Escribinos</h3><p>Contanos qué te pasa, qué tratamiento te interesa y en qué zona estás. Te respondemos por WhatsApp.</p></li>
      <li><span class="rail__n">02</span><h3>Coordinamos día y horario</h3><p>Buscamos el turno que te sirva y te pasamos los datos del odontólogo matriculado que te va a atender antes de confirmar.</p></li>
      <li><span class="rail__n">03</span><h3>Revisión y diagnóstico</h3><p>En la consulta se revisa tu boca, se piden las radiografías que hagan falta y se te explica qué encontraron.</p></li>
      <li><span class="rail__n">04</span><h3>Presupuesto sin costo y decidís vos</h3><p>Te pasan el plan de tratamiento y el presupuesto. No se empieza nada hasta que vos digas que sí.</p></li>
    </ol>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">Por qué coordinar con nosotros</p>
      <h2>Buscar dentista cerca de mí, sin adivinar</h2>
    </div>
    <div class="benefits">
      <div class="card card--bare"><h3>Sabés quién te atiende</h3><p>Antes de confirmar el turno te pasamos el nombre y los datos del profesional. Nada de ir a ciegas a un consultorio que encontraste en un mapa.</p></div>
      <div class="card card--bare"><h3>Un presupuesto antes de empezar</h3><p>Primero se revisa, después se presupuesta por escrito. Decidís con el plan en la mano, sin apuro y sin costo por el presupuesto.</p></div>
      <div class="card card--bare"><h3>El horario que te sirva</h3><p>Mañana, tarde, cerca de tu casa o cerca del trabajo. Decinos qué te queda mejor y buscamos el turno en base a eso.</p></div>
      <div class="card card--bare"><h3>Todo por WhatsApp</h3><p>Una sola conversación para coordinar, cambiar el turno o hacer una pregunta. Sin llamadas de venta.</p></div>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container split split--even">
    <div>
      <p class="eyebrow">Zonas</p>
      <h2>Dónde coordinamos consultas</h2>
      <p>Coordinamos en todo Asunción y el Gran Asunción. Contanos en qué zona estás y buscamos la consulta lo más cerca posible de donde vivís o trabajás.</p>
      <p>Si trabajás en el centro de Asunción y vivís en otra ciudad, decilo cuando escribas: muchas veces conviene coordinar el turno cerca del trabajo y no cerca de casa.</p>
    </div>
    <div>
      <ul class="zone-list">
        <li><a href="/">Asunción</a></li>
        <?php foreach (zones() as $Z_slug => $Z): ?><li><a href="/zonas/<?= e($Z_slug) ?>/"><?= e($Z['name']) ?></a></li><?php endforeach; ?>
      </ul>
      <div class="card card--bare interior"><h3>¿Estás fuera del Gran Asunción?</h3><p>Coordinamos según el caso: escribinos y te confirmamos si podemos llegar.</p></div>
    </div>
  </div>
</section>

<?php if (guides()): ?>
<section class="section">
  <div class="container">
    <div class="section-head"><p class="eyebrow">Guías</p><h2>Antes de ir al dentista, leé esto</h2></div>
    <div class="guide-grid">
      <?php foreach (guides() as $G): ?>
      <a class="card card--hair guide-card" href="/guias/<?= e($G['slug']) ?>/"><h3><?= e($G['h1']) ?></h3><p><?= e($G['card'] ?? mb_strimwidth($G['lead'], 0, 170, '…')) ?></p><span class="svc-card__more">Leer guía →</span></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section section--tint">
  <div class="container split">
    <div class="split__sticky">
      <p class="eyebrow">Preguntas frecuentes</p>
      <h2>Lo que más nos preguntan</h2>
      <div class="card card--accent"><h3>¿Tu pregunta no está acá?</h3><p>Escribinos y te la respondemos por WhatsApp. No hace falta que sepas el nombre del tratamiento.</p>
        <a class="btn btn--wa-light" href="<?= e(wa_link('Hola, vengo de dentista.com.py — tengo una pregunta: ')) ?>" data-wa data-ev-loc="faq" rel="noopener" target="_blank"><?php require ROOT . '/inc/icon-wa.php'; ?>Hacé tu pregunta</a></div>
    </div>
    <?php faq_block($H_faq, ''); ?>
  </div>
</section>

<?php partner_band(); ?>
<?php cta_band('Tu consulta, coordinada hoy.', 'Dejanos tus datos o escribinos por WhatsApp y te confirmamos disponibilidad.', $H_wa, 'cta-band'); ?>
<?php }]);
