#!/bin/sh

# Génère la clé si elle n'existe pas
if [ ! -f /var/www/html/.env ]; then
  cp .env.example .env
  php artisan key:generate
fi

php artisan config:cache
php artisan migrate --force

# Lance le serveur Laravel
php artisan serve --host=0.0.0.0 --port=80
