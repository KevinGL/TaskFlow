FROM php:8.2-fpm-bullseye

# Extensions PHP
RUN apt-get update && apt-get install -y git unzip libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier tout le projet
COPY . .

# Installer dépendances Symfony avec scripts
RUN composer install --no-dev --optimize-autoloader

# Préparer var et vendor
RUN mkdir -p var/cache var/log var/sessions && chmod -R 777 var vendor

# Exposer le port
EXPOSE 10000

# Démarrer PHP intégré
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
