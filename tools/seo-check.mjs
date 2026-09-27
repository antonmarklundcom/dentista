#!/usr/bin/env node
// On-page SEO/CRO check for every URL in the sitemap of a running local copy.
//   php -S 127.0.0.1:8093 index.php   (in another terminal)
//   node tools/seo-check.mjs [http://127.0.0.1:8093]
// Exits 1 when an error is found; warnings are printed but don't fail.

const base = (process.argv[2] || "http://127.0.0.1:8093").replace(/\/$/, "");
const prod = "https://dentista.com.py";

const sitemap = await (await fetch(`${base}/sitemap.xml`)).text();
const paths = [...sitemap.matchAll(/<loc>([^<]+)<\/loc>/g)].map((m) => m[1].replace(prod, ""));

const errors = [];
const warns = [];
const titles = new Map();
const descs = new Map();
const internal = new Set();
const pick = (html, re) => (html.match(re) || [])[1];
const decode = (s) => s.replace(/&amp;/g, "&").replace(/&quot;/g, '"').replace(/&#0?39;/g, "'");

for (const p of paths) {
  const res = await fetch(base + p);
  const html = await res.text();
  const err = (m) => errors.push(`${p}: ${m}`);
  const warn = (m) => warns.push(`${p}: ${m}`);
  if (res.status !== 200) { err(`HTTP ${res.status}`); continue; }

  const title = decode(pick(html, /<title>([^<]*)<\/title>/) || "");
  const desc = decode(pick(html, /<meta name="description" content="([^"]*)"/) || "");
  if (!title) err("no <title>");
  else if (title.length > 62) warn(`title ${title.length} chars (Google cuts ~60): ${title}`);
  else if (title.length < 25) warn(`title short (${title.length}): ${title}`);
  if (!desc) err("no meta description");
  else if (desc.length > 160) warn(`description ${desc.length} chars (cut ~155)`);
  else if (desc.length < 70) warn(`description short (${desc.length})`);
  titles.set(title, [...(titles.get(title) || []), p]);
  descs.set(desc, [...(descs.get(desc) || []), p]);

  const h1s = html.match(/<h1[\s>]/g) || [];
  if (h1s.length !== 1) err(`${h1s.length} <h1>`);
  const canon = pick(html, /<link rel="canonical" href="([^"]+)"/);
  if (canon !== prod + p) err(`canonical ${canon} ≠ ${prod + p}`);
  for (const m of html.matchAll(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/g)) {
    try { JSON.parse(m[1]); } catch { err("JSON-LD does not parse"); }
  }

  for (const img of html.matchAll(/<img\b[^>]*>/g)) {
    const tag = img[0];
    if (!/\balt="/.test(tag)) err(`img without alt: ${tag.slice(0, 80)}`);
    if (!/\bwidth="\d+"/.test(tag) || !/\bheight="\d+"/.test(tag)) warn(`img without width/height (CLS): ${tag.slice(0, 80)}`);
  }
  const imgs = [...html.matchAll(/<img\b[^>]*>/g)].map((m) => m[0]);
  const eager = imgs.filter((t) => /fetchpriority="high"/.test(t));
  if (eager.length > 1) warn(`${eager.length} images with fetchpriority=high`);

  // CRO: every indexable page should offer a way to act.
  if (!/data-wa\b/.test(html) && !/id="pedir-turno"/.test(html)) warn("no WhatsApp link or form on the page");

  for (const m of html.matchAll(/href="(\/[^"#?]*)/g)) internal.add(m[1]);
}

for (const [t, ps] of titles) if (t && ps.length > 1) errors.push(`duplicate title on ${ps.join(", ")}: ${t}`);
for (const [d, ps] of descs) if (d && ps.length > 1) errors.push(`duplicate description on ${ps.join(", ")}`);

for (const link of internal) {
  if (/^\/(assets|wa|admin|enviar\.php|lp)\b/.test(link)) continue;
  const r = await fetch(base + link, { redirect: "manual" });
  if (r.status >= 400) errors.push(`broken internal link ${link} → ${r.status}`);
  else if (r.status >= 300) warns.push(`internal link redirects ${link} → ${r.headers.get("location")}`);
}

console.log(`checked ${paths.length} pages, ${internal.size} internal links`);
for (const w of warns) console.log(`warn  ${w}`);
for (const e of errors) console.log(`ERROR ${e}`);
console.log(`${errors.length} errors, ${warns.length} warnings`);
process.exit(errors.length ? 1 : 0);
