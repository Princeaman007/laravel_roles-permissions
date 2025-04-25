#!/bin/bash

# Appliquer les variables d'environnement de Render à Laravel
if [ ! -z "$APP_KEY" ]; then
  echo "Using APP_KEY from environment variables..."
  sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|g" .env
fi

# Activer le mode débogage pour voir les erreurs
sed -i "s|APP_DEBUG=.*|APP_DEBUG=true|g" .env

# Configuration de la base de données
if [ ! -z "$DB_HOST" ]; then
  sed -i "s|DB_HOST=.*|DB_HOST=$DB_HOST|g" .env
  sed -i "s|DB_PORT=.*|DB_PORT=$DB_PORT|g" .env
  sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|g" .env
  sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|g" .env
  sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|g" .env
fi

# Configurer correctement Apache pour utiliser le port de Render
if [ ! -z "$PORT" ]; then
  echo "Configuring Apache to use PORT: $PORT"
  
  # Configuration du port dans 000-default.conf
  sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/000-default.conf
  
  # Configuration dans ports.conf
  sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
fi

# Effacer les caches
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Ne pas générer de caches en mode débogage
# Les lignes suivantes sont commentées
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Migrations (optionnel)
php artisan migrate --force || echo "Migration failed, but continuing..."

# Créer le lien symbolique pour le stockage
php artisan storage:link || echo "Storage link already exists"

# Vérifier les droits sur storage et bootstrap/cache
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Afficher la configuration pour le débogage
echo "Current environment configuration:"
php artisan env

# Vérifier les logs avant le démarrage
tail -n 20 storage/logs/laravel.log 2>/dev/null || echo "No Laravel logs found yet"

# Lancer Apache en premier plan
echo "Starting Apache..."
apache2-foreground