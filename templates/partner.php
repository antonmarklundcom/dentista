<?php
$P_faq = [
    ['q' => '¿Qué necesito para sumarme?', 'a' => 'Ser odontólogo matriculado en Paraguay, atender en Asunción o el Gran Asunción, responder a los pacientes derivados dentro del día y pasar el presupuesto por escrito antes de empezar cualquier tratamiento. Verificamos la matrícula antes de activarte.'],
    ['q' => '¿Cuánto cuesta sumarse?', 'a' => 'Sumarse y la primera conversación no tienen costo. El modelo comercial (por paciente derivado, por zona exclusiva o plan mensual) se define según tu zona, tus especialidades y cuántos pacientes nuevos podés recibir. Te lo explicamos antes de que recibas cualquier paciente.'],
    ['q' => '¿Cómo me llegan los pacientes?', 'a' => 'Por WhatsApp, con nombre, contacto, el tratamiento que busca, la zona, para cuándo lo necesita y la página desde la que llegó.'],
    ['q' => '¿Los pacientes son exclusivos?', 'a' => 'Cada paciente se deriva a un solo consultorio. No vendemos el mismo contacto a varios odontólogos.'],
    ['q' => '¿Puedo elegir tratamientos y zonas?', 'a' => 'Sí. Definís las especialidades y los tratamientos que querés recibir, las zonas y un cupo mensual. Si un mes estás completo, lo pausamos.'],
    ['q' => '¿Los pacientes aceptaron compartir sus datos?', 'a' => 'Sí. Cada paciente marca que acepta que compartamos sus datos con un odontólogo para coordinar su consulta.'],
    ['q' => '¿Tengo que firmar un contrato largo?', 'a' => 'No trabajamos con permanencias largas. Lo que buscamos es que los pacientes derivados terminen atendidos, y eso se sostiene solo si a los dos nos sirve.'],
];
$P_crumbs = [['Inicio', '/'], ['Para odontólogos', '/para-odontologos/']];
$P_wa = 'Hola, soy odontólogo y quiero recibir pacientes de dentista.com.py.';
$P_specs = ['Ortodoncia', 'Prótesis', 'Implantes', 'Limpieza y periodoncia', 'Blanqueamiento', 'Bruxismo', 'Endodoncia', 'Cirugía y muelas del juicio', 'Odontopediatría', 'Odontología general', 'Urgencias'];
// Demand from the locked keyword map in content/ (Google Keyword Planner, monthly searches in Paraguay).
$P_demand = [];
$P_acc = ['protesis' => 'prótesis', 'odontologicos' => 'odontológicos', 'odontologico' => 'odontológico', 'encias' => 'encías', 'odontopediatria' => 'odontopediatría', 'clinica' => 'clínica', 'odontologia' => 'odontología'];
foreach (array_merge(services(), guides()) as $r) if (!empty($r['volume']) && !empty($r['keyword'])) $P_demand[strtr($r['keyword'], $P_acc)] = (int)$r['volume'];
arsort($P_demand);
$P_demand = array_slice($P_demand, 0, 12, true);
render([
    'title' => 'Pacientes para tu consultorio dental | Odontólogos',
    'description' => 'Recibí pacientes nuevos para tu consultorio dental en Asunción y Gran Asunción. Sumate a la red de Dentista.com.py: pacientes que ya buscan tratamiento.',
    'wa' => $P_wa,
    'bodyClass' => 'page-partner',
    'partner' => true,
    'schema' => [breadcrumb_schema($P_crumbs), faq_schema($P_faq)],
    'body' => function () use ($P_faq, $P_crumbs, $P_wa, $P_specs, $P_demand) { ?>
<section class="hero hero--dark">
  <div class="container hero__grid">
    <div class="hero__copy">
      <?php crumbs($P_crumbs); ?>
      <?= eyebrow('Red de odontólogos · Gran Asunción') ?>
      <h1 class="display">Pacientes <em>nuevos</em> para tu consultorio dental.</h1>
      <p class="lead">Recibí por WhatsApp pacientes de tu zona que ya eligieron tratamiento. Vos definís qué atendés, dónde y cuántos por mes. Vos atendés; nosotros conseguimos el paciente.</p>
      <?php ticks_inline(['Pacientes de tu zona, con el tratamiento ya elegido', 'Nombre, WhatsApp, tratamiento, zona y urgencia en cada contacto', 'Cada paciente va a un solo consultorio', 'El paciente aceptó que compartamos sus datos con vos'], 'ticks-inline--stack'); ?>
      <?php $P_top = array_slice($P_demand, 0, 3, true); if ($P_top): ?>
      <div class="stats">
        <p class="stats__src">Búsquedas mensuales en Google en Paraguay (Google Keyword Planner)</p>
        <dl><?php foreach ($P_top as $k => $v): ?><div><dt><?= e($k) ?></dt><dd><?= e(number_format($v, 0, ',', '.')) ?></dd></div><?php endforeach; ?></dl>
      </div>
      <?php endif; ?>
    </div>
    <div class="hero__form">
      <form class="lead-form lead-form--partner" id="pedir-turno" method="post" action="/enviar.php" data-lead-form>
        <div class="lead-form__head"><div>
          <h2 class="lead-form__title">Sumate a la red</h2>
          <p class="lead-form__sub">Te escribimos por WhatsApp para contarte cómo funciona en tu zona.</p>
        </div></div>
        <?php require ROOT . '/inc/form-error.php'; ?>
        <input type="hidden" name="type" value="partner">
        <input type="hidden" name="source" value="para-odontologos">
        <input type="hidden" name="page" value="/para-odontologos/">
        <?php foreach (['utm_source','utm_medium','utm_campaign','utm_term','utm_content'] as $k): ?><input type="hidden" name="<?= $k ?>" value="" data-track="<?= $k ?>"><?php endforeach; ?>
        <div class="hp" aria-hidden="true"><label>No completar <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="field-row">
          <label class="field"><span>Nombre y apellido</span><input type="text" name="name" required maxlength="80" autocomplete="name" placeholder="Dr./Dra."></label>
          <label class="field"><span>Consultorio</span><input type="text" name="clinic" maxlength="120" autocomplete="organization"></label>
        </div>
        <div class="field-row">
          <label class="field"><span>WhatsApp</span><input type="tel" name="phone" required maxlength="20" inputmode="tel" autocomplete="tel" placeholder="0981 123 456" pattern="[0-9 +()\-]{6,20}"></label>
          <label class="field"><span>Email</span><input type="email" name="email" maxlength="120" autocomplete="email"></label>
        </div>
        <div class="field-row">
          <label class="field"><span>Zona del consultorio</span>
            <select name="zone" required><option value="asuncion">Asunción</option><?php foreach (zones() as $zs => $zz): ?><option value="<?= e($zs) ?>"><?= e($zz['name']) ?></option><?php endforeach; ?><option value="otra">Otra ciudad</option></select></label>
          <label class="field"><span>Pacientes nuevos por mes</span>
            <select name="capacity"><option>Hasta 5</option><option selected>De 5 a 10</option><option>De 10 a 20</option><option>Más de 20</option></select></label>
        </div>
        <fieldset class="field lf-group"><legend class="field-legend">Especialidades que atendés</legend>
          <div class="checks"><?php foreach ($P_specs as $sp): ?><label class="check check--pill"><input type="checkbox" name="specialties[]" value="<?= e($sp) ?>"><span><?= icon('check', 'ico ico--xs') ?><?= e($sp) ?></span></label><?php endforeach; ?></div>
        </fieldset>
        <label class="field"><span>Matrícula profesional <i class="muted">(opcional)</i></span><input type="text" name="license" maxlength="40"></label>
        <label class="check"><input type="checkbox" name="consent" value="1" required><span>Acepto que Dentista.com.py me contacte por WhatsApp o email. <a href="/privacidad/">Privacidad</a></span></label>
        <button class="btn btn--accent btn--block" type="submit">Quiero recibir pacientes<?= arrow() ?></button>
        <p class="lead-form__foot">Sin compromiso. Verificamos la matrícula antes de activar tu perfil.</p>
      </form>
    </div>
  </div>
</section>

<section class="section" id="como-funciona" aria-labelledby="pc-h">
  <div class="container">
    <?= eyebrow('Cómo funciona') ?>
    <h2 id="pc-h">De la búsqueda en Google <em>a tu sillón.</em></h2>
    <?php how_block([
        ['Completás el formulario', 'Datos del consultorio, zona y especialidades.'],
        ['Verificamos tu matrícula', 'Es lo que les prometemos a los pacientes.'],
        ['Definís tu cupo', 'Qué tratamientos, qué zonas y cuántos pacientes por mes.'],
        ['Recibís pacientes', 'Por WhatsApp, con todo lo que necesitás para coordinar.'],
    ]); ?>
  </div>
</section>

<?php if (img_exists('odontologa') || img_exists('consultorio')): ?>
<section class="photo-pair container" aria-hidden="true">
  <?= img('odontologa', 'photo-pair__a', '(min-width: 1024px) 400px, 50vw') ?><?= img('consultorio', 'photo-pair__b', '(min-width: 1024px) 780px, 100vw') ?>
</section>
<?php endif; ?>

<section class="section section--tint" aria-labelledby="pr-h">
  <div class="container trust__grid">
    <div>
      <?= eyebrow('Qué recibís') ?>
      <h2 id="pr-h">Cada paciente llega <em>con contexto.</em></h2>
      <p class="section-lead">Nada de formularios vacíos: sabés qué busca, dónde está y para cuándo lo necesita antes de escribirle.</p>
      <ul class="features features--grid">
        <li><span class="features__i"><?= icon('user') ?></span><p>Nombre del paciente</p></li>
        <li><span class="features__i"><?= icon('chat') ?></span><p>WhatsApp</p></li>
        <li><span class="features__i"><?= icon('tooth') ?></span><p>Tratamiento que busca</p></li>
        <li><span class="features__i"><?= icon('pin') ?></span><p>Zona</p></li>
        <li><span class="features__i"><?= icon('clock') ?></span><p>Urgencia</p></li>
        <li><span class="features__i"><?= icon('shield') ?></span><p>Consentimiento registrado</p></li>
      </ul>
    </div>
    <figure class="lead-card">
      <div class="lead-card__box">
        <div class="lead-card__head"><p class="lead-card__t">Nuevo paciente</p><p><?= e(site()['urgency']['semana'] ?? 'Esta semana') ?></p></div>
        <dl>
          <dt>Tratamiento</dt><dd><?= e(services()['protesis-dental']['name'] ?? 'Prótesis dental') ?></dd>
          <dt>Zona</dt><dd>San Lorenzo</dd>
          <dt>Nombre</dt><dd>[Nombre del paciente]</dd>
          <dt>WhatsApp</dt><dd>+595 9•• ••• •••</dd>
          <dt>Comentario</dt><dd>“Me falta una pieza de abajo, quiero saber qué opciones tengo.”</dd>
        </dl>
        <p class="lead-card__foot"><?= icon('check', 'ico ico--xs') ?>Aceptó que compartamos sus datos con un odontólogo</p>
      </div>
      <figcaption>Ejemplo ilustrativo de un contacto.</figcaption>
    </figure>
  </div>
</section>

<?php if ($P_demand): $P_max = max($P_demand); ?>
<section class="section" aria-labelledby="pd-h">
  <div class="container demand">
    <div>
      <?= eyebrow('Demanda real') ?>
      <h2 id="pd-h">La gente ya está <em>buscando.</em></h2>
      <p class="section-lead">Búsquedas mensuales en Google en Paraguay (Google Keyword Planner). Nosotros convertimos esas búsquedas en consultas y te las pasamos.</p>
      <a class="link-arrow" href="#pedir-turno" data-scroll-form>Sumate a la red<?= arrow() ?></a>
    </div>
    <figure class="bars">
      <table>
        <caption class="sr">Búsquedas mensuales promedio en Google, Paraguay</caption>
        <thead class="sr"><tr><th scope="col">Búsqueda</th><th scope="col">Búsquedas por mes</th></tr></thead>
        <tbody>
        <?php foreach ($P_demand as $k => $v): ?>
          <tr><th scope="row"><?= e($k) ?></th><td><span class="bars__bar" style="--w:<?= round($v / $P_max * 100, 1) ?>%"></span><span class="bars__n"><?= e(number_format($v, 0, ',', '.')) ?></span></td></tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <figcaption>Fuente: Google Keyword Planner, búsquedas mensuales promedio en Paraguay.</figcaption>
    </figure>
  </div>
</section>
<?php endif; ?>

<section class="section section--tint" aria-labelledby="pf-h">
  <div class="container faq-split">
    <div>
      <?= eyebrow('Preguntas') ?>
      <h2 id="pf-h">Antes de sumarte</h2>
      <p class="section-lead">¿Otra duda? Escribinos y te contamos cómo funciona en tu zona.</p>
      <?= wa_btn($P_wa, 'Hablemos por WhatsApp', 'partner-faq') ?>
    </div>
    <?php faq_block($P_faq, '', true); ?>
  </div>
</section>

<section class="cta-band"><div class="container cta-band__inner cta-band__inner--split">
  <h2>Tu agenda, con<br><em>pacientes nuevos.</em></h2>
  <div class="cta-band__btns">
    <a class="btn btn--light btn--lg" href="#pedir-turno" data-scroll-form>Quiero recibir pacientes<?= arrow() ?></a>
    <?= wa_btn($P_wa, 'Hablemos por WhatsApp', 'partner-cta', 'btn btn--outline-light btn--lg') ?>
  </div>
</div></section>
<?php }]);
