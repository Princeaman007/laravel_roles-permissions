#!/bin/bash

# Appliquer les variables d'environnement de Render à Laravel
if [ ! -z "$APP_KEY" ]; then
  echo "Using APP_KEY from environment variables..."
  sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|g" .env
fi

# Configuration de la base de données
if [ ! -z "$DB_HOST" ]; then
  sed -i "s|DB_HOST=.*|DB_HOST=$DB_HOST|g" .env
  sed -i "s|DB_PORT=.*|DB_PORT=$DB_PORT|g" .env
  sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|g" .env
  sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|g" .env
  sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|g" .env
fi

# D'autres variables d'environnement peuvent être ajoutées de la même façon

# Effacer les caches précédents
php artisan config:clear
php artisan cache:clear

# Génère les caches pour la production APRÈS avoir configuré les variables d'environnement
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrations (optionnel, à désactiver si vous ne voulez pas les exécuter à chaque démarrage)
php artisan migrate --force || echo "Migration failed, but continuing..."

# Créer le lien symbolique pour le stockage
php artisan storage:link || echo "Storage link already exists"

# Lancer Apache en premier plan
apache2-foreground