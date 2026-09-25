<?php
/*
 * Image registry. key => [file (stem in assets/img/), alt, size [w,h] of the 1280 version].
 * img('key') renders only when assets/img/<file>-640.webp exists, so missing
 * images never break a page. Prompts: docs/image-prompts.md.
 * Files come from webimg: <file>-640.avif/.webp and <file>-1280.avif/.webp.
 */
return [
    'items' => [
        // Lifestyle
        'hero'          => ['file' => 'hero-sonrisa-asuncion', 'alt' => 'Mujer sonriendo con dientes sanos en una terraza de Asunción con un lapacho en flor', 'size' => [1280, 1600]],
        'pareja'        => ['file' => 'pareja-sonriendo-asuncion', 'alt' => 'Pareja riendo en un café de Villa Morra, Asunción', 'size' => [1280, 860]],
        'familia'       => ['file' => 'familia-dentista-asuncion', 'alt' => 'Familia paraguaya sonriendo en su casa', 'size' => [1280, 860]],
        'adulto-mayor'  => ['file' => 'adulto-mayor-sonrisa', 'alt' => 'Hombre de 65 años con una sonrisa completa y natural', 'size' => [1280, 1600]],

        // Treatments (key = service slug)
        'brackets'                  => ['file' => 'brackets-ortodoncia-asuncion', 'alt' => 'Joven con brackets metálicos sonriendo', 'size' => [1280, 964]],
        'brackets-autoligables'     => ['file' => 'brackets-autoligables-asuncion', 'alt' => 'Joven con brackets estéticos autoligables transparentes', 'size' => [1280, 964]],
        'alineadores-transparentes' => ['file' => 'alineadores-transparentes', 'alt' => 'Hombre colocándose un alineador dental transparente', 'size' => [1280, 964]],
        'protesis-dental'           => ['file' => 'protesis-dental-asuncion', 'alt' => 'Mujer de 60 años riendo con una prótesis dental de aspecto natural', 'size' => [1280, 964]],
        'puente-dental'             => ['file' => 'puente-dental-asuncion', 'alt' => 'Modelo dental con un puente fijo de tres piezas', 'size' => [1280, 964]],
        'profilaxis-dental'         => ['file' => 'limpieza-sarro-profilaxis', 'alt' => 'Limpieza dental con ultrasonido para quitar el sarro', 'size' => [1280, 964]],
        'blanqueamiento-dental'     => ['file' => 'blanqueamiento-dental', 'alt' => 'Guía de color dental junto a la sonrisa de una paciente', 'size' => [1280, 964]],
        'bruxismo'                  => ['file' => 'bruxismo-placa-descarga', 'alt' => 'Placa de descarga transparente para el bruxismo', 'size' => [1280, 964]],
        'encias-inflamadas'         => ['file' => 'encias-sanas', 'alt' => 'Sonrisa con encías sanas, rosadas y firmes', 'size' => [1280, 964]],
        'muela-del-juicio'          => ['file' => 'muela-del-juicio-radiografia', 'alt' => 'Radiografía panorámica que muestra una muela del juicio', 'size' => [1280, 964]],
        'implantes-dentales'        => ['file' => 'implantes-dentales-asuncion', 'alt' => 'Hombre de 50 años sonriendo tras un implante dental', 'size' => [1280, 964]],
        'carillas-dentales'         => ['file' => 'despues-carillas', 'alt' => 'Sonrisa con dientes frontales armoniosos, estética dental (imagen ilustrativa)', 'size' => [1280, 964]],
        'odontopediatria'           => ['file' => 'odontopediatria-ninos', 'alt' => 'Niño riendo en el sillón dental durante una consulta de odontopediatría', 'size' => [1280, 964]],

        // Partner page (illustrative, not a real partner)
        'odontologa'    => ['file' => 'odontologa-consultorio', 'alt' => 'Odontóloga en un consultorio moderno (imagen ilustrativa)', 'size' => [1280, 1600]],
        'consultorio'   => ['file' => 'consultorio-moderno-asuncion', 'alt' => 'Consultorio odontológico moderno en Asunción', 'size' => [1280, 860]],
    ],

    // Before/after simulations. Always rendered with the caption below.
    'caption' => 'Simulación ilustrativa — no es un paciente real.',
    'beforeAfter' => [
        ['key' => 'blanqueamiento', 'label' => 'Blanqueamiento', 'service' => 'blanqueamiento-dental',
         'before' => ['file' => 'antes-blanqueamiento', 'alt' => 'Simulación: dientes con manchas antes del blanqueamiento'],
         'after'  => ['file' => 'despues-blanqueamiento', 'alt' => 'Simulación: los mismos dientes después del blanqueamiento']],
        ['key' => 'ortodoncia', 'label' => 'Ortodoncia', 'service' => 'brackets',
         'before' => ['file' => 'antes-ortodoncia', 'alt' => 'Simulación: dientes apiñados antes de la ortodoncia'],
         'after'  => ['file' => 'despues-ortodoncia', 'alt' => 'Simulación: dientes alineados después de la ortodoncia']],
        ['key' => 'carillas', 'label' => 'Estética dental', 'service' => 'carillas-dentales',
         'before' => ['file' => 'antes-carillas', 'alt' => 'Simulación: dientes frontales desgastados y con espacios'],
         'after'  => ['file' => 'despues-carillas', 'alt' => 'Simulación: dientes frontales armoniosos después del tratamiento estético']],
        ['key' => 'protesis', 'label' => 'Prótesis', 'service' => 'protesis-dental',
         'before' => ['file' => 'antes-protesis', 'alt' => 'Simulación: sonrisa con piezas dentales faltantes'],
         'after'  => ['file' => 'despues-protesis', 'alt' => 'Simulación: sonrisa completa con prótesis dental']],
    ],
];
