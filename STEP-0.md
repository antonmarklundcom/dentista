# STEP 0 — RECON · dentista.com.py

Regional vertikal (paraguay-local-site LÄGE 3). Fokus: Gran Asunción.
Spår: EDITORIAL. Samma mönster som pozo.com.py och gruas.com.py.
Ingen kod i STEP 0.

---

## a) 30 sökordskandidater — Google Keyword Planner

```
dentista
odontologo
clinica dental
dentista asuncion
odontologo asuncion
clinica dental asuncion
implantes dentales
ortodoncia
brackets
blanqueamiento dental
limpieza dental
endodoncia
protesis dental
corona dental
carillas dentales
extraccion de muela
muela del juicio
odontopediatria
urgencia dental
dentista 24 horas
implantes dentales asuncion
ortodoncia asuncion
brackets asuncion
tratamiento de conducto asuncion
dentista san lorenzo
dentista luque
dentista fernando de la mora
dentista lambare
precio implante dental paraguay
cuanto cuestan los brackets paraguay
```

## a2) Sökordskarta efter KWP-körning (låst)

Volymer från användarens KWP-export. En sida äger exakt ett primärt sökord.

**Startsidan** — `dentista` (1 000) + `dentista cerca de mi` (1 000).
H1: `Dentista en Asunción y Gran Asunción`. Notera att `dentista asuncion`
bara har 110 — huvudtermen bär volymen, geon läggs på för intentionen.

**Servicios (5), rangordnade efter faktisk volym:**

| Sida | Primärt sökord | Vol. | H2-teman ur svansen |
|---|---|---|---|
| `/servicios/brackets` | `brackets` | **4 400** | precio, tipos, cuánto duran |
| `/servicios/profilaxis-dental` | `profilaxis dental` | 1 300 | limpieza de sarro (480), limpieza dental (170), ultrasonido dental (140), destartraje (40), curetaje |
| `/servicios/blanqueamiento-dental` | `blanqueamiento dental` | 1 000 | blanqueamiento dental precio (110), blanqueador de dientes (110) |
| `/servicios/muela-del-juicio` | `muela de juicio` | 590 | muelas juicio (480), extraccion de dientes (480), **exodoncia (210)**, extraccion de muela (140), extracción de muela infectada (140), restos radiculares (40) |
| `/servicios/odontopediatria` | `odontopediatria` | 320 | odontopediatra (170), odonto pediatría (320) |

**Två fynd som ändrar bygget:**

- `brackets` (4 400) är tre gånger större än något annat i exporten. Det är
  paraguayansk vardagsterm för ortodonti — sidan ska heta *brackets*, inte
  *ortodoncia*, och den är sajtens tyngsta sida, inte implantat.
- `exodoncia` (210) får **ingen egen sida**. Den ligger som H2 på
  muela-del-juicio, annars konkurrerar två sidor om samma intention.

**Saknas i exporten:** implantes dentales, ortodoncia, endodoncia, protesis,
carillas, corona. Kör dem i KWP innan sida 16 och uppåt. Implantat är
kommersiellt tyngst men får ingen sida förrän volymen är verifierad —
gissningar bygger tunna sidor.

**Zonas (3 + interior):**

| Sida | Primärt sökord | Vol. |
|---|---|---|
| `/zonas/san-lorenzo` | `odontologo san lorenzo` | 210 |
| `/zonas/luque` | `dentista luque` | 50 |
| `/zonas/lambare` | `dentista lambare` | 30 |
| `/zonas/interior` | specialfall, ingen volym | — |

San Lorenzo-sidans H1 måste säga **odontólogo**, inte dentista: `odontologo
san lorenzo` 210 mot `dentista san lorenzo` 50. Samma ort, fyra gånger
skillnaden, enbart på ordvalet. Fernando de la Mora (10), Capiatá och Mariano
Roque Alonso får ingen egen sida — de nämns i löptext på täckningssektionen.

**Guías (2)** — informationell svans utan kannibalisering mot servicios:

| Sida | Primärt sökord | Vol. |
|---|---|---|
| `/guias/curetaje-dental` | `curetaje` | 170 |
| `/guias/dientes-postizos` | `dientes postizos` | 90 |

Reserv om någon av dem visar sig tunn: `dolor de muelas intenso` (40, växande
+400 %), `restos radiculares` (40).

**Kontrollera en sak i exporten:** att den kördes med platsfilter Paraguay och
inte global spanska. `dentista` 1 000 och `dentista cerca de mi` 1 000 är höga
för en PY-marknad; utan platsfilter är samtliga tal uppblåsta och rangordningen
mellan sidorna kan ändras.

---

## b) Blockerande frågor — besvarade och låsta

1. **Ingen odontólogo/matrícula ännu.** Ingen `Reg. Prof. N°` någonstans på
   sajten. Förtroendebandet byggs om från meritband till **löftesband** — se nedan.
2. **WhatsApp:** obesvarad → default **+595 995 628862** (stage 1). ⚠️ Antagande.
   Numret ligger på ett ställe i koden (`--wa-number`), så bytet är en rad.
3. **Inga priser.** Ingen prislista, inga intervall, ingen kalkylator med belopp.
   P10-panelen blir i stället en behandlingsväljare som bygger det förifyllda
   WhatsApp-meddelandet — samma konverteringsvärde, noll siffror.
4. **Inga foton, inga reseñas.** AI-motiv i card- och section-slotsen,
   `proof-photo` lämnas tom, reseñas-sektionen ersatt enligt §5.
5. **Ingen adress.** Footer: `Asunción, Paraguay`. Inget `streetAddress`, ingen
   karta med nål, ingen `openingHours` som inte är sann, ingen `aggregateRating`.
   Byts när partner finns.

### Förtroendebandet utan meriter (sektion 03)

Ett meritband kräver meriter. Fyra påståenden som är sanna dag ett:

```
Presupuesto sin costo  ·  Respondemos por WhatsApp  ·  Asunción y Gran Asunción  ·  Coordinamos el horario que te sirva
```

Bannlyst tills partner finns: matrícula, RUC, factura legal, "profesionales
matriculados", år i branschen, antal patienter, garantier, betyg, `Dr./Dra.`
med namn, "nuestro equipo", "nuestra clínica".

Positioneringen hålls i **koordinerande** form, aldrig ägande: *"Contanos qué
necesitás y coordinamos tu consulta odontológica en Gran Asunción."* Sajten
påstår aldrig att den är en klinik.

FAQ får en rad som möter frågan direkt, formulerad som åtagande och inte som
faktapåstående om en person som ännu inte finns:

> **¿El profesional está matriculado?**
> Sí. Trabajamos únicamente con odontólogos matriculados, y te pasamos los
> datos del profesional antes de confirmar la consulta.

⚠️ **En risk att ta ställning till, inte att bygga bort:** kommer ett lead in
innan en partner finns går det inte att leverera på. Svara hellre "te confirmo
disponibilidad hoy" och håll leadet varmt än att boka en tid som inte finns —
det är skillnaden mellan en långsam start och ett bränt rykte i en stad där
folk frågar runt.

Allt annat är härlett: yrke, spår, sektionsordning, layoutmönster,
konverteringsläge (whatsapp-first — tandvård är bokning, inte akututryckning),
copy-vinkel, täckningsspråk, bildriktning.

---

## c) Designspår — EDITORIAL (låst)

Värden utskrivna, inte hänvisade. Detta block klistras rakt in i
`tokens.css`-TRACK vid bygget.

| Token | Värde |
|---|---|
| `--font-display` | **Fraunces**, Georgia, serif — vikt 400–450, aldrig 700 |
| `--font-text` | **Inter Tight**, system-ui, sans-serif |
| `--base` | `#F7F4ED` (varm benvit) |
| `--ink` | `#14241E` (djup grön-svart) |
| `--accent` | `#C2603A` (terrakotta) — **enda** accenten, bara CTA + en highlight per skärm |
| `--surface` | `#FFFFFF` |
| `--hairline` | `color-mix(in srgb, #14241E 10%, transparent)` |
| Radier | `--r-sm 6px` · `--r-md 14px` · `--r-lg 28px` |
| Skuggor | `--shadow-1: 0 1px 2px rgb(0 0 0/.04), 0 4px 12px rgb(0 0 0/.06)` · `--shadow-2: 0 2px 4px rgb(0 0 0/.06), 0 16px 40px rgb(0 0 0/.10)` |
| Typskala | ratio 1.30, bas 17px — `--t-0 1.0625rem` · `--t-2 1.797rem` · `--t-3 2.336rem` · `--t-4 3.037rem` · `--t-5 3.948rem` · `--t-6 5.133rem` |
| Material | flat + hårstreck, generös luft. Grain **endast** på de mörka banden. |
| Rörelse | `--ease-out cubic-bezier(.16,1,.3,1)` · 280ms · stagger 60–80ms, max 6 · rörelsebudget 15% |
| WhatsApp | `#25D366` **enbart** inuti knappens glyf (`.btn--wa svg{fill:#25D366}`), aldrig sektionsfyllning |

### Anti-footprint-kontroll (§10.7)

| Axel | pozo.com.py / gruas.com.py | **dentista.com.py** | Skiljer? |
|---|---|---|---|
| Spår | INDUSTRIAL | EDITORIAL | ja |
| Bas | `#0E0E0F` mörkdominant | `#F7F4ED` ljusdominant | ja — motsatt värdeschema |
| Display | Bricolage Grotesque | Fraunces (serif) | ja |
| Text | Geist | Inter Tight | ja |
| Accent | `#E8562A` | `#C2603A` | samma varma familj — se not |
| Material | grain överallt, hårda kanter | hårstreck, luft, grain bara på mörka band | ja |
| Innehållsdjup | djup/avstånd-kalkylator tidigt | cotizador per behandling sent + P7-explainer (finns inte i grúas/pozo) | ja |
| Sektionsordning | kalkylator direkt efter tjänster | kalkylator efter process, täckning före statement | ja |

**Not om accenten:** `#C2603A` och `#E8562A` ligger i samma varma familj, men
sajterna läses inte som samma sajt — värdeschemat är inverterat (kritvit vs
nästan svart) och displayfonten är serif vs grotesk. Rekommendation: **behåll
`#C2603A`**. Först när en tredje varm vertikal tillkommer flyttas den ur
familjen; då är dentista den som byter, inte grúas.

---

## d) Sektion → layoutmönster (hela sidan)

| # | Sektion | Mönster | Bryter container | Kortvariant |
|---|---|---|---|---|
| 01 | Sticky header — `Dentista.com.py` + WhatsApp-knapp + nummer som text | — | nej | — |
| 02 | Hero — `ASUNCIÓN · GRAN ASUNCIÓN`, H1 = primärt sökord | **P1** asymmetrisk split 7/5 | nej (visual sticker ut höger) | `card--raised` ×1 (bokningspanel, gränsöverlapp) |
| 03 | Franja de promesa — Presupuesto sin costo · Respondemos por WhatsApp · Asunción y Gran Asunción · Coordinamos tu horario (**inga meriter**, se §b) | **P8** full-bleed ribbon `.grain` | **ja, full-bleed** | — |
| 04 | Servicios (5 behandlingar) | **P3** staggered-weight grid | nej | `card--ink` ×1 (implantes, span-2) + `card--hair` ×4 |
| 05 | Tratamiento destacado — implantes/ortodoncia, förklarande | **P7** sticky-side scroll | nej | `card--bare` ×3 |
| 06 | Banda full-bleed — miljöbild + en mening + CTA | **P6** bleed-image overlap | **ja, full-bleed + gränsöverlapp** | `card--raised` ×1 (korsar sektionsgränsen `translateY(40%)`) |
| 07 | Cómo es tu primera consulta — 4 steg | **P5** numbered process rail | nej | — (oversized siffror `--t-5`, accent 20%) |
| 08 | Armá tu consulta — behandlingsväljare som bygger WhatsApp-texten, **noll belopp**, CTA "Pedí tu presupuesto sin costo" | **P10** data panel | nej | `card--raised` ×1 |
| 09 | Zonas de cobertura — Gran Asunción + Interior som specialfall | **P4** editorial två-kolumn | nej | `card--hair` ×2 |
| 10 | Statement CTA — en rad, `.statement` | **P9** oversized statement | **ja, offsetbrott** | — |
| 11 | Preguntas frecuentes — 6 st + FAQPage-schema | **P4** editorial två-kolumn | nej | `card--accent` ×1 (WhatsApp-uppmaning) |
| 12 | Contacto — WhatsApp-block + formulär (3 fält) + trygghetsstack | **P1** speglad 5/7 | nej | `card--accent` ×2 |
| 13 | Footer — NAP utan gatuadress, horarios, sociala länkar, integritet | — | nej | — |
| — | WhatsApp-FAB + sticky mobilbar | — | fixed | — |

**Villkorskontroll:**

- Inga två likadana i rad — P1·P8·P3·P7·P6·P5·P10·P4·P9·P4·P1, ingen upprepning i följd (P4 i 09 och 11 är åtskilda av P9; P1 i 02 och 12 av tio sektioner). ✔
- ≥1 full-bleed — sektion 03 och 06. ✔
- ≥1 gränsöverlapp — hero-panelen (02) och den upphöjda panelen i 06. ✔
- ≥1 oversized statement — sektion 10 (`.statement`, `--t-6`), plus stegsiffrorna i 07. ✔
- ≥3 kortvarianter, ingen mer än 4× — `card--raised` ×3, `card--hair` ×6 → **justering: hair kapas till 4** (servicios ×4, zonas byter till `card--bare` ×2), `card--ink` ×1, `card--accent` ×3, `card--bare` ×5 → **bare kapas till 4** (P7 kör 3, zonas 1 + löptext). Slutliga tal: hair 4 · bare 4 · accent 3 · raised 3 · ink 1. ✔
- Sektion 8 (reseñas i §2:s standardordning) är utbytt mot cotizador enligt §5 tills riktiga citat finns — inte en tom sektion. ✔

**Sidarkitektur efter godkänd startsida (CORE 15, §10.4.1)** — låst mot KWP i §a2:
`/` · `/servicios/{brackets, profilaxis-dental, blanqueamiento-dental, muela-del-juicio, odontopediatria}` ·
`/zonas/{san-lorenzo, luque, lambare}` · `/zonas/interior` · `/armá-tu-consulta` (slug `/consulta`) ·
`/contacto` · `/preguntas-frecuentes` · `/guias/{curetaje-dental, dientes-postizos}`.
Ett primärt sökord per sida, aldrig två sidor på samma.

**WhatsApp-attribution:** ett nummer på ett ställe i koden (`--wa-number`),
förifylld text unik per sektion och sida:
`https://wa.me/595995628862?text=Hola%2C%20vengo%20de%20dentista.com.py%20(implantes)%20-%20quiero%20consultar%20por%20`
Alla CTA bär `data-ev="whatsapp_click"` + `data-ev-loc`.

**Voseo i all CTA:** *Agendá tu cita · Escribinos · Consultá sin compromiso ·
Pedí tu presupuesto.* Aldrig "Agenda"/"Escríbenos".

---

## e) Bildprompter (8 st, fristående — klistras rakt in i Higgsfield-UI)

Ingen `<<<element_id>>>` i någon prompt. Palett, ljus, objektiv och stämning
upprepas i varje, negativblocket sist. Inga bildtexter med namn, inga
ansikten som testimonials, ingen före/efter — `proof-photo`-slotsen står tom
tills riktiga foton finns.

**1 — `hero-bleed` · 21:9 · 2048px · nano_banana_2**
> Editorial documentary photograph of a Paraguayan dentist in her thirties, wearing a clean cream-toned scrub top and loupes, leaning in to examine a seated adult patient in a modern dental chair. Bright contemporary treatment room in Asunción: warm bone-white walls (#F7F4ED), deep green-black cabinetry (#14241E), a single terracotta (#C2603A) accent on a chair cushion. Soft natural daylight through a large side window, gentle falloff, no harsh clinical blue. Shot on 50mm at f/2.2, shallow depth of field, calm and reassuring mood, magazine-quality colour grading, matte finish, fine natural film grain. Composition leaves clean negative space on the left third for headline text.
> Negative: no text, no letters, no logos, no watermarks, no signage, no name badges, no certificates on walls, no distorted or extra fingers, no malformed teeth, no gore, no blood, no plastic waxy skin, no heavy HDR, no cold blue clinical cast, no stock-photo thumbs-up, no before-and-after split frame, no collage, no borders.

**2 — `section-break` · 21:9 · 1024px · soul_cinematic**
> Editorial wide interior photograph of an empty modern dental clinic reception in Asunción, Paraguay: warm bone-white walls (#F7F4ED), deep green-black joinery (#14241E), one terracotta (#C2603A) upholstered bench, a potted tropical plant, soft daylight raking across the floor from a tall window. Quiet, expensive, unhurried atmosphere. Shot on 35mm at f/4, straight-on architectural framing, film-grade grain, muted warm palette, no people in frame.
> Negative: no text, no letters, no logos, no watermarks, no signage, no brand names, no clutter, no fisheye distortion, no cold blue cast, no neon, no HDR halos, no collage, no borders.

**3 — `card-motif` (muela del juicio) · 4:3 · 1024px · nano_banana_flash**
> Editorial photograph of a dentist's gloved hand indicating a lower third molar on a panoramic dental x-ray displayed on a soft-glow lightbox, sterile forceps resting on a bone-white (#F7F4ED) cloth beside it. Deep green-black (#14241E) background falloff, one terracotta (#C2603A) detail on the instrument handle. Soft diffused natural daylight from the left, calm and clinical without coldness, no blue cast. Shot on 85mm at f/4, shallow depth of field, warm matte grading, fine natural grain, no people's faces in frame.
> Negative: no text, no letters, no numbers, no logos, no watermarks, no patient names on the x-ray, no distorted or extra fingers, no malformed teeth, no gore, no blood, no open wounds, no plastic waxy skin, no cold blue cast, no HDR, no collage, no borders.

**4 — `card-motif` (ortodoncia) · 4:3 · 1024px · nano_banana_flash**
> Editorial close-up photograph of a young adult Paraguayan patient smiling gently with ceramic orthodontic brackets, seen at a three-quarter angle, framed from the nose down so the mouth is the subject. Bone-white (#F7F4ED) background, deep green-black (#14241E) shadow falloff, a terracotta (#C2603A) collar on the clothing. Soft natural window light, warm skin tones, no clinical blue. Shot on 85mm at f/2.8, shallow depth of field, calm editorial mood, matte grading, fine natural grain.
> Negative: no text, no letters, no logos, no watermarks, no name badges, no malformed or extra teeth, no distorted lips, no plastic waxy skin, no exaggerated whitening, no cold blue cast, no HDR, no collage, no borders.

**5 — `card-motif` (profilaxis / limpieza) · 4:3 · 1024px · nano_banana_flash**
> Editorial photograph of a dental hygienist's gloved hands performing a professional cleaning on a seated adult patient, viewed over the shoulder, instruments and suction visible, patient's eyes out of frame. Bone-white (#F7F4ED) room tones, deep green-black (#14241E) equipment, a single terracotta (#C2603A) accent. Soft natural daylight, warm and calm, no harsh clinical blue. Shot on 50mm at f/2.8, shallow depth of field, matte editorial grading, fine natural grain.
> Negative: no text, no letters, no logos, no watermarks, no name badges, no distorted or extra fingers, no malformed teeth, no gore, no blood, no plastic waxy skin, no cold blue cast, no HDR, no collage, no borders.

**6 — `card-motif` (odontopediatría) · 4:3 · 1024px · nano_banana_flash**
> Editorial photograph of a relaxed Paraguayan child, about eight years old, sitting upright in a dental chair and laughing while a dentist out of focus in the background prepares a small mirror instrument. Warm bone-white (#F7F4ED) room, deep green-black (#14241E) chair, terracotta (#C2603A) cushion detail. Soft natural daylight, joyful and unposed, no clinical blue, no fear or distress. Shot on 50mm at f/2.0, shallow depth of field, warm matte grading, fine natural grain.
> Negative: no text, no letters, no logos, no watermarks, no name badges, no distorted or extra fingers, no malformed teeth, no crying, no distress, no needles visible, no gore, no blood, no plastic waxy skin, no cold blue cast, no HDR, no collage, no borders.

**7 — `card-motif` (bioseguridad) · 4:3 · 1024px · seedream_v5_pro**
> Editorial still-life photograph of a sterile dental instrument tray: mirror, probe and forceps arranged on a fresh bone-white (#F7F4ED) cloth, sealed sterilisation pouch beside it, deep green-black (#14241E) worktop, one terracotta (#C2603A) element in the frame. Soft diffused natural daylight from the upper left, precise gentle shadows, no clinical blue. Shot on 85mm at f/5.6, top-down three-quarter angle, tactile realism, warm matte grading, fine natural grain, no people in frame.
> Negative: no text, no letters, no logos, no watermarks, no brand names, no packaging labels, no certificates, no rust, no clutter, no cold blue cast, no HDR, no collage, no borders.

**8 — `card-motif` (blanqueamiento) · 4:3 · 1024px · nano_banana_flash**
> Editorial close-up photograph of a dentist holding a dental shade guide beside the smile of a relaxed adult Paraguayan patient, framed from the nose down so the mouth and the guide are the subject. Bone-white (#F7F4ED) surroundings, deep green-black (#14241E) shadow falloff, a terracotta (#C2603A) accent on the clothing. Soft natural window light, warm natural skin and tooth tones, no clinical blue, no artificial glare. Shot on 85mm at f/2.8, shallow depth of field, calm editorial mood, matte grading, fine natural grain.
> Negative: no text, no letters, no numbers, no logos, no watermarks, no name badges, no unnaturally white or glowing teeth, no malformed or extra teeth, no distorted lips, no distorted or extra fingers, no plastic waxy skin, no before-and-after split frame, no cold blue cast, no HDR, no collage, no borders.

**Kostnadsregel:** kör `get_cost: true` per modell och upplösning innan första
batchen och redovisa credit-mattan. `use_unlim` finns inte på kontot. Setet
genereras **en gång för vertikalen dental** och återanvänds på varje
dentist-sajt via crop, scrim och accentöverlägg — inte per sajt.

---

## f) Bildmanifest

```json
{
  "vertical": "dental",
  "track": "EDITORIAL",
  "element_id": null,
  "images": [
    {
      "slot": "hero-bleed",
      "file": "dentista-asuncion-consultorio-dental.avif",
      "alt": "Odontóloga atendiendo a un paciente en un consultorio dental en Asunción",
      "ratio": "21:9", "px": 2048, "model": "nano_banana_2", "prompt": "#1"
    },
    {
      "slot": "section-break",
      "file": "clinica-dental-asuncion-recepcion.avif",
      "alt": "Recepción de una clínica dental moderna en Asunción",
      "ratio": "21:9", "px": 1024, "model": "soul_cinematic", "prompt": "#2"
    },
    {
      "slot": "card-motif",
      "file": "extraccion-muela-del-juicio-asuncion.avif",
      "alt": "Radiografía panorámica que muestra una muela del juicio antes de la extracción",
      "ratio": "4:3", "px": 1024, "model": "nano_banana_flash", "prompt": "#3"
    },
    {
      "slot": "card-motif",
      "file": "brackets-ortodoncia-asuncion.avif",
      "alt": "Paciente joven con brackets sonriendo en la consulta",
      "ratio": "4:3", "px": 1024, "model": "nano_banana_flash", "prompt": "#4"
    },
    {
      "slot": "card-motif",
      "file": "profilaxis-limpieza-dental-asuncion.avif",
      "alt": "Profilaxis y limpieza dental profesional realizada en el consultorio",
      "ratio": "4:3", "px": 1024, "model": "nano_banana_flash", "prompt": "#5"
    },
    {
      "slot": "card-motif",
      "file": "odontopediatria-ninos-asuncion.avif",
      "alt": "Niño tranquilo en el sillón dental durante una consulta de odontopediatría",
      "ratio": "4:3", "px": 1024, "model": "nano_banana_flash", "prompt": "#6"
    },
    {
      "slot": "card-motif",
      "file": "bioseguridad-instrumental-dental-esterilizado.avif",
      "alt": "Instrumental dental esterilizado listo para la atención",
      "ratio": "4:3", "px": 1024, "model": "seedream_v5_pro", "prompt": "#7"
    },
    {
      "slot": "card-motif",
      "file": "blanqueamiento-dental-asuncion.avif",
      "alt": "Guía de color dental junto a la sonrisa de una paciente durante el blanqueamiento",
      "ratio": "4:3", "px": 1024, "model": "nano_banana_flash", "prompt": "#8"
    },
    {
      "slot": "proof-photo",
      "file": null,
      "alt": null,
      "status": "PENDIENTE — solo fotografía real del trabajo terminado. No generar."
    }
  ]
}
```

Filerna levereras i AVIF + WebP i 640/1280/1920 via `fetch-images.mjs` och
committas i repot. Hero ≤120 KB, sidvikt ≤500 KB, allt under fold `loading="lazy"`
med explicita dimensioner.

---

## Nästa steg

1. Svara på de fem frågorna i (b).
2. Kör keyword-listan i KWP och skicka exporten — den låser H1, tjänsterubriker
   och de två guide-ämnena.
3. Därefter LÄGE 0: `BUILD-SPEC.md` med färdig copy ordagrant, sedan bygget.
