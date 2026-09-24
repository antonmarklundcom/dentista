<?php
$P_faq = [
    ['q' => '¿Cuánto cuesta sumarse?', 'a' => 'Sumarse y la primera conversación no tienen costo. El modelo comercial (por paciente derivado, por zona exclusiva o plan mensual) se define según tu zona, tus especialidades y cuántos pacientes nuevos podés recibir.'],
    ['q' => '¿Qué datos trae cada paciente?', 'a' => 'Nombre, WhatsApp, el tratamiento que busca, la zona, para cuándo lo necesita y la página desde la que llegó. Todos los pacientes aceptan expresamente que compartamos sus datos con un odontólogo para coordinar la consulta.'],
    ['q' => '¿Los pacientes son exclusivos?', 'a' => 'Cada paciente se deriva a un solo consultorio. No vendemos el mismo contacto a varios odontólogos.'],
    ['q' => '¿Qué requisitos piden?', 'a' => 'Ser odontólogo matriculado en Paraguay, atender en Asunción o el Gran Asunción, responder a los pacientes derivados dentro del día y pasar el presupuesto por escrito antes de empezar cualquier tratamiento.'],
    ['q' => '¿Puedo elegir qué tratamientos recibir?', 'a' => 'Sí. Definís las especialidades y los tratamientos que querés recibir, las zonas y un cupo mensual. Si un mes estás completo, lo pausamos.'],
    ['q' => '¿Tengo que firmar un contrato largo?', 'a' => 'No trabajamos con permanencias largas. Lo que buscamos es que los pacientes derivados terminen atendidos, y eso se sostiene solo si a los dos nos sirve.'],
];
$P_crumbs = [['Inicio', '/'], ['Para odontólogos', '/para-odontologos/']];
$P_wa = 'Hola, soy odontólogo y quiero recibir pacientes de dentista.com.py.';
$P_specs = ['Ortodoncia / brackets', 'Implantes', 'Prótesis', 'Estética / blanqueamiento', 'Endodoncia', 'Cirugía / exodoncia', 'Odontopediatría', 'Periodoncia', 'Odontología general', 'Urgencias'];
render([
    'title' => 'Pacientes para tu consultorio dental | Odontólogos',
    'description' => 'Recibí pacientes nuevos para tu consultorio dental en Asunción y Gran Asunción. Sumate a la red de Dentista.com.py: pacientes que ya buscan tratamiento.',
    'wa' => $P_wa,
    'bodyClass' => 'page-partner',
    'schema' => [breadcrumb_schema($P_crumbs), faq_schema($P_faq)],
    'body' => function () use ($P_faq, $P_crumbs, $P_wa, $P_specs) { ?>
<section class="hero hero--inner hero--dark grain">
  <div class="container hero__grid">
    <div class="hero__copy">
      <?php crumbs($P_crumbs); ?>
      <p class="eyebrow">Para odontólogos</p>
      <h1>Pacientes nuevos para tu consultorio dental</h1>
      <p class="lead">Dentista.com.py capta a las personas que buscan dentista en Google en Asunción y el Gran Asunción, y las deriva por WhatsApp a odontólogos matriculados de su zona. Vos atendés; nosotros conseguimos el paciente.</p>
      <ul class="ticks ticks--lg">
        <li>Pacientes que ya buscan un tratamiento concreto</li>
        <li>Cada paciente va a un solo consultorio</li>
        <li>Elegís tratamientos, zona y cupo mensual</li>
        <li>Sin permanencia larga</li>
      </ul>
    </div>
    <div class="hero__form">
      <form class="lead-form card card--raised" id="pedir-turno" method="post" action="/enviar.php" data-lead-form>
        <p class="eyebrow">Cupos por zona</p>
        <h2 class="lead-form__title">Quiero recibir pacientes</h2>
        <p class="lead-form__sub">Te escribimos por WhatsApp para contarte cómo funciona en tu zona.</p>
        <?php require ROOT . '/inc/form-error.php'; ?>
        <input type="hidden" name="type" value="partner">
        <input type="hidden" name="source" value="para-odontologos">
        <input type="hidden" name="page" value="/para-odontologos/">
        <?php foreach (['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign','utm_term','utm_content'] as $k): ?><input type="hidden" name="<?= $k ?>" value="" data-track="<?= $k ?>"><?php endforeach; ?>
        <div class="hp" aria-hidden="true"><label>No completar <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="field-row">
          <label class="field"><span>Tu nombre</span><input type="text" name="name" required maxlength="80" autocomplete="name" placeholder="Dr./Dra."></label>
          <label class="field"><span>Consultorio</span><input type="text" name="clinic" maxlength="120" autocomplete="organization"></label>
        </div>
        <div class="field-row">
          <label class="field"><span>WhatsApp</span><input type="tel" name="phone" required maxlength="20" inputmode="tel" autocomplete="tel" pattern="[0-9 +()\-]{6,20}"></label>
          <label class="field"><span>Email</span><input type="email" name="email" maxlength="120" autocomplete="email"></label>
        </div>
        <div class="field-row">
          <label class="field"><span>Zona del consultorio</span>
            <select name="zone" required><option value="asuncion">Asunción</option><?php foreach (zones() as $zs => $zz): ?><option value="<?= e($zs) ?>"><?= e($zz['name']) ?></option><?php endforeach; ?><option value="otra">Otra ciudad</option></select></label>
          <label class="field"><span>Pacientes nuevos por mes</span>
            <select name="capacity"><option>1–10</option><option selected>10–30</option><option>30–60</option><option>Más de 60</option></select></label>
        </div>
        <fieldset class="field"><legend>Especialidades</legend>
          <div class="checks"><?php foreach ($P_specs as $sp): ?><label class="check check--pill"><input type="checkbox" name="specialties[]" value="<?= e($sp) ?>"><span><?= e($sp) ?></span></label><?php endforeach; ?></div>
        </fieldset>
        <label class="field"><span>N° de matrícula (opcional)</span><input type="text" name="license" maxlength="40"></label>
        <label class="check"><input type="checkbox" name="consent" value="1" required><span>Acepto que Dentista.com.py me contacte por WhatsApp o email. <a href="/privacidad/">Privacidad</a></span></label>
        <button class="btn btn--primary btn--block" type="submit">Quiero recibir pacientes</button>
      </form>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head">
      <p class="eyebrow">El problema</p>
      <h2>Los pacientes buscan en Google. La mayoría de los consultorios no aparece.</h2>
      <p>Todos los días hay personas en Asunción buscando “dentista cerca de mí”, “brackets”, “limpieza dental” o “muela de juicio”. Aparecer ahí exige un sitio optimizado, anuncios bien armados y alguien que responda rápido por WhatsApp. Eso es lo que hacemos por vos.</p>
    </div>
    <div class="benefits">
      <div class="card card--bare"><h3>Demanda que ya existe</h3><p>No hacemos publicidad “de marca”: captamos búsquedas de personas que ya decidieron que necesitan un dentista.</p></div>
      <div class="card card--bare"><h3>Pacientes filtrados</h3><p>Cada contacto llega con tratamiento, zona y urgencia. Sabés qué te piden antes de responder.</p></div>
      <div class="card card--bare"><h3>Sin gastar en anuncios</h3><p>La inversión en Google, el sitio y la atención inicial por WhatsApp corren por nuestra cuenta.</p></div>
      <div class="card card--bare"><h3>Medible</h3><p>Sabés cuántos pacientes te derivamos, de qué tratamiento y cuántos terminaron en turno.</p></div>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container split">
    <div class="split__sticky">
      <p class="eyebrow">Cómo funciona</p>
      <h2>De la búsqueda en Google a tu sillón</h2>
      <a class="btn btn--wa" href="<?= e(wa_link($P_wa)) ?>" data-wa data-ev-loc="partner" rel="noopener" target="_blank"><?php require ROOT . '/inc/icon-wa.php'; ?>Hablemos por WhatsApp</a>
    </div>
    <ol class="rail">
      <li><span class="rail__n">01</span><h3>Te sumás</h3><p>Nos contás dónde atendés, qué tratamientos hacés y verificamos tu matrícula.</p></li>
      <li><span class="rail__n">02</span><h3>Definimos tu cupo</h3><p>Zona, tratamientos, horarios y cuántos pacientes nuevos querés por mes.</p></li>
      <li><span class="rail__n">03</span><h3>Te derivamos pacientes</h3><p>Te llegan por WhatsApp con nombre, contacto, tratamiento, zona y urgencia. Cada uno va a un solo consultorio.</p></li>
      <li><span class="rail__n">04</span><h3>Atendés y medimos</h3><p>Nos avisás qué pacientes concretaron turno. Con eso ajustamos volumen y tratamientos.</p></li>
    </ol>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head"><p class="eyebrow">Qué buscan los pacientes</p><h2>Los tratamientos que más se buscan en Paraguay</h2>
      <p>Priorizamos los tratamientos con más búsquedas y los de mayor valor para el consultorio.</p></div>
    <ul class="zone-list zone-list--wide">
      <?php foreach (services() as $sv): ?><li><?= e($sv['name']) ?></li><?php endforeach; ?>
      <li>Urgencias y dolor de muelas</li><li>Revisión general</li>
    </ul>
  </div>
</section>

<section class="section section--tint">
  <div class="container narrow"><?php faq_block($P_faq, 'Preguntas de odontólogos'); ?></div>
</section>

<section class="cta-band grain"><div class="container cta-band__row"><div><h2>¿Atendés en Gran Asunción?</h2><p>Los cupos se asignan por zona y especialidad. Escribinos y te contamos si hay lugar en la tuya.</p></div>
<div class="btn-row"><a class="btn btn--primary" href="#pedir-turno" data-scroll-form>Quiero recibir pacientes</a></div></div></section>
<?php }]);
