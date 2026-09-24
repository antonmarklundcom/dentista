<?php
/* /gracias/: muestra la referencia del lead una sola vez (la sesión se consume acá). */
session_start();
$T = $_SESSION['thanks'] ?? null;
unset($_SESSION['thanks']);
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
  <p>Mientras tanto, te puede servir: <a href="<?= e(guide_url($TG['slug'])) ?>"><?= e($TG['h1']) ?></a>.</p>
  <?php endif; endif; ?>
  <?php endif; ?>
  <p class="btn-row"><a class="btn btn--ghost" href="/">Volver al inicio</a></p>
</div></section>
<?php }]);
