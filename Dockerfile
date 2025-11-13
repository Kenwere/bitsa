# Use PHP 8.2 FPM image
FROM php:8.2-fpm

# Install system dependencies + PostgreSQL dev library
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip curl libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies WITHOUT scripts (artisan doesn't exist yet)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy project files
COPY . /var/www/html

# Run Laravel post-install commands now that artisan exists
RUN php artisan package:discover --ansi

# Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx config and start script
COPY ./deploy/nginx.conf /etc/nginx/conf.d/default.conf
COPY ./deploy/start.sh /start.sh
RUN chmod +x /start.sh

# Default command to run the app
CMD ["/start.sh"]
