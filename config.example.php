<?php
/*
 * Copiá este archivo como config.php EN EL SERVIDOR (hPanel → Administrador de
 * archivos) y completalo. config.php está en .gitignore: nunca se sube a GitHub.
 * Sin config.php el sitio funciona igual: los leads quedan en /leads/*.csv.
 */
return [
    'site_url'        => 'https://dentista.com.py',
    'whatsapp'        => '595995628862',        // ⚠️ confirmar. Formato internacional sin + ni espacios.
    'phone_display'   => '+595 995 628862',     // ⚠️ confirmar
    'noindex'         => false,                 // true = staging / demo

    // VenderCRM — la clave va SOLO en config.php del servidor (o variable de
    // entorno VENDERCRM_API_KEY). Nunca en el repo ni en JavaScript.
    'vcrm_url'        => 'https://crm.clientes.com.py',
    'vcrm_api_key'    => '',

    // Aviso por email de cada lead (opcional, usa mail() de PHP).
    'email_to'        => '',
    'email_from'      => 'no-reply@dentista.com.py',

    // Panel /admin/. Generá el hash con:
    //   php -r "echo password_hash('TU-CLAVE', PASSWORD_DEFAULT), PHP_EOL;"
    // Vacío = el panel está desactivado.
    'admin_password_hash' => '',
];
