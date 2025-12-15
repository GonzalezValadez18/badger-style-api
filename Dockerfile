FROM php:8.2-apache

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Apache rewrite
RUN a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Código
WORKDIR /var/www/html
COPY . .

# Permisos Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Apache debe servir /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-enabled/000-default.conf

# Puerto Render
EXPOSE 10000
RUN sed -i 's/80/10000/g' \
    /etc/apache2/ports.conf \
    /etc/apache2/sites-enabled/000-default.conf

CMD ["apache2-foreground"]
