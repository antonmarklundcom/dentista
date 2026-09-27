<?php
/*
 * Trust and conversion blocks that only render with real data:
 * professionals (content/profesionales.php), patient reviews
 * (content/testimonios.php) and "desde" prices (service 'price_from').
 * Empty data = nothing printed, so no placeholder ever reaches the page.
 */

function professionals(): array
{
    static $p;
    if ($p !== null) return $p;
    $all = is_file(ROOT . '/content/profesionales.php') ? require ROOT . '/content/profesionales.php' : [];
    return $p = array_values(array_filter($all, fn($x) => !empty($x['name']) && !empty($x['license']) && !empty($x['consent'])));
}

function testimonials(): array
{
    static $t;
    if ($t !== null) return $t;
    $all = is_file(ROOT . '/content/testimonios.php') ? require ROOT . '/content/testimonios.php' : [];
    return $t = array_values(array_filter($all, fn($x) => ($x['consent'] ?? false) === true && trim((string)($x['quote'] ?? '')) !== ''));
}

/** Professionals filtered by service and/or zone; with no match, falls back to the whole network. */
function professionals_block(?string $service = null, ?string $zone = null, string $class = 'section section--line'): void
{
    $all = professionals();
    if (!$all) return;
    $list = array_filter($all, fn($p) => (!$service || in_array($service, $p['services'] ?? [], true))
                                      && (!$zone || in_array($zone, $p['zones'] ?? [], true)));
    if (!$list) $list = $all;
    $list = array_slice($list, 0, 6);
    ?>
<section class="<?= e($class) ?>" aria-labelledby="pros-h">
  <div class="container">
    <div class="section-head"><div><?= eyebrow('Profesionales de la red') ?><h2 id="pros-h">Odontólogos con los que coordinamos</h2></div>
      <p>Antes de confirmar tu turno te pasamos el nombre, el registro profesional y la dirección del consultorio.</p></div>
    <div class="card-grid card-grid--pros">
      <?php foreach ($list as $p): $ph = !empty($p['photo']) ? img($p['photo'], 'pro__photo', '96px') : ''; ?>
      <article class="card pro">
        <div class="pro__head"><?= $ph ?: '<span class="pro__initials" aria-hidden="true">' . e(mb_substr(preg_replace('/^(Dra?\.?\s+)/u', '', $p['name']), 0, 1)) . '</span>' ?>
          <div><h3><?= e($p['name']) ?></h3><p class="pro__title"><?= e($p['title'] ?? 'Odontólogo/a') ?></p></div></div>
        <p class="pro__license"><?= icon('check', 'ico ico--xs') ?><?= e($p['license']) ?></p>
        <?php if (!empty($p['bio'])): ?><p><?= e($p['bio']) ?></p><?php endif; ?>
        <?php $zn = array_filter(array_map(fn($z) => zones()[$z]['name'] ?? null, $p['zones'] ?? [])); if ($zn): ?>
        <p class="pro__zones">Atiende en <?= e(implode(', ', $zn)) ?></p><?php endif; ?>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php
}

/** Real patient reviews, preferring ones about this service. */
function testimonials_block(?string $service = null, string $class = 'section section--tint'): void
{
    $all = testimonials();
    if (!$all) return;
    usort($all, fn($a, $b) => (($b['service'] ?? '') === $service) <=> (($a['service'] ?? '') === $service) ?: strcmp($b['date'] ?? '', $a['date'] ?? ''));
    $list = array_slice($all, 0, 3);
    ?>
<section class="<?= e($class) ?>" aria-labelledby="rev-h">
  <div class="container">
    <div class="section-head"><div><?= eyebrow('Pacientes') ?><h2 id="rev-h">Lo que cuentan quienes ya coordinaron</h2></div></div>
    <div class="card-grid card-grid--reviews">
      <?php foreach ($list as $t): $svc = services()[$t['service'] ?? ''] ?? null; $zn = zones()[$t['zone'] ?? '']['name'] ?? null; ?>
      <figure class="card review">
        <blockquote><p>“<?= e($t['quote']) ?>”</p></blockquote>
        <figcaption><strong><?= e($t['name'] ?? 'Paciente') ?></strong><?= $zn ? ', ' . e($zn) : '' ?>
          <span><?= $svc ? e($svc['nav']) . ' · ' : '' ?><?= !empty($t['date']) ? e(date('m/Y', strtotime($t['date']) ?: time())) : '' ?><?= !empty($t['source']) ? ' · vía ' . e($t['source']) : '' ?></span></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php
}

function gs(int $n): string { return 'Gs. ' . number_format($n, 0, ',', '.'); }

/** "Desde Gs. X" line for a service, or '' when no real price is set. */
function price_line(array $s): string
{
    if (empty($s['price_from'])) return '';
    return '<p class="price-from"><span class="price-from__k">Desde</span> <strong>' . e(gs((int)$s['price_from'])) . '</strong>'
         . ' <span class="price-from__n">' . e($s['price_note'] ?? 'Valor orientativo; el presupuesto final se da después de la evaluación.') . '</span></p>';
}

/** schema.org Offer for a service with a real "desde" price, or null. */
function price_offer(array $s): ?array
{
    if (empty($s['price_from'])) return null;
    return ['@type' => 'Offer', 'priceCurrency' => 'PYG',
            'priceSpecification' => ['@type' => 'PriceSpecification', 'minPrice' => (int)$s['price_from'], 'priceCurrency' => 'PYG']];
}

/** Compact mid-article call to action for guides. */
function mid_cta(array $svc, string $wa): void
{
    ?>
<aside class="mid-cta" aria-label="Coordiná tu consulta">
  <p class="mid-cta__t">¿Querés que lo revise un odontólogo?</p>
  <p class="mid-cta__p">Te coordinamos una evaluación de <?= e(mb_strtolower($svc['nav'])) ?> en Asunción o el Gran Asunción. El presupuesto no tiene costo.</p>
  <div class="btn-row"><?= wa_btn($wa, 'Escribinos por WhatsApp', 'guide-mid', 'btn btn--accent btn--sm') ?>
    <a class="link-arrow" href="/servicios/<?= e($svc['slug']) ?>/">Ver <?= e(mb_strtolower($svc['nav'])) ?><?= arrow() ?></a></div>
</aside>
<?php
}
