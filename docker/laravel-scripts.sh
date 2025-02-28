#!/bin/sh

cd /var/www

composer install --no-dev --optimize-autoloader

php artisan key:generate

echo "Running migrations"
php artisan migrate --force
echo "Migration ran successfully" 

echo "Running nodejs"
npm install
npm run production
echo "Nodejs ran successfully"

chown -R www-data:www-data /var/www && chmod -R 775 /var/www

php-fpm83 -D

nginx -g "daemon off;"

php artisan settings:system-start

php artisan queue:work --tries=6 &

echo "Server ran successfully"
