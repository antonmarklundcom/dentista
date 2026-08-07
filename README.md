# dentista.com.py

Regional vertikal (Gran Asunción) enligt paraguay-local-site LÄGE 3.
Spår: EDITORIAL. WhatsApp-first, statisk HTML för Hostinger.

**Beslutskedjan:** `STEP-0.md` (recon, låsta beslut) → `BUILD-SPEC.md` (spec, färdig copy)
→ startsidan i det här repot. Avvik aldrig från specen utan att uppdatera den först.

## Innehåll

| Fil | Roll |
|---|---|
| `index.html` | Startsidan. Tokens + kritisk CSS inline, sektion 01–13 enligt BUILD-SPEC §3 |
| `assets/css/site.css` | Sektion 04–13, FAB, mobilbar, consent |
| `assets/js/site.js` | WhatsApp-konstanten, cotizadorn, consent, analytics-shim |
| `assets/js/motion.js` | Kopierad ORDAGRANT från web-design-system. Ändra aldrig |
| `contacto.php` | Formulärhandler → VenderCRM. Nyckeln lämnar aldrig servern |
| `gracias.html` · `privacidad.html` | Tacksida och integritetspolicy (Ley 6534/2020) |
| `tools/fetch-images.mjs` | Hämtar och konverterar bilderna till AVIF+WebP enligt manifestet |
| `tools/swap-wa.mjs` | Byter WhatsApp-/telefonnumret i hela sajten på ett kommando |

## Status

- ✅ Startsidan komplett, all copy ordagrant ur BUILD-SPEC §4
- ✅ Verifierad i Chromium på 360 / 390 / 768 / 1024 / 1440 / 1920 — noll horisontell scroll
- ✅ Kontrast ≥4.5:1 på all text, träffytor ≥48px, JSON-LD validerar, fungerar utan JS
- ⛔ **Bilderna är inte genererade än.** `.media`-panelerna visar en tonad yta tills
  filerna finns (`assets/img/` är tom). Kör bildbatchen enligt BUILD-SPEC §6.
- ⛔ **VenderCRM inte kopplat.** `contacto.php` loggar till `leads.log` tills
  `VENDERCRM_URL` och `VENDERCRM_API_KEY` finns.
- ⛔ `noindex,nofollow` ligger kvar — sajten är i demo-läge.

Fullständig lista över antaganden: BUILD-SPEC §9.

## Kör lokalt

```bash
python3 -m http.server 8099     # formuläret kräver PHP, resten funkar
php -S localhost:8099           # om du vill testa contacto.php
```

## Bilder

```bash
npm i sharp
# tools/images.json: { "<basnamn ur BUILD-SPEC §6>": "<url till genererad bild>" }
node tools/fetch-images.mjs
```

Basnamnen är låsta i bildmanifestet (STEP-0 §f). Döp aldrig om filer för hand.
`proof-photo`-sloten står tom med flit — bara riktiga foton får hamna där.

## Byta WhatsApp-nummer

```bash
node tools/swap-wa.mjs 595981234567
```

Uppdatera sedan `OLD`-konstanten i `tools/swap-wa.mjs` och committa diffen.

## Deploy (Hostinger, statiskt + PHP)

1. Ladda upp allt utom `tools/`, `STEP-0.md`, `BUILD-SPEC.md` och `README.md` till `public_html/`.
2. Sätt `VENDERCRM_URL` och `VENDERCRM_API_KEY` i hPanel, eller lägg
   `/home/<user>/private/vendercrm.php` **utanför** `public_html`.
3. Vid lansering: ta bort `noindex`-taggen i alla tre HTML-filerna, byt `Disallow: /`
   mot `Allow: /` i `robots.txt`, aktivera `vc-attribution.js`-taggen i `index.html`,
   lägg in DNS-TXT för Search Console och skicka in sitemapen.
