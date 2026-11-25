Notebook v1 — updated 2026-11-25

Last time: theme built and checked locally (all 10 pages 200, form saves to Requests, mobile ok).
Waiting on you: create the public GitHub repo + Pages (auto-protection blocks me), then CNAME for the subdomain in GoDaddy.
Next: after Pages is live — check the Playground demo in Chrome; add docs/CNAME after DNS.

Local preview: php -S 127.0.0.1:8797 in .localwp/wordpress (admin login in .localwp/admin.txt).
Publish commands (person runs):
  gh repo create TjmerMTA/hearth-frost-wp --public --source /Users/nazar/appliance-wp --push
  gh api -X POST repos/TjmerMTA/hearth-frost-wp/pages -f "source[branch]=main" -f "source[path]=/docs"
Update: edit theme → ./build.sh → bump ?v= in docs/index.html → commit → push.
