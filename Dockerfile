# Base PHP avec FPM
FROM php:8.2-fpm-bullseye

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier composer.json et composer.lock et installer les dépendances
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copier le reste du code
COPY . .

# Exposer le port pour le serveur PHP intégré
EXPOSE 10000

# Start command : serveur PHP intégré
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
