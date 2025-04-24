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

# Installe les dépendances Laravel (prod uniquement)
RUN composer install --no-dev --optimize-autoloader

# Donne les bons droits à Laravel (cache + storage)
RUN chown -R www-data:www-data storage bootstrap/cache public

# Copie la configuration Apache pour Laravel
COPY ./apache/laravel.conf /etc/apache2/sites-available/000-default.conf



# Lancement d'Apache en mode foreground
CMD ["apache2-foreground"]
