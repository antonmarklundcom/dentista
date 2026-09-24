<?php $FE = $_GET['error'] ?? ''; if ($FE === 'datos'): ?>
<p class="form-error" role="alert">Revisá tu nombre y tu WhatsApp, y marcá la casilla de aceptación.</p>
<?php elseif ($FE === 'limite'): ?>
<p class="form-error" role="alert">Recibimos varios envíos seguidos. Escribinos directo por WhatsApp.</p>
<?php endif; unset($FE); ?>
