<?php
/*
 * Copiá este archivo como config.php EN EL SERVIDOR (hPanel → Administrador de
 * archivos) y completalo. config.php está en .gitignore: nunca se sube a GitHub.
 * Sin config.php el sitio funciona igual con estos valores por defecto.
 */
return [
    'site_url'        => 'https://dentista.com.py',
    'whatsapp'        => '595995628862',        // ⚠️ confirmar. Formato internacional sin + ni espacios.
    'phone_display'   => '+595 995 628862',     // ⚠️ confirmar
    'noindex'         => false,                 // true = staging / demo

    // A dónde llegan los leads (además del CSV en /leads/).
    'email_to'        => '',                    // ej. tu@gmail.com
    'email_from'      => 'no-reply@dentista.com.py',
    'webhook_url'     => '',                    // VenderCRM /api/v1/leads, Zapier, Make…
    'webhook_key'     => '',                    // se envía como header X-API-Key

    // Google Analytics 4 + Google Ads. Vacío = no se carga ningún script.
    'ga4_id'             => '',                 // G-XXXXXXXXXX
    'ads_id'             => '',                 // AW-XXXXXXXXXX
    'ads_label_patient'  => '',                 // etiqueta de conversión "Lead paciente"
    'ads_label_partner'  => '',                 // etiqueta de conversión "Lead odontólogo"
    'ads_label_whatsapp' => '',                 // etiqueta de conversión "Clic WhatsApp"
    'gsc_verification'   => '',                 // meta google-site-verification (opcional)
];
