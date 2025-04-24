FROM php:8.2-fpm

# Installe les extensions nécessaires
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crée le dossier de l'app
WORKDIR /var/www/html

# Copie tous les fichiers
COPY . .

# Installe les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Donne les droits à Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Le script de démarrage
CMD ["./start.sh"]
