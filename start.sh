#!/bin/bash

# Configurer Apache pour écouter sur le port spécifié par Render
if [ -n "$PORT" ]; then
  sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
  sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf
fi

# Attente de MySQL avec timeout
MAX_TRIES=30
COUNTER=0
echo "⏳ Attente que MySQL soit prêt..."

until nc -z $DB_HOST 3306 || [ $COUNTER -eq $MAX_TRIES ]; do
  echo "⏱️ MySQL pas encore prêt, on attend... ($COUNTER/$MAX_TRIES)"
  COUNTER=$((COUNTER+1))
  sleep 5
done

if [ $COUNTER -eq $MAX_TRIES ]; then
  echo "❌ Impossible de se connecter à MySQL après $MAX_TRIES tentatives. Vérifiez la configuration de votre base de données."
  echo "DB_HOST: $DB_HOST"
  echo "DB_PORT: $DB_PORT"
  echo "Tentative de continuer quand même..."
else
  echo "✅ MySQL est prêt !"
fi

# Clear + cache config une fois MySQL dispo
php artisan config:clear
php artisan cache:clear
php artisan config:cache

echo "🔁 Lancement des migrations..."
php artisan migrate --force

echo "🚀 Lancement du serveur Apache"
apache2-foreground