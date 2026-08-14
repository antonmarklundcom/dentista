# Imágenes del sitio — `/assets/`

El sitio funciona sin las imágenes: cada `<img>` que falte se reemplaza
automáticamente por un bloque oscuro con un leve degradado dorado, así el
layout nunca se rompe. Subí los archivos por el File Manager de Hostinger a
`public_html/assets/` con **exactamente** estos nombres.

## Archivos requeridos

| Archivo | Uso | Formato sugerido | Notas de encuadre |
|---|---|---|---|
| `hero-desktop.png` | Hero, escritorio | 2400 × 1400 px | Ocupa el 62 % derecho de la pantalla, `object-position: 60% 40%`. El lado **izquierdo** de la foto queda cubierto por el degradado negro — dejá el sujeto hacia el centro-derecha. |
| `hero-mobile.png` | Hero, móvil | 1200 × 1500 px (vertical) | `object-position: 58% 30%`. El tercio inferior queda oscurecido por el degradado y el texto. |
| `urgencia.png` | Tratamientos, figura grande | 1200 × 1500 px (4:5) | `object-position: 50% 32%`. Abajo lleva el caption "Urgencias y dolor" sobre un degradado. |
| `implante.png` | Tratamientos, figura chica | 1600 × 900 px (16:9) | Caption "Implantes" abajo a la izquierda. |
| `alineadores.png` | Tratamientos, figura chica | 1600 × 900 px (16:9) | Caption "Alineadores". |
| `instrumentos.png` | Tratamientos, figura chica | 1600 × 900 px (16:9) | Caption "Odontología de precisión". |
| `orientacion.png` | Sección "Urgencias", mitad izquierda | 1800 × 1400 px | `object-position: 32% 45%`. El borde **derecho** se funde a negro. |
| `asuncion.png` | Marca de agua del skyline en la sección de Preguntas | 1600 px de ancho | Se muestra al 9 % de opacidad y en escala de grises parcial. Un skyline con silueta clara funciona mejor. |
| `og.jpg` | Vista previa al compartir (WhatsApp, Facebook) | 1200 × 630 px | Recomendado: el hero con el logotipo. |
| `apple-touch-icon.png` | Ícono en iOS | 180 × 180 px | Fondo `#0B0C0B`, diente dorado `#B89458`. |

## Estado actual

Ya están subidas y en uso (formato WebP, livianas):

- `alineadores.webp` — alineador transparente en la mano
- `hero-desktop.webp` y `orientacion.webp` — pareja conversando en la mesa
- `hero-mobile.webp` — recorte vertical de la misma foto, sujeto centro-derecha
- `urgencia.webp` — sonrisa en primer plano
- `implante.webp` — implante dental sobre mármol
- `og.jpg` — compuesto a partir del hero con el logotipo superpuesto
- `apple-touch-icon.png` — ícono renderizado desde `favicon.svg`

Todavía faltan (el sitio se ve bien igual, con el degradado de reemplazo):

- `instrumentos.png` — instrumental odontológico de precisión
- `asuncion.png` — skyline de Asunción para la marca de agua de la sección de preguntas

## Dirección de arte

Paleta oscura y cálida, fondo casi negro (`#0B0C0B`), luz lateral suave y
acentos dorados (`#B89458`). Nada de blanco clínico ni azul de stock. Piel y
tonos cálidos reales, gente paraguaya, sin sonrisas exageradas de banco de
imágenes. Poca profundidad de campo, grano fino, aspecto editorial.

## Peso

Convertí a JPG con calidad ~78 o a WebP antes de subir. Apuntá a menos de
250 KB por imagen; el hero puede llegar a 400 KB. Si las subís en PNG pesado,
el sitio se ve igual pero carga lento en 4G.
