FROM composer:2 AS composer

FROM php:8.3-fpm-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends git libicu-dev libzip-dev unzip \
    && docker-php-ext-install -j$(nproc) bcmath intl pcntl pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]
