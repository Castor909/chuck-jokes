FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libonig-dev libxml2-dev zlib1g-dev \
    libsqlite3-dev pkg-config \
 && docker-php-ext-install pdo pdo_sqlite intl mbstring xml zip \
 && rm -rf /var/lib/apt/lists/*

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# копируем только composer-файлы для кэширования слоя (опционально)
COPY composer.json composer.lock* /var/www/html/
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress || true

# копируем приложение
COPY . /var/www/html

# права
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 /var/www/html/tmp /var/www/html/logs || true

EXPOSE 9000
CMD ["php-fpm"]