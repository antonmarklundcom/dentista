# Dentista.com.py

Sitio estático (HTML + CSS + JS, sin build) para subir tal cual a Hostinger.
Reconstrucción 1:1 del diseño de referencia `Dentista_Homepage.dc.html`, con la
lógica del componente (mobile/desktop, selector de 3 pasos, FAQ, menú) portada
a JavaScript sin dependencias.

## Estructura

```
index.html          Home completa
404.html            Página de error
css/styles.css      Todo el diseño — tokens, layout, animaciones
js/config.js        ÚNICO archivo a editar para salir a producción
js/main.js          Selector, FAQ, menú móvil, reveals
assets/             Imágenes + favicon (ver assets/IMAGENES.md)
.htaccess           HTTPS, www→no-www, gzip, caché, headers
robots.txt          + sitemap.xml
```

## Antes de publicar

1. **Número de WhatsApp** — abrí `js/config.js` y cambiá `whatsappNumber`
   por el número real en formato internacional sin `+` ni espacios.
   Ejemplo: `0981 123 456` → `"595981123456"`.
   Todos los botones del sitio se actualizan solos.
2. **Imágenes** — subí los archivos a `assets/` según `assets/IMAGENES.md`.
   El sitio funciona igual si todavía faltan.
3. **Dominio** — si el dominio final no es `dentista.com.py`, actualizá la
   etiqueta `<link rel="canonical">` y las `og:` en `index.html`, más
   `robots.txt` y `sitemap.xml`.

## Deploy en Hostinger

Subí el contenido de este repositorio dentro de `public_html/` (que
`index.html` quede en la raíz de `public_html`, no en una subcarpeta).
No hace falta Node, ni PHP, ni base de datos.

## Cómo funciona el selector

`js/main.js` mantiene tres valores: necesidad, ciudad y urgencia. Cada
selección revela el paso siguiente, avanza la barra de progreso (8 → 34 → 66
→ 100 %) y reescribe el `href` de **todos** los enlaces con atributo
`data-wa`, de modo que el mensaje precargado de WhatsApp llega armado:

```
Hola, necesito orientación: Implantes dentales — Luque — Esta semana
```

Sin JavaScript, el sitio sigue siendo legible y los botones de WhatsApp se
pueden hacer funcionar poniendo el `href` a mano en el HTML.

## Responsive

El componente original alternaba markup con una bandera `mobile` en JS. Acá el
corte es CSS puro en **1060 px**: por debajo se muestra el hero vertical, la
grilla de necesidades pasa a 2 columnas, las secciones divididas se apilan y
aparece la barra fija de WhatsApp al pie.
