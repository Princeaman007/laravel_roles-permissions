#!/bin/bash

# 📁 Créer les répertoires nécessaires pour Laravel
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# 🔐 S'assurer que les permissions sont correctes
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 🧠 Afficher la version de PHP et les infos
php --version
echo "PORT: ${PORT:-8080}"

# ⚙️ Configurer Apache pour écouter sur le bon port
echo "Configuration d'Apache pour le port ${PORT:-8080}"
sed -i "s/Listen 80/Listen ${PORT:-8080}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-8080}/g" /etc/apache2/sites-available/000-default.conf
echo "Apache configuré pour le port ${PORT:-8080}"

# 🧪 Debug rapide : montrer les variables de base de données
echo "Variables d'environnement DB :"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"

# ⚙️ Laravel - configuration & cache
echo "⚙️ Configuration de Laravel..."
php artisan config:clear
php artisan cache:clear || true

# 🔁 Migrations (on continue même si ça échoue)
echo "🔁 Tentative de migration de la base de données..."
php artisan migrate --force || echo "⚠️ Migration échouée, mais on continue"

# ✅ Créer le lien symbolique pour accéder aux images
php artisan storage:link || echo "📁 Le lien de stockage existe déjà"

# 🚀 Lancer Apache
echo "🚀 Lancement du serveur Apache sur le port ${PORT:-8080}"
apache2-foreground
