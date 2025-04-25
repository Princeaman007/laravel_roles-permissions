FROM php:8.2-apache

# Active mod_rewrite pour Laravel
RUN a2enmod rewrite

# Installe les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définit le dossier de travail
WORKDIR /var/www/html

# Copie tous les fichiers du projet Laravel dans le container
COPY . .

# Créer un fichier .env approprié pour la production
COPY .env.example .env

# Installe les dépendances Laravel (prod uniquement)
RUN composer install --no-dev --optimize-autoloader

# Ne pas générer de clé ici, Render s'en chargera
# SUPPRIMÉ: RUN php artisan key:generate --force

# Donne les bons droits à Laravel (cache + storage)
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 storage bootstrap/cache

# Préparer le script d'entrée
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Copie la configuration Apache pour Laravel
COPY ./apache/laravel.conf /etc/apache2/sites-available/000-default.conf

# Pour supporter le port dynamique de Render
ENV PORT=10000
RUN sed -i "s/80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf
RUN sed -i "s/80/:${PORT}/g" /etc/apache2/ports.conf

# Utiliser un script d'entrée plutôt que de lancer directement Apache
ENTRYPOINT ["/entrypoint.sh"]