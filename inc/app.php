<?php
declare(strict_types=1);

defined('ROOT') || define('ROOT', dirname(__DIR__));

$GLOBALS['CFG'] = array_merge(
    require ROOT . '/config.example.php',
    is_file(ROOT . '/config.php') ? (require ROOT . '/config.php') : []
);

function cfg(string $k, $default = null) { return $GLOBALS['CFG'][$k] ?? $default; }

function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

function base_url(): string
{
    $u = cfg('site_url') ?: ('https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
    return rtrim($u, '/');
}

function abs_url(string $path): string { return base_url() . $path; }

/** WhatsApp link through /wa/, which logs the click and redirects to wa.me. */
function wa_link(string $text): string
{
    return '/wa/?t=' . rawurlencode($text);
}

function site(): array
{
    static $s;
    return $s ??= require ROOT . '/content/site.php';
}

/** Loads content/<dir>/*.php records, keyed by slug and sorted by 'order'. */
function records(string $dir): array
{
    static $cache = [];
    if (isset($cache[$dir])) return $cache[$dir];
    $out = [];
    foreach (glob(ROOT . "/content/$dir/*.php") as $f) {
        $r = require $f;
        $r['slug'] = basename($f, '.php');
        $out[$r['slug']] = $r;
    }
    uasort($out, fn($a, $b) => ($a['order'] ?? 99) <=> ($b['order'] ?? 99));
    return $cache[$dir] = $out;
}

require_once __DIR__ . '/parts.php';

function services(): array { return records('servicios'); }
function guides(): array   { return records('guias'); }

/** Guides shown in the "Salud dental" hub (the consultorios page lives at its own top-level URL). */
function edu_guides(): array { return array_diff_key(guides(), ['consultorios-odontologicos' => 1]); }

function guide_url(string $slug): string
{
    return $slug === 'consultorios-odontologicos' ? '/consultorios-odontologicos/' : '/salud-dental/' . $slug . '/';
}

/*
 * Images: content/images.php maps a key (usually a page slug) to file + alt.
 * img() prints a responsive <picture> only if the optimized files exist in
 * assets/img/, so pages render cleanly before the images are generated.
 * Files per key: <file>-640.avif/.webp, <file>-1280.avif/.webp (webimg output).
 */
function images(): array
{
    static $i;
    return $i ??= (is_file(ROOT . '/content/images.php') ? require ROOT . '/content/images.php' : []);
}

function img_exists(string $key): bool
{
    $m = images()['items'][$key] ?? null;
    return $m && is_file(ROOT . '/assets/img/' . $m['file'] . '-640.webp');
}

function img(string $key, string $class = '', string $sizes = '(min-width: 1024px) 600px, 100vw', bool $eager = false): string
{
    if (!img_exists($key)) return '';
    $m = images()['items'][$key];
    $b = '/assets/img/' . $m['file'];
    $set = fn(string $ext) => implode(', ', array_filter([
        "$b-640.$ext 640w",
        is_file(ROOT . "$b-1280.$ext") ? "$b-1280.$ext 1280w" : null,
    ]));
    [$w, $h] = $m['size'] ?? [1280, 960];
    return '<picture class="' . e($class) . '">'
        . (is_file(ROOT . "$b-640.avif") ? '<source type="image/avif" srcset="' . e($set('avif')) . '" sizes="' . e($sizes) . '">' : '')
        . '<source type="image/webp" srcset="' . e($set('webp')) . '" sizes="' . e($sizes) . '">'
        . '<img src="' . e("$b-640.webp") . '" alt="' . e($m['alt']) . '" width="' . (int)$w . '" height="' . (int)$h . '"'
        . ($eager ? ' fetchpriority="high"' : ' loading="lazy" decoding="async"') . '>'
        . '</picture>';
}
function zones(): array
{
    static $z;
    return $z ??= require ROOT . '/content/zonas.php';
}

/** Lead value for Ads conversion value, in PYG. */
function lead_value(?string $service, string $type = 'paciente'): int
{
    $s = site();
    if ($type === 'partner') return $s['leadValues']['partner'];
    $tier = services()[$service]['tier'] ?? 'C';
    return $s['leadValues'][$tier] ?? $s['leadValues']['C'];
}

/** Renders a full page: $page = [title, description, canonical, noindex, schema[], body(callable), bodyClass, lp]. */
function render(array $page): void
{
    extract(['page' => $page]);
    require ROOT . '/inc/layout.php';
}

function not_found(): void
{
    http_response_code(404);
    render([
        'title' => 'Página no encontrada — Dentista.com.py',
        'description' => 'La página que buscás no existe. Volvé al inicio o escribinos por WhatsApp y coordinamos tu consulta odontológica.',
        'noindex' => true,
        'body' => function () { ?>
<section class="section"><div class="container narrow">
  <p class="eyebrow">Error 404</p>
  <h1>Esta página no existe</h1>
  <p class="lead">Puede que el enlace esté mal escrito o que la página se haya movido.</p>
  <p class="btn-row"><a class="btn btn--primary" href="/">Ir al inicio</a>
  <a class="btn btn--ghost" href="/servicios/">Ver tratamientos</a></p>
</div></section>
<?php }]);
}
