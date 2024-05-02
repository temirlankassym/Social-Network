FROM php:8.2-fpm-alpine

RUN apk add --no-cache postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . .

RUN composer install --no-plugins --no-scripts --ignore-platform-reqs

CMD php artisan serve --host=0.0.0.0 --port=8000
