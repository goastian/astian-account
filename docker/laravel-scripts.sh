#!/bin/sh
set -e   

cd /var/www 

echo "⚙️ Running system configuration..."
php artisan settings:system-start

# chown -R www-data:www-data /var/www
# chmod -R 775 /var/www

# File Permissions
find /var/www -type f -exec chmod 664 '{}' \+

# Directory Permissions
find /var/www -type d -exec chmod 775 '{}' \+

chmod -R 444 /var/www/.env
chmod 600 secrets/oauth/*.key

echo "🚀 Starting PHP-FPM..."
php-fpm83 -D

echo "🌐 Starting Nginx..."
nginx -g "daemon off;" &   

echo "🛠️ Starting Supervisor..."
supervisord -c /etc/supervisord.conf &  

echo "✅ All services started"

tail -f /dev/null
