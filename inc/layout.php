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
<meta property="og:type" content="website">
<meta property="og:locale" content="es_PY">
<meta property="og:site_name" content="Dentista.com.py">
<meta property="og:title" content="<?= e($page['title']) ?>">
<meta property="og:description" content="<?= e($page['description']) ?>">
<meta property="og:url" content="<?= e($L_canonical) ?>">
<meta name="theme-color" content="#14241E">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="preload" href="/assets/fonts/fraunces.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/fonts/inter-tight.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="/assets/css/site.css?v=<?= @filemtime(ROOT . '/assets/css/site.css') ?>">
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
    <a class="brand" href="/" aria-label="Dentista.com.py, inicio"><span class="brand__mark" aria-hidden="true"></span><span>Dentista<b>.com.py</b></span></a>
    <?php if (!$L_lp): ?>
    <nav class="nav" id="nav" aria-label="Principal">
      <a href="/servicios/">Tratamientos</a>
      <a href="/zonas/">Zonas</a>
      <a href="/salud-dental/">Salud dental</a>
      <a href="/para-odontologos/">Para odontólogos</a>
      <a href="/contacto/">Contacto</a>
    </nav>
    <?php endif; ?>
    <div class="site-header__cta">
      <a class="btn btn--wa btn--sm" href="<?= e(wa_link($L_waDefault)) ?>" data-wa data-ev-loc="header" rel="noopener" target="_blank"><?php require ROOT . '/inc/icon-wa.php'; ?><span>Escribinos</span></a>
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
      <div>
        <p class="brand brand--footer"><span>Dentista<b>.com.py</b></span></p>
        <p class="muted">Coordinamos tu consulta odontológica con odontólogos matriculados en <?= e(site()['area']) ?>. No somos una clínica: te conectamos con el profesional indicado para tu caso.</p>
        <p class="muted">Respondemos por WhatsApp: <a href="<?= e(wa_link($L_waDefault)) ?>" data-wa data-ev-loc="footer" rel="noopener" target="_blank"><?= e(cfg('phone_display')) ?></a></p>
      </div>
      <div>
        <p class="footer-h">Tratamientos</p>
        <ul class="footer-list"><?php foreach (services() as $F_s): ?><li><a href="/servicios/<?= e($F_s['slug']) ?>/"><?= e($F_s['nav']) ?></a></li><?php endforeach; ?></ul>
      </div>
      <div>
        <p class="footer-h">Zonas</p>
        <ul class="footer-list"><li><a href="/">Asunción</a></li><?php foreach (zones() as $F_slug => $F_z): ?><li><a href="/zonas/<?= e($F_slug) ?>/"><?= e($F_z['name']) ?></a></li><?php endforeach; ?></ul>
      </div>
      <div>
        <p class="footer-h">Más</p>
        <ul class="footer-list">
          <li><a href="/salud-dental/">Salud dental</a></li>
          <li><a href="/para-odontologos/">Para odontólogos</a></li>
          <li><a href="/contacto/">Contacto</a></li>
          <li><a href="/privacidad/">Privacidad</a></li>
        </ul>
      </div>
    </div>
    <?php endif; ?>
    <p class="footer-legal">© <?= date('Y') ?> Dentista.com.py · <?= e(site()['area']) ?>, Paraguay · <a href="/privacidad/">Privacidad</a>. La información del sitio es orientativa y no reemplaza la evaluación de un odontólogo.</p>
  </div>
</footer>
<div class="mobile-bar">
  <a class="btn btn--wa" href="<?= e(wa_link($L_waDefault)) ?>" data-wa data-ev-loc="mobile-bar" rel="noopener" target="_blank"><?php require ROOT . '/inc/icon-wa.php'; ?>WhatsApp</a>
  <a class="btn btn--primary" href="#pedir-turno" data-scroll-form>Pedí tu turno</a>
</div>
</body>
</html>
