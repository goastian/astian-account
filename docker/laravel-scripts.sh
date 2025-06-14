#!/bin/sh
set -e   

cd /var/www 

echo "🔑 Generating application key..."
php artisan settings:key-generator

echo "🔄 Running database migrations..."
php artisan migrate --force
echo "✅ Migrations completed successfully"

echo "📦 Installing Node.js dependencies..."
npm install

echo "⚡ Building frontend assets..."
npm run production
echo "✅ Frontend build completed"

echo "⚙️ Running system configuration..."
php artisan settings:system-start

echo "🚀 Starting PHP-FPM..."
php-fpm83 -D

echo "🌐 Starting Nginx..."
nginx -g "daemon off;" &   

echo "🛠️ Starting Supervisor..."
supervisord -c /etc/supervisord.conf &  

echo "✅ All services started"

tail -f /dev/null