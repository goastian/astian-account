#!/bin/sh
set -e   

cd /var/www 

echo "⚙️ Running system configuration..."
php artisan settings:system-start

chown -R www-data:www-data /var/www
chmod -R 775 /var/www
chmod 600 secrets/oauth/*.key

echo "🚀 Starting PHP-FPM..."
php-fpm83 -D

echo "🌐 Starting Nginx..."
nginx -g "daemon off;" &   

echo "🛠️ Starting Supervisor..."
supervisord -c /etc/supervisord.conf &  

echo "✅ All services started"

tail -f /dev/null