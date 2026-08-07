/**
 * fetch-images.mjs — hämtar bilderna från Higgsfield-genereringen, konverterar
 * till AVIF + WebP i 640/1280/1920 och döper dem enligt bildmanifestet i
 * STEP-0.md §f / BUILD-SPEC.md §6.
 *
 * Körs LOKALT, aldrig på servern. Resultatet committas i repot.
 *
 *   npm i sharp
 *   node tools/fetch-images.mjs
 *
 * Källorna läses ur tools/images.json:
 *   { "dentista-asuncion-consultorio-dental": "https://…/generation.png", … }
 * Nycklarna MÅSTE vara basnamnen i manifestet — filnamn sätts aldrig för hand.
 */
import { readFile, mkdir, writeFile } from 'node:fs/promises';
import { existsSync } from 'node:fs';
import path from 'node:path';
import sharp from 'sharp';

const ROOT   = path.resolve(import.meta.dirname, '..');
const OUT    = path.join(ROOT, 'assets', 'img');
const SOURCES = path.join(ROOT, 'tools', 'images.json');

/* Bredder per slot. Hero och section-break behöver 1920, card-motif aldrig. */
const WIDTHS = {
  'dentista-asuncion-consultorio-dental': [640, 1280, 1920],
  'clinica-dental-asuncion-recepcion':    [640, 1280, 1920],
  _default:                               [640, 1280],
};

/* Kvalitet: hero ska landa under 120 KB, resten under ~60 KB. */
const QUALITY = { avif: 52, webp: 74 };

async function run() {
  if (!existsSync(SOURCES)) {
    console.error(
      `Saknar ${path.relative(ROOT, SOURCES)}.\n` +
      'Skapa den med { "<basnamn ur manifestet>": "<url till genererad bild>" }.'
    );
    process.exit(1);
  }

  const map = JSON.parse(await readFile(SOURCES, 'utf8'));
  await mkdir(OUT, { recursive: true });

  const report = [];

  for (const [base, url] of Object.entries(map)) {
    const res = await fetch(url);
    if (!res.ok) {
      console.error(`✗ ${base}: ${res.status} ${res.statusText}`);
      process.exitCode = 1;
      continue;
    }
    const buf = Buffer.from(await res.arrayBuffer());
    const widths = WIDTHS[base] ?? WIDTHS._default;

    for (const w of widths) {
      for (const fmt of ['avif', 'webp']) {
        const file = path.join(OUT, `${base}-${w}.${fmt}`);
        const out = await sharp(buf)
          .resize({ width: w, withoutEnlargement: true })
          .toFormat(fmt, { quality: QUALITY[fmt] })
          .toBuffer();
        await writeFile(file, out);
        report.push({ file: path.basename(file), kb: Math.round(out.length / 1024) });
      }
    }
    console.log(`✓ ${base}`);
  }

  console.table(report);

  const hero = report.filter(r => r.file.startsWith('dentista-asuncion-consultorio-dental-1280'));
  for (const h of hero) {
    if (h.kb > 120) console.warn(`⚠️  ${h.file} är ${h.kb} KB — QA-gränsen för hero är 120 KB.`);
  }
}

run().catch(err => { console.error(err); process.exit(1); });
