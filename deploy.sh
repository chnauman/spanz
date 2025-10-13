#!/bin/bash

# Laravel Deployment Script
# This script prepares a Laravel application for production deployment

set -e

echo "🚀 Starting Laravel deployment process..."

# Check if .env file exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.example..."
    cp .env.example .env
else
    echo "✅ .env file already exists"
fi

# Install/Update Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install/Update NPM dependencies and build assets
echo "🎨 Building frontend assets..."
npm install
npm run build

# Generate application key if not set
echo "🔑 Generating application key..."
php artisan key:generate --force

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Clear and cache configuration
echo "⚡ Optimizing application for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Set proper permissions
echo "🔒 Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Configure your web server (Apache/Nginx) to point to the 'public' directory"
echo "2. Set up SSL certificate"
echo "3. Configure your database connection in .env"
echo "4. Set up queue workers if using queues"
echo "5. Configure cron job for Laravel scheduler"
echo ""
echo "🔧 Useful commands:"
echo "- View logs: tail -f storage/logs/laravel.log"
echo "- Clear cache: php artisan cache:clear"
echo "- Restart queue: php artisan queue:restart"

