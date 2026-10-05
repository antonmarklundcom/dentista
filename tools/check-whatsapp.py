"""Check every published HTML WhatsApp destination, including no-JS links."""
from pathlib import Path
from html.parser import HTMLParser
from urllib.parse import urlparse, parse_qs
import json, re

root=Path(__file__).resolve().parents[1]/'site'
config=json.loads(re.search(r'window\.DENTISTA_CONTACT\s*=\s*(\{.*?\});', (root/'assets/js/contact-config.js').read_text(encoding='utf-8'), re.S)[1])
assert config['enabled'] and config['ownerConfirmed']
number=config['whatsappNumber']

class Links(HTMLParser):
    def __init__(self,html):
        super().__init__(); self.links=[]; self.feed(html)
    def handle_starttag(self,tag,attrs):
        if tag=='a': self.links.append(dict(attrs))

pages=whatsapp=phone=0
for file in root.rglob('*.html'):
    html=file.read_text(encoding='utf-8')
    metadata=json.loads(re.search(r'window\.DENTISTA_CONFIG\s*=\s*(\{[^\n]+?\});',html)[1])
    relative=file.relative_to(root).as_posix()
    route='/' if relative=='index.html' else '/404.html' if relative=='404.html' else '/'+relative.removesuffix('index.html')
    count=0
    for link in Links(html).links:
        uri=urlparse(link.get('href',''))
        if link.get('data-contact-kind')=='phone':
            assert link['href']=='tel:+'+number, (relative,link)
            phone+=1
        if link.get('data-contact-kind')=='whatsapp' or uri.hostname=='wa.me':
            assert uri.scheme=='https' and uri.hostname=='wa.me' and uri.path=='/'+number, (relative,link)
            text=parse_qs(uri.query).get('text',[''])[0]
            assert text==link.get('data-message'), (relative,link)
            assert 'vengo de Dentista.com.py' in text, (relative,text)
            assert 'Página: https://dentista.com.py'+route+' ' in text, (relative,text)
            if metadata.get('treatment'): assert metadata['treatment'] in text, (relative,text)
            if metadata.get('city'): assert metadata['city'] in text, (relative,text)
            whatsapp+=1; count+=1
    assert count>0, relative
    pages+=1
print(json.dumps({'pages':pages,'whatsapp_links':whatsapp,'phone_links':phone,'recipient':number,'result':'PASS'},indent=2))
