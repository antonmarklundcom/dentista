"""Integration test of owner confirmation, static fallbacks and disable/reset."""
from pathlib import Path
import tempfile, shutil, subprocess, sys, hashlib
repo=Path(__file__).resolve().parents[1]
original=hashlib.sha256((repo/'site/assets/js/contact-config.js').read_bytes()).hexdigest()
with tempfile.TemporaryDirectory(prefix='dentista-contact-test-') as folder:
    scratch=Path(folder).resolve()
    assert scratch.is_relative_to(Path(tempfile.gettempdir()).resolve())
    shutil.copytree(repo/'site',scratch/'site')
    (scratch/'tools').mkdir()
    shutil.copyfile(repo/'tools/configure-contact.py',scratch/'tools/configure-contact.py')
    script=str(scratch/'tools/configure-contact.py')
    rejected=subprocess.run([sys.executable,script,'--number','595981234567'],capture_output=True,text=True)
    assert rejected.returncode!=0, 'Unconfirmed activation must fail'
    subprocess.run([sys.executable,script,'--number','595981234567','--display','Test only','--owner-confirmed'],check=True)
    for file in (scratch/'site').rglob('*.html'):
        html=file.read_text(encoding='utf-8')
        assert 'href="https://wa.me/595995628862' not in html
        assert 'href="tel:+595995628862' not in html
        assert 'href="https://wa.me/595981234567' in html
    subprocess.run([sys.executable,script,'--disable'],check=True)
    for file in (scratch/'site').rglob('*.html'):
        html=file.read_text(encoding='utf-8')
        assert 'href="https://wa.me/' not in html
        assert 'href="tel:' not in html
assert hashlib.sha256((repo/'site/assets/js/contact-config.js').read_bytes()).hexdigest()==original
print('PASS: confirmation gate, all static CTA destinations, reset, production config unchanged.')
