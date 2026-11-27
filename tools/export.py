#!/usr/bin/env python3
"""Static copy of the local WordPress (fast demo on GitHub Pages; the live WordPress stays at docs/wp/).

Usage: export.py <local_url> <docs_dir> <base_path>
  e.g. export.py http://127.0.0.1:8797 docs /hearth-frost-wp

Crawls every page reachable from the home page, saves pages as <path>/index.html and the theme/WordPress
files they use, rewrites the local address to <base_path>, drops ?ver= from file links, and switches the
request form to its demo answer (no server on GitHub Pages). Keeps docs/wp/, docs/hearth-frost.zip, docs/CNAME.
"""
import os, re, sys, shutil, urllib.request, urllib.error, urllib.parse, json

LOCAL, DOCS, BASE = sys.argv[1].rstrip("/"), sys.argv[2], sys.argv[3].rstrip("/")
KEEP = {"wp", "hearth-frost.zip", "CNAME", ".nojekyll"}
SKIP = re.compile(r"^/(wp-admin|wp-login|wp-json|xmlrpc|wp-cron|feed|comments/feed|wp-sitemap)|/feed/?$|/embed/?$")
DEMO_MSG = "Thank you. Your request has been received — we will call you shortly to confirm the appointment."


def get(path):
    req = urllib.request.Request(LOCAL + path, headers={"User-Agent": "hf-export"})
    try:
        with urllib.request.urlopen(req, timeout=60) as r:
            return r.status, r.headers.get("Content-Type", ""), r.read()
    except urllib.error.HTTPError as e:
        return e.code, e.headers.get("Content-Type", ""), e.read()


def local_refs(text):
    """Local paths referenced from HTML/CSS (absolute local URLs and root-relative paths)."""
    host = re.escape(LOCAL)
    out = set()
    for m in re.finditer(host + r"(/[^\s\"'<>)\\]*)", text):
        out.add(m.group(1))
    for m in re.finditer(r"""(?:href|src)=["'](/[^/"'][^"']*)["']""", text):
        out.add(m.group(1))
    return out


def clean(p):
    p = urllib.parse.urldefrag(p)[0]
    path, _, query = p.partition("?")
    return path, query


def out_file(path, is_page):
    rel = urllib.parse.unquote(path).lstrip("/")
    if is_page:
        return os.path.join(DOCS, rel, "index.html")
    return os.path.join(DOCS, rel)


def rewrite(text):
    text = re.sub("(" + re.escape(LOCAL) + r"/[^\s\"'<>)?]*)\?ver=[^\s\"'<>)&]*", r"\1", text)
    text = text.replace(LOCAL, BASE).replace(LOCAL.replace("/", "\\/"), BASE.replace("/", "\\/"))
    return text


def main():
    for name in os.listdir(DOCS):
        if name in KEEP:
            continue
        p = os.path.join(DOCS, name)
        shutil.rmtree(p) if os.path.isdir(p) else os.remove(p)

    pages, files, seen = ["/"], [], set()
    while pages:
        path = pages.pop(0)
        if path in seen:
            continue
        seen.add(path)
        code, ctype, body = get(path)
        if code != 200 or "text/html" not in ctype:
            print("skip", code, path)
            continue
        html = body.decode("utf-8")
        for ref in local_refs(html):
            rpath, query = clean(ref)
            if SKIP.search(rpath) or (query and not rpath.startswith(("/wp-content/", "/wp-includes/"))):
                continue
            if rpath.startswith(("/wp-content/", "/wp-includes/")):
                if rpath not in files:
                    files.append(rpath)
            elif rpath.endswith("/") and rpath not in seen:
                pages.append(rpath)
        # Head links to server-only endpoints (oEmbed, REST API, feeds, XML-RPC) do not exist in a static copy.
        html = re.sub(r"<link[^>]+(?:oembed|wp-json|api\.w\.org|xmlrpc|/feed/|EditURI|shortlink)[^>]*>\n?", "", html)
        html = rewrite(html)
        html = html.replace('var HF = {', 'var HF = {"demo":' + json.dumps(DEMO_MSG) + ',', 1)
        if path == "/":
            # Old links ".../?admin" and "?page=" open the live WordPress.
            html = html.replace("<head>", "<head>\n<script>if(/[?&](admin|page)(=|&|$)/.test(location.search))location.replace('wp/'+location.search)</script>", 1)
        f = out_file(path, True)
        os.makedirs(os.path.dirname(f), exist_ok=True)
        open(f, "w", encoding="utf-8").write(html)
        print("page", path)

    i = 0
    while i < len(files):
        rpath = files[i]; i += 1
        code, ctype, body = get(rpath)
        if code != 200:
            print("MISSING", code, rpath)
            continue
        if rpath.endswith(".css"):
            css = body.decode("utf-8")
            for m in re.finditer(r"url\(\s*['\"]?([^'\")]+)['\"]?\s*\)", css):
                u = m.group(1)
                if u.startswith(("data:", "http:", "https:", "//")):
                    continue
                dep = clean(urllib.parse.urljoin(rpath, u))[0]
                if dep not in files:
                    files.append(dep)
            body = rewrite(css).encode("utf-8")
        f = out_file(rpath, False)
        os.makedirs(os.path.dirname(f), exist_ok=True)
        open(f, "wb").write(body)

    code, _, body = get("/this-page-does-not-exist-hf/")
    open(os.path.join(DOCS, "404.html"), "w", encoding="utf-8").write(rewrite(body.decode("utf-8")))
    open(os.path.join(DOCS, ".nojekyll"), "w").close()
    print(f"{len(seen)} pages, {len(files)} files, 404.html")


main()
