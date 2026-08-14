/* ==========================================================================
   Dentista.com.py — behaviour
   Vanilla JS, no dependencies. Everything degrades gracefully without it.
   ========================================================================== */
(function () {
  'use strict';

  var CFG = window.DENTISTA_CONFIG || {};
  var NUMBER = (CFG.whatsappNumber || '595000000000').replace(/\D/g, '');
  var DEFAULT_MSG = CFG.defaultMessage || 'Hola, quiero orientación para encontrar un dentista.';
  var PREFIX = CFG.guidedMessagePrefix || 'Hola, necesito orientación: ';

  document.documentElement.classList.add('js');

  /* --- State ------------------------------------------------------------- */
  var state = { need: null, zone: null, when: null };

  /* --- WhatsApp links ---------------------------------------------------- */
  function waLink() {
    var msg = DEFAULT_MSG;
    if (state.need) {
      msg = PREFIX + state.need +
        (state.zone ? ' — ' + state.zone : '') +
        (state.when ? ' — ' + state.when : '');
    }
    return 'https://wa.me/' + NUMBER + '?text=' + encodeURIComponent(msg);
  }

  function syncWaLinks() {
    var href = waLink();
    var links = document.querySelectorAll('[data-wa]');
    for (var i = 0; i < links.length; i++) links[i].setAttribute('href', href);
  }

  /* --- Mobile menu ------------------------------------------------------- */
  var menu = document.getElementById('mobileMenu');
  var burger = document.getElementById('burger');

  function setMenu(open) {
    if (!menu) return;
    menu.classList.toggle('open', open);
    menu.setAttribute('aria-hidden', open ? 'false' : 'true');
    if (burger) burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.style.overflow = open ? 'hidden' : '';
  }

  if (burger) burger.addEventListener('click', function () { setMenu(!menu.classList.contains('open')); });
  if (menu) {
    menu.addEventListener('click', function (e) {
      if (e.target.closest('a') || e.target.closest('.menu-close')) setMenu(false);
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && menu && menu.classList.contains('open')) setMenu(false);
  });
  window.addEventListener('resize', function () {
    if (window.innerWidth >= 1060 && menu && menu.classList.contains('open')) setMenu(false);
  });

  /* --- Selector: step 1, ¿qué necesitás? --------------------------------- */
  var needs = document.querySelectorAll('.need');

  function selectNeed(btn) {
    for (var i = 0; i < needs.length; i++) {
      var on = needs[i] === btn;
      needs[i].classList.toggle('on', on);
      needs[i].setAttribute('aria-pressed', on ? 'true' : 'false');
    }
    state.need = btn.getAttribute('data-need');
    state.zone = null;
    state.when = null;
    clearChips('zone');
    clearChips('when');
    render();
    // Bring step 2 into view on mobile, where it lands below the fold.
    if (window.innerWidth < 1060) {
      var z = document.getElementById('stepZone');
      if (z) z.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }

  for (var n = 0; n < needs.length; n++) {
    needs[n].addEventListener('click', (function (b) {
      return function () { selectNeed(b); };
    })(needs[n]));
  }

  /* --- Selector: steps 2 & 3, chips -------------------------------------- */
  function clearChips(group) {
    var chips = document.querySelectorAll('.chip[data-group="' + group + '"]');
    for (var i = 0; i < chips.length; i++) {
      chips[i].classList.remove('on');
      chips[i].setAttribute('aria-pressed', 'false');
    }
  }

  var chips = document.querySelectorAll('.chip');
  for (var c = 0; c < chips.length; c++) {
    chips[c].addEventListener('click', (function (chip) {
      return function () {
        var group = chip.getAttribute('data-group');
        clearChips(group);
        chip.classList.add('on');
        chip.setAttribute('aria-pressed', 'true');
        if (group === 'zone') {
          state.zone = chip.textContent.trim();
          state.when = null;
          clearChips('when');
        } else {
          state.when = chip.textContent.trim();
        }
        render();
      };
    })(chips[c]));
  }

  /* --- Selector: render -------------------------------------------------- */
  var stepZone = document.getElementById('stepZone');
  var stepWhen = document.getElementById('stepWhen');
  var ready = document.getElementById('ready');
  var summary = document.getElementById('summary');
  var bar = document.getElementById('progressBar');
  var dots = document.querySelectorAll('.progress-steps > div');

  function render() {
    var hasNeed = !!state.need, hasZone = !!state.zone, hasWhen = !!state.when;

    if (stepZone) stepZone.classList.toggle('show', hasNeed);
    if (stepWhen) stepWhen.classList.toggle('show', hasZone);
    if (ready) ready.classList.toggle('show', hasWhen);

    if (summary) {
      summary.textContent = hasNeed
        ? [state.need, state.zone, state.when].filter(Boolean).join(' · ')
        : '';
    }

    var steps = (hasNeed ? 1 : 0) + (hasZone ? 1 : 0) + (hasWhen ? 1 : 0);
    if (bar) bar.style.width = [8, 34, 66, 100][steps] + '%';

    var done = [hasNeed, hasZone, hasWhen, hasWhen];
    for (var i = 0; i < dots.length; i++) dots[i].classList.toggle('done', !!done[i]);

    syncWaLinks();
  }

  /* --- FAQ accordion ----------------------------------------------------- */
  var items = document.querySelectorAll('.faq-item');
  for (var f = 0; f < items.length; f++) {
    (function (item) {
      var q = item.querySelector('.faq-q');
      var a = item.querySelector('.faq-a');
      if (!q || !a) return;
      q.addEventListener('click', function () {
        var open = item.classList.contains('open');
        for (var j = 0; j < items.length; j++) {
          items[j].classList.remove('open');
          var other = items[j].querySelector('.faq-a');
          var btn = items[j].querySelector('.faq-q');
          if (other) other.style.maxHeight = '0px';
          if (btn) btn.setAttribute('aria-expanded', 'false');
        }
        if (!open) {
          item.classList.add('open');
          a.style.maxHeight = a.scrollHeight + 'px';
          q.setAttribute('aria-expanded', 'true');
        }
      });
    })(items[f]);
  }

  window.addEventListener('resize', function () {
    var open = document.querySelector('.faq-item.open .faq-a');
    if (open) open.style.maxHeight = open.scrollHeight + 'px';
  });

  /* --- Missing images: show a tinted block instead of a broken icon ------- */
  var imgs = document.querySelectorAll('img');
  for (var m = 0; m < imgs.length; m++) {
    (function (img) {
      img.addEventListener('error', function () { img.classList.add('is-missing'); });
      if (img.complete && img.naturalWidth === 0) img.classList.add('is-missing');
    })(imgs[m]);
  }

  /* --- Scroll reveal ----------------------------------------------------- */
  var reveals = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && reveals.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    for (var r = 0; r < reveals.length; r++) io.observe(reveals[r]);
  } else {
    for (var r2 = 0; r2 < reveals.length; r2++) reveals[r2].classList.add('in');
  }

  /* --- Sticky mobile CTA ------------------------------------------------- */
  if (CFG.stickyWhatsapp === false) {
    var sticky = document.querySelector('.sticky-wa');
    if (sticky) sticky.parentNode.removeChild(sticky);
  }

  /* --- Boot -------------------------------------------------------------- */
  render();
})();
