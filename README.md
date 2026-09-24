# Dentista.com.py

Lead-generation site for dental patients in Asunción / Gran Asunción, plus a partner funnel
for dentists who buy those leads. Plain PHP 8 + HTML + CSS, no database, no build step, no
framework. Runs on Hostinger shared hosting.

## Two funnels

| Funnel | Pages | Form `type` | Ads conversion label |
|---|---|---|---|
| **Patients** (the leads you sell) | `/`, `/servicios/*`, `/zonas/*`, `/guias/*`, `/contacto/` | `paciente` | `ads_label_patient` |
| **Dentists** (your buyers) | `/para-odontologos/` | `partner` | `ads_label_partner` |
| **Google Ads landings** (noindex, no menu) | `/lp/<service>/` e.g. `/lp/brackets/` | `paciente` | `ads_label_patient` |

Every lead is written to `leads/leads-YYYY-MM.csv` (web access denied) and optionally
emailed and POSTed to a webhook (VenderCRM, Zapier, Make). Each row carries: ref, treatment,
zone, urgency, **tier + value**, consent, source page, **gclid + UTM**. That is what makes a
lead sellable: you can show a dentist exactly what the patient wants, how urgent it is and
where it came from.

## Content = data

| File | What |
|---|---|
| `content/site.php` | business facts, lead values per tier (Ads bidding), promises |
| `content/servicios/<slug>.php` | one treatment page + its Ads landing (`lp` key) |
| `content/guias/<slug>.php` | one guide |
| `content/zonas.php` | city pages |

Add a treatment = add one file in `content/servicios/`. Menu, forms, sitemap, footer, service
cards and `/lp/<slug>/` all pick it up automatically. One primary keyword per page (see
`KEYWORDS.md` and the locked KWP map in git history, `STEP-0.md`).

## Local preview

```sh
php -S localhost:8080 index.php
```

## Deploy on Hostinger

1. hPanel → the site → **Advanced → Git** → connect this repo, branch `main`, directory
   empty (= `public_html`). Enable auto-deploy (webhook). No GitHub Actions needed.
   (Or upload the files to `public_html/` with File Manager.)
2. **PHP 8.1+** (hPanel → PHP Configuration), with `curl`.
3. In File Manager copy `config.example.php` → `config.php` and fill it in: WhatsApp number,
   `email_to`, GA4 id, Ads id + conversion labels. `config.php` is git-ignored — it is never
   overwritten by a deploy.
4. Check that `leads/` is writable, then submit a test lead and confirm it lands in the CSV
   and your email.
5. Google Search Console: add the domain, submit `https://dentista.com.py/sitemap.xml`.

## Google Ads setup

1. Create 3 conversion actions in Google Ads: **Lead paciente** (primary), **Lead odontólogo**
   (primary, separate campaign), **Clic WhatsApp** (secondary). Paste the labels into `config.php`.
2. Turn on *Enhanced conversions for leads* — the thank-you page already sends the
   phone/email through gtag (hashed by Google).
3. Final URL per ad group: `https://dentista.com.py/lp/<service>/?utm_source=google&utm_medium=cpc&utm_campaign=<service>`.
4. Values: conversions carry `leadValues` from `content/site.php` (PYG). Set them to what a
   dentist pays you per lead of that tier, then bid on *Maximize conversion value*.
5. Offline conversion import: the CSV keeps `gclid`, so leads that turn into booked
   appointments can be uploaded back to Ads as a stronger conversion.
