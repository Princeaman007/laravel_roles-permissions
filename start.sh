#!/bin/bash

# Afficher la version de PHP et les informations système
php --version
echo "PORT: ${PORT:-8080}"

# Configurer Apache pour écouter sur le port spécifié par Render
echo "Configuration d'Apache pour écouter sur le port ${PORT:-8080}"
sed -i "s/Listen 80/Listen ${PORT:-8080}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-8080}/g" /etc/apache2/sites-available/000-default.conf
echo "Apache configuré pour le port ${PORT:-8080}"

# Afficher les variables d'environnement pour débogage
echo "Variables d'environnement de base de données:"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"

# Ignorer les erreurs MySQL pour le déploiement initial
echo "Configuration de Laravel..."
php artisan config:clear
php artisan cache:clear || true

# Ignorer les erreurs de migration pour le déploiement initial
echo "🔁 Tentative de migration de la base de données..."
php artisan migrate --force || echo "⚠️ Migration échouée, mais on continue"

# Lancer Apache en premier plan
echo "🚀 Lancement du serveur Apache sur le port ${PORT:-8080}"
apache2-foreground