# Utiliser l'image PHP officielle avec Apache
FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Changer le DocumentRoot vers /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier Composer depuis une image officielle
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Installer les dépendances PHP de Laravel
RUN composer install --no-dev --optimize-autoloader

# Donner les bonnes permissions aux dossiers requis
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/public

# Copier .env.example en .env
COPY .env.example .env

# Générer la clé de l'application
RUN php artisan key:generate

# Exposer le port utilisé par Apache
EXPOSE 80
