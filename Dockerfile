FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:cache || true

EXPOSE 8000

RUN echo "upload_max_filesize=100M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/uploads.ini
    
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}