FROM php:8.2-apache

# Active mod_rewrite pour les routes Laravel
RUN a2enmod rewrite

# Installe les extensions nécessaires
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Dossier de l'application
WORKDIR /var/www/html

# Copie des fichiers
COPY . .

# Installe les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Droits Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Copie la config Apache pour Laravel
COPY ./apache/laravel.conf /etc/apache2/sites-available/000-default.conf

# Lancement Apache
CMD ["apache2-foreground"]
