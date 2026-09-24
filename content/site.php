<?php
/*
 * Datos del negocio. Regla: nada inventado. Lo que no está confirmado queda
 * en null y la parte del sitio que lo mostraría se oculta.
 */
return [
    'name'        => 'Dentista.com.py',
    'area'        => 'Asunción y Gran Asunción',
    'country'     => 'Paraguay',
    'email'       => null,           // email público, cuando exista
    'instagram'   => null,
    'facebook'    => null,

    // Valor estimado de cada lead para Google Ads (guaraníes). No se muestra en
    // el sitio: es la señal de puja. Ajustalo a lo que te paga el odontólogo
    // por cada tipo de paciente. Tier A = tratamientos de ticket alto.
    'leadValues'  => [
        'A'       => 150000,
        'B'       => 80000,
        'C'       => 40000,
        'partner' => 500000,
    ],

    // Franja de promesa: solo cosas que son verdad desde el día uno.
    'promises' => [
        'Presupuesto sin costo',
        'Respondemos por WhatsApp',
        'Asunción y Gran Asunción',
        'Coordinamos el horario que te sirva',
    ],

    // Opciones de urgencia del formulario: clave => etiqueta.
    'urgency' => [
        'hoy'      => 'Hoy o mañana (tengo dolor)',
        'semana'   => 'Esta semana',
        'mes'      => 'Este mes',
        'info'     => 'Solo quiero información',
    ],
];
