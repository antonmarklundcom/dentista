"""Package only the reviewed static site; never archives the whole checkout."""
from pathlib import Path
import argparse, zipfile, hashlib, json
ROOT=Path(__file__).resolve().parents[1]/'site'
parser=argparse.ArgumentParser();parser.add_argument('--output',required=True);args=parser.parse_args()
dest=Path(args.output).resolve()
if dest.is_relative_to(ROOT.resolve()):parser.error('Output must stay outside the public site directory.')
allowed={'.html','.css','.js','.svg','.webp','.jpg','.xml','.txt'}
files=sorted(p for p in ROOT.rglob('*') if p.is_file())
for p in files:
    if p.name!='.htaccess' and p.suffix not in allowed:parser.error('Unexpected file in publish directory: '+str(p))
dest.parent.mkdir(parents=True,exist_ok=True)
with zipfile.ZipFile(dest,'w',zipfile.ZIP_DEFLATED) as archive:
    for p in files:archive.write(p,p.relative_to(ROOT).as_posix())
print(json.dumps({'file':str(dest),'files':len(files),'bytes':dest.stat().st_size,'sha256':hashlib.sha256(dest.read_bytes()).hexdigest()},indent=2))
