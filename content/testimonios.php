<?php
/*
 * Opiniones reales de pacientes que coordinaron por el sitio. Regla: nunca
 * inventadas ni "mejoradas"; se publican con el permiso del paciente y sin
 * datos de salud que no haya aceptado mostrar. Mientras esta lista esté vacía,
 * el bloque de opiniones no se muestra. No se genera AggregateRating: las
 * opiniones propias no dan estrellas en Google y marcarlas sería engañoso.
 *
 * Cada entrada:
 *   'quote'    => 'Texto tal como lo escribió el paciente (se puede acortar, no reescribir).',
 *   'name'     => 'María G.',          // nombre + inicial
 *   'zone'     => 'luque',             // clave de content/zonas.php, o null
 *   'service'  => 'profilaxis-dental', // clave de content/servicios/, o null
 *   'date'     => '2026-10-12',
 *   'source'   => 'WhatsApp',          // dónde lo dejó: WhatsApp, Google, Instagram…
 *   'consent'  => true,                // aceptó que se publique; false = no se muestra
 */
return [];
