<?php
/*
 * Formulario de paciente. Variables opcionales antes del include:
 *   $F_service  slug preseleccionado     $F_zone   zona preseleccionada
 *   $F_source   de dónde viene (home, servicio:brackets, lp:implantes…)
 *   $F_title    título de la tarjeta
 *   $F_variant  'steps' (default: 3 pasos con JS) | 'compact' (landing de Ads, todo junto)
 *   $F_only     (compact) slugs a mostrar como opciones de tratamiento
 *
 * Sin JS es un formulario normal con todos los campos. Con JS (form[data-steps])
 * se vuelve un asistente de 3 pasos. Los chips son radios reales.
 * Campos que lee /enviar.php: type, source, page, utm_*, website, name, phone,
 * service, zone, urgency, consent.
 */
$F_service ??= '';
$F_zone    ??= '';
$F_source  ??= 'web';
$F_title   ??= 'Pedí tu turno';
$F_variant ??= 'steps';
$F_only    ??= null;
$F_id = 'lf' . substr(md5($F_source), 0, 5);
$F_steps = $F_variant === 'steps';

$F_treat = [];
foreach (services() as $F_s) $F_treat[$F_s['slug']] = $F_s['nav'];
if ($F_only !== null) $F_treat = array_intersect_key($F_treat, array_flip($F_only)) ?: $F_treat;
$F_treat['revision'] = $F_steps ? 'Revisión general / no sé' : 'No sé, quiero evaluación';
if ($F_service !== '' && !isset($F_treat[$F_service])) $F_service = '';

$F_zones = ['asuncion' => 'Asunción'];
foreach (zones() as $F_slug => $F_z) $F_zones[$F_slug] = $F_z['name'];
$F_zones['otra'] = 'Otra ciudad';
if (!isset($F_zones[$F_zone])) $F_zone = 'asuncion';
?>
<form class="lead-form<?= $F_steps ? ' lead-form--steps' : ' lead-form--compact' ?>" id="pedir-turno" method="post" action="/enviar.php" data-lead-form<?= $F_steps ? ' data-steps' : '' ?>>
  <div class="lead-form__head">
    <div>
      <h2 class="lead-form__title"><?= e($F_title) ?></h2>
      <p class="lead-form__sub">Sin costo. Te respondemos por WhatsApp.</p>
    </div>
    <span class="badge"><?= icon('lock', 'ico ico--xs') ?>Datos protegidos</span>
  </div>
  <?php if ($F_steps): ?>
  <div class="lf-progress" data-progress hidden>
    <p class="lf-progress__label" aria-live="polite" data-progress-label>Paso 1 de 3</p>
    <div class="lf-progress__bar" aria-hidden="true"><span></span><span></span><span></span></div>
  </div>
  <?php endif; ?>
  <?php require ROOT . '/inc/form-error.php'; ?>
  <input type="hidden" name="type" value="paciente">
  <input type="hidden" name="source" value="<?= e($F_source) ?>">
  <input type="hidden" name="page" value="<?= e(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH)) ?>">
  <?php foreach (['utm_source','utm_medium','utm_campaign','utm_term','utm_content'] as $F_k): ?>
  <input type="hidden" name="<?= $F_k ?>" value="" data-track="<?= $F_k ?>">
  <?php endforeach; ?>
  <div class="hp" aria-hidden="true"><label>No completar <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

  <div class="lf-step" data-step="1">
    <fieldset class="lf-group">
      <legend class="lf-legend" tabindex="-1"><?= $F_steps ? '¿Qué tratamiento buscás?' : '¿Qué necesitás?' ?></legend>
      <div class="chips-grid">
        <?php $F_i = 0; foreach ($F_treat as $F_slug => $F_label): ?>
        <label class="chip"><input type="radio" name="service" value="<?= e($F_slug) ?>"<?= $F_slug === $F_service ? ' checked' : '' ?><?= $F_i++ === 0 ? ' required' : '' ?> data-label="<?= e($F_label) ?>"><span class="chip__dot" aria-hidden="true"></span><span><?= e($F_label) ?></span></label>
        <?php endforeach; ?>
      </div>
      <p class="lf-err" data-err hidden>Elegí una opción para seguir.</p>
    </fieldset>
    <?php if ($F_steps): ?>
    <button class="btn btn--accent btn--block lf-next" type="button" data-next hidden>Continuar<?= arrow() ?></button>
    <p class="lf-alt" data-js-only hidden>¿Preferís escribir directo? <a href="<?= e(wa_link('Hola, vengo de dentista.com.py — quiero coordinar una consulta odontológica.')) ?>" data-wa data-ev-loc="form-step1" rel="nofollow noopener" target="_blank">Escribinos por WhatsApp</a></p>
    <?php endif; ?>
  </div>

  <div class="lf-step" data-step="2">
    <?php if ($F_steps): ?><div class="lf-summary" data-summary hidden><span data-summary-text></span><button type="button" class="lf-change" data-back>Cambiar</button></div><?php endif; ?>
    <?php if ($F_steps): ?>
    <fieldset class="lf-group">
      <legend class="lf-legend" tabindex="-1">¿En qué zona te queda mejor?</legend>
      <div class="chips-grid chips-grid--sm">
        <?php $F_i = 0; foreach ($F_zones as $F_slug => $F_label): ?>
        <label class="chip chip--sm"><input type="radio" name="zone" value="<?= e($F_slug) ?>"<?= $F_slug === $F_zone ? ' checked' : '' ?><?= $F_i++ === 0 ? ' required' : '' ?> data-label="<?= e($F_label) ?>"><span class="chip__dot" aria-hidden="true"></span><span><?= e($F_label) ?></span></label>
        <?php endforeach; ?>
      </div>
    </fieldset>
    <?php else: ?>
    <div class="field-row">
      <label class="field"><span>Zona</span>
        <select name="zone" required>
          <?php foreach ($F_zones as $F_slug => $F_label): ?><option value="<?= e($F_slug) ?>"<?= $F_slug === $F_zone ? ' selected' : '' ?>><?= e($F_label) ?></option><?php endforeach; ?>
        </select></label>
      <label class="field"><span>¿Para cuándo?</span>
        <select name="urgency" required>
          <?php foreach (site()['urgency'] as $F_k => $F_v): ?><option value="<?= e($F_k) ?>"<?= $F_k === 'semana' ? ' selected' : '' ?>><?= e($F_v) ?></option><?php endforeach; ?>
        </select></label>
    </div>
    <?php endif; ?>
    <?php if ($F_steps): ?>
    <fieldset class="lf-group">
      <legend class="lf-legend lf-legend--2">¿Para cuándo?</legend>
      <div class="chips-grid">
        <?php $F_i = 0; foreach (site()['urgency'] as $F_k => $F_v): ?>
        <label class="chip"><input type="radio" name="urgency" value="<?= e($F_k) ?>"<?= $F_k === 'semana' ? ' checked' : '' ?><?= $F_i++ === 0 ? ' required' : '' ?> data-label="<?= e($F_v) ?>"><span class="chip__dot" aria-hidden="true"></span><span><?= e($F_v) ?></span></label>
        <?php endforeach; ?>
      </div>
    </fieldset>
    <button class="btn btn--accent btn--block lf-next" type="button" data-next hidden>Continuar<?= arrow() ?></button>
    <?php endif; ?>
  </div>

  <div class="lf-step" data-step="3">
    <?php if ($F_steps): ?><div class="lf-summary" data-summary hidden><span data-summary-text></span><button type="button" class="lf-change" data-back>Cambiar</button></div><?php endif; ?>
    <label class="field" for="<?= $F_id ?>-name"><span>Tu nombre</span>
      <input id="<?= $F_id ?>-name" type="text" name="name" required maxlength="80" autocomplete="given-name" placeholder="Ej.: María"></label>
    <label class="field" for="<?= $F_id ?>-phone"><span>Tu WhatsApp</span>
      <span class="phone"><span class="phone__cc" aria-hidden="true">+595</span><input id="<?= $F_id ?>-phone" type="tel" name="phone" required maxlength="20" inputmode="tel" autocomplete="tel-national" placeholder="981 123 456" pattern="[0-9 +()\-]{6,20}" aria-describedby="<?= $F_id ?>-ph"></span></label>
    <p class="field-hint" id="<?= $F_id ?>-ph">Número de Paraguay, con o sin el 0 inicial.</p>
    <label class="check"><input type="checkbox" name="consent" value="1" required>
      <span>Acepto que Dentista.com.py comparta mis datos con un odontólogo para coordinar mi consulta. <a href="/privacidad/">Privacidad</a></span></label>
    <button class="btn btn--accent btn--block" type="submit"><?= $F_steps ? 'Enviar y coordinar mi consulta' : 'Pedí tu presupuesto sin costo' ?></button>
    <p class="lead-form__foot"><?= icon('lock', 'ico ico--xs') ?><?= $F_steps ? 'Solo usamos tus datos para coordinar tu consulta.' : 'Te respondemos por WhatsApp. Sin compromiso.' ?></p>
  </div>
</form>
<?php unset($F_service, $F_zone, $F_source, $F_title, $F_variant, $F_only, $F_id, $F_steps, $F_treat, $F_zones, $F_s, $F_slug, $F_z, $F_k, $F_v, $F_i, $F_label); ?>
