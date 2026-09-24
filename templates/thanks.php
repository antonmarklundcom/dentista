<?php
/*
 * /gracias/: dispara la conversión de Google Ads UNA sola vez por lead
 * (la sesión se consume acá), con valor y transaction_id para deduplicar,
 * y datos para conversiones mejoradas (gtag los hashea antes de enviarlos).
 */
session_start();
$T = $_SESSION['conversion'] ?? null;
unset($_SESSION['conversion']);
$T_partner = ($T['type'] ?? '') === 'partner';
$T_svc = $T && !$T_partner ? (services()[$T['service']] ?? null) : null;
$T_wa = $T_partner
    ? 'Hola, soy odontólogo, acabo de completar el formulario (ref. ' . ($T['ref'] ?? '') . ').'
    : 'Hola, acabo de pedir turno en dentista.com.py' . ($T_svc ? ' para ' . mb_strtolower($T_svc['nav']) : '') . ' (ref. ' . ($T['ref'] ?? '') . ').';
render([
    'title' => 'Recibimos tus datos | Dentista.com.py',
    'description' => 'Recibimos tus datos. Te escribimos por WhatsApp para coordinar tu consulta odontológica en Asunción y Gran Asunción.',
    'noindex' => true,
    'wa' => $T_wa,
    'body' => function () use ($T, $T_partner, $T_svc, $T_wa) { ?>
<section class="hero hero--inner"><div class="container narrow">
  <p class="eyebrow">Listo<?= $T ? ' · Ref. ' . e($T['ref']) : '' ?></p>
  <h1><?= $T ? '¡Gracias, ' . e(explode(' ', $T['name'])[0]) . '! Recibimos tus datos.' : 'Recibimos tus datos.' ?></h1>
  <p class="lead"><?= $T_partner
      ? 'Te escribimos por WhatsApp para contarte cómo funciona la red en tu zona y qué cupos hay.'
      : 'Te vamos a escribir por WhatsApp para coordinar el turno y pasarte los datos del odontólogo antes de confirmar.' ?></p>
  <div class="card card--accent">
    <h2 class="h3">¿Querés adelantar?</h2>
    <p>Escribinos ahora por WhatsApp con tu número de referencia y lo vemos ya.</p>
    <a class="btn btn--wa-light" href="<?= e(wa_link($T_wa)) ?>" data-wa data-ev-loc="thanks" rel="noopener" target="_blank"><?php require ROOT . '/inc/icon-wa.php'; ?>Seguir por WhatsApp</a>
  </div>
  <?php if (!$T_partner): ?>
  <h2>Para tener a mano en la consulta</h2>
  <ul class="ticks">
    <li>Tu cédula.</li>
    <li>Radiografías o estudios anteriores, si tenés.</li>
    <li>La lista de medicamentos que tomás y si tenés alguna alergia.</li>
    <li>Si estás embarazada o tenés alguna enfermedad crónica, avisalo al coordinar.</li>
  </ul>
  <?php if ($T_svc && !empty($T_svc['guides'])): $TG = guides()[$T_svc['guides'][0]] ?? null; if ($TG): ?>
  <p>Mientras tanto, te puede servir: <a href="/guias/<?= e($TG['slug']) ?>/"><?= e($TG['h1']) ?></a>.</p>
  <?php endif; endif; ?>
  <?php endif; ?>
  <p class="btn-row"><a class="btn btn--ghost" href="/">Volver al inicio</a></p>
</div></section>
<?php if ($T && cfg('ads_id') || $T && cfg('ga4_id')):
    $label = cfg($T_partner ? 'ads_label_partner' : 'ads_label_patient');
    $phone = preg_replace('/\D/', '', $T['phone']);
    if (str_starts_with($phone, '0')) $phone = '595' . substr($phone, 1);
    elseif (!str_starts_with($phone, '595')) $phone = '595' . $phone;
    $ud = array_filter(['phone_number' => '+' . $phone, 'email' => $T['email'] ?: null]);
?>
<script>
window.addEventListener('load', function () {
  if (typeof gtag !== 'function') return;
  gtag('set', 'user_data', <?= json_encode($ud) ?>);
  gtag('event', 'generate_lead', {currency: 'PYG', value: <?= (int)$T['value'] ?>, lead_type: <?= json_encode($T['type']) ?>, service: <?= json_encode($T['service']) ?>});
  <?php if (cfg('ads_id') && $label): ?>
  gtag('event', 'conversion', {send_to: <?= json_encode(cfg('ads_id') . '/' . $label) ?>, value: <?= (int)$T['value'] ?>, currency: 'PYG', transaction_id: <?= json_encode($T['ref']) ?>});
  <?php endif; ?>
});
</script>
<?php endif; ?>
<?php }]);
