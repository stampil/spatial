#!/bin/bash
# Builds in build/htdocs what goes on the hosting: the committed site without the development files, the rules of Apache
# and inc/config.php, written from the DB_HOST, DB_NAME, DB_USER and DB_PASSWORD of the environment.
set -euo pipefail
ROOT=$(cd "$(dirname "$0")/.." && pwd)
SITE="$ROOT/build/htdocs"
DB_PORT=3306
rm -rf "$ROOT/build"
mkdir -p "$SITE"
git -C "$ROOT" archive HEAD | tar -x -C "$SITE"
rm -rf "$SITE/.github" "$SITE/tools" "$SITE/SUIVI.md" "$SITE/Readme.md" "$SITE/.gitignore"
find "$SITE/database" -type f ! -name installation.sql -delete
cat > "$SITE/.htaccess" <<'RULES'
# The sources are only included by index.php, never served; the state of the FTP upload stays on the server.
RewriteEngine On
RewriteRule ^(inc|model|database)(/|$) - [F,L]
<FilesMatch "^\.ftp-deploy-sync-state\.json$">
	Require all denied
</FilesMatch>
RULES
DB_PORT=$DB_PORT php -r 'file_put_contents($argv[1], "<?php\nreturn " . var_export(array("host" => getenv("DB_HOST"), "port" => getenv("DB_PORT"), "name" => getenv("DB_NAME"), "user" => getenv("DB_USER"), "pass" => getenv("DB_PASSWORD")), true) . ";\n");' "$SITE/inc/config.php"
echo "$SITE"
