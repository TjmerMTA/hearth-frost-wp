#!/usr/bin/env python3
"""Shrink theme photos for fast loading: landscape <=1600px wide, portrait <=1100px wide, JPEG q70 progressive.
Usage: optimize.py <img_dir>   (re-run safe: only shrinks, never enlarges)"""
import sys, glob, os
from PIL import Image
for f in sorted(glob.glob(os.path.join(sys.argv[1], "*.jpg"))):
    before = os.path.getsize(f)
    im = Image.open(f).convert("RGB")
    w, h = im.size
    maxw = 1100 if h > w else 1600
    if w > maxw:
        im = im.resize((maxw, round(h * maxw / w)), Image.LANCZOS)
    im.save(f + ".tmp", "JPEG", quality=70, optimize=True, progressive=True)
    after = os.path.getsize(f + ".tmp")
    if after < before:
        os.replace(f + ".tmp", f)
    else:
        os.remove(f + ".tmp"); after = before
    print(f"{os.path.basename(f):22} {before//1024:5} KB -> {after//1024:5} KB  {im.size}")
