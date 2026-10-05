from pathlib import Path
from html.parser import HTMLParser
from urllib.parse import urlparse, unquote
from xml.etree import ElementTree as ET
import json, sys

ROOT=Path(__file__).resolve().parents[1]/'site'
class Document(HTMLParser):
    def __init__(self,text):
        super().__init__(convert_charrefs=True); self.links=[]; self.ids=set(); self.canonical=[]; self.h1=0; self.schema=[]; self.current=''; self.json=False; self.meta={}; self.images=[]; self.feed(text)
    def handle_starttag(self,tag,attrs):
        a=dict(attrs)
        if 'id' in a: self.ids.add(a['id'])
        if tag=='h1': self.h1+=1
        if tag=='meta': self.meta[a.get('name',a.get('property',''))]=a.get('content','')
        if tag=='link' and a.get('rel')=='canonical': self.canonical.append(a['href'])
        if tag=='a' and 'href' in a: self.links.append(a['href'])
        if tag in ['script','img'] and 'src' in a: self.links.append(a['src'])
        if tag=='link' and 'href' in a and a.get('rel')!='canonical': self.links.append(a['href'])
        if tag=='img': self.images.append(a)
        if tag=='script' and a.get('type')=='application/ld+json': self.json=True; self.current=''
    def handle_data(self,data):
        if self.json:self.current+=data
    def handle_endtag(self,tag):
        if tag=='script' and self.json:self.schema.append(json.loads(self.current)); self.json=False

errors=[]; links=0
docs={file:Document(file.read_text(encoding='utf-8')) for file in ROOT.rglob('*.html')}
urls=[urlparse(loc.text).path for loc in ET.parse(ROOT/'sitemap.xml').iter('{http://www.sitemaps.org/schemas/sitemap/0.9}loc')]
for path in urls:
    file=ROOT/path.strip('/')/'index.html' if path!='/' else ROOT/'index.html'
    if file not in docs:errors.append(f'Sitemap route missing: {path}')
for file,doc in docs.items():
    rel=file.relative_to(ROOT).as_posix(); route='/' if rel=='index.html' else '/'+str(file.parent.relative_to(ROOT)).replace('\\','/')+'/'
    if doc.h1!=1:errors.append(f'{rel}: expected one H1')
    if rel!='404.html' and doc.canonical!=['https://dentista.com.py'+route]:errors.append(f'{rel}: canonical mismatch')
    if rel!='404.html' and not doc.schema:errors.append(f'{rel}: missing schema')
    if not doc.meta.get('description'):errors.append(f'{rel}: description missing')
    contact_config=(ROOT/'assets/js/contact-config.js').read_text(encoding='utf-8')
    contact_enabled=bool(__import__('re').search(r'(?:"enabled"|enabled)\s*:\s*true',contact_config))
    if not contact_enabled and ('href="https://wa.me/' in file.read_text(encoding='utf-8') or 'href="tel:' in file.read_text(encoding='utf-8')):
        errors.append(f'{rel}: unconfirmed external contact destination')
    for img in doc.images:
        if not all(img.get(k) for k in ['alt','width','height']):errors.append(f'{rel}: image alt/dimensions missing')
    for href in doc.links:
        links+=1; uri=urlparse(href)
        if uri.scheme in ['https','http','tel','mailto']:continue
        target=ROOT/unquote(uri.path.lstrip('/')) if uri.path.startswith('/') else file.parent/unquote(uri.path)
        if not uri.path: target=file
        if target.is_dir():target=target/'index.html'
        if not target.is_file():errors.append(f'{rel}: broken link {href}')
        elif uri.fragment and target in docs and unquote(uri.fragment) not in docs[target].ids: errors.append(f'{rel}: missing fragment {href}')
    if doc.meta.get('og:image')!='https://dentista.com.py/assets/images/og-default.jpg':errors.append(f'{rel}: OG image mismatch')
for file in ROOT.rglob('*'):
    if file.is_file() and (file.suffix in ['.php','.md','.py','.csv','.log','.zip'] or file.name in ['.env','config.php']):errors.append(f'Private/unsupported deploy file: {file}')
result={'pages':len(docs),'sitemap_urls':len(urls),'checked_links':links,'errors':errors,'image_bytes':{p.name:p.stat().st_size for p in (ROOT/'assets/images').iterdir()}}
print(json.dumps(result,ensure_ascii=False,indent=2))
sys.exit(bool(errors))
