"""Local-only static preview; optional synthetic WhatsApp test receiver.
Never serves the parent checkout, docs, leads, credentials or hidden files.
"""
from http.server import SimpleHTTPRequestHandler, ThreadingHTTPServer
from pathlib import Path
from urllib.parse import urlparse, parse_qs
import argparse, io, json, re

ROOT = Path(__file__).resolve().parents[1] / 'site'
parser=argparse.ArgumentParser()
parser.add_argument('--port', type=int, default=8097)
parser.add_argument('--test-contact', action='store_true')
args=parser.parse_args()

class Handler(SimpleHTTPRequestHandler):
    def __init__(self,*a,**kw): super().__init__(*a,directory=str(ROOT),**kw)
    def log_message(self,*a): pass
    def response(self, content, status=200, mime='text/html; charset=utf-8'):
        data=content.encode('utf-8') if isinstance(content,str) else content
        self.send_response(status); self.send_header('Content-Type',mime)
        self.send_header('Content-Length',str(len(data)))
        self.send_header('Cache-Control','no-store'); self.end_headers()
        return io.BytesIO(data)
    def send_head(self):
        path=urlparse(self.path).path
        if args.test_contact and path.startswith('/__mock_whatsapp/'):
            return self.response('<!doctype html><html lang="es"><title>Receptor de prueba local</title><h1>Receptor de prueba local</h1><p>No se envió ningún mensaje externo.</p><pre>'+ __import__('html').escape(json.dumps(parse_qs(urlparse(self.path).query),ensure_ascii=False))+'</pre></html>')
        if args.test_contact and path == '/assets/js/contact-config.js':
            return self.response('window.DENTISTA_CONTACT = {enabled:true,ownerConfirmed:true,whatsappNumber:"595981234567",phoneDisplay:"Número sintético de prueba"};',mime='application/javascript')
        if args.test_contact and path == '/assets/js/contact.js':
            return self.response((ROOT/'assets/js/contact.js').read_text(encoding='utf-8').replace('https://wa.me/','/__mock_whatsapp/'),mime='application/javascript')
        target=Path(self.translate_path(self.path)).resolve()
        if not target.is_relative_to(ROOT.resolve()) or any(p.startswith('.') for p in Path(path).parts):
            return self.response('Acceso denegado',403)
        if target.is_dir() and not (target/'index.html').is_file():
            return self.response('Acceso denegado',403)
        if not target.exists(): return self.response((ROOT/'404.html').read_bytes(),404)
        return super().send_head()

print(f'Preview http://127.0.0.1:{args.port}/; synthetic receiver: {args.test_contact}',flush=True)
ThreadingHTTPServer(('127.0.0.1',args.port),Handler).serve_forever()
