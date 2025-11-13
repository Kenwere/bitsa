# Use PHP 8.2 FPM image
FROM php:8.2-fpm

# Install system dependencies + PostgreSQL dev library + Nginx
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip curl libpq-dev \
    nginx \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files to leverage Docker caching
COPY composer.json composer.lock ./

# Install PHP dependencies (skip scripts for now, artisan does not exist yet)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Install Doctrine DBAL for migrations
RUN composer require doctrine/dbal --with-all-dependencies --no-interaction

# Copy the rest of the project files
COPY . .

# Run package discovery now that artisan exists
RUN php artisan package:discover --ansi

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx config and start script
COPY ./deploy/nginx.conf /etc/nginx/conf.d/default.conf
COPY ./deploy/start.sh /start.sh
RUN chmod +x /start.sh

# Expose port 80 for Nginx
EXPOSE 80

# Default command
CMD ["/start.sh"]
