/* Dentista.com.py — menú, atribución UTM, clics de WhatsApp y formularios. Sin dependencias ni Google. */
(function () {
  'use strict';
  var KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
  var TTL = 90 * 864e5;

  function store(k, v) { try { localStorage.setItem('dp_' + k, JSON.stringify({ v: v, t: Date.now() })); } catch (e) {} }
  function read(k) {
    try {
      var o = JSON.parse(localStorage.getItem('dp_' + k) || 'null');
      return o && Date.now() - o.t < TTL ? o.v : '';
    } catch (e) { return ''; }
  }
  function track() {}

  // 1. Guardar UTM de la URL (última interacción paga gana) y completar los formularios.
  var qs = new URLSearchParams(location.search);
  if (KEYS.some(function (k) { return qs.get(k); })) KEYS.forEach(function (k) { store(k, qs.get(k) || ''); });
  if (!read('utm_source') && document.referrer && document.referrer.indexOf(location.host) === -1) {
    try { store('utm_source', new URL(document.referrer).hostname); store('utm_medium', 'referral'); } catch (e) {}
  }
  document.querySelectorAll('[data-track]').forEach(function (el) { el.value = read(el.getAttribute('data-track')); });

  // 2. Menú móvil.
  var tog = document.querySelector('.nav-toggle'), nav = document.getElementById('nav');
  if (tog && nav) tog.addEventListener('click', function () {
    var open = tog.getAttribute('aria-expanded') !== 'true';
    tog.setAttribute('aria-expanded', open);
    document.body.classList.toggle('nav-open', open);
  });

  // 3. Clics de WhatsApp.
  document.addEventListener('click', function (ev) {
    var a = ev.target.closest('[data-wa]');
    if (a) {
      // /wa/ registra el clic; le sumamos qué botón fue.
      var loc = a.getAttribute('data-ev-loc');
      if (loc && a.href.indexOf('/wa/') !== -1 && a.href.indexOf('&l=') === -1) a.href += '&l=' + encodeURIComponent(loc);
      return;
    }
    var s = ev.target.closest('[data-scroll-form]');
    if (s) {
      var f = document.getElementById('pedir-turno');
      ev.preventDefault();
      if (f) {
        f.scrollIntoView({ behavior: 'smooth', block: 'start' });
        var first = f.querySelector('input:not([type=hidden]):not([tabindex="-1"])');
        if (first) setTimeout(function () { first.focus({ preventScroll: true }); }, 500);
      } else {
        location.href = '/contacto/#pedir-turno';
      }
    }
  });

  // 4. Formularios: evento de inicio, anti doble envío.
  document.querySelectorAll('[data-lead-form]').forEach(function (f) {
    var started = false;
    f.addEventListener('focusin', function () {
      if (!started) { started = true; track('form_start', { form: f.querySelector('[name=type]').value, page_path: location.pathname }); }
    });
    f.addEventListener('submit', function () {
      var b = f.querySelector('[type=submit]');
      if (b) { b.disabled = true; b.textContent = 'Enviando…'; }
    });
  });

  // 5. FAQ abiertas (qué dudas tiene la gente).
  document.querySelectorAll('.faq__item').forEach(function (d) {
    d.addEventListener('toggle', function () {
      if (d.open) track('faq_open', { question: d.querySelector('summary').textContent.slice(0, 90) });
    });
  });

  // 6. Aparición suave al hacer scroll.
  if ('IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { rootMargin: '0px 0px -8% 0px' });
    document.querySelectorAll('.card, .rail li, .section-head, .prose-section').forEach(function (el) {
      el.classList.add('reveal'); io.observe(el);
    });
  }
})();
