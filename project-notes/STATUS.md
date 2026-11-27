Notebook v1 — updated 2026-11-27

Last time: 2026-11-27 client edits — two dark photos replaced with bright ones (Outdoor Kitchen card, "Why Hearth & Frost"); all photos shrunk 5.0 → 2.1 MB; main link is now a static copy (opens in ~1 s), live WordPress moved to /wp/.
Waiting on you: client's reaction to the new photos and speed. (optional) CNAME for a subdomain in GoDaddy → tjmermta.github.io.
Next: after DNS — add docs/CNAME, push. Later: move to client hosting (README.md).

Links: site (fast static copy) — https://tjmermta.github.io/hearth-frost-wp/ ;
live WordPress — …/wp/ ; dashboard — …/?admin or …/wp/?admin (Playground auto-logs in).
Repo: github.com/TjmerMTA/hearth-frost-wp (public, Pages from main:/docs).

Update procedure (theme change):
1. edit theme/ (new photo: .venv/bin/python tools/optimize.py theme/hearth-frost/assets/img)
2. ./build.sh ; bump ?v= in docs/wp/index.html
3. local WP: cd .localwp/wordpress && php -S 127.0.0.1:8797 ; photos changed → php .localwp/refresh-media.php
4. .venv/bin/python tools/export.py http://127.0.0.1:8797 docs /hearth-frost-wp
5. commit, push.
Local admin login in .localwp/admin.txt.
Note: in Playground {{site}} shows the sandbox's internal URL; on real hosting it becomes the real domain.
