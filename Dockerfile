FROM php:8.2-cli

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier composer files et installer dépendances
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copier le reste du code
COPY . .

# Exposer le port PHP intégré
EXPOSE 10000

# Start command PHP intégré
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
