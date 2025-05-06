#!/bin/sh
echo "### Synchronisation des fichiers RSYNC ###"
rsync  -e 'ssh' -S -av ./ habib@172.16.124.15:/var/www/Vousse  --include="public/.htaccess" --include=".env"  --include=".env.prod.local" --include="public/build/manifest.json" --exclude-from=".gitignore" --exclude=".*"
echo "### CONNECTION SSH ###"
ssh habib@172.16.124.15 -o "StrictHostKeyChecking=no" <<'eof'
echo "### Déplacement dans le dossier web ###"
cd /var/www/Vousse
echo "### RENOMMAGE .env.local ###"
mv .env.prod.local .env.local
echo "### COMPOSER INSTALL ###"
composer install

echo "### DOCTRINE MIGRATION ###"
symfony console --no-interaction doctrine:database:create --if-not-exists
symfony console --no-interaction doctrine:migrations:migrate