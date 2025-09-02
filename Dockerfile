# Image PHP avec extensions nécessaires
FROM php:8.2-cli

# Installer extensions MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le projet
WORKDIR /var/www/html
COPY . .

# Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader

# Exposer le port que PHP va utiliser
EXPOSE 10000

# Commande pour démarrer le serveur PHP intégré
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
