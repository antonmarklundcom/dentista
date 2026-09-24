<?php
/** @var array $page */
$L_path      = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$L_canonical = $page['canonical'] ?? abs_url($L_path);
$L_noindex   = !empty($page['noindex']) || cfg('noindex');
$L_lp        = !empty($page['lp']);
$L_waDefault = $page['wa'] ?? 'Hola, vengo de dentista.com.py — quiero coordinar una consulta odontológica.';
$L_schema    = $page['schema'] ?? [];
$L_schema[]  = [
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    'name'     => site()['name'],
    'url'      => base_url() . '/',
    'inLanguage' => 'es-PY',
];
$L_og = null;
if (img_exists('hero')) {
    $L_ogf = '/assets/img/' . images()['items']['hero']['file'];
    $L_og = abs_url(is_file(ROOT . $L_ogf . '-1280.webp') ? $L_ogf . '-1280.webp' : $L_ogf . '-640.webp');
}
$L_nav = [
    ['/servicios/', 'Tratamientos'],
    ['/salud-dental/', 'Salud dental'],
    ['/zonas/', 'Zonas'],
    ['/para-odontologos/', 'Para odontólogos'],
    ['/contacto/', 'Contacto'],
];
?><!doctype html>
<html lang="es-PY">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page['title']) ?></title>
<meta name="description" content="<?= e($page['description']) ?>">
<?php if ($L_noindex): ?><meta name="robots" content="noindex,follow">
<?php else: ?><link rel="canonical" href="<?= e($L_canonical) ?>">
<?php endif; ?>
<?php if (cfg('gsc_verification')): ?><meta name="google-site-verification" content="<?= e(cfg('gsc_verification')) ?>">
<?php endif; ?>
<meta property="og:type" content="<?= e($page['ogType'] ?? 'website') ?>">
<meta property="og:locale" content="es_PY">
<meta property="og:site_name" content="Dentista.com.py">
<meta property="og:title" content="<?= e($page['title']) ?>">
<meta property="og:description" content="<?= e($page['description']) ?>">
<meta property="og:url" content="<?= e($L_canonical) ?>">
<?php if ($L_og): ?><meta property="og:image" content="<?= e($L_og) ?>">
<?php endif; ?>
<meta name="theme-color" content="#14241E">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="preload" href="/assets/fonts/fraunces.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/fonts/inter-tight.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="/assets/css/site.css?v=<?= @filemtime(ROOT . '/assets/css/site.css') ?>">
<script>document.documentElement.className+=' js';setTimeout(function(){if(!window.__dp)document.documentElement.classList.remove('js')},4000)</script>
<?php foreach ($L_schema as $L_s): ?>
<script type="application/ld+json"><?= json_encode($L_s, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php endforeach; ?>
<script src="<?= e(rtrim((string)cfg('vcrm_url'), '/')) ?>/vc-attribution.js" defer></script>
<script src="/assets/js/site.js?v=<?= @filemtime(ROOT . '/assets/js/site.js') ?>" defer></script>
</head>
<body class="<?= e($page['bodyClass'] ?? '') ?><?= $L_lp ? ' is-lp' : '' ?>">
<a class="skip" href="#main">Saltar al contenido</a>
<header class="site-header">
  <div class="container site-header__row">
    <a class="brand" href="/" aria-label="Dentista.com.py, inicio"><span class="brand__mark" aria-hidden="true"><?= icon('tooth') ?></span><span class="brand__name">Dentista<b>.com.py</b></span></a>
    <?php if (!$L_lp): ?>
    <nav class="nav" id="nav" aria-label="Principal">
      <?php foreach ($L_nav as [$L_href, $L_label]): ?>
      <a href="<?= e($L_href) ?>"<?= str_starts_with($L_path, $L_href) ? ' aria-current="page"' : '' ?>><?= e($L_label) ?></a>
      <?php endforeach; ?>
    </nav>
    <?php endif; ?>
    <div class="site-header__cta">
      <?= wa_btn($L_waDefault, 'WhatsApp', 'header', 'btn btn--outline btn--sm') ?>
      <?php if (!$L_lp): ?><button class="nav-toggle" type="button" aria-controls="nav" aria-expanded="false"><span class="sr">Menú</span><i></i></button><?php endif; ?>
    </div>
  </div>
</header>
<main id="main">
<?php ($page['body'])(); ?>
</main>
<footer class="site-footer">
  <div class="container">
    <?php if (!$L_lp): ?>
    <div class="footer-grid">
      <div class="footer-brand">
        <p class="brand brand--footer">Dentista<b>.com.py</b></p>
        <p>Coordinamos tu consulta con odontólogos matriculados de <?= e(site()['area']) ?>. No somos una clínica: te conectamos con el profesional indicado para tu caso.</p>
        <p>WhatsApp: <a href="<?= e(wa_link($L_waDefault)) ?>" data-wa data-ev-loc="footer" rel="nofollow noopener" target="_blank"><?= e(cfg('phone_display')) ?></a></p>
      </div>
      <nav aria-label="Tratamientos">
        <p class="footer-h">Tratamientos</p>
        <ul class="footer-list"><?php foreach (services() as $F_s): ?><li><a href="/servicios/<?= e($F_s['slug']) ?>/"><?= e($F_s['nav']) ?></a></li><?php endforeach; ?></ul>
      </nav>
      <nav aria-label="Zonas">
        <p class="footer-h">Zonas</p>
        <ul class="footer-list"><li><a href="/">Asunción</a></li><?php foreach (zones() as $F_slug => $F_z): ?><li><a href="/zonas/<?= e($F_slug) ?>/"><?= e($F_z['name']) ?></a></li><?php endforeach; ?></ul>
      </nav>
      <nav aria-label="Dentista.com.py">
        <p class="footer-h">Dentista.com.py</p>
        <ul class="footer-list">
          <li><a href="/salud-dental/">Salud dental</a></li>
          <?php if (isset(guides()['consultorios-odontologicos'])): ?><li><a href="<?= e(guide_url('consultorios-odontologicos')) ?>">Consultorios odontológicos</a></li><?php endif; ?>
          <li><a href="/para-odontologos/">Para odontólogos</a></li>
          <li><a href="/contacto/">Contacto</a></li>
          <li><a href="/privacidad/">Privacidad</a></li>
        </ul>
      </nav>
    </div>
    <?php endif; ?>
    <p class="footer-legal">Dentista.com.py no es una clínica ni presta servicios odontológicos. Coordinamos consultas con odontólogos matriculados independientes de <?= e(site()['area']) ?>. La información del sitio es orientativa y no reemplaza la evaluación de un odontólogo. © <?= date('Y') ?> Dentista.com.py<?= $L_lp ? ' · <a href="/privacidad/">Privacidad</a>' : '' ?></p>
  </div>
</footer>
<div class="mobile-bar">
  <?= wa_btn($L_waDefault, 'WhatsApp', 'mobile-bar', 'btn btn--ink') ?>
  <a class="btn btn--accent" href="#pedir-turno" data-scroll-form><?= !empty($page['partner']) ? 'Sumate' : 'Pedí tu turno' ?></a>
</div>
</body>
</html>
