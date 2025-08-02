#!/bin/sh
set -e   

cd /var/www 

echo "⚙️ Running system configuration..."

cp /root/.env /var/www/.env

chown -R www-data:www-data .

find . -type d -exec chmod 750 {} \;
find . -type f -exec chmod 640 {} \;

chmod -R 770 storage
chmod -R 770 bootstrap/cache
chmod 400 .env
chmod 600 secrets/oauth/*.key

php artisan settings:system-start

echo "🚀 Starting PHP-FPM..."
php-fpm83 -D

echo "🌐 Starting Nginx..."
nginx -g "daemon off;" &   

echo "🛠️ Starting Supervisor..."
supervisord -c /etc/supervisord.conf &  

echo "✅ All services started"

tail -f /dev/null