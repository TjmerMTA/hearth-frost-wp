#!/bin/sh
# Pack the theme into docs/hearth-frost.zip (the demo page installs it into WordPress from there).
set -e
cd "$(dirname "$0")/theme"
rm -f ../docs/hearth-frost.zip
find hearth-frost -name .DS_Store -delete
zip -qr -X ../docs/hearth-frost.zip hearth-frost
echo "docs/hearth-frost.zip: $(du -h ../docs/hearth-frost.zip | cut -f1)"
