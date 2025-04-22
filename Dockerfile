# Utiliser l'image PHP officielle avec Apache
FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Activer le module Apache mod_rewrite
RUN a2enmod rewrite

# Copier les fichiers du projet
COPY . /var/www/html

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Donner les permissions appropriées
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copier le fichier .env.example en .env
COPY .env.example .env

# Générer la clé de l'application
RUN php artisan key:generate

# Exposer le port 80
EXPOSE 80
