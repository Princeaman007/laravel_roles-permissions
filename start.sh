#!/bin/bash

# Afficher la version de PHP et les informations système
php --version
echo "PORT: $PORT"

# Configurer Apache pour écouter sur le port spécifié par Render
if [ -n "$PORT" ]; then
  echo "Configuration d'Apache pour écouter sur le port $PORT"
  sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
  sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf
  echo "Apache configuré pour le port $PORT"
fi

# Afficher les variables d'environnement pour débogage
echo "Variables d'environnement de base de données:"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"

# Si DB_HOST n'est pas défini correctement, essayer une alternative
if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "127.0.0.1" ]; then
  echo "⚠️ DB_HOST mal configuré, tentative avec le service MySQL"
  export DB_HOST="laravel-mysql"
  echo "DB_HOST modifié: $DB_HOST"
fi

# Attente de MySQL avec timeout - version simplifiée
MAX_TRIES=5
COUNTER=0
echo "⏳ Tentative de connexion à MySQL ($DB_HOST:$DB_PORT)..."

until nc -z $DB_HOST 3306 || [ $COUNTER -eq $MAX_TRIES ]; do
  echo "⏱️ MySQL pas encore accessible... ($COUNTER/$MAX_TRIES)"
  COUNTER=$((COUNTER+1))
  sleep 2
done

# Ignorer les erreurs MySQL pour le déploiement initial
echo "Configuration de Laravel..."
php artisan config:clear
php artisan cache:clear || true

# Ignorer les erreurs de migration pour le déploiement initial
echo "🔁 Tentative de migration de la base de données..."
php artisan migrate --force || echo "⚠️ Migration échouée, mais on continue"

# Lancer Apache en premier plan
echo "🚀 Lancement du serveur Apache sur le port $PORT"
apache2-foreground