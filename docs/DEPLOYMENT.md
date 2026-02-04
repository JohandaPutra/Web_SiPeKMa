# Deployment Guide - SIPEKMA

**Panduan Lengkap Deployment ke Production**

---

## 📋 Daftar Isi

1. [Overview](#overview)
2. [Server Requirements](#server-requirements)
3. [Pre-Deployment Checklist](#pre-deployment-checklist)
4. [Deployment Steps](#deployment-steps)
5. [Environment Configuration](#environment-configuration)
6. [Database Migration](#database-migration)
7. [Web Server Configuration](#web-server-configuration)
8. [SSL Certificate](#ssl-certificate)
9. [Performance Optimization](#performance-optimization)
10. [Post-Deployment](#post-deployment)
11. [Backup Strategy](#backup-strategy)
12. [Troubleshooting](#troubleshooting)

---

## 📖 Overview

### Deployment Information

| Item | Detail |
|------|--------|
| **Application** | SIPEKMA |
| **Framework** | Laravel 11 |
| **Deployment Type** | Traditional hosting / VPS |
| **Server OS** | Ubuntu 22.04 LTS (recommended) |
| **Web Server** | Nginx / Apache |
| **PHP Version** | 8.2+ |
| **Database** | MySQL 8.0+ |

### Deployment Flow

```
1. Prepare Server
   ↓
2. Install Dependencies (PHP, MySQL, Composer, Node.js)
   ↓
3. Clone Repository
   ↓
4. Configure Environment
   ↓
5. Install Dependencies (Composer, NPM)
   ↓
6. Build Assets
   ↓
7. Run Migrations & Seeders
   ↓
8. Configure Web Server
   ↓
9. Setup SSL
   ↓
10. Test & Verify
   ↓
11. Go Live
```

---

## 🖥️ Server Requirements

### Minimum Requirements

| Resource | Minimum | Recommended | Purpose |
|----------|---------|-------------|---------|
| **CPU** | 2 cores | 4 cores | Application processing |
| **RAM** | 2 GB | 4 GB | PHP & MySQL |
| **Storage** | 20 GB | 50 GB SSD | Application & database |
| **Bandwidth** | 1 TB/month | Unlimited | Data transfer |

### Software Requirements

| Software | Version | Required |
|----------|---------|----------|
| **Ubuntu** | 22.04 LTS | ✅ Recommended |
| **PHP** | 8.2 or higher | ✅ Required |
| **MySQL** | 8.0 or higher | ✅ Required |
| **Composer** | 2.x | ✅ Required |
| **Node.js** | 18.x or higher | ✅ Required |
| **NPM** | 9.x or higher | ✅ Required |
| **Nginx** | 1.18+ | ✅ Recommended |
| **Apache** | 2.4+ | 🟡 Alternative |
| **Git** | 2.x | ✅ Required |

### PHP Extensions

```bash
# Required PHP extensions
php8.2-cli
php8.2-fpm
php8.2-mysql
php8.2-mbstring
php8.2-xml
php8.2-bcmath
php8.2-curl
php8.2-zip
php8.2-gd
php8.2-intl
```

---

## ✅ Pre-Deployment Checklist

### Code Preparation

| Task | Command | Status |
|------|---------|--------|
| ✅ Run tests | `php artisan test` | Must pass |
| ✅ Code formatting | `php artisan pint` | Must run |
| ✅ Build assets | `npm run build` | Must complete |
| ✅ Check errors | `php artisan route:list` | No errors |
| ✅ Optimize autoloader | `composer dump-autoload --optimize` | Done |
| ✅ Clear dev dependencies | `composer install --no-dev` | Done |

### Configuration Files

| File | Check | Purpose |
|------|-------|---------|
| `.env.production` | ✅ Created | Production environment |
| `composer.json` | ✅ Verified | Dependencies list |
| `package.json` | ✅ Verified | NPM dependencies |
| `vite.config.js` | ✅ Verified | Asset build config |

### Security Checklist

| Task | Status |
|------|--------|
| ✅ Change APP_KEY | Done |
| ✅ Set APP_DEBUG=false | Done |
| ✅ Set APP_ENV=production | Done |
| ✅ Strong database password | Done |
| ✅ HTTPS enforced | Planned |
| ✅ Secure file permissions | Planned |

---

## 🚀 Deployment Steps

### Step 1: Prepare Server

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install basic utilities
sudo apt install -y git curl wget unzip software-properties-common
```

### Step 2: Install PHP 8.2

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP and extensions
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mysql \
    php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl \
    php8.2-zip php8.2-gd php8.2-intl

# Verify PHP version
php -v
```

### Step 3: Install Composer

```bash
# Download Composer
curl -sS https://getcomposer.org/installer | php

# Move to global
sudo mv composer.phar /usr/local/bin/composer

# Verify
composer --version
```

### Step 4: Install MySQL

```bash
# Install MySQL Server
sudo apt install -y mysql-server

# Secure installation
sudo mysql_secure_installation

# Login to MySQL
sudo mysql

# Create database and user
CREATE DATABASE db_sipekma_production;
CREATE USER 'sipekma_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON db_sipekma_production.* TO 'sipekma_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 5: Install Node.js & NPM

```bash
# Install Node.js 20.x
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verify
node -v
npm -v
```

### Step 6: Install Nginx

```bash
# Install Nginx
sudo apt install -y nginx

# Start and enable Nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Verify
sudo systemctl status nginx
```

### Step 7: Clone Repository

```bash
# Create directory
sudo mkdir -p /var/www/sipekma
sudo chown -R $USER:$USER /var/www/sipekma

# Clone repository
cd /var/www
git clone https://github.com/yourusername/Web_SiPeKMa.git sipekma

# Navigate to project
cd sipekma
```

### Step 8: Install Dependencies

```bash
# Install Composer dependencies (production only)
composer install --no-dev --optimize-autoloader

# Install NPM dependencies
npm ci --only=production

# Build assets for production
npm run build
```

### Step 9: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Edit environment file
nano .env
```

**Production .env:**

```env
APP_NAME=SIPEKMA
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://sipekma.yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sipekma_production
DB_USERNAME=sipekma_user
DB_PASSWORD=strong_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

VITE_APP_NAME="${APP_NAME}"
```

```bash
# Generate application key
php artisan key:generate

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 10: Set Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/sipekma

# Set directory permissions
sudo find /var/www/sipekma -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/sipekma -type f -exec chmod 644 {} \;

# Storage and cache writable
sudo chmod -R 775 /var/www/sipekma/storage
sudo chmod -R 775 /var/www/sipekma/bootstrap/cache
```

### Step 11: Run Migrations

```bash
# Run migrations
php artisan migrate --force

# Seed database (optional, for master data)
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=ProdiSeeder
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=JenisKegiatanSeeder
php artisan db:seed --class=JenisPendanaanSeeder
```

---

## ⚙️ Environment Configuration

### Production Environment Variables

| Variable | Development | Production |
|----------|-------------|------------|
| `APP_ENV` | local | **production** |
| `APP_DEBUG` | true | **false** |
| `APP_URL` | http://localhost:8001 | **https://yourdomain.com** |
| `LOG_LEVEL` | debug | **error** |
| `DB_HOST` | 127.0.0.1 | **Production DB host** |
| `SESSION_SECURE_COOKIE` | false | **true** |

### Database Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sipekma_production
DB_USERNAME=sipekma_user
DB_PASSWORD=very_strong_password_123!@#
```

---

## 🗄️ Database Migration

### Migration Commands

```bash
# Check migration status
php artisan migrate:status

# Run migrations (production)
php artisan migrate --force

# Rollback if needed (be careful!)
php artisan migrate:rollback --step=1
```

### Seeding Production Data

```bash
# Seed essential data only
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=ProdiSeeder --force
php artisan db:seed --class=AdminUserSeeder --force
php artisan db:seed --class=JenisKegiatanSeeder --force
php artisan db:seed --class=JenisPendanaanSeeder --force

# DO NOT run test data seeders in production!
# php artisan db:seed --class=UserSeeder (SKIP)
# php artisan db:seed --class=KegiatanSeeder (SKIP)
```

---

## 🌐 Web Server Configuration

### Nginx Configuration

**File:** `/etc/nginx/sites-available/sipekma`

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name sipekma.yourdomain.com;
    root /var/www/sipekma/public;

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
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Enable site:**

```bash
# Create symbolic link
sudo ln -s /etc/nginx/sites-available/sipekma /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

### Apache Configuration (Alternative)

**File:** `/etc/apache2/sites-available/sipekma.conf`

```apache
<VirtualHost *:80>
    ServerName sipekma.yourdomain.com
    DocumentRoot /var/www/sipekma/public

    <Directory /var/www/sipekma/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/sipekma_error.log
    CustomLog ${APACHE_LOG_DIR}/sipekma_access.log combined
</VirtualHost>
```

**Enable site:**

```bash
# Enable site and rewrite module
sudo a2ensite sipekma.conf
sudo a2enmod rewrite

# Restart Apache
sudo systemctl restart apache2
```

---

## 🔒 SSL Certificate

### Using Let's Encrypt (Free SSL)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain certificate for Nginx
sudo certbot --nginx -d sipekma.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

**Updated Nginx config with SSL:**

```nginx
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name sipekma.yourdomain.com;
    root /var/www/sipekma/public;

    ssl_certificate /etc/letsencrypt/live/sipekma.yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/sipekma.yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # ... rest of configuration
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name sipekma.yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

---

## ⚡ Performance Optimization

### Laravel Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### Asset Optimization

```bash
# Build production assets
npm run build

# Assets are automatically minified by Vite
```

### Database Optimization

```sql
-- Add indexes for better performance
ALTER TABLE kegiatans ADD INDEX idx_status (status);
ALTER TABLE kegiatans ADD INDEX idx_user_status (user_id, status);
ALTER TABLE approval_histories ADD INDEX idx_created_at (created_at);
```

### Enable OPcache

**File:** `/etc/php/8.2/fpm/conf.d/99-opcache.ini`

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.revalidate_freq=0
opcache.save_comments=1
```

```bash
# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

---

## 🔍 Post-Deployment

### Verification Checklist

| Test | URL | Expected Result |
|------|-----|-----------------|
| ✅ Homepage | https://sipekma.yourdomain.com | Dashboard loads |
| ✅ Login | /login | Login page loads |
| ✅ Database | N/A | Migrations successful |
| ✅ Assets | View source | CSS/JS loaded from /build/ |
| ✅ SSL | Check browser | Padlock icon visible |
| ✅ Forms | Create kegiatan | CSRF token works |
| ✅ File upload | Upload proposal | File saved successfully |

### Monitoring Setup

```bash
# View Laravel logs
tail -f /var/www/sipekma/storage/logs/laravel.log

# View Nginx access logs
tail -f /var/log/nginx/access.log

# View Nginx error logs
tail -f /var/log/nginx/error.log

# View PHP-FPM logs
tail -f /var/log/php8.2-fpm.log
```

---

## 💾 Backup Strategy

### Database Backup

**Automated backup script:** `/usr/local/bin/backup-sipekma.sh`

```bash
#!/bin/bash

# Configuration
DB_NAME="db_sipekma_production"
DB_USER="sipekma_user"
DB_PASS="strong_password_here"
BACKUP_DIR="/var/backups/sipekma"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz

# Backup files
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /var/www/sipekma/storage/app/uploads

# Delete backups older than 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

**Make executable and schedule:**

```bash
# Make executable
sudo chmod +x /usr/local/bin/backup-sipekma.sh

# Add to crontab (daily at 2 AM)
sudo crontab -e
# Add line:
0 2 * * * /usr/local/bin/backup-sipekma.sh >> /var/log/sipekma-backup.log 2>&1
```

### Application Backup

```bash
# Backup entire application
sudo tar -czf /var/backups/sipekma/sipekma_full_$(date +%Y%m%d).tar.gz \
    /var/www/sipekma \
    --exclude=/var/www/sipekma/node_modules \
    --exclude=/var/www/sipekma/vendor
```

---

## 🔧 Troubleshooting

### Common Issues

#### Issue 1: 500 Internal Server Error

**Possible Causes:**
- PHP errors in logs
- Wrong file permissions
- Missing .env file

**Solution:**

```bash
# Check Laravel logs
tail -f /var/www/sipekma/storage/logs/laravel.log

# Fix permissions
sudo chown -R www-data:www-data /var/www/sipekma
sudo chmod -R 775 /var/www/sipekma/storage
sudo chmod -R 775 /var/www/sipekma/bootstrap/cache

# Verify .env exists
ls -la /var/www/sipekma/.env
```

#### Issue 2: Assets Not Loading

**Possible Causes:**
- Assets not built
- Wrong public path
- Web server misconfiguration

**Solution:**

```bash
# Rebuild assets
cd /var/www/sipekma
npm run build

# Clear cache
php artisan cache:clear
php artisan view:clear

# Verify build directory
ls -la /var/www/sipekma/public/build/
```

#### Issue 3: Database Connection Error

**Possible Causes:**
- Wrong credentials
- MySQL not running
- Wrong host/port

**Solution:**

```bash
# Test MySQL connection
mysql -u sipekma_user -p

# Check MySQL status
sudo systemctl status mysql

# Verify .env credentials match MySQL user
```

---

## 📞 Maintenance Mode

### Enable Maintenance Mode

```bash
# Enable maintenance mode
php artisan down --message="Sistem sedang maintenance" --retry=60

# Allow specific IPs
php artisan down --allow=103.xxx.xxx.xxx --allow=192.168.1.100
```

### Disable Maintenance Mode

```bash
# Disable maintenance mode
php artisan up
```

---

**Last Updated:** 02 Februari 2026  
**Version:** 1.0.0  
**Deployment Version:** 1.0  
**Status:** ✅ Ready for Production
