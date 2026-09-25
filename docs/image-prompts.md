# Image plan — Dentista.com.py

Generated 2026-09-25 (gpt_image_2_5 sunburst, 15.25 credits); job ids and URLs in `docs/imagery-manifest.json`. The file stems and alt
texts are registered in `content/images.php`; each page shows its image automatically as soon as
`assets/img/<stem>-640.webp` exists. Optimize with webimg to AVIF + WebP at 640 and 1280 px:
`<stem>-640.avif`, `<stem>-640.webp`, `<stem>-1280.avif`, `<stem>-1280.webp`
(targets: hero ≤ 120 KB, cards ≤ 60 KB at 640 px).

## Rules for every image

- **People:** beautiful, natural, well-groomed. A Paraguayan / Argentine / Italian /
  Southern-European look, the mix you actually see in Asunción: olive to fair skin, dark to
  light-brown hair, some light eyes. Vary the ages (child, 20s, 30s, 50s, 65+). Natural teeth,
  not veneers-white.
- **Setting:** Asunción cues without clichés: pink lapacho trees, warm red brick, modern flats
  in Villa Morra, tereré on a sunny terrace, a bright clinic.
- **No brands anywhere:** no logos, no packaging, no "Invisalign"/"Colgate"/"3M" or any
  product names, no readable text, no signage, no certificates.
- **Before/after is a SIMULATION.** The site has no real patient results yet, so every pair
  shows the caption "Simulación ilustrativa — no es un paciente real". Swap in real partner
  cases, with written patient consent, as soon as they exist. Unlabelled AI before/afters would
  be fake results (misleading to patients, and risky for Google Ads' healthcare policy).
- **Dentists pictured are illustrative:** no names, no "Dr." captions, until real partners
  sign.

**Shared style block** (append to every prompt):
> Editorial lifestyle photography, soft natural daylight, warm bone-white (#F7F4ED) and deep
> green-black (#14241E) tones with one terracotta (#C2603A) accent, 50–85mm lens, shallow depth
> of field, fine film grain, true-to-life skin texture, natural teeth.
> Negative: no text, no logos, no brand names, no watermarks, no signage, no distorted teeth,
> no extra fingers, no plastic skin, no over-whitened teeth, no cold blue clinical cast, no
> collage, no borders.

## 1 — Hero and lifestyle (4)

| File | Ratio | Prompt |
|---|---|---|
| `hero-sonrisa-asuncion` | 4:5 | Woman about 30, Italian-Paraguayan features, dark wavy hair, genuine wide smile showing natural healthy teeth, sitting on a sunny terrace in Asunción with a blooming pink lapacho tree softly out of focus behind her, holding a tereré guampa, linen shirt in bone white. |
| `pareja-sonriendo-asuncion` | 3:2 | Couple in their 30s, Argentine-European look, laughing together at a café table on a tree-lined street in Villa Morra, afternoon light, relaxed and confident smiles. |
| `familia-dentista-asuncion` | 3:2 | Paraguayan family: mother, father and a 7-year-old daughter, smiling in a bright modern living room, the girl showing a gap-toothed smile, warm and candid. |
| `adulto-mayor-sonrisa` | 4:5 | Distinguished man about 65, Italian-Paraguayan look, silver hair, warm confident smile with a complete natural-looking set of teeth, in a garden with red-brick wall, soft evening light. |

## 2 — Treatment cards (12, 4:3)

| File | Treatment | Prompt |
|---|---|---|
| `brackets-ortodoncia-asuncion` | Brackets | Teen girl about 16, confident smile with classic metal braces, three-quarter view, framed nose-down to shoulders, bright bedroom window light. |
| `brackets-autoligables-asuncion` | Self-ligating / aesthetic brackets | Young woman about 19, smiling with discreet clear ceramic braces, three-quarter view, framed nose-down to shoulders, bright room. |
| `alineadores-transparentes` | Aligners | Man about 28 placing a completely unbranded clear plastic aligner on his upper teeth in front of a bathroom mirror, morning light, close crop on hands and smile. |
| `protesis-dental-asuncion` | Prosthesis | Woman about 62, fair skin, light-brown hair, laughing openly with a full natural-looking smile, seated at a dinner table with family blurred behind. |
| `limpieza-sarro-profilaxis` | Cleaning / tartar | Over-the-shoulder view of a dental hygienist's gloved hands doing an ultrasonic cleaning on a relaxed adult patient, patient's eyes out of frame, instruments clean and unbranded. |
| `blanqueamiento-dental` | Whitening | Close-up of a dentist holding an unbranded shade guide next to the smile of a woman about 35, nose-down framing, natural (not extreme) white teeth. |
| `bruxismo-placa-descarga` | Bruxism | Hands holding a clear unbranded night guard (dental splint) over a bedside table with a book and glass of water, soft lamp light. |
| `encias-sanas` | Gums | Macro of a healthy smile with pink firm gums, woman about 40, lips slightly parted, soft daylight, clinical but warm. |
| `muela-del-juicio-radiografia` | Wisdom tooth | Gloved hand pointing at a lower third molar on a panoramic x-ray on a lightbox, no names or text on the x-ray. |
| `puente-dental-asuncion` | Bridge | Studio still life of an unbranded dental model with a three-unit ceramic bridge on a bone-white surface, soft side light, no text. |
| `implantes-dentales-asuncion` | Implants | Man about 50, Spanish-Paraguayan look, short beard, relaxed smile in a bright clinic waiting area, blurred plants behind. |
| `odontopediatria-ninos` | Children | Boy about 8 sitting in a dental chair laughing while a dentist out of focus shows him a small mirror, no fear, bright colours kept subtle. |

## 3 — Before / after simulations (4 pairs, 1:1, same person + same angle)

Generate the AFTER first. Then create the BEFORE from that image (image-to-image / edit with
the after as reference), changing only the teeth. Same face, light, framing, lipstick, hair.
Frame nose-down to chin, three-quarter to frontal.

Files: `antes-<pair>` and `despues-<pair>` (e.g. `antes-blanqueamiento`, `despues-blanqueamiento`).

| Pair | Subject | BEFORE edit | AFTER |
|---|---|---|---|
| `blanqueamiento` | Woman ~32, olive skin | teeth yellowed with mild coffee staining | same teeth two shades whiter, natural enamel |
| `ortodoncia` | Man ~24, fair skin | crowded, overlapping lower incisors, one rotated canine | straight, evenly aligned teeth |
| `carillas` | Woman ~40, Italian look | small chipped front teeth with uneven edges and gaps | harmonious natural-looking front teeth |
| `protesis` | Man ~66, silver hair | two missing upper side teeth and one lower molar gap visible when smiling | complete, natural-looking smile |

Caption on the site, always: **"Simulación ilustrativa — no es un paciente real."**

## 4 — Partner page (2)

| File | Prompt |
|---|---|
| `odontologa-consultorio` | Female dentist about 38, Paraguayan-European look, hair tied back, cream scrubs, standing confidently in a modern treatment room with warm wood and green-black cabinetry, arms relaxed, soft window light. (Illustrative, no name badge.) |
| `consultorio-moderno-asuncion` | Empty modern dental treatment room in Asunción, dental chair in deep green upholstery, large window with a pink lapacho outside, warm and uncluttered, no people. |

## 5 — Education

Diagrams are coded as inline SVG, not generated: tooth anatomy, how tartar forms, correct
brushing angle, bruxism wear. They stay crisp, weigh a few KB, and Google can read the text.
