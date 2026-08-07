# HANDOFF — dentista.com.py

Startsidan är byggd, verifierad och pushad till `claude/dentista-build-1` (PR #2).
Enda som återstår innan sajten kan visas: **bilderna**.

## Läget

- ✅ `STEP-0.md` — recon, låsta beslut
- ✅ `BUILD-SPEC.md` — spec med färdig copy (§4) och två dokumenterade avvikelser (§11)
- ✅ Startsidan: `index.html`, `assets/css/site.css`, `assets/js/{site,motion}.js`,
  `contacto.php`, `gracias.html`, `privacidad.html`, robots/sitemap/favicon/manifest
- ✅ Verifierad i Chromium på 360/390/768/1024/1440/1920: noll horisontell scroll,
  en H1, kortvarianter hair 4 · bare 4 · accent 3 · raised 3 · ink 1, 3,5 % animerat,
  all text ≥4.5:1, träffytor ≥48 px, JSON-LD validerar, fungerar utan JS
- ⛔ `assets/img/` är TOM. `.media`-panelerna visar tonad yta tills filerna finns.
- ⛔ VenderCRM inte kopplat (`contacto.php` loggar till `leads.log`)
- ⛔ `noindex,nofollow` kvar på alla tre HTML-filer

## Bilderna — det som stoppade oss

De 8 bilderna ÄR genererade i Higgsfield (2026-08-07, modell `nano_banana_2`).
Sandlådans nätverkspolicy blockerar `d8j0ntlcm91z4.cloudfront.net` (403 på CONNECT),
så de går inte att hämta från exekveringsmiljön.

Generation-ID → slot (filnamnen från Higgsfield innehåller dessa UUID:n, så
mappningen kan göras automatiskt — inget behöver döpas om för hand):

| Generation-ID | Basnamn i manifestet | Slot |
|---|---|---|
| `e2844e01-b58c-4408-8d83-95c01cc9b7ff` | `dentista-asuncion-consultorio-dental` | hero-bleed 21:9 |
| `dbac215a-6398-4695-9e60-0673b8d95b0f` | `clinica-dental-asuncion-recepcion` | section-break 21:9 |
| `654545ce-012e-46b9-ac6d-a6ded175c759` | `brackets-ortodoncia-asuncion` | card-motif 4:3 |
| `77a04d5a-8272-414e-9d52-df7fb9e86916` | `profilaxis-limpieza-dental-asuncion` | card-motif 4:3 |
| `59d255fe-41f2-41cc-87e1-ff2ea88c8acc` | `blanqueamiento-dental-asuncion` | card-motif 4:3 |
| `ddc10267-96a2-4e2d-93d2-4216508d3f4a` | `extraccion-muela-del-juicio-asuncion` | card-motif 4:3 |
| `a35cf6a6-0efe-4b96-b9ac-d962bc1ba67f` | `odontopediatria-ninos-asuncion` | card-motif 4:3 |
| `2febe88e-a332-41ee-8736-33d4f9915ece` | `bioseguridad-instrumental-dental-esterilizado` | card-motif 4:3 |

`proof-photo` står tom med flit — bara riktiga foton får hamna där.

## Nästa session

1. Ladda upp de 8 PNG-filerna till `tools/src-images/` på branchen, **med de namn de
   redan har**. Filnamnen bär generation-UUID:t, så mappningen sker automatiskt.
2. Kör konverteringen (AVIF+WebP, 640/1280/1920), skriv in verkliga `width`/`height`
   i `index.html`, kontrollera hero ≤120 KB och sidvikt ≤500 KB.
3. Kör om QA: brytpunkter, kontrast, träffytor, CLS, skärmbilder.
4. Committa och pusha till `claude/dentista-build-1`.

Därefter: VenderCRM-nyckel, sedan CORE 15 enligt BUILD-SPEC §7.
