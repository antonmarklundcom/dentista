<?php
/*
 * Odontólogos de la red que aceptaron aparecer en el sitio. Regla: solo datos
 * reales y confirmados por el profesional. Mientras esta lista esté vacía, los
 * bloques de profesionales no se muestran.
 *
 * Cada entrada:
 *   'slug'      => 'ana-benitez',                  // único, minúsculas
 *   'name'      => 'Dra. Ana Benítez',
 *   'title'     => 'Odontóloga · Ortodoncia',     // especialidad declarada por el profesional
 *   'license'   => 'Reg. Prof. 1234',             // número de registro/matrícula tal como figura
 *   'zones'     => ['san-lorenzo', 'fernando-de-la-mora'],   // claves de content/zonas.php
 *   'services'  => ['brackets', 'alineadores-transparentes'], // claves de content/servicios/
 *   'bio'       => 'Una o dos frases escritas o aprobadas por el profesional.',
 *   'photo'     => 'prof-ana-benitez',            // clave de content/images.php (foto REAL), o null
 *   'consent'   => '2026-10-01',                  // fecha en que aceptó aparecer; sin fecha no se muestra
 */
return [];
