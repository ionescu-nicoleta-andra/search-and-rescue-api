# docker/php/Dockerfile
FROM php:8.2-fpm

# Arguments for UID/GID (optional)
ARG user=www-data
ARG uid=1000

# Install system dependencies
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libxml2-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Install extensions (if needed)
# pecl install xdebug && docker-php-ext-enable xdebug

# Create application directory
WORKDIR /var/www/html

# Copy composer files & install dependencies (cache layer)
COPY composer.json composer.lock /var/www/html/
RUN composer install --no-dev --prefer-dist --no-scripts --no-interaction --optimize-autoloader

# Copy rest of app
COPY . /var/www/html

# Set permissions
RUN chown -R ${user}:${user} /var/www/html/storage /var/www/html/bootstrap/cache

# Expose php-fpm
EXPOSE 9000

CMD ["php-fpm"]
