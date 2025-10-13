# Laravel Project Deployment Guide

This guide provides step-by-step instructions for deploying the Laravel project to production.

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and NPM
- Database (MySQL/PostgreSQL/SQLite)
- Web server (Apache/Nginx)

## Quick Deployment

### Option 1: Using Composer Scripts

```bash
# For fresh deployment with migrations and seeding
composer run deploy-fresh

# For production deployment (without seeding)
composer run deploy-production

# For optimization only
composer run deploy
```

### Option 2: Using Deployment Scripts

#### Linux/macOS:
```bash
chmod +x deploy.sh
./deploy.sh
```

#### Windows:
```cmd
deploy.bat
```

## Manual Deployment Steps

### 1. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Dependencies Installation

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install and build frontend assets
npm install
npm run build
```

### 3. Database Setup

```bash
# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force
```

### 4. Production Optimization

```bash
# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Create storage link
php artisan storage:link
```

### 5. File Permissions (Linux/macOS)

```bash
# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Web Server Configuration

### Apache (.htaccess)

Ensure the `.htaccess` file in the `public` directory is present and properly configured.

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/project/public;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    
    index index.php;
    
    charset utf-8;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Environment Configuration

### Required Environment Variables

```env
APP_NAME="Your App Name"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Queue Configuration

If your application uses queues, set up queue workers:

```bash
# Start queue worker
php artisan queue:work --daemon

# Or use supervisor for production
```

### Supervisor Configuration

Create `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
stopwaitsecs=3600
```

## Cron Job Setup

Add Laravel scheduler to crontab:

```bash
# Edit crontab
crontab -e

# Add this line
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

## SSL Certificate

### Using Let's Encrypt (Certbot)

```bash
# Install certbot
sudo apt install certbot python3-certbot-apache

# Get certificate
sudo certbot --apache -d your-domain.com
```

## Monitoring and Logs

### Log Files

- Application logs: `storage/logs/laravel.log`
- Web server logs: `/var/log/apache2/` or `/var/log/nginx/`

### Monitoring Commands

```bash
# View real-time logs
tail -f storage/logs/laravel.log

# Check queue status
php artisan queue:monitor

# Clear caches if needed
php artisan cache:clear
php artisan config:clear
```

## Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 755 storage bootstrap/cache
   ```

2. **Cache Issues**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Storage Link Issues**
   ```bash
   php artisan storage:link
   ```

4. **Database Connection Issues**
   - Check database credentials in `.env`
   - Ensure database server is running
   - Verify database exists

### Performance Optimization

```bash
# Run optimization script
./optimize.sh

# Or manually
composer dump-autoload --optimize --no-dev
php artisan optimize
```

## Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use HTTPS with valid SSL certificate
- [ ] Set strong database passwords
- [ ] Configure proper file permissions
- [ ] Enable firewall and security headers
- [ ] Regular security updates
- [ ] Backup strategy in place

## Backup Strategy

```bash
# Database backup
mysqldump -u username -p database_name > backup.sql

# File backup
tar -czf backup.tar.gz /path/to/your/project
```

## Rollback Procedure

```bash
# Restore from backup
mysql -u username -p database_name < backup.sql
tar -xzf backup.tar.gz

# Clear caches
php artisan cache:clear
php artisan config:clear
```

## Support

For deployment issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Web server error logs
3. PHP error logs
4. Database connection status

