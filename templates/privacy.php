<?php
render([
    'title' => 'Política de privacidad | Dentista.com.py',
    'description' => 'Cómo Dentista.com.py usa los datos que dejás en el formulario o por WhatsApp para coordinar tu consulta odontológica, y cómo pedir que los borremos.',
    'body' => function () { ?>
<section class="hero hero--inner"><div class="container narrow">
  <h1>Política de privacidad</h1>
  <p class="lead">Qué datos pedimos, para qué los usamos y cómo pedir que los borremos.</p>
</div></section>
<div class="container narrow prose">
  <h2>Quiénes somos</h2>
  <p>Dentista.com.py es un servicio de coordinación de consultas odontológicas en Asunción y el Gran Asunción. No somos una clínica: conectamos a pacientes con odontólogos matriculados de nuestra red.</p>
  <h2>Qué datos pedimos</h2>
  <p>Tu nombre, tu número de WhatsApp, el tratamiento que te interesa, tu zona y para cuándo lo necesitás. Si sos odontólogo, además los datos de tu consultorio y especialidades. También registramos de qué página y de qué campaña llegaste, para medir qué funciona.</p>
  <h2>Para qué los usamos</h2>
  <p>Para responderte y coordinar tu consulta. Al enviar el formulario aceptás que compartamos tus datos con un único odontólogo de la red para que te atienda. No vendemos tus datos a terceros ajenos a la coordinación de tu consulta ni te agregamos a listas de publicidad.</p>
  <h2>Cookies y medición</h2>
  <p>Usamos Google Analytics y Google Ads para medir visitas y la efectividad de los anuncios. Estas herramientas pueden usar cookies. Podés bloquearlas desde la configuración de tu navegador.</p>
  <h2>Datos de salud</h2>
  <p>No te pedimos historia clínica ni diagnósticos. Si nos contás algo sobre tu salud por WhatsApp, lo usamos solo para coordinar la consulta con el profesional.</p>
  <h2>Cómo pedir que borremos tus datos</h2>
  <p>Escribinos por WhatsApp pidiendo la baja y borramos tus datos de nuestros registros.</p>
  <p class="disclaimer">Última actualización: <?= e(date('d/m/Y', filemtime(__FILE__))) ?>.</p>
</div>
<?php }]);
