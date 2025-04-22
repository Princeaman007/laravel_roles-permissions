#!/bin/bash

echo "⏳ Attente que MySQL soit prêt..."

until nc -z mysql 3306; do
  echo "⏱️  MySQL pas encore prêt, on attend..."
  sleep 2
done

echo "✅ MySQL est prêt !"

# Clear + cache config une fois MySQL dispo
php artisan config:clear
php artisan cache:clear
php artisan config:cache

echo "🔁 Lancement des migrations..."
php artisan migrate --force

echo "🚀 Lancement du serveur Apache"
apache2-foreground
