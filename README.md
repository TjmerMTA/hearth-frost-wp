# Hearth & Frost — WordPress theme + demo

Demo site for a luxury kitchen-appliance repair company (Sub-Zero / Wolf / Cove service).
"Hearth & Frost" and all contacts are placeholders — change them in **Appearance → Customize → Company & contacts**.

- `theme/hearth-frost/` — the WordPress theme.
- `./build.sh` — packs it into `docs/hearth-frost.zip`.
- `docs/` — demo page (GitHub Pages): real WordPress in the browser via WordPress Playground,
  installs `hearth-frost.zip` and opens the home page with the admin bar.

## Move to the client's hosting

1. WordPress → Appearance → Themes → Add New → Upload Theme → `hearth-frost.zip` → Activate.
2. On first run the theme creates everything once: Home, Services (6), About, Service Areas,
   Schedule Service (form), Journal (3 articles), Privacy Policy, Terms of Use, Reviews, menus,
   pretty permalinks.
3. Customize → Company & contacts: name, phone, e-mail, address, hours, region, where requests are e-mailed.
   These values are filled into every page, including the Privacy Policy and Terms of Use.
4. Requests from the form appear under **Requests** in the admin and are e-mailed (configure SMTP on the host).

## Where things are edited

| What | Where |
|---|---|
| Home page texts, numbers, photos, FAQ, areas | Appearance → Customize → Home page |
| Services | Services (admin menu) |
| Reviews | Reviews (admin menu) |
| Legal pages | Pages → Privacy Policy / Terms of Use |
| Menus | Appearance → Menus |

Photos: Unsplash (Unsplash License, free for commercial use) — see `theme/hearth-frost/CREDITS.md`.
The legal texts are realistic drafts; have them reviewed by a California attorney before launch.
