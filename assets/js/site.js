/* Dentista.com.py — menú, atribución UTM, clics de WhatsApp, formulario en pasos y antes/después. Sin dependencias ni Google. */
(function () {
  'use strict';
  window.__dp = 1; // failsafe flag read by the inline script in <head>
  var KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
  var TTL = 90 * 864e5;

  function store(k, v) { try { localStorage.setItem('dp_' + k, JSON.stringify({ v: v, t: Date.now() })); } catch (e) {} }
  function read(k) {
    try {
      var o = JSON.parse(localStorage.getItem('dp_' + k) || 'null');
      return o && Date.now() - o.t < TTL ? o.v : '';
    } catch (e) { return ''; }
  }
  function each(sel, fn, root) { Array.prototype.forEach.call((root || document).querySelectorAll(sel), fn); }

  // 1. Guardar UTM de la URL (última interacción paga gana) y completar los formularios.
  var qs = new URLSearchParams(location.search);
  if (KEYS.some(function (k) { return qs.get(k); })) KEYS.forEach(function (k) { store(k, qs.get(k) || ''); });
  if (!read('utm_source') && document.referrer && document.referrer.indexOf(location.host) === -1) {
    try { store('utm_source', new URL(document.referrer).hostname); store('utm_medium', 'referral'); } catch (e) {}
  }
  each('[data-track]', function (el) { el.value = read(el.getAttribute('data-track')); });

  // 2. Menú móvil.
  var tog = document.querySelector('.nav-toggle'), nav = document.getElementById('nav');
  function setNav(open) {
    if (!tog) return;
    tog.setAttribute('aria-expanded', open);
    document.body.classList.toggle('nav-open', open);
  }
  if (tog && nav) {
    tog.addEventListener('click', function () { setNav(tog.getAttribute('aria-expanded') !== 'true'); });
    nav.addEventListener('click', function (e) { if (e.target.closest('a')) setNav(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && document.body.classList.contains('nav-open')) { setNav(false); tog.focus(); } });
  }

  // 3. Formulario de paciente en 3 pasos (mejora progresiva: sin JS es un formulario normal).
  function wizard(f) {
    var steps = Array.prototype.slice.call(f.querySelectorAll('.lf-step'));
    var bars = f.querySelectorAll('.lf-progress__bar span');
    var label = f.querySelector('[data-progress-label]');
    var cur = 1;
    f.noValidate = true;
    each('[data-progress], [data-next], [data-js-only], [data-summary]', function (el) { el.hidden = false; }, f);

    function picked(name) { var i = f.querySelector('input[name="' + name + '"]:checked'); return i ? i.getAttribute('data-label') : ''; }
    function summarize(n) {
      var t = n === 2 ? 'Tratamiento: ' + picked('service') : [picked('service'), picked('zone'), picked('urgency')].filter(Boolean).join(' · ');
      each('[data-summary-text]', function (el) { el.textContent = t; }, steps[n - 1]);
    }
    function go(n, focus) {
      cur = n;
      f.setAttribute('data-current', n);
      steps.forEach(function (s) { s.classList.toggle('is-current', +s.getAttribute('data-step') === n); });
      Array.prototype.forEach.call(bars, function (b, i) { b.classList.toggle('is-on', i < n); });
      if (label) label.textContent = 'Paso ' + n + ' de 3';
      if (n > 1) summarize(n);
      if (!focus) return;
      var top = f.getBoundingClientRect().top;
      if (top < 0 || top > innerHeight * 0.6) f.scrollIntoView({ behavior: 'smooth', block: 'start' });
      var t = n === 3 ? f.querySelector('input[name=name]') : steps[n - 1].querySelector('.lf-legend');
      if (t) setTimeout(function () { t.focus({ preventScroll: true }); }, 60);
    }
    function valid(n) {
      var s = steps[n - 1], bad = null;
      each('fieldset.lf-group', function (g) {
        var r = g.querySelector('input[type=radio]');
        if (!r) return;
        var ok = !!g.querySelector('input:checked');
        g.classList.toggle('is-invalid', !ok);
        var err = g.querySelector('[data-err]');
        if (err) err.hidden = ok;
        if (!ok && !bad) bad = r;
      }, s);
      each('input:not([type=radio]):not([type=hidden]), select', function (el) {
        var ok = el.checkValidity();
        if (ok) el.removeAttribute('aria-invalid'); else el.setAttribute('aria-invalid', 'true');
        if (!ok && !bad) bad = el;
      }, s);
      if (bad) {
        bad.focus();
        if (bad.type !== 'radio' && bad.reportValidity) bad.reportValidity();
        return false;
      }
      return true;
    }
    each('[data-next]', function (b) { b.addEventListener('click', function () { if (valid(cur)) go(cur + 1, true); }); }, f);
    each('[data-back]', function (b) { b.addEventListener('click', function () { go(Math.max(1, cur - 1), true); }); }, f);
    f.addEventListener('change', function (e) {
      if (e.target.type === 'radio') {
        var g = e.target.closest('.lf-group');
        if (g) { g.classList.remove('is-invalid'); var err = g.querySelector('[data-err]'); if (err) err.hidden = true; }
      } else if (e.target.checkValidity && e.target.checkValidity()) e.target.removeAttribute('aria-invalid');
    });
    f.addEventListener('submit', function (e) {
      if (cur < 3) { e.preventDefault(); if (valid(cur)) go(cur + 1, true); return; }
      for (var n = 1; n <= 3; n++) if (!valid(n)) { e.preventDefault(); if (n !== cur) go(n, true); return; }
    });
    f._go = function (n) { go(n, true); };
    f._cur = function () { return cur; };
    go(1, false);
  }
  each('form[data-steps]', wizard);

  // 4. Todos los formularios: anti doble envío.
  each('[data-lead-form]', function (f) {
    f.addEventListener('submit', function (e) {
      if (e.defaultPrevented) return;
      var b = f.querySelector('[type=submit]');
      if (b) { setTimeout(function () { b.disabled = true; b.textContent = 'Enviando…'; }, 0); }
    });
  });

  // 5. Clics: WhatsApp (/wa/ registra el clic; le sumamos qué botón fue) y botones que llevan al formulario.
  document.addEventListener('click', function (ev) {
    var a = ev.target.closest('[data-wa]');
    if (a) {
      var loc = a.getAttribute('data-ev-loc');
      if (loc && a.href.indexOf('/wa/') !== -1 && a.href.indexOf('&l=') === -1) a.href += '&l=' + encodeURIComponent(loc);
      return;
    }
    var s = ev.target.closest('[data-scroll-form]');
    if (!s) return;
    var f = document.getElementById('pedir-turno');
    ev.preventDefault();
    if (!f) { location.href = '/contacto/#pedir-turno'; return; }
    var pick = s.getAttribute('data-pick');
    var r = pick && f.querySelector('input[name=service][value="' + pick + '"]');
    if (r) { r.checked = true; r.dispatchEvent(new Event('change', { bubbles: true })); }
    f.scrollIntoView({ behavior: 'smooth', block: 'start' });
    if (f._go) {
      f._go(r && f._cur() === 1 ? 2 : f._cur());
    } else {
      var first = f.querySelector('input:not([type=hidden]):not([tabindex="-1"]), select');
      if (first) setTimeout(function () { first.focus({ preventScroll: true }); }, 500);
    }
  });

  // 6. Antes / después: el range controla el recorte.
  each('.ba', function (fig) {
    var r = fig.querySelector('.ba__range');
    if (!r) return;
    function u() { fig.style.setProperty('--pos', r.value + '%'); r.setAttribute('aria-valuetext', r.value + '% antes, ' + (100 - r.value) + '% después'); }
    r.addEventListener('input', u);
    u();
  });
})();
