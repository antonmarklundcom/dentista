/* Pure contact rules shared by the browser and the local test suite. */
(function (root, factory) {
  const api = factory();
  if (typeof module === 'object' && module.exports) module.exports = api;
  else root.DentistaContact = api;
})(typeof window === 'undefined' ? this : window, function () {
  'use strict';
  const topics = ['urgencia dental', 'implantes o prótesis', 'ortodoncia o alineadores', 'estética dental', 'limpieza o consulta', 'una evaluación odontológica'];
  const cities = ['Asunción', 'Luque', 'San Lorenzo', 'Fernando de la Mora', 'Lambaré', 'Capiatá', 'Mariano Roque Alonso', 'Limpio', 'Otra ciudad'];
  function isAvailable(config) {
    const number = config?.whatsappNumber || '';
    return config?.enabled === true && config?.ownerConfirmed === true &&
      /^5959\d{8}$/.test(number) && !/^(\d)\1{6,}$/.test(number.slice(3));
  }
  function selection(topic, city) {
    return { topic: topics.includes(topic) ? topic : '', city: cities.includes(city) ? city : '' };
  }
  function message(topic, city, page = '/') {
    const picked = selection(topic, city);
    const subject = picked.topic || 'una evaluación odontológica';
    const safePage = /^\/[a-z0-9/-]*\/$/.test(page) || page === '/' ? page : '/contacto/';
    return `Hola, encontré Dentista.com.py y quisiera consultar sobre ${subject}.` +
      (picked.city ? ` Mi ciudad: ${picked.city}.` : '') +
      ` ¿Podrían confirmar disponibilidad, profesional y ubicación? Página: ${safePage}`;
  }
  function whatsapp(config, text) {
    return isAvailable(config) ? `https://wa.me/${config.whatsappNumber}?text=${encodeURIComponent(text)}` : '';
  }
  function contactPath(topic, city) {
    const picked = selection(topic, city);
    const params = new URLSearchParams();
    if (picked.topic) params.set('tema', picked.topic);
    if (picked.city) params.set('ciudad', picked.city);
    return '/contacto/' + (params.size ? `?${params}` : '') + '#canal';
  }
  return Object.freeze({ topics, cities, isAvailable, selection, message, whatsapp, contactPath });
});
