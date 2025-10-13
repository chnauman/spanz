@echo off
REM Laravel Production Optimization Script for Windows
REM This script optimizes a Laravel application for production

echo ⚡ Starting Laravel optimization process...

REM Clear all caches first
echo 🧹 Clearing existing caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

REM Optimize Composer autoloader
echo 📦 Optimizing Composer autoloader...
composer dump-autoload --optimize --no-dev

REM Cache configurations
echo ⚙️ Caching configurations...
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

REM Optimize for production
echo 🚀 Applying production optimizations...
php artisan optimize

REM Clear and rebuild frontend assets
echo 🎨 Rebuilding frontend assets...
npm run build

echo ✅ Optimization completed successfully!
echo.
echo 📊 Performance improvements applied:
echo - Composer autoloader optimized
echo - Configuration cached
echo - Routes cached
echo - Views cached
echo - Events cached
echo - Frontend assets optimized

pause

