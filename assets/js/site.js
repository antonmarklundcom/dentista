/* dentista.com.py — site.js
   Innehåll: WhatsApp-konstanten, cotizadorn (sektion 08), consent-bannern och
   analytics-shimmen. Ingen tredjepartsladdning, inga beroenden.

   ▸ BYTE AV WHATSAPP-NUMMER: ändra WA_NUMBER här och kör `node tools/swap-wa.mjs`
     så uppdateras även de statiska href:arna och den synliga texten i HTML:en. */
(function () {
  'use strict';

  var WA_NUMBER = '595995628862';               // enda stället i JS
  var SITE      = 'dentista.com.py';

  /* ── 1. Analytics-shim (web-design-system/references/analytics-prep.md) ──
     Laddar ingenting. Pushar in i en array tills GA4/GTM/Plausible kopplas på. */
  window.dataLayer = window.dataLayer || [];
  document.addEventListener('click', function (e) {
    var t = e.target.closest('[data-ev]');
    if (!t) return;
    window.dataLayer.push({
      event: t.dataset.ev,
      ev_loc: t.dataset.evLoc || '',
      page_path: location.pathname,
      site: location.hostname
    });
  }, true);

  /* ── 2. WhatsApp-länkar: numret sätts från konstanten ovan.
        Det statiska href:et i HTML:en är no-JS-fallback. ── */
  document.querySelectorAll('a[href^="https://wa.me/"]').forEach(function (a) {
    a.href = a.href.replace(/wa\.me\/\d+/, 'wa.me/' + WA_NUMBER);
  });

  /* ── 3. Bildslots som ännu inte har en fil (BUILD-SPEC §9, platshållare 8):
        döljer den trasiga <img> så .media-panelen visar sin tonade yta i stället
        för en trasig bildikon. Tas aldrig bort — den skyddar även mot 404 i drift. */
  document.querySelectorAll('.media img').forEach(function (img) {
    img.addEventListener('error', function () { img.style.display = 'none'; });
    if (img.complete && img.naturalWidth === 0) img.style.display = 'none';
  });

  /* ── 4. Cotizador (sektion 08) — bygger WhatsApp-texten. NOLL belopp. ── */
  var trat = document.getElementById('c-trat');
  var zona = document.getElementById('c-zona');
  var hora = document.getElementById('c-hora');
  var prev = document.getElementById('c-preview');
  var cta  = document.getElementById('c-cta');

  if (trat && zona && hora && prev && cta) {
    var opened = false;

    var render = function () {
      var t = trat.value, z = zona.value, h = hora.value;
      var msg = 'Hola, vengo de ' + SITE + ' — necesito ' + t + ' en ' + z + ', prefiero ' + h + '.';
      prev.textContent = '"' + msg + '"';
      cta.href = 'https://wa.me/' + WA_NUMBER + '?text=' +
        encodeURIComponent('Hola, vengo de ' + SITE + ' (cotizador) - necesito ' + t + ' en ' + z + ', prefiero ' + h);
    };

    var onChange = function () {
      if (!opened) {
        opened = true;
        window.dataLayer.push({
          event: 'calc_open', ev_loc: 'cotizador',
          page_path: location.pathname, site: location.hostname
        });
      }
      render();
    };

    [trat, zona, hora].forEach(function (el) { el.addEventListener('change', onChange); });
    render();
  }

  /* ── 5. Consent (Ley 6534/2020). Inget förikryssat, inget laddas oavsett val. ── */
  var box = document.getElementById('consent');
  if (box) {
    var KEY = 'vc_consent';
    var stored = null;
    try { stored = localStorage.getItem(KEY); } catch (err) { stored = 'skip'; }

    if (!stored) {
      box.hidden = false;
      var close = function (value) {
        try { localStorage.setItem(KEY, value); } catch (err) { /* privat läge: visa igen nästa gång */ }
        box.hidden = true;
      };
      document.getElementById('consent-ok').addEventListener('click', function () { close('accepted'); });
      document.getElementById('consent-no').addEventListener('click', function () { close('declined'); });
    }
  }
})();
