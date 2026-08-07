# Källbilder (original-PNG från Higgsfield)

Lägg de åtta originalfilerna här och döp dem exakt så här:

```
dentista-asuncion-consultorio-dental.png            (hero, 21:9)
clinica-dental-asuncion-recepcion.png               (section-break, 21:9)
brackets-ortodoncia-asuncion.png                    (card-motif, 4:3)
profilaxis-limpieza-dental-asuncion.png             (card-motif, 4:3)
blanqueamiento-dental-asuncion.png                  (card-motif, 4:3)
extraccion-muela-del-juicio-asuncion.png            (card-motif, 4:3)
odontopediatria-ninos-asuncion.png                  (card-motif, 4:3)
bioseguridad-instrumental-dental-esterilizado.png   (card-motif, 4:3)
```

Sedan:

```bash
npm i sharp
node tools/fetch-images.mjs
```

Skriptet läser `tools/images.json`. Varje värde får vara antingen en URL eller
en sökväg relativt repots rot — lokala filer används när nätverkspolicyn
blockerar Higgsfields CDN.

Originalen behöver inte committas; det är de genererade AVIF/WebP-filerna i
`assets/img/` som sajten använder.
