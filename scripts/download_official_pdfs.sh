#!/usr/bin/env bash
set -euo pipefail
ROOT="${1:-./php_starter/public_html/public/forms}"
MANIFEST="${2:-./data/official_resources_top25.json}"
mkdir -p "$ROOT"
python3 - "$ROOT" "$MANIFEST" <<'PY'
import json, os, re, subprocess, sys
root, manifest = sys.argv[1], sys.argv[2]
items=json.load(open(manifest, encoding='utf-8'))
for r in items:
    if r.get('resource_status')!='downloadable_official_pdf':
        continue
    url=r.get('url','')
    if not url: continue
    country=r.get('slug') or re.sub(r'[^a-z0-9]+','-',r.get('country','').lower()).strip('-')
    title=re.sub(r'[^a-zA-Z0-9._-]+','-',r.get('title','resource').lower()).strip('-')[:90]
    if not title.endswith('.pdf'): title += '.pdf'
    outdir=os.path.join(root,country); os.makedirs(outdir, exist_ok=True)
    out=os.path.join(outdir,title)
    print('download', url, '->', out)
    subprocess.run(['curl','-L','--fail','--retry','3','--connect-timeout','15','-o',out,url], check=False)
PY
