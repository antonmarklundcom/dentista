"""Update JS + HTML fallbacks together, only on explicit owner confirmation.
Use --disable to return to the safe review state. Never sends a lead.
"""
from pathlib import Path
from html.parser import HTMLParser
from html import escape
from urllib.parse import quote
import argparse, re, json, hashlib

ROOT=Path(__file__).resolve().parents[1]/'site'
parser=argparse.ArgumentParser()
parser.add_argument('--number')
parser.add_argument('--display')
parser.add_argument('--owner-confirmed',action='store_true')
parser.add_argument('--disable',action='store_true')
args=parser.parse_args()
if not args.disable and (not args.owner_confirmed or not args.number or not re.fullmatch(r'5959\d{8}',args.number) or re.fullmatch(r'(\d)\1{6,}',args.number[3:])):
    parser.error('A valid Paraguay mobile number and explicit --owner-confirmed are required.')
enabled=not args.disable
config={'enabled':enabled,'ownerConfirmed':enabled,'whatsappNumber':args.number if enabled else '', 'phoneDisplay':(args.display or '+'+args.number) if enabled else ''}
version=hashlib.sha256(json.dumps(config,sort_keys=True).encode()).hexdigest()[:12]
class Attributes(HTMLParser):
    def handle_starttag(self,tag,attrs): self.attrs=dict(attrs)

class PageTitle(HTMLParser):
    def __init__(self, html):
        super().__init__(); self.in_title=False; self.title=''; self.feed(html)
    def handle_starttag(self,tag,attrs):
        if tag=='title': self.in_title=True
    def handle_endtag(self,tag):
        if tag=='title': self.in_title=False
    def handle_data(self,data):
        if self.in_title: self.title+=data

def replace(match):
    opening,label=match[1],match[2]
    if 'data-contact-kind=' not in opening:return match[0]
    parsed=Attributes(); parsed.feed('<a'+opening+'>'); attrs=parsed.attrs
    phone=attrs['data-contact-kind']=='phone'
    opening=re.sub(r'\sdata-message="[^"]*"','',opening)
    opening+=' data-message="'+escape(page_message,quote=True)+'"'
    if phone and enabled and attrs.get('data-contact-label','').startswith('+'):
        opening=re.sub(r'\sdata-contact-label="[^"]*"','',opening)
        opening+=' data-contact-label="'+escape(config['phoneDisplay'],quote=True)+'"'
    href=('tel:+'+args.number if phone else 'https://wa.me/'+args.number+'?text='+quote(page_message,safe='')) if enabled else '/contacto/#canal'
    opening=re.sub(r'\s(?:href|target|rel)="[^"]*"','',opening)
    opening+=' href="'+escape(href,quote=True)+'"'
    if enabled and not phone:opening+=' target="_blank" rel="noopener noreferrer"'
    text=(config['phoneDisplay'] if phone else attrs.get('data-contact-label','Consultar por WhatsApp')) if enabled else ('Ver canal de contacto' if phone else 'Consultar disponibilidad')
    return '<a'+opening+'>'+escape(text)+'</a>'
for file in ROOT.rglob('*.html'):
    html=file.read_text(encoding='utf-8')
    page_config=json.loads(re.search(r'window\.DENTISTA_CONFIG\s*=\s*(\{[^\n]+?\});',html)[1])
    relative=file.relative_to(ROOT).as_posix()
    route='/' if relative=='index.html' else '/404.html' if relative=='404.html' else '/'+relative.removesuffix('index.html')
    title=PageTitle(html).title.split('|')[0].strip()
    subject=page_config.get('treatment') or ('atención odontológica en '+page_config['city'] if page_config.get('city') else 'atención odontológica')
    page_message=f'Hola, vengo de Dentista.com.py. Me interesa: {subject}. Página consultada: {title}. Página: https://dentista.com.py{route} ¿Podrían confirmar disponibilidad, profesional y ubicación?'
    html=re.sub(r'<a\b([^>]+)>(.*?)</a>',replace,html,flags=re.S)
    html=re.sub(r'(/assets/js/contact-config\.js\?v=)[^"]*',lambda m:m[1]+version,html)
    if '/contacto/' in file.as_posix():
        notice=('<strong>Canal de orientación por WhatsApp: '+escape(config['phoneDisplay'])+'</strong><p>Consultá disponibilidad y confirmá profesional, ubicación y horario antes de reservar.</p>') if enabled else '<strong>El canal de contacto todavía no está habilitado.</strong><p>Podés preparar tu mensaje aquí. No se envía una solicitud ni se confirma una reserva.</p>'
        html=re.sub(r'(<div class="contact-notice"[^>]*>).*?(</div>)',lambda m:m[1]+notice+m[2],html,flags=re.S)
    file.write_text(html,encoding='utf-8')
(ROOT/'assets/js/contact-config.js').write_text('/* Updated by tools/configure-contact.py; no secrets here. */\nwindow.DENTISTA_CONTACT = '+json.dumps(config,ensure_ascii=False,indent=2)+';\n',encoding='utf-8')
print('Contact activated; review the complete diff and run checks before deployment.' if enabled else 'Contact disabled; all static and JS links now show the contact status.')
