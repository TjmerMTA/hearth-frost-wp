#!/usr/bin/env python3
"""Local preview server for docs/ with CORS (Playground fetches the theme zip cross-origin).
Usage: serve.py <docs_dir> <port>"""
import sys, http.server, functools

class H(http.server.SimpleHTTPRequestHandler):
    def end_headers(self):
        self.send_header("Access-Control-Allow-Origin", "*")
        self.send_header("Cache-Control", "no-store")
        super().end_headers()

d, port = sys.argv[1], int(sys.argv[2])
http.server.ThreadingHTTPServer(("127.0.0.1", port), functools.partial(H, directory=d)).serve_forever()
