<?php
/*
 * Formulario de paciente. Variables opcionales antes del include:
 *   $F_service  slug preseleccionado     $F_zone   zona preseleccionada
 *   $F_source   de dónde viene (home, servicio:brackets, lp:implantes…)
 *   $F_title    título de la tarjeta
 */
$F_service ??= '';
$F_zone    ??= '';
$F_source  ??= 'web';
$F_title   ??= 'Pedí tu turno';
?>
<form class="lead-form card card--raised" id="pedir-turno" method="post" action="/enviar.php" data-lead-form>
  <p class="eyebrow">Sin costo · Sin compromiso</p>
  <h2 class="lead-form__title"><?= e($F_title) ?></h2>
  <p class="lead-form__sub">Dejanos tus datos y te escribimos por WhatsApp para coordinar día, hora y profesional.</p>
  <?php require ROOT . '/inc/form-error.php'; ?>
  <input type="hidden" name="type" value="paciente">
  <input type="hidden" name="source" value="<?= e($F_source) ?>">
  <input type="hidden" name="page" value="<?= e(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH)) ?>">
  <?php foreach (['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign','utm_term','utm_content'] as $F_k): ?>
  <input type="hidden" name="<?= $F_k ?>" value="" data-track="<?= $F_k ?>">
  <?php endforeach; ?>
  <div class="hp" aria-hidden="true"><label>No completar <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

  <label class="field"><span>Tu nombre</span>
    <input type="text" name="name" required maxlength="80" autocomplete="given-name" placeholder="Ej. María"></label>
  <label class="field"><span>Tu WhatsApp</span>
    <input type="tel" name="phone" required maxlength="20" inputmode="tel" autocomplete="tel" placeholder="Ej. 0981 123 456" pattern="[0-9 +()\-]{6,20}"></label>
  <div class="field-row">
    <label class="field"><span>¿Qué necesitás?</span>
      <select name="service" required>
        <option value="">Elegí una opción</option>
        <?php foreach (services() as $F_s): ?>
        <option value="<?= e($F_s['slug']) ?>"<?= $F_s['slug'] === $F_service ? ' selected' : '' ?>><?= e($F_s['nav']) ?></option>
        <?php endforeach; ?>
        <option value="revision"<?= $F_service === 'revision' ? ' selected' : '' ?>>Revisión general / no sé</option>
      </select></label>
    <label class="field"><span>¿Dónde?</span>
      <select name="zone" required>
        <option value="asuncion"<?= $F_zone === '' || $F_zone === 'asuncion' ? ' selected' : '' ?>>Asunción</option>
        <?php foreach (zones() as $F_slug => $F_z): ?>
        <option value="<?= e($F_slug) ?>"<?= $F_slug === $F_zone ? ' selected' : '' ?>><?= e($F_z['name']) ?></option>
        <?php endforeach; ?>
        <option value="otra">Otra ciudad</option>
      </select></label>
  </div>
  <label class="field"><span>¿Para cuándo?</span>
    <select name="urgency" required>
      <?php foreach (site()['urgency'] as $F_k => $F_v): ?>
      <option value="<?= e($F_k) ?>"<?= $F_k === 'semana' ? ' selected' : '' ?>><?= e($F_v) ?></option>
      <?php endforeach; ?>
    </select></label>
  <label class="check"><input type="checkbox" name="consent" value="1" required>
    <span>Acepto que Dentista.com.py comparta mis datos con un odontólogo para coordinar mi consulta. <a href="/privacidad/">Privacidad</a></span></label>
  <button class="btn btn--primary btn--block" type="submit">Quiero mi turno</button>
  <p class="lead-form__foot">Te respondemos por WhatsApp. No hacemos spam ni llamadas de venta.</p>
</form>
<?php unset($F_service, $F_zone, $F_source, $F_title, $F_s, $F_slug, $F_z, $F_k, $F_v); ?>
