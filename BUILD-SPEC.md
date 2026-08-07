# BUILD-SPEC — dentista.com.py (startsida)

**Läge:** paraguay-local-site LÄGE 0 (spec, ingen kod) → underlag för LÄGE 1-bygget.
**Vertikal:** regional vertikal, LÄGE 3 (§10). Domänen är varumärket. Ingen gatuadress.
**Facit:** `STEP-0.md` i detta repo. Där STEP-0 har beslutat något är det kopierat
hit ordagrant. Inget nytt beslut har fattats utanför punkt 4 (copy), som STEP-0
avsiktligt lämnade till läge 0.

**Exekveringsprompt efter godkännande:**
> *"Implementera BUILD-SPEC.md exakt. Avvik inte. Fråga vid oklarhet istället för att gissa."*

**Två avstämningar mot STEP-0 som exekveringen INTE får tolka om** (§d skrevs före
KWP-exporten låstes i §a2; §a2 vinner, och det står uttryckligen i §a2 att sajtens
tyngsta sida är *brackets*, inte implantat):

| §d säger | Låst värde i detta bygge | Grund |
|---|---|---|
| 04: `card--ink` ×1 = **implantes**, span-2 | `card--ink` ×1 = **brackets**, span-2 | §a2: `brackets` 4 400, implantat saknas i exporten och får ingen sida |
| 05: Tratamiento destacado = **implantes/ortodoncia** | Tratamiento destacado = **brackets** | §a2: sidan heter *brackets*, inte *ortodoncia* |

Inget annat i §d ändras: mönsterföljden, containerbrotten, gränsöverlappen och
kortvariantstalen står kvar exakt som räknade.

---

## 1. Intake-block (§0) — ifyllt

```
NEGOCIO:        Dentista.com.py            (domänen ÄR varumärket, §10.1 — inget påhittat firmanamn)
OFICIO:         dentista / odontólogo
CIUDAD:         Asunción + Departamento Central
BARRIOS:        — utelämnas medvetet (§10.2: ingen gatuadress, inga barriopåståenden)
ZONAS:          Asunción · Luque · San Lorenzo · Fernando de la Mora · Lambaré ·
                Capiatá · Mariano Roque Alonso + "Interior" som specialfall
WHATSAPP:       +595 995 628862                      ⚠️ ANTAGANDE (STEP-0 §b2, stage-1-numret)
TELÉFONO FIJO:  inget                                ⚠️ ANTAGANDE (finns inget uppgivet)
SERVICIOS:      brackets · profilaxis dental · blanqueamiento dental ·
                muela del juicio (exodoncia) · odontopediatría          (låst i §a2)
DIFERENCIAL:    svar på WhatsApp + presupuesto sin costo + samordnad tid i Gran Asunción
                — ett LÖFTE, aldrig en merit (§b)
CONFIANZA:      INGEN merit publiceras. Ingen matrícula, ingen Reg. Prof. N°, ingen RUC,
                ingen factura legal, inga år, inga antal, inga garantier, inga betyg.
                Sektion 03 är ett LÖFTESBAND, inte ett meritband (STEP-0 §b).
RESEÑAS:        INGA — sektionen ersatt av cotizador/behandlingsväljare (§5-ersättning)
FOTOS:          INGA riktiga. AI-motiv i `hero-bleed`, `section-break`, `card-motif`.
                `proof-photo` lämnas TOM och listas som pendiente.
CONVERSIÓN:     whatsapp-first (tandvård = bokning, inte akututryckning — härlett, §b)
DISEÑO:         EDITORIAL (STEP-0 §c, låst; anti-footprint mot pozo/grúas verifierad)
PRECIOS:        INGA. Ingen prislista, inga intervall, ingen kalkylator med belopp (§b3).
PAGOS:          visas INTE                            ⚠️ ANTAGANDE (obekräftat, och sajten
                                                        är ingen klinik som tar betalt)
```

**Ytterligare antaganden markerade ⚠️ som inte ryms i blocket:**

- ⚠️ Demo/pre-launch: sidan byggs med `<meta name="robots" content="noindex,nofollow">` tills partner finns.
- ⚠️ VenderCRM-URL och site-API-nyckel saknas — se §8.
- ⚠️ Sociala profiler (Facebook/Instagram) finns inte ännu → `sameAs` utelämnas helt, inga sociala ikoner i footern.
- ⚠️ Öppettider: ingen `openingHours` i schemat och ingen "Horarios"-rad med klockslag i footern. Footern skriver i stället svarslöftet, som är sant.

---

## 2. Designspår + tokens (STEP-0 §c, ordagrant)

Spår: **EDITORIAL**. Blocket nedan klistras rakt in i TRACK-delen av
`web-design-system/references/tokens.css`. Resten av `tokens.css` behålls oförändrad
och shippas inline i `<style>`.

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

Kompletterande värden som exekveringen tar oförändrade ur `tokens.css`:
`--ink-70` · `--ink-55` · `--hairline-strong` · `--t--1 0.812rem` · `--t-1 1.383rem` ·
`--measure 65ch` · spacing-skalan `--s-1…--s-40` · `--ease-io cubic-bezier(.4,0,.2,1)` ·
`--dur-fast 180ms` / `--dur 280ms` / `--dur-slow 400ms` · `--container 1200px` ·
`--gutter clamp(1rem,5vw,3rem)` · `.grain` · `.scrim` · kortvarianterna
`.card--bare` `.card--hair` `.card--raised` `.card--ink` `.card--accent` · `.btn`-familjen.

Typsnittsladdning: **exakt två** familjer, Fraunces (400–450) + Inter Tight (400/500),
`font-display: swap`, latin-subset, preload på båda. Inget tredje typsnitt.

WhatsApp-numret ligger på **ett** ställe: konstanten `--wa-number` / `WA = '595995628862'`
högst upp. Byte av nummer ska vara en rad.

---

## 3. Sektionsordning + layoutmönster (STEP-0 §d, ordagrant)

| # | Sektion | Mönster | Bryter container | Kortvariant |
|---|---|---|---|---|
| 01 | Sticky header — `Dentista.com.py` + WhatsApp-knapp + nummer som text | — | nej | — |
| 02 | Hero — `ASUNCIÓN · GRAN ASUNCIÓN`, H1 = primärt sökord | **P1** asymmetrisk split 7/5 | nej (visual sticker ut höger) | `card--raised` ×1 (bokningspanel, gränsöverlapp) |
| 03 | Franja de promesa — Presupuesto sin costo · Respondemos por WhatsApp · Asunción y Gran Asunción · Coordinamos tu horario (**inga meriter**, se §b) | **P8** full-bleed ribbon `.grain` | **ja, full-bleed** | — |
| 04 | Servicios (5 behandlingar) | **P3** staggered-weight grid | nej | `card--ink` ×1 (**brackets**, span-2) + `card--hair` ×4 |
| 05 | Tratamiento destacado — **brackets**, förklarande | **P7** sticky-side scroll | nej | `card--bare` ×3 |
| 06 | Banda full-bleed — miljöbild + en mening + CTA | **P6** bleed-image overlap | **ja, full-bleed + gränsöverlapp** | `card--raised` ×1 (korsar sektionsgränsen `translateY(40%)`) |
| 07 | Cómo es tu primera consulta — 4 steg | **P5** numbered process rail | nej | — (oversized siffror `--t-5`, accent 20%) |
| 08 | Armá tu consulta — behandlingsväljare som bygger WhatsApp-texten, **noll belopp**, CTA "Pedí tu presupuesto sin costo" | **P10** data panel | nej | `card--raised` ×1 |
| 09 | Zonas de cobertura — Gran Asunción + Interior som specialfall | **P4** editorial två-kolumn | nej | `card--bare` ×1 + löptext |
| 10 | Statement CTA — en rad, `.statement` | **P9** oversized statement | **ja, offsetbrott** | — |
| 11 | Preguntas frecuentes — 6 st + FAQPage-schema | **P4** editorial två-kolumn | nej | `card--accent` ×1 (WhatsApp-uppmaning) |
| 12 | Contacto — WhatsApp-block + formulär (3 fält) + trygghetsstack | **P1** speglad 5/7 | nej | `card--accent` ×2 |
| 13 | Footer — NAP utan gatuadress, svarslöfte, integritet | — | nej | — |
| — | WhatsApp-FAB + sticky mobilbar | — | fixed | — |

**Containerbrott:** sektion 03 (full-bleed ribbon), 06 (full-bleed bild) och 10
(offsetbrott, `.statement` skjuts ut ur containern med `margin-left: clamp(0px,8vw,160px)`).

**Gränsöverlapp (exakt två):**
1. Hero-panelen i 02 — `card--raised`, `transform: translateY(28%)` ned över gränsen mot 03 på ≥1024px.
2. Panelen i 06 — `card--raised`, `transform: translateY(40%)` ned över gränsen mot 07 på ≥1024px.
Under 1024px nollställs båda överlappen (`transform:none`), panelerna flödar normalt.

**Villkorskontroll (STEP-0 §d, redovisad):** mönsterföljd P1·P8·P3·P7·P6·P5·P10·P4·P9·P4·P1 —
ingen upprepning i följd ✔ · ≥1 full-bleed (03, 06) ✔ · ≥1 gränsöverlapp (02, 06) ✔ ·
≥1 oversized statement (10, plus stegsiffrorna i 07) ✔ ·
kortvarianter: **hair 4 · bare 4 · accent 3 · raised 3 · ink 1** — 5 varianter, ingen över 4× ✔ ·
reseñas-sektionen ersatt av cotizador, inte tom ✔

**Kortvarianternas exakta placering (så talen ovan går ihop vid exekvering):**

| Variant | Antal | Var |
|---|---|---|
| `card--hair` | 4 | 04: de fyra icke-brackets-tjänsterna |
| `card--bare` | 4 | 05: ×3 · 09: ×1 (Interior-noten) |
| `card--accent` | 3 | 11: ×1 (WhatsApp-uppmaning) · 12: ×2 (WhatsApp-block + trygghetsstack) |
| `card--raised` | 3 | 02: ×1 (bokningspanel) · 06: ×1 (överlappspanel) · 08: ×1 (cotizador) |
| `card--ink` | 1 | 04: brackets, span-2 |

**Responsivt kontrakt:** brytpunkter **endast** 640 / 1024 / 1280. Alla splits (P1, P7)
kollapsar till en kolumn <1024px — visualen ÖVER texten i heron, UNDER texten i övriga.
P3-gridet: 4 kolumner ≥1024px (brackets span-2) → 2 kolumner <1024px → 1 <640px.
P5-steppern blir vertikal rail med 1px förbindelselinje <768px. All typografi i `clamp()`.
Container `width: min(1200px, 100% - 48px)`, mobil `100% - 32px`. Full-bleed:
`width:100vw; margin-left:calc(50% - 50vw)` + `overflow-x:hidden` på `body`.
FAB `right:16px; bottom:16px`, under 768px `bottom:88px` och `body{padding-bottom:88px}`.
Träffytor ≥48×48px, FAB ≥56px. Noll horisontell scroll på 360/390/768/1024/1440.

---

## 4. FÄRDIG COPY — ordagrant, ingen omskrivning

Paraguayansk spanska, **voseo i all CTA**. `<html lang="es-PY">`.
Noll engelska i UI. Ingen merit, ingen siffra, inget belopp, inget datum, ingen person.
Positioneringen är **koordinerande** — sajten säger aldrig "nuestra clínica", "nuestro
equipo", "atendemos" om sig själv som vårdgivare.

### 01 — Header

```
Marca (textlogotyp):   Dentista.com.py
Nav:                   Servicios · Tu primera consulta · Zonas · Preguntas
Teléfono som text:     +595 995 628862
CTA (btn--wa):         Escribinos
```

### 02 — Hero (P1, 7/5)

```
Eyebrow:   ASUNCIÓN · GRAN ASUNCIÓN

H1:        Dentista en Asunción y Gran Asunción

Ingress:   Contanos qué necesitás y coordinamos tu consulta odontológica con
           odontólogos matriculados en Asunción y el Gran Asunción. Te
           respondemos por WhatsApp y el presupuesto no tiene costo.

CTA 1 (btn--wa, primär):     Escribinos por WhatsApp
CTA 2 (btn--ghost, tel:):    Llamanos: +595 995 628862

Micro-rad under knapparna:
           Sin compromiso. Contanos tu caso y te decimos cómo seguir.
```

**Bokningspanel — `card--raised`, gränsöverlapp:**

```
Etikett (eyebrow):  CÓMO EMPEZAMOS

Rubrik:             Coordiná tu consulta en tres pasos

1.  Nos escribís qué te pasa o qué tratamiento buscás.
2.  Te confirmamos disponibilidad y los datos del profesional.
3.  Vas a tu consulta en el horario que te sirva.

Fotrad i panelen:   Presupuesto sin costo · Respondemos por WhatsApp
```

### 03 — Franja de promesa (P8, full-bleed, `.grain`)

Fyra påståenden, ordagrant ur STEP-0 §b, i denna ordning, separerade med `·`:

```
Presupuesto sin costo  ·  Respondemos por WhatsApp  ·  Asunción y Gran Asunción  ·  Coordinamos el horario que te sirva
```

Inget mer i bandet. Ingen ikonrad, ingen siffra, ingen merit.

### 04 — Servicios (P3)

```
Eyebrow:   TRATAMIENTOS

H2:        Los tratamientos que más nos consultan

Ingress:   Contanos cuál te interesa y coordinamos la consulta. Si todavía no
           sabés qué necesitás, escribinos igual: primero se revisa y recién
           después se decide el tratamiento.
```

**Kort 1 — `card--ink`, span-2 (primärt):**

```
Rubrik:   Brackets y ortodoncia
Text:     Es la consulta más frecuente y también la que más dudas trae: qué tipo
          de brackets te conviene, cuánto tiempo vas a usarlos y cómo se controla
          el tratamiento mes a mes. Se define después de revisarte la boca, nunca
          antes.
CTA:      Consultá por brackets   →  (wa-länk, text "(brackets)")
Bild:     brackets-ortodoncia-asuncion
```

**Kort 2 — `card--hair`:**

```
Rubrik:   Profilaxis y limpieza dental
Text:     Limpieza profesional con ultrasonido para sacar el sarro que el cepillo
          ya no saca. Es la consulta más simple de coordinar y la que más problemas
          evita más adelante.
CTA:      Consultá por profilaxis
Bild:     profilaxis-limpieza-dental-asuncion
```

**Kort 3 — `card--hair`:**

```
Rubrik:   Blanqueamiento dental
Text:     Blanqueamiento hecho en el consultorio, con guía de color antes y
          después para que veas el cambio real. Primero se revisa que las encías
          y los dientes estén en condiciones.
CTA:      Consultá por blanqueamiento
Bild:     blanqueamiento-dental-asuncion
```

**Kort 4 — `card--hair`:**

```
Rubrik:   Muela del juicio y exodoncias
Text:     Extracción de muelas del juicio, dientes muy dañados y restos
          radiculares. Se pide una radiografía panorámica antes para saber
          exactamente cómo está ubicada la muela.
CTA:      Consultá por muela del juicio
Bild:     extraccion-muela-del-juicio-asuncion
```

**Kort 5 — `card--hair`:**

```
Rubrik:   Odontopediatría
Text:     Consultas para chicos, con tiempo y sin apuro. La primera visita es para
          que el chico conozca el consultorio y se anime, no para hacerle todo el
          primer día.
CTA:      Consultá por odontopediatría
Bild:     odontopediatria-ninos-asuncion
```

### 05 — Tratamiento destacado: brackets (P7, sticky-side)

**Sticky vänsterkolumn:**

```
Eyebrow:   EL TRATAMIENTO MÁS CONSULTADO

H2:        Brackets, explicados sin vueltas

Text:      Casi todo lo que se pregunta sobre brackets se responde en la primera
           consulta. Esto es lo que conviene saber antes de escribirnos.

CTA:       Consultá por brackets
```

**Höger, `card--bare` ×3:**

```
1)  Rubrik:  Primero se revisa, después se planifica
    Text:    No se puede decir qué brackets necesitás ni cuánto va a durar el
             tratamiento sin verte la boca. En la primera consulta se revisa, se
             piden las radiografías necesarias y recién ahí se arma el plan.

2)  Rubrik:  Hay más de un tipo, y no todos sirven para todos
    Text:    Metálicos, estéticos, autoligado. La diferencia no es solo el
             precio: cambia el tipo de movimiento que se puede hacer y los
             controles que vas a necesitar. Se elige según tu caso.

3)  Rubrik:  El control mensual es parte del tratamiento
    Text:    Los brackets funcionan si volvés a los controles. Cuando coordinamos
             tu consulta, coordinamos también que los controles te queden cerca y
             en un horario que puedas cumplir.
```

### 06 — Banda full-bleed (P6)

Bild: `clinica-dental-asuncion-recepcion` (`section-break`, scrim + `.grain`).

```
Mening över bilden (en rad):
           Una consulta tranquila, con todo explicado antes de empezar.

CTA (btn--wa):   Agendá tu cita
```

**Överlappspanel — `card--raised`, `translateY(40%)`:**

```
Etikett:   SIN APUROS

Rubrik:    Nadie firma nada antes de entender qué le van a hacer

Text:      Te explicamos qué se encontró, qué opciones hay y qué pasa si decidís
           esperar. Después decidís vos.
```

### 07 — Cómo es tu primera consulta (P5, 4 steg)

```
Eyebrow:   TU PRIMERA CONSULTA

H2:        Cómo es tu primera consulta

01  Escribinos
    Contanos qué te pasa, qué tratamiento te interesa y en qué zona estás.
    Te respondemos por WhatsApp.

02  Coordinamos día y horario
    Buscamos el turno que te sirva y te pasamos los datos del odontólogo
    matriculado que te va a atender antes de confirmar.

03  Revisión y diagnóstico
    En la consulta se revisa tu boca, se piden las radiografías que hagan falta
    y se te explica qué encontraron.

04  Presupuesto sin costo y decidís vos
    Te pasan el plan de tratamiento y el presupuesto. No se empieza nada hasta
    que vos digas que sí.
```

### 08 — Armá tu consulta (P10, `card--raised`, NOLL belopp)

```
Eyebrow:   ARMÁ TU CONSULTA

H2:        Armá tu consulta en treinta segundos

Ingress:   Elegí el tratamiento y tu zona. Con eso preparamos tu mensaje de
           WhatsApp y te respondemos con la disponibilidad.

Fält 1 — etikett:  ¿Qué necesitás?
Alternativ (i denna ordning):
           Brackets y ortodoncia
           Profilaxis y limpieza dental
           Blanqueamiento dental
           Muela del juicio o extracción
           Odontopediatría (niños)
           Todavía no sé / quiero una revisión

Fält 2 — etikett:  ¿En qué zona estás?
Alternativ:
           Asunción
           Luque
           San Lorenzo
           Fernando de la Mora
           Lambaré
           Capiatá
           Mariano Roque Alonso
           Otra ciudad del interior

Fält 3 — etikett:  ¿Cuándo te queda mejor?
Alternativ:
           Mañana
           Tarde
           Me da igual

Förhandsvisad rad (uppdateras live, ren text, ingen siffra):
           Tu mensaje: "Hola, vengo de dentista.com.py — necesito {tratamiento}
           en {zona}, prefiero {horario}."

CTA (btn--wa, primär):   Pedí tu presupuesto sin costo

Notrad under knappen:
           No pedimos datos de tarjeta ni pagos por acá. El presupuesto se te
           pasa en la consulta y no tiene costo.
```

Regel för exekveringen: väljaren visar **inga** priser, inga intervall, inga
"desde"-belopp. Den bygger enbart WhatsApp-textens `?text=`-parameter.
Ändring av val → `data-ev="calc_open"` vid första interaktionen,
`data-ev="calc_complete"` på knappklick.

### 09 — Zonas de cobertura (P4)

```
Eyebrow:   ZONAS

H2:        Dónde coordinamos consultas

Brödtext:  Trabajamos en todo Asunción y el Gran Asunción — Luque, San Lorenzo,
           Fernando de la Mora, Lambaré, Capiatá y Mariano Roque Alonso. Contanos
           en qué zona estás y coordinamos la consulta lo más cerca posible de
           donde vivís o trabajás.

Andra stycket:
           Si trabajás en el centro de Asunción y vivís en otra ciudad, decilo
           cuando escribas: muchas veces conviene coordinar el turno cerca del
           trabajo y no cerca de casa.
```

**`card--bare` ×1 — Interior:**

```
Rubrik:    ¿Estás fuera del Gran Asunción?
Text:      Fuera del Gran Asunción coordinamos según el caso: escribinos y te
           confirmamos si podemos llegar.
CTA:       Consultá por tu ciudad
```

Ingen grå chip-rad med ortnamn (§10.4). Orterna står i löptext, punkt.

### 10 — Statement CTA (P9, offsetbrott)

```
Statement (en rad, --t-6):
           Tu consulta, coordinada hoy.

Underrad:  Escribinos por WhatsApp y te confirmamos disponibilidad.

CTA (btn--wa):   Escribinos ahora
```

Inget mer i sektionen. Ingen bild, inga kort.

### 11 — Preguntas frecuentes (P4) — 6 frågor MED svar

```
Eyebrow:   PREGUNTAS FRECUENTES

H2:        Lo que más nos preguntan
```

**1. ¿El profesional está matriculado?**
Sí. Trabajamos únicamente con odontólogos matriculados, y te pasamos los datos del
profesional antes de confirmar la consulta.

**2. ¿Cuánto sale el tratamiento?**
Depende de lo que encuentren cuando te revisen, así que no publicamos precios: un
número puesto antes de verte la boca no te sirve para decidir. El presupuesto se te
pasa en la consulta, por escrito y sin costo, y recién ahí decidís si seguís.

**3. ¿Cómo hago para sacar turno?**
Escribinos por WhatsApp contando qué necesitás y en qué zona estás. Te respondemos
con la disponibilidad, coordinamos día y horario y te confirmamos los datos del
profesional. No hace falta llamar ni ir hasta el consultorio para sacar el turno.

**4. Tengo dolor de muela ahora. ¿Qué hago?**
Escribinos igual y contanos hace cuánto te duele, si tenés la cara hinchada y si
estás tomando algo. Buscamos el turno más cercano que haya y te confirmamos el mismo
día si conseguimos lugar. No prometemos atención inmediata las veinticuatro horas:
te decimos con claridad cuándo hay lugar.

**5. ¿Atienden a chicos?**
Sí, coordinamos consultas de odontopediatría. La primera visita suele ser corta y
sirve para que el chico conozca el consultorio y se anime. Si viene con miedo,
avisanos antes y lo tenemos en cuenta al coordinar el turno.

**6. ¿En qué zonas trabajan?**
En Asunción y todo el Gran Asunción: Luque, San Lorenzo, Fernando de la Mora,
Lambaré, Capiatá y Mariano Roque Alonso. Fuera del Gran Asunción coordinamos según
el caso: escribinos y te confirmamos si podemos llegar.

**`card--accent` ×1, sist i sektionen:**

```
Rubrik:   ¿Tu pregunta no está acá?
Text:     Escribinos y te la respondemos por WhatsApp. No hace falta que sepas el
          nombre del tratamiento.
CTA:      Hacé tu pregunta
```

Varje `<details>` bär `data-ev="faq_open"` + `data-ev-loc="faq"`.

### 12 — Contacto (P1 speglad, 5/7)

```
Eyebrow:   CONTACTO

H2:        Contanos qué necesitás

Ingress:   La forma más rápida es WhatsApp. Si preferís que te escribamos nosotros,
           dejanos tus datos y te contactamos.
```

**`card--accent` ×1 — WhatsApp-block:**

```
Rubrik:   WhatsApp
Text:     Es por donde respondemos más rápido.
CTA (btn--wa):   Escribinos por WhatsApp
Nummer som text: +595 995 628862
Telefonlänk:     Llamanos: +595 995 628862
```

**Formulär (3 fält + honeypot):**

```
Etikett 1:   Nombre           (name="nombre", required)
Etikett 2:   WhatsApp         (name="telefono", type=tel, required,
                               placeholder "0981 123 456")
Etikett 3:   ¿Qué necesitás?  (name="mensaje", textarea 4 rader)
Honeypot:    name="website"   (dolt, aria-hidden)
Knapp:       Enviar mi consulta
Notrad:      Usamos tus datos solo para responderte esta consulta.
```

**`card--accent` ×2 — trygghetsstack (löften, inga meriter):**

```
· Presupuesto sin costo y por escrito
· Odontólogos matriculados — te pasamos los datos antes de confirmar
· Coordinamos el horario que te sirva
· No se empieza ningún tratamiento sin tu confirmación
```

**Tacksida `gracias.html`:**

```
H1:      Recibimos tu consulta
Text:    Te vamos a escribir por WhatsApp al número que dejaste. Si querés
         adelantar, escribinos vos ahora mismo.
CTA:     Escribinos por WhatsApp
Länk:    Volver al inicio
```

### 13 — Footer

```
Marca:       Dentista.com.py
Rad 1:       Coordinamos consultas odontológicas en Asunción y el Gran Asunción.
NAP:         Asunción, Paraguay
             +595 995 628862
Svarslöfte:  Respondemos por WhatsApp todos los días.
Länkar:      Servicios · Tu primera consulta · Zonas · Preguntas · Política de privacidad
Copyright:   © 2026 Dentista.com.py
```

Ingen gatuadress, inget postnummer, ingen karta, inga klockslag, inga sociala ikoner
(⚠️ ingen profil bekräftad), ingen RUC-rad.

### Cookie-banner (Ley 6534/2020)

```
Text:        Usamos cookies solo para que el sitio funcione y para entender qué
             páginas se visitan. Podés aceptarlas o seguir sin ellas.
Knapp 1:     Aceptar
Knapp 2:     Seguir sin aceptar
Länk:        Política de privacidad
```

Ingen förikryssad ruta. Ingen tredjepartstagg laddas oavsett val i stage 1.

### FAB och sticky mobilbar

```
FAB aria-label:      Escribinos por WhatsApp
Mobilbar primär:     WhatsApp     (btn--wa)
Mobilbar sekundär:   Llamar       (tel:)
```

### Bannlyst ordlista (får inte förekomma någonstans i output)

`matrícula` som påstående om sajten · `Reg. Prof. N°` · `RUC` · `factura legal` ·
`años de experiencia` · antal patienter/jobb · `garantía` · betyg/stjärnor ·
`Dr.`/`Dra.` + namn · `nuestro equipo` · `nuestra clínica` · `nuestros consultorios` ·
`atención 24 horas` · alla belopp i `Gs.` · `desde` + siffra · påhittade citat ·
`tú`-former (`escríbenos`, `llámanos`, `agenda`, `consulta sin compromiso` i
imperativ tú) · engelska UI-ord.

---

## 5. Filträd (exakta filnamn)

```
/
├── index.html                     ← hela startsidan, kritisk CSS inline
├── contacto.php                   ← formulärhandler → VenderCRM (§8)
├── gracias.html                   ← tacksida, copy i §4
├── privacidad.html                ← integritetspolicy (Ley 6534/2020)
├── robots.txt
├── sitemap.xml
├── favicon.svg
├── site.webmanifest
├── BUILD-SPEC.md                  ← denna fil
├── STEP-0.md                      ← facit, redan i repot
├── README.md
├── .gitignore                     ← ignorerar leads.log och .env
├── assets/
│   ├── css/
│   │   └── site.css               ← tokens.css resolverad + sidstilar (icke-kritisk del)
│   ├── js/
│   │   ├── motion.js              ← web-design-system/references/motion.js, ORDAGRANT
│   │   └── site.js                ← wa-konstant, cotizador, FAQ, consent, analytics-shim
│   └── img/
│       ├── dentista-asuncion-consultorio-dental-1920.avif   (+ -1280, -640, + .webp)
│       ├── clinica-dental-asuncion-recepcion-1920.avif      (+ -1280, -640, + .webp)
│       ├── brackets-ortodoncia-asuncion-1280.avif           (+ -640, + .webp)
│       ├── profilaxis-limpieza-dental-asuncion-1280.avif    (+ -640, + .webp)
│       ├── blanqueamiento-dental-asuncion-1280.avif         (+ -640, + .webp)
│       ├── extraccion-muela-del-juicio-asuncion-1280.avif   (+ -640, + .webp)
│       ├── odontopediatria-ninos-asuncion-1280.avif         (+ -640, + .webp)
│       └── bioseguridad-instrumental-dental-esterilizado-1280.avif (+ -640, + .webp)
└── tools/
    └── fetch-images.mjs           ← hämtar, konverterar till AVIF+WebP, döper enligt §6
```

`leads.log` skapas i runtime av `contacto.php` och committas aldrig.
Ingen npm, ingen build, ingen `node_modules` i det som deployas — `tools/` körs lokalt.

---

## 6. Bildplan (STEP-0 §f, bildmanifest — kopierad)

| Slot | Fil (basnamn) | Alt-text | Ratio | px | Modell | Prompt |
|---|---|---|---|---|---|---|
| `hero-bleed` | `dentista-asuncion-consultorio-dental.avif` | Odontóloga atendiendo a un paciente en un consultorio dental en Asunción | 21:9 | 2048 | nano_banana_2 | STEP-0 §e #1 |
| `section-break` | `clinica-dental-asuncion-recepcion.avif` | Recepción de una clínica dental moderna en Asunción | 21:9 | 1024 | soul_cinematic | STEP-0 §e #2 |
| `card-motif` | `extraccion-muela-del-juicio-asuncion.avif` | Radiografía panorámica que muestra una muela del juicio antes de la extracción | 4:3 | 1024 | nano_banana_flash | STEP-0 §e #3 |
| `card-motif` | `brackets-ortodoncia-asuncion.avif` | Paciente joven con brackets sonriendo en la consulta | 4:3 | 1024 | nano_banana_flash | STEP-0 §e #4 |
| `card-motif` | `profilaxis-limpieza-dental-asuncion.avif` | Profilaxis y limpieza dental profesional realizada en el consultorio | 4:3 | 1024 | nano_banana_flash | STEP-0 §e #5 |
| `card-motif` | `odontopediatria-ninos-asuncion.avif` | Niño tranquilo en el sillón dental durante una consulta de odontopediatría | 4:3 | 1024 | nano_banana_flash | STEP-0 §e #6 |
| `card-motif` | `bioseguridad-instrumental-dental-esterilizado.avif` | Instrumental dental esterilizado listo para la atención | 4:3 | 1024 | seedream_v5_pro | STEP-0 §e #7 |
| `card-motif` | `blanqueamiento-dental-asuncion.avif` | Guía de color dental junto a la sonrisa de una paciente durante el blanqueamiento | 4:3 | 1024 | nano_banana_flash | STEP-0 §e #8 |
| `proof-photo` | — | — | — | — | — | **PENDIENTE — solo fotografía real del trabajo terminado. No generar.** |

**Slot → sektion:**

| Bild | Sektion |
|---|---|
| `dentista-asuncion-consultorio-dental` | 02 hero, högerkolumn (visualen sticker ut höger) |
| `clinica-dental-asuncion-recepcion` | 06 full-bleed band, scrim + `.grain` |
| `brackets-ortodoncia-asuncion` | 04 kort 1 (`card--ink`, span-2) + 05 sticky-kolumn |
| `profilaxis-limpieza-dental-asuncion` | 04 kort 2 |
| `blanqueamiento-dental-asuncion` | 04 kort 3 |
| `extraccion-muela-del-juicio-asuncion` | 04 kort 4 |
| `odontopediatria-ninos-asuncion` | 04 kort 5 |
| `bioseguridad-instrumental-dental-esterilizado` | 12 kontaktsektionen, bakom trygghetsstacken (dämpad, `--r-md`) |

**Leverans:** AVIF + WebP i 640/1280/1920 via `tools/fetch-images.mjs`, committas i repot.
Hero ≤120 KB, `fetchpriority="high"`, **aldrig** `loading="lazy"`, `aspect-ratio` satt mot CLS.
Allt under fold `loading="lazy"` med explicita `width`/`height`.
Total sidvikt ≤500 KB.

**Kostnadsregel (STEP-0 §e):** kör `get_cost: true` per modell och upplösning innan första
batchen och redovisa credit-mattan. `use_unlim` finns inte på kontot. Setet genereras
**en gång för vertikalen dental** och återanvänds på varje dentist-sajt via crop, scrim
och accentöverlägg — inte per sajt.

**Förbud:** inga genererade ansikten som testimonials, ingen före/efter som påstås vara
eget arbete, inga namn i bildtext, inget innehåll i `proof-photo` förrän riktiga foton finns.

---

## 7. Keyword-mappning (STEP-0 §a2 — kopierad)

En sida äger exakt **ett** primärt sökord. Aldrig två sidor på samma.

**Startsidan (detta bygge)** — `dentista` (1 000) + `dentista cerca de mi` (1 000).
**H1: `Dentista en Asunción y Gran Asunción`.**
Notera att `dentista asuncion` bara har 110 — huvudtermen bär volymen, geon läggs på
för intentionen.

**H2-teman på startsidan** (subteman, ingen egen sida): brackets · profilaxis dental ·
blanqueamiento dental · muela del juicio · odontopediatría · zonas · primera consulta.

**Servicios (5), rangordnade efter faktisk volym:**

| Sida | Primärt sökord | Vol. | H2-teman ur svansen |
|---|---|---|---|
| `/servicios/brackets` | `brackets` | **4 400** | precio, tipos, cuánto duran |
| `/servicios/profilaxis-dental` | `profilaxis dental` | 1 300 | limpieza de sarro (480), limpieza dental (170), ultrasonido dental (140), destartraje (40), curetaje |
| `/servicios/blanqueamiento-dental` | `blanqueamiento dental` | 1 000 | blanqueamiento dental precio (110), blanqueador de dientes (110) |
| `/servicios/muela-del-juicio` | `muela de juicio` | 590 | muelas juicio (480), extraccion de dientes (480), **exodoncia (210)**, extraccion de muela (140), extracción de muela infectada (140), restos radiculares (40) |
| `/servicios/odontopediatria` | `odontopediatria` | 320 | odontopediatra (170), odonto pediatría (320) |

**Zonas (3 + interior):**

| Sida | Primärt sökord | Vol. |
|---|---|---|
| `/zonas/san-lorenzo` | `odontologo san lorenzo` | 210 |
| `/zonas/luque` | `dentista luque` | 50 |
| `/zonas/lambare` | `dentista lambare` | 30 |
| `/zonas/interior` | specialfall, ingen volym | — |

San Lorenzo-sidans H1 måste säga **odontólogo**, inte dentista: `odontologo san lorenzo`
210 mot `dentista san lorenzo` 50. Fernando de la Mora (10), Capiatá och Mariano Roque
Alonso får ingen egen sida — de nämns i löptext på täckningssektionen.

**Guías (2):** `/guias/curetaje-dental` → `curetaje` (170) · `/guias/dientes-postizos` →
`dientes postizos` (90). Reserv: `dolor de muelas intenso` (40, +400 %), `restos radiculares` (40).

**Saknas i exporten:** implantes dentales, ortodoncia, endodoncia, protesis, carillas,
corona. Körs i KWP innan sida 16 och uppåt. **Implantat får ingen sida förrän volymen är
verifierad** — därför är brackets sajtens tyngsta sida i detta bygge.

**Fortsatt sidarkitektur (CORE 15, §10.4.1)** — byggs först efter godkänd startsida:
`/` · `/servicios/{brackets, profilaxis-dental, blanqueamiento-dental, muela-del-juicio,
odontopediatria}` · `/zonas/{san-lorenzo, luque, lambare}` · `/zonas/interior` ·
`/consulta` · `/contacto` · `/preguntas-frecuentes` · `/guias/{curetaje-dental,
dientes-postizos}`.

**Kontrollpunkt (STEP-0 §a2):** ⚠️ verifiera att KWP-exporten kördes med platsfilter
Paraguay och inte global spanska. Utan platsfilter är samtliga tal uppblåsta och
rangordningen mellan sidorna kan ändras.

**Meta för startsidan:**

```
<title>Dentista en Asunción y Gran Asunción | Dentista.com.py</title>
<meta name="description" content="Coordinamos tu consulta odontológica en Asunción y el
Gran Asunción: brackets, limpieza, blanqueamiento, muela del juicio y odontopediatría.
Escribinos por WhatsApp, presupuesto sin costo.">
<link rel="canonical" href="https://dentista.com.py/">
og:title / og:description = samma · og:image = /assets/img/dentista-asuncion-consultorio-dental-1280.webp (absolut URL)
<meta name="robots" content="noindex,nofollow">   ← ⚠️ tas bort vid lansering
```

**Schema (STEP-0 §b5 + §10.3):** `LocalBusiness` med `@type: "Dentist"`, `name`,
`url`, `telephone: "+595995628862"`, `address` = `{ addressLocality: "Asunción",
addressRegion: "Central", addressCountry: "PY" }` — **inget `streetAddress`**,
`areaServed` = Asunción, Luque, San Lorenzo, Fernando de la Mora, Lambaré, Capiatá,
Mariano Roque Alonso, Paraguay. **Ingen `aggregateRating`, ingen `openingHours`,
ingen `priceRange`, ingen `sameAs`.** Plus `FAQPage` med de sex frågorna i §4 ordagrant.

---

## 8. Lead-koppling — VenderCRM

Arkitekturregeln: **webbläsaren pratar aldrig med VenderCRM.** Formuläret postar till
sajtens egen `contacto.php`, som postar vidare med nyckeln. Endpointen skickar medvetet
inga CORS-headers.

```
besökare → [formulär] → /contacto.php → POST {VENDERCRM_URL}/api/v1/leads
                          (håller nyckeln)
```

| Post | Värde |
|---|---|
| Sajtens slug | `dentista-com-py` |
| Endpoint | `POST {VENDERCRM_URL}/api/v1/leads` |
| Headers | `Content-Type: application/json` · `X-Api-Key: {VENDERCRM_API_KEY}` |
| CRM base-URL | ⚠️ **SAKNAS** — sätts som env `VENDERCRM_URL` |
| API-nyckel | ⚠️ **SAKNAS** — env `VENDERCRM_API_KEY`, aldrig i HTML, aldrig i klient-JS, aldrig committad |
| `source` | `site:dentista-com-py` för formuläret, `cotizador-dentista` när `form_id` sätts av sektion 08 |

**Payload (fältnamn exakt):**

| Fält | Krav | Källa |
|---|---|---|
| `phone` | **ja** | `$_POST['telefono']` — 6–30 tecken, lokalt format `0981 123 456` normaliseras serverside till `+595…` |
| `idempotency_key` | **ja** | `hash('sha256', $phone . '|' . gmdate('Y-m-d-H'))` |
| `name` | nej | `$_POST['nombre']` |
| `email` | nej | finns inte i 3-fältsformuläret → **skickas inte alls** (tomt `""` ger 422) |
| `message` | nej | `$_POST['mensaje']` |
| `source` | nej | `$_POST['form_id']` ?? `site:dentista-com-py` |
| `page_url`, `referrer`, `utm_*`, `gclid`, `fbclid` | nej | ur `vc_attr`-cookien |
| `fields` | nej | `{"tratamiento": "...", "zona": "...", "horario": "..."}` från cotizadorn när den använts |

**Aldrig skickas:** `pipeline`, `stage`, `owner`, `tag` — routing ligger på site-posten i CRM:et.

**Sex produktionsregler, alla obligatoriska i handlern:**

1. Nyckeln serverside. `getenv('VENDERCRM_API_KEY')` — om `false` på Hostinger: include utanför `public_html` (`/home/user/private/vendercrm.php`).
2. `idempotency_key` alltid med, formeln ovan (dubbelklick och timeout-retries får inte skapa dubbletter).
3. `phone` är identiteten — `required` i HTML, `type="tel"` + `inputmode="tel"`, validerad även serverside.
4. Honeypot `name="website"`, dold, `aria-hidden` — ifylld → redirect till `gracias.html`, ingenting postas.
5. Blockera aldrig besökaren: `CURLOPT_TIMEOUT => 10`, try/catch, `error_log()` vid fel, alltid redirect till `gracias.html`.
6. Attribution: `<script src="{VENDERCRM_URL}/vc-attribution.js" defer>` på varje sida, cookien `vc_attr` läses serverside.

**Statusar handlern måste hantera:** `201` skapad · `200` idempotent replay (räknas som
success) · `401` fel/saknad nyckel → logga högt · `403` sajt avaktiverad/prenumeration
read-only · `422` valideringsfel, body namnger fältet · `429` rate limit 60/min → backa av.

**Stage 1, innan VenderCRM-URL och nyckel finns:** handlern byggs **färdig** med
`VENDERCRM_URL` och `VENDERCRM_API_KEY` som env-platshållare och loggar varje submit till
`leads.log` som fallback. **Ingen `mailto:`, ingen Formspree, ingen tredjepartsendpoint** —
de skapar migreringsskuld på hundra sajter. WhatsApp-flödet fungerar oavsett, och det är
därför det är primärt.

**Site-posten i VenderCRM ska skapas nu** (§0.5 punkt 8) — kan inte göras i denna session,
se platshållarlistan.

**Verifiering innan bygget kallas klart** (körs när nyckeln finns): skicka riktigt formulär →
kontakt syns i **Contactos** med normaliserat `+595…` → deal i **Pipeline** om default-stage
är satt → leadet räknas mot sajten i **Sitios** → identisk submit två gånger skapar **en**
kontakt.

**WhatsApp-attribution (STEP-0 §d):** ett nummer på ett ställe (`--wa-number`), förifylld
text unik per sektion:

```
https://wa.me/595995628862?text=Hola%2C%20vengo%20de%20dentista.com.py%20(brackets)%20-%20quiero%20consultar%20por%20
```

Sektionsnycklar i parentesen: `hero` · `brackets` · `profilaxis` · `blanqueamiento` ·
`muela-del-juicio` · `odontopediatria` · `banda` · `cotizador` · `zonas` · `statement` ·
`faq` · `contacto` · `fab` · `mobilbar`.

**Analytics-shim** (`references/analytics-prep.md`, ~350 byte, laddar ingenting) ligger i
`assets/js/site.js`. Varje CTA bär `data-ev` + `data-ev-loc`. Kanoniska namn, inga varianter:
`whatsapp_click` · `call_click` · `form_submit` · `calc_open` · `calc_complete` · `faq_open` ·
`zone_click`. `<head>` bär de inerta placeholder-kommentarerna för GTM/GA4 och
Search Console-verifiering på fast position.

---

## 9. Platshållarlista — måste bekräftas

| # | Post | Status | Konsekvens om fel |
|---|---|---|---|
| 1 | WhatsApp-nummer `+595 995 628862` | ⚠️ antaget (stage-1-numret, STEP-0 §b2) | Byte = en rad (`--wa-number`) |
| 2 | VenderCRM base-URL | ⚠️ **saknas** | Handlern loggar till `leads.log`, inga leads i CRM |
| 3 | VenderCRM site-API-nyckel för `dentista-com-py` | ⚠️ **saknas** — site-posten är inte skapad | 401 tills den finns |
| 4 | Odontólogo-partner + matrícula | ⚠️ finns inte | **Ingen `Reg. Prof. N°` någonstans.** Löftesband, inte meritband |
| 5 | RUC / factura legal | ⚠️ finns inte | Raden döljs helt, inte "pendiente" på sidan |
| 6 | Gatuadress | ⚠️ finns inte (medvetet i läge 3) | Ingen karta, inget `streetAddress`, footer = `Asunción, Paraguay` |
| 7 | Öppettider | ⚠️ obekräftade | Ingen `openingHours`, inga klockslag i footern |
| 8 | Riktiga foton | ⚠️ finns inte | `proof-photo` tom, AI-motiv i övriga slots |
| 9 | Reseñas / betyg | ⚠️ finns inte | Ingen `aggregateRating`, sektion 8 = cotizador |
| 10 | Priser | ⚠️ ska inte visas (§b3) | Noll belopp på hela sidan |
| 11 | Betalmetoder | ⚠️ obekräftade | Visas inte |
| 12 | Facebook / Instagram | ⚠️ finns inte | `sameAs` utelämnas, inga sociala ikoner |
| 13 | Fast telefon (021) | ⚠️ finns inte | Endast mobilnumret visas |
| 14 | Domän live + hosting-slot | ⚠️ obekräftat | Deploy till Hostinger `public_html/` när klart |
| 15 | KWP-exportens platsfilter | ⚠️ ska verifieras (STEP-0 §a2) | Rangordningen mellan servicios-sidorna kan ändras |
| 16 | `noindex` borttagen vid lansering | ⚠️ öppen punkt | Sajten rankar aldrig om den glöms |
| 17 | Leverans före partner finns | ⚠️ **verksamhetsrisk, inte byggfel** (STEP-0 §b) | Kommer lead in innan partner finns: svara *"te confirmo disponibilidad hoy"* och håll leadet varmt — boka aldrig en tid som inte finns |
| 18 | Personuppgiftspolicy `privacidad.html` | ⚠️ mall behövs | Ley 6534/2020, länkas från footer och consent-banner |

---

## 10. QA-lista (web-design-system/references/qa-preflight.md) — kryssrutor

Ingenting deployas, visas för prospekt eller presenteras för användaren förrän varje rad
passerar. Fel rapporteras explicit — ett fallerande bygge shippas aldrig tyst.

**Content integrity**
- [ ] Zero placeholder text visible in rendered output. No "ILLUSTRATIVE PANEL", no "[COMPLETAR]" on-page, no lorem, no "TODO".
- [ ] Zero empty list rows, dangling dashes, or half-filled tables.
- [ ] No fabricated reviews, ratings, counts, years, certifications, or guarantees. Missing proof = slot marked in the handover report, not invented.
- [ ] Every image slot either has an asset or is explicitly listed as pending.

**Layout**
- [ ] No two elements overlap unintentionally at 360 / 768 / 1280 / 1920 px.
- [ ] No more than 2 consecutive sections share a layout pattern.
- [ ] Page contains >=1 full-bleed, >=1 intentional overlap, >=1 oversized statement.
- [ ] >=3 card variants used; no variant more than 4 times.
- [ ] No section is >70% empty space without an image or texture doing work.

**Type & colour**
- [ ] Exactly one display face + one text face; both preloaded, `font-display: swap`.
- [ ] Body >=17px, line-height >=1.6, measure <=65ch.
- [ ] Muted text passes 4.5:1 against its actual background (check the cream/grey combos).
- [ ] Exactly one accent colour. `#25D366` appears only inside a WhatsApp glyph.

**Motion**
- [ ] `prefers-reduced-motion: reduce` disables all animation — tested.
- [ ] <=15% of elements animate.
- [ ] No entrance animation on above-the-fold hero text.
- [ ] No parallax below 1024px.

**Performance**
- [ ] Hero image <=120 KB, AVIF with WebP fallback, `fetchpriority="high"`, NOT lazy.
- [ ] All below-fold images `loading="lazy"` + explicit `width`/`height` (no CLS).
- [ ] Total page weight <=500 KB. Lighthouse mobile performance >=90.

**Technical**
- [ ] One H1. Semantic landmarks. Descriptive alt text in the site's language.
- [ ] Canonical, og-tags with a real image, viewport, favicon.
- [ ] LocalBusiness JSON-LD validates; FAQPage where an FAQ exists.
- [ ] Form posts to the site's own PHP/server handler, which forwards to VenderCRM. No `mailto:`, no third-party endpoint, no API key in client source.
- [ ] Tracked events fire: `whatsapp_click`, `call_click`, `form_submit`, each with `page_path`. Without these you cannot claim the site converts.
- [ ] Demo builds carry `<meta name="robots" content="noindex,nofollow">`.

**Tillägg för denna vertikal (paraguay-local-site §9 + STEP-0 §b):**
- [ ] Voseo genomgående i alla CTA; noll `tú`-former; noll engelska i UI
- [ ] WhatsApp-länk testad, format `5959…` utan plus och mellanslag, förifylld text unik per sektion
- [ ] Telefonnumret klickbart **och** synligt som text
- [ ] Noll belopp, noll `Gs.`, noll "desde"-siffra på hela sidan
- [ ] Noll merit: ingen matrícula, RUC, factura legal, år, antal, garanti, betyg, `Dr./Dra.`-namn
- [ ] Ingen mening som påstår att sajten är en klinik (`nuestra clínica`, `nuestro equipo`, `nuestros consultorios`)
- [ ] Consent-banner finns, inget förikryssat
- [ ] Noll horisontell scroll på 360 / 390 / 768 / 1024 / 1440 (`document.documentElement.scrollWidth === clientWidth`)
- [ ] Träffytor ≥48×48px, FAB ≥56px, FAB `bottom:88px` under 768px utan krock med mobilbaren

---

## 11. Avvikelser från specen som exekveringen tvingade fram

Två saker gick inte att bygga exakt som specen skrev dem. Båda är dokumenterade
här i stället för att tyst avvika, och båda ligger i koden.

**1. `--accent-ink: #A75332` — härledd accent för TEXT och CTA-fyllning.**
`--accent #C2603A` klarar inte QA-gatens 4.5:1 när den används som textfärg:
3,80:1 mot `--base`, 4,18:1 mot `--surface`, och vit text på den 4,18:1. Eyebrows,
länkar och primärknappens etikett föll alltså på kontrastraden. `--accent` står
kvar oförändrad som **den enda accenten** och används på allt dekorativt (kortkanter,
punkter, citatkant, FAQ-tecken, rail-siffrorna). Den härledda `--accent-ink` är
samma hue 14 % mörkare och används **bara** där accenten bär text:
4,87:1 mot base, 4,51:1 mot den tonade cremen i sektion 08/12, 5,35:1 mot vitt.
Detta är en tokenhärledning, inte ett andra accentfärgval — sajten läser fortfarande
som en enda accent.

**2. `data-reveal` borttaget från de två gränsöverlappspanelerna.**
`motion.js` (kopierad ordagrant, får inte ändras) skriver inline `transform` på varje
`[data-reveal]` och sätter den till `none` när elementet avslöjas. Det raderade
CSS-transformen som ÄR gränsöverlappet — båda panelerna landade med 0 px överlapp.
Panelerna avslöjas därför inte längre med scroll-reveal; överlappen mäter nu 99 px
(hero → franja) och 105 px (banda → proceso) vid 1440 px. Regeln för framtida sidor:
**ett element får bära `data-reveal` eller en layout-`transform`, aldrig båda.**

**Rail-siffrorna (07)** är märkta `aria-hidden="true"`. De ligger på 20 % accent enligt
P5 och klarar därför inte 4.5:1 — som dekor ska de inte heller göra det. Stegordningen
bärs av `<ol>`-semantiken och av rubrikerna, så ingen information går förlorad.

---

## Godkännandepunkt

Specen är låst i alla tio punkter. Nästa steg kräver ditt godkännande, och därefter:

1. Bekräfta eller korrigera platshållarna 1–3 i §9 (WhatsApp-nummer, CRM-URL, API-nyckel).
2. Skapa site-posten `dentista-com-py` i VenderCRM → **Sitios** och hämta nyckeln.
3. Kör bildbatchen enligt §6 (`get_cost: true` först, redovisa credit-mattan).
4. Exekvera: *"Implementera BUILD-SPEC.md exakt. Avvik inte. Fråga vid oklarhet istället för att gissa."*
