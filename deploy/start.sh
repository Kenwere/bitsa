#!/bin/sh

cd /var/www/html

# Cache Laravel configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations & seed
php artisan migrate --force
php artisan db:seed --force

# Create storage symlink
php artisan storage:link

# Start PHP-FPM in foreground on TCP port 9000
php-fpm --nodaemonize --fpm-config /usr/local/etc/php-fpm.conf &

# Start Nginx in foreground
/usr/sbin/nginx -g 'daemon off;'
