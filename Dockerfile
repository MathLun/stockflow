FROM php:8.4-fpm

WORKDIR /var/www/html

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    nginx \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        pdo_sqlite \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Composer dependencies
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Application
COPY . .

# SQLite
RUN touch database/database.sqlite

# Laravel migrations
RUN php artisan migrate --force

# Laravel package discovery
RUN php artisan package:discover --ansi

# Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Remove default Nginx configuration
RUN rm -f /etc/nginx/sites-enabled/default

# Laravel writable directories
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

# PHP-FPM
RUN sed -i 's|^listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

EXPOSE 10000

CMD ["sh", "-c", "nginx -t && php-fpm -D && nginx -g 'daemon off;'"]
