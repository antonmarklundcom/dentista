# Dentista.com.py

Lead-generation site for dental patients in Asunción / Gran Asunción, plus a partner funnel
for dentists who receive those leads. Plain PHP 8 + HTML + CSS, no database, no build step, no
framework, no Google scripts. Runs on Hostinger shared hosting.

## Two funnels

| Funnel | Pages | Form `type` |
|---|---|---|
| **Patients** (the leads you sell) | `/`, `/servicios/*`, `/zonas/*`, `/salud-dental/*`, `/consultorios-odontologicos/`, `/contacto/` | `paciente` |
| **Dentists** (your buyers) | `/para-odontologos/` | `partner` |
| **Ad landings** (noindex, no menu) | `/lp/<service>/` e.g. `/lp/protesis-dental/` | `paciente` |

## Where leads go

1. **VenderCRM:** `enviar.php` posts every lead server-side to
   `https://crm.clientes.com.py/api/v1/leads`. The phone is normalized to `+595…`, and first-touch
   UTM/fbclid comes from the CRM's `vc-attribution.js` cookie. Treatment, zone, urgency, tier,
   lead value and ref are sent as `fields`. The idempotency key is phone + hour, so double
   submits don't duplicate.
2. **Backup CSV:** `leads/leads-YYYY-MM.csv`, always written. Each row carries the CRM result
   (`201` ok, `error` = CRM unreachable, `off` = no key). Web access is denied.
3. **Email (optional):** a copy goes to `email_to`.

A slow or failing CRM never blocks the visitor: they still see `/gracias/`, and the lead waits in
the CSV with a **Reenviar** button in the admin.

WhatsApp buttons go through `/wa/`, which logs the click (page, button, treatment, UTM) to
`leads/wa-clicks-YYYY-MM.csv` and redirects to wa.me.

## Admin panel: `/admin/`

Password login (bcrypt hash in `config.php`, 5 attempts per 15 min, 8 h session). It shows:

- monthly lead and WhatsApp-click counts, estimated value, and leads that didn't reach the CRM
- a lead table with filter and search, a WhatsApp link per lead, and **Reenviar** for failed CRM sends
- WhatsApp clicks by treatment, page, button and source
- CSV download per month

## Content = data

| File | What |
|---|---|
| `content/site.php` | business facts, lead values per tier, promises |
| `content/servicios/<slug>.php` | one treatment page + its ad landing (`lp` key) |
| `content/guias/<slug>.php` | one "Salud dental" guide (`consultorios-odontologicos` is served at its own top-level URL) |
| `content/zonas.php` | city pages |
| `content/images.php` | image registry: file stem + alt text per page, before/after pairs |

To add a treatment, add one file in `content/servicios/`. The menu, forms, sitemap, footer,
cards and `/lp/<slug>/` all pick it up. There is one primary keyword per page. Keyword data:
`docs/kwp.csv` and `docs/kwp-2026-09.md`.

## Images

Prompts: `docs/image-prompts.md`. Every page shows its image automatically once
`assets/img/<stem>-640.webp` exists (plus `-1280.webp` and `.avif`, via webimg). Until then,
layouts render cleanly without them. Before/after pairs always carry the caption
"Simulación ilustrativa — no es un paciente real."

## Local preview

```sh
php -S localhost:8080 index.php
```

## Deploy on Hostinger

1. hPanel → the site → **Advanced → Git** → connect this repo, branch `main`, directory
   empty (= `public_html`). Enable auto-deploy. No GitHub Actions needed.
2. **PHP 8.1+** with `curl` (hPanel → PHP Configuration).
3. In File Manager, copy `config.example.php` to `config.php` and fill in:
   - `vcrm_api_key`: the dentista.com.py key from VenderCRM → Sitios (never commit it)
   - `whatsapp` / `phone_display`
   - `admin_password_hash`: `php -r "echo password_hash('YOUR-PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"`
   - `email_to` (optional)

   `config.php` is git-ignored, so deploys never overwrite it.
4. Check that `leads/` is writable. Submit a test lead, then confirm it appears in VenderCRM →
   Contactos and in `/admin/` with CRM status ✓.
5. Search Console / Bing Webmaster: submit `https://dentista.com.py/sitemap.xml`.
