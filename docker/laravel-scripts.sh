#!/bin/sh
set -e  # Exit immediately if a command fails

cd /var/www 

echo "🔑 Generating application key..."
php artisan key:generate

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
nginx -g "daemon off;"
echo "Server ran successfully"

php artisan queue:work --tries=6 & 
echo "Starting queue worker..."