/**
 * swap-wa.mjs — byter WhatsApp-/telefonnumret i hela sajten på ett kommando.
 *
 * BUILD-SPEC §2: numret ska bo på ett ställe. Det gör det i
 * assets/js/site.js (WA_NUMBER); den här filen håller de statiska href:arna,
 * tel:-länkarna och den synliga texten i synk med den konstanten.
 *
 *   node tools/swap-wa.mjs 595981234567
 *
 * Kör den efter varje nummerbyte och committa diffen.
 */
import { readFile, writeFile, readdir } from 'node:fs/promises';
import path from 'node:path';

const ROOT = path.resolve(import.meta.dirname, '..');
const OLD  = '595995628862';                       // nuvarande stage-1-nummer
const NEXT = (process.argv[2] || '').replace(/\D/g, '');

if (!/^595\d{9}$/.test(NEXT)) {
  console.error('Ange nya numret i formatet 595XXXXXXXXX, t.ex. node tools/swap-wa.mjs 595981234567');
  process.exit(1);
}

/** Alla format numret förekommer i. */
const forms = old => {
  const local = old.slice(3);                                  // 995628862
  return [
    [old, NEXT],                                               // wa.me/595…  och WA_NUMBER
    [`+${old}`, `+${NEXT}`],                                   // tel:+595…
    [`+595 ${local.slice(0,3)} ${local.slice(3)}`,             // synlig text: +595 995 628862
     `+595 ${NEXT.slice(3,6)} ${NEXT.slice(6)}`],
  ];
};

const EXT = new Set(['.html', '.php', '.js', '.css', '.json', '.xml', '.md', '.webmanifest']);
const SKIP = new Set(['node_modules', '.git', 'assets/img']);

async function* walk(dir) {
  for (const entry of await readdir(dir, { withFileTypes: true })) {
    const full = path.join(dir, entry.name);
    if (SKIP.has(entry.name) || SKIP.has(path.relative(ROOT, full))) continue;
    if (entry.isDirectory()) yield* walk(full);
    else if (EXT.has(path.extname(entry.name))) yield full;
  }
}

let touched = 0;
for await (const file of walk(ROOT)) {
  const before = await readFile(file, 'utf8');
  let after = before;
  for (const [from, to] of forms(OLD)) after = after.split(from).join(to);
  if (after !== before) {
    await writeFile(file, after);
    console.log(`✓ ${path.relative(ROOT, file)}`);
    touched++;
  }
}

console.log(touched ? `\n${touched} filer uppdaterade till ${NEXT}.` : 'Inget att byta — hittade inte det gamla numret.');
console.log('Kom ihåg: uppdatera även OLD-konstanten i den här filen efter bytet.');
