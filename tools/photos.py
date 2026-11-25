#!/usr/bin/env python3
"""Download Unsplash photos (Unsplash License: free commercial use) by id.

  photos.py thumbs <out_dir> <id> [<id> ...]       -> 480px previews + contact sheet
  photos.py final  <out_dir> <name>=<id> [...]     -> 2000px JPEG (q=78) named <name>.jpg
"""
import sys, os, urllib.request, io

def fetch(pid, w, q):
    url = f"https://images.unsplash.com/{pid}?w={w}&q={q}&fm=jpg&fit=max"
    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
    with urllib.request.urlopen(req, timeout=60) as r:
        return r.read()

def thumbs(out, ids):
    from PIL import Image, ImageDraw
    os.makedirs(out, exist_ok=True)
    tiles = []
    for pid in ids:
        try:
            data = fetch(pid, 480, 70)
        except Exception as e:
            print("FAIL", pid, e); continue
        im = Image.open(io.BytesIO(data)).convert("RGB")
        im.thumbnail((480, 320))
        tiles.append((pid, im))
    cols = 4
    W, H = 490, 350
    rows = (len(tiles) + cols - 1) // cols
    sheet = Image.new("RGB", (cols * W, rows * H), "white")
    d = ImageDraw.Draw(sheet)
    for i, (pid, im) in enumerate(tiles):
        x, y = (i % cols) * W, (i // cols) * H
        sheet.paste(im, (x + 5, y + 5))
        d.text((x + 8, y + 330), f"{i}: {pid[6:]}", fill="black")
    path = os.path.join(out, "sheet.jpg")
    sheet.save(path, quality=80)
    for i, (pid, _) in enumerate(tiles):
        print(i, pid)
    print(path)

def final(out, pairs):
    os.makedirs(out, exist_ok=True)
    for p in pairs:
        name, pid = p.split("=", 1)
        data = fetch(pid, 1800, 72)
        path = os.path.join(out, name + ".jpg")
        with open(path, "wb") as f:
            f.write(data)
        print(name, pid, len(data) // 1024, "KB")

if __name__ == "__main__":
    mode, out, rest = sys.argv[1], sys.argv[2], sys.argv[3:]
    thumbs(out, rest) if mode == "thumbs" else final(out, rest)
