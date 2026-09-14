FROM php:8.4-cli-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
        nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath pcntl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN cp .env.example .env \
    && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && php artisan key:generate \
    && npm ci \
    && npm run build \
    && rm -rf node_modules

ENV PORT=8000
EXPOSE 8000

CMD php artisan pbm:provision && php artisan serve --host=0.0.0.0 --port=${PORT}
