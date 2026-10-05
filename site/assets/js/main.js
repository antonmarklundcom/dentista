(() => {
  'use strict';
  document.documentElement.classList.add('js-enabled');
  const api = window.DentistaContact;
  const contact = window.DENTISTA_CONTACT || {};
  const page = window.DENTISTA_CONFIG || {};
  const available = api.isAvailable(contact);

  // Invalid or unconfirmed config always leads to a clear contact status.
  document.querySelectorAll('[data-contact-kind]').forEach(link => {
    const phone = link.dataset.contactKind === 'phone';
    link.href = available ? (phone ? `tel:+${contact.whatsappNumber}` : api.whatsapp(contact, link.dataset.message)) : '/contacto/#canal';
    link.textContent = available ? (phone ? contact.phoneDisplay : link.dataset.contactLabel) : (phone ? 'Ver canal de contacto' : 'Consultar disponibilidad');
    if (available && !phone) { link.target = '_blank'; link.rel = 'noopener noreferrer'; }
    else { link.removeAttribute('target'); link.removeAttribute('rel'); }
  });
  const notice = document.querySelector('[data-contact-notice]');
  if (notice && available) {
    notice.replaceChildren();
    const title = document.createElement('strong');
    title.textContent = `Canal de orientación por WhatsApp: ${contact.phoneDisplay}`;
    const explanation = document.createElement('p');
    explanation.textContent = 'La disponibilidad se consulta por mensaje. No se confirma un turno automáticamente; revisá quién te atiende y dónde antes de reservar.';
    notice.append(title, explanation);
  }

  const menu = document.querySelector('[data-menu-button]');
  const nav = document.querySelector('[data-nav]');
  function closeMenu(restoreFocus = false) {
    if (!menu || !nav) return;
    menu.setAttribute('aria-expanded', 'false');
    nav.classList.remove('is-open');
    if (restoreFocus) menu.focus();
  }
  if (menu && nav) {
    menu.addEventListener('click', () => {
      const open = menu.getAttribute('aria-expanded') === 'true';
      menu.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('is-open', !open);
    });
    nav.addEventListener('click', event => { if (event.target.closest('a')) closeMenu(); });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && menu.getAttribute('aria-expanded') === 'true') closeMenu(true);
    });
    document.addEventListener('click', event => {
      if (!event.target.closest('.site-header')) closeMenu();
    });
    document.addEventListener('focusin', event => {
      if (!event.target.closest('.site-header')) closeMenu();
    });
    matchMedia('(min-width:1024px)').addEventListener('change', () => closeMenu());
  }

  const selector = document.querySelector('[data-treatment-selector]');
  const selectorAction = document.querySelector('[data-selector-action]');
  const selectorCity = document.querySelector('[data-selector-city]');
  const selectorStatus = document.querySelector('[data-selector-status]');
  let chosenTopic = '';
  function updateSelector() {
    const city = selectorCity?.value || '';
    const text = api.message(chosenTopic, city, '/');
    selectorAction.href = available ? api.whatsapp(contact, text) : api.contactPath(chosenTopic, city);
    selectorAction.textContent = available ? 'Revisar consulta en WhatsApp ↗' : 'Continuar a contacto →';
    if (selectorStatus) selectorStatus.textContent = chosenTopic ? `Tema: ${chosenTopic}${city ? ` · ${city}` : ''}. Podés cambiar tu selección.` : 'Podés continuar sin elegir un tema.';
  }
  if (selector && selectorAction) {
    selector.addEventListener('click', event => {
      const choice = event.target.closest('button[data-intent]');
      if (!choice) return;
      chosenTopic = api.selection(choice.dataset.intent, '').topic;
      selector.querySelectorAll('button').forEach(button => {
        button.classList.toggle('is-selected', button === choice);
        button.setAttribute('aria-pressed', String(button === choice));
      });
      updateSelector();
    });
    selectorCity?.addEventListener('change', updateSelector);
    updateSelector();
  }

  const form = document.querySelector('[data-inquiry-form]');
  if (form) {
    const topic = form.elements.topic;
    const city = form.elements.city;
    const params = new URLSearchParams(location.search);
    const prefill = api.selection(params.get('tema'), params.get('ciudad'));
    topic.value = prefill.topic;
    city.value = prefill.city;
    const result = form.querySelector('[data-message-result]');
    const open = form.querySelector('[data-message-open]');
    function invalidate() { result.hidden = true; open.hidden = true; open.removeAttribute('href'); }
    form.addEventListener('change', invalidate);
    form.addEventListener('submit', event => {
      event.preventDefault();
      if (!form.reportValidity()) return;
      const picked = api.selection(topic.value, city.value);
      if (!picked.topic) { topic.value = ''; topic.reportValidity(); return; }
      const text = api.message(picked.topic, picked.city, page.path || '/contacto/');
      form.querySelector('[data-message-preview]').textContent = text;
      form.querySelector('[data-message-status]').textContent = available ? 'Mensaje preparado. Abrir WhatsApp no lo envía: revisalo y enviá solo si querés continuar.' : 'Mensaje preparado. El canal aún no está habilitado; no se envió ninguna solicitud.';
      result.hidden = false;
      if (available) {
        open.href = api.whatsapp(contact, text);
        open.target = '_blank'; open.rel = 'noopener noreferrer'; open.hidden = false;
      }
    });
  }
  // No analytics, third-party scripts or stored patient/campaign data.
})();
