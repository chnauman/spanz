#!/bin/bash

# Laravel Production Optimization Script
# This script optimizes a Laravel application for production

set -e

echo "⚡ Starting Laravel optimization process..."

# Clear all caches first
echo "🧹 Clearing existing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Optimize Composer autoloader
echo "📦 Optimizing Composer autoloader..."
composer dump-autoload --optimize --no-dev

# Cache configurations
echo "⚙️ Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimize for production
echo "🚀 Applying production optimizations..."
php artisan optimize

# Clear and rebuild frontend assets
echo "🎨 Rebuilding frontend assets..."
npm run build

# Set proper permissions
echo "🔒 Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Optimization completed successfully!"
echo ""
echo "📊 Performance improvements applied:"
echo "- Composer autoloader optimized"
echo "- Configuration cached"
echo "- Routes cached"
echo "- Views cached"
echo "- Events cached"
echo "- Frontend assets optimized"
echo "- File permissions set"

