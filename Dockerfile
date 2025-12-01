FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash git curl icu-dev libzip-dev libpq-dev zlib-dev \
    && docker-php-ext-install intl pdo pdo_pgsql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && php artisan key:generate --force \
    || true

CMD ["php-fpm"]


