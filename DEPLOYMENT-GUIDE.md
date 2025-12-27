# Deployment Guide - Cargo Depot Container Management System

## Pre-Deployment Checklist

### Environment Requirements
- [ ] PHP 8.1 or higher installed
- [ ] Composer installed
- [ ] Web server (Apache/Nginx) configured
- [ ] Database server (MySQL/PostgreSQL) running
- [ ] SSL certificate for HTTPS (recommended)
- [ ] Git installed for version control

### Server Configuration
- [ ] PHP extensions enabled:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
- [ ] Appropriate file permissions set
- [ ] Environment variables configured
- [ ] Firewall rules configured

## Deployment Steps

### 1. Server Setup

#### For Ubuntu/Debian
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3 and required extensions
sudo apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql \
    php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip \
    php8.3-gd php8.3-bcmath php8.3-sqlite3

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Nginx (or Apache)
sudo apt install -y nginx

# Install MySQL
sudo apt install -y mysql-server
```

### 2. Clone Repository
```bash
# Create application directory
sudo mkdir -p /var/www/cargodepot
cd /var/www/cargodepot

# Clone from repository
git clone <repository-url> .

# Set ownership
sudo chown -R www-data:www-data /var/www/cargodepot
```

### 3. Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### 4. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with production settings
nano .env
```

#### Production .env Settings
```env
APP_NAME="Cargo Depot"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cargodepot
DB_USERNAME=cargodepot_user
DB_PASSWORD=STRONG_PASSWORD_HERE

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Database Setup
```bash
# Create database
mysql -u root -p
```
```sql
CREATE DATABASE cargodepot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cargodepot_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON cargodepot.* TO 'cargodepot_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Run migrations
php artisan migrate --force

# Seed database with initial data
php artisan db:seed --force

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Web Server Configuration

#### Nginx Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/cargodepot/public;

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
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/cargodepot /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 7. SSL Certificate Setup
```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### 8. Setup Supervisor for Queue Workers
```bash
# Install Supervisor
sudo apt install -y supervisor

# Create configuration
sudo nano /etc/supervisor/conf.d/cargodepot-worker.conf
```

```ini
[program:cargodepot-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/cargodepot/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/cargodepot/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start cargodepot-worker:*
```

### 9. Setup Scheduled Tasks
```bash
# Edit crontab
sudo crontab -e -u www-data

# Add Laravel scheduler
* * * * * cd /var/www/cargodepot && php artisan schedule:run >> /dev/null 2>&1
```

### 10. Security Hardening
```bash
# Disable directory listing
sudo nano /etc/nginx/nginx.conf
# Add: autoindex off;

# Set proper file permissions
sudo find /var/www/cargodepot -type f -exec chmod 644 {} \;
sudo find /var/www/cargodepot -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/cargodepot/storage
sudo chmod -R 775 /var/www/cargodepot/bootstrap/cache

# Install and configure firewall
sudo apt install -y ufw
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable

# Install fail2ban for brute force protection
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
```

## Post-Deployment Tasks

### 1. Create Admin User
```bash
php artisan tinker
```
```php
$user = new App\Models\User();
$user->name = 'Admin User';
$user->email = 'admin@yourdomain.com';
$user->password = Hash::make('secure_password');
$user->role = 'admin';
$user->save();
exit
```

### 2. Test Application
- [ ] Visit homepage
- [ ] Test user login
- [ ] Create test gate entry
- [ ] Verify database connections
- [ ] Check email functionality
- [ ] Test file uploads
- [ ] Verify queue processing

### 3. Setup Monitoring
```bash
# Install monitoring tools
# - Laravel Telescope (development)
# - Laravel Horizon (queue monitoring)
# - New Relic or similar APM
```

### 4. Setup Backups
```bash
# Database backup script
sudo nano /usr/local/bin/backup-cargodepot.sh
```
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/cargodepot"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u cargodepot_user -p'PASSWORD' cargodepot > $BACKUP_DIR/db_$DATE.sql

# Backup application files
tar -czf $BACKUP_DIR/app_$DATE.tar.gz /var/www/cargodepot/storage

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
# Make executable
sudo chmod +x /usr/local/bin/backup-cargodepot.sh

# Add to crontab (daily at 2 AM)
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-cargodepot.sh
```

## Monitoring and Maintenance

### Log Monitoring
```bash
# Application logs
tail -f /var/www/cargodepot/storage/logs/laravel.log

# Nginx access logs
tail -f /var/log/nginx/access.log

# Nginx error logs
tail -f /var/log/nginx/error.log

# Queue worker logs
tail -f /var/www/cargodepot/storage/logs/worker.log
```

### Performance Optimization
```bash
# Install Redis for caching
sudo apt install -y redis-server
sudo systemctl enable redis-server

# Install and configure OPcache
sudo nano /etc/php/8.3/fpm/conf.d/10-opcache.ini
```

### Regular Maintenance Tasks
```bash
# Clear expired cache
php artisan cache:clear

# Clear old logs (older than 30 days)
find /var/www/cargodepot/storage/logs -name "*.log" -mtime +30 -delete

# Optimize database
php artisan optimize

# Update dependencies (with caution)
composer update
```

## Rollback Procedure
```bash
# In case of deployment issues

# 1. Revert code
git reset --hard <previous-commit-hash>

# 2. Rollback migrations (if needed)
php artisan migrate:rollback

# 3. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Restore from backup
mysql -u cargodepot_user -p cargodepot < /var/backups/cargodepot/db_TIMESTAMP.sql
```

## Troubleshooting

### Common Issues

**Issue: 500 Internal Server Error**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Check permissions
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

**Issue: Database Connection Failed**
```bash
# Verify database credentials in .env
# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

**Issue: Queue Not Processing**
```bash
# Restart queue workers
sudo supervisorctl restart cargodepot-worker:*

# Check supervisor logs
sudo tail -f /var/log/supervisor/supervisord.log
```

## Support and Contact
For deployment issues, contact:
- Technical Support: support@cargodepot.com
- Emergency: emergency@cargodepot.com

## License
MIT License - See LICENSE file for details
