#!/bin/sh

# Navigate to project root
cd /var/www/html

# Run Laravel artisan commands
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (DB must be reachable at runtime)
php artisan migrate --force

# Optional: seed database
php artisan db:seed --force

# Create storage symlink
php artisan storage:link

# Start PHP-FPM in the background
php-fpm -D

# Start Nginx in the foreground
nginx -g 'daemon off;'
