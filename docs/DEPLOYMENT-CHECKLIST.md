# 📋 Deployment Checklist - SIPEKMA

**Project:** SIPEKMA (Sistem Informasi Usulan Kegiatan)  
**Target:** cPanel Shared Hosting  
**Date:** February 16, 2026

---

## ✅ Pre-Deployment (Development)

### 1. Code & Dependencies

- [ ] All git changes committed
- [ ] Branch master up-to-date: `git pull origin master`
- [ ] No uncommitted files: `git status` clean
- [ ] Composer dependencies installed: `composer install --optimize-autoloader --no-dev`
- [ ] NPM dependencies installed: `npm install`
- [ ] Production build: `npm run build` successful
- [ ] Assets exist: `public/build/manifest.json` & `public/build/assets/`

### 2. Configuration

- [ ] `.env.production` created with correct values
- [ ] `.htaccess` root & public updated (security headers, redirects)
- [ ] Database migrations tested: `php artisan migrate:fresh --seed`
- [ ] Verify seeders data: RoleSeeder, ProdiSeeder, AdminUserSeeder

### 3. Code Quality

- [ ] No errors: `php artisan route:list` successful
- [ ] Laravel Pint: `php artisan pint` (code formatting)
- [ ] Check logs: `storage/logs/laravel.log` no critical errors
- [ ] Test all features manually (CRUD, upload, export)

### 4. Documentation

- [ ] `docs/DEPLOYMENT.md` up-to-date
- [ ] README.md contains production info (if needed)
- [ ] `.env.example` synced with latest config

### 5. Archive Preparation

**Exclude from upload:**
```
❌ node_modules/
❌ .git/
❌ tests/
❌ .env (local)
❌ .editorconfig
❌ phpunit.xml
```

**Include:**
```
✅ app/
✅ bootstrap/
✅ config/
✅ database/
✅ public/ (with build assets)
✅ resources/
✅ routes/
✅ storage/ (empty logs)
✅ vendor/
✅ .htaccess (root & public)
✅ .env.production (rename to .env after upload)
✅ artisan
```

- [ ] Create ZIP: `sipekma-production.zip` (excluding above files)

---

## 🌐 cPanel Setup

### 1. Server Configuration

- [ ] Login to cPanel: `https://yourdomain.com:2083`
- [ ] **MultiPHP Manager**: Set PHP version to 8.2 or 8.3
- [ ] **MultiPHP INI Editor**: Verify extensions:
  - [ ] BCMath, Ctype, cURL, DOM, Fileinfo
  - [ ] JSON, Mbstring, OpenSSL, PDO, PDO MySQL
  - [ ] Tokenizer, XML, Zip, GD
- [ ] **MultiPHP INI Editor**: Set limits:
  - `upload_max_filesize = 10M`
  - `post_max_size = 10M`
  - `memory_limit = 256M`
  - `max_execution_time = 300`

### 2. Domain/Subdomain Setup

**Choose deployment type:**

- [ ] **Option A:** Root domain (`yourdomain.com`)
  - Upload to: `/home/username/public_html/`
  - Document root: `/home/username/public_html/public`

- [ ] **Option B:** Subdomain (`sipekma.yourdomain.com`)
  - Create subdomain via cPanel
  - Upload to: `/home/username/sipekma/`
  - Document root: `/home/username/sipekma/public`

- [ ] **Option C:** Addon domain (`sipekma.com`)
  - Create addon domain via cPanel
  - Upload to: `/home/username/sipekma/`
  - Document root: `/home/username/sipekma/public`

### 3. File Upload

**Via File Manager:**
- [ ] Upload `sipekma-production.zip` to deployment directory
- [ ] Extract ZIP file
- [ ] Move files from extracted folder to root (if nested)
- [ ] Delete ZIP file after extraction
- [ ] Verify structure: `artisan` file exists in root

**Via FTP (Alternative):**
- [ ] Get FTP credentials from cPanel → FTP Accounts
- [ ] Connect with FileZilla/WinSCP
- [ ] Upload all files to deployment directory

### 4. Document Root Configuration

- [ ] **cPanel → Domains → Manage**
  - Change Document Root to: `/home/username/sipekma/public`
  - OR ensure `.htaccess` redirect works (root → public)

---

## 🗄️ Database Setup

### 1. Create Database

- [ ] **MySQL Database Wizard**
- [ ] Database name: `sipekma` → becomes `cpanelusername_sipekma`
- [ ] Database user: `sipekma_user` → becomes `cpanelusername_sipekma_user`
- [ ] Generate strong password
- [ ] **SAVE credentials:**
  ```
  DB_DATABASE=cpanelusername_sipekma
  DB_USERNAME=cpanelusername_sipekma_user
  DB_PASSWORD=[generated_password]
  ```
- [ ] Grant ALL PRIVILEGES to user

### 2. Verify Database

- [ ] **phpMyAdmin**: Database `cpanelusername_sipekma` exists
- [ ] User has access to database
- [ ] Empty database (ready for migrations)

---

## ⚙️ Environment Configuration

### 1. Create .env File

- [ ] **File Manager**: Navigate to `/home/username/sipekma/`
- [ ] Rename `.env.production` → `.env`
- [ ] Edit `.env` file:

```env
APP_NAME="SiPeKMa"
APP_ENV=production
APP_KEY=[will generate next]
APP_DEBUG=false
APP_URL=https://sipekma.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpanelusername_sipekma
DB_USERNAME=cpanelusername_sipekma_user
DB_PASSWORD=[your_database_password]

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=[email_password]
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

- [ ] Save `.env` file

### 2. Generate APP_KEY

**Option A: Via cPanel Terminal (if available):**
```bash
cd /home/username/sipekma
php artisan key:generate
```

**Option B: Generate Locally:**
```powershell
# Local machine
php artisan key:generate --show
# Copy output: base64:abc123...
# Paste to APP_KEY in .env via File Manager
```

- [ ] Verify: `.env` contains `APP_KEY=base64:...`

---

## 🔐 File Permissions

### Set Permissions via File Manager

- [ ] Select `storage/` folder
  - Right-click → Change Permissions
  - Set: **755** (check "Recurse into subdirectories")
- [ ] Select `bootstrap/cache/` folder
  - Right-click → Change Permissions
  - Set: **755** (check "Recurse into subdirectories")
- [ ] File `.env`
  - Right-click → Change Permissions
  - Set: **644** (rw-r--r--)

### Verify Permissions

```
storage/           → 755 (drwxr-xr-x)
bootstrap/cache/   → 755 (drwxr-xr-x)
.env               → 644 (rw-r--r--)
```

---

## 🚀 Laravel Optimization

### Option A: Via cPanel Terminal

```bash
cd /home/username/sipekma

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run migrations (FIRST TIME ONLY)
php artisan migrate --force

# Seed database (FIRST TIME ONLY)
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=ProdiSeeder --force
php artisan db:seed --class=AdminUserSeeder --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link

# Verify
php artisan about
```

- [ ] All commands executed successfully
- [ ] No errors in output

### Option B: Via PHP Exec Script (if no Terminal)

- [ ] Create `public/deploy.php` (content in DEPLOYMENT.md guide)
- [ ] Access: `https://sipekma.yourdomain.com/deploy.php`
- [ ] Verify output: migrations & seeds successful
- [ ] **DELETE `deploy.php` after use** (IMPORTANT for security!)

---

## 🔒 SSL Certificate

### 1. Enable AutoSSL

- [ ] **cPanel → SSL/TLS Status**
- [ ] Click **Run AutoSSL**
- [ ] Wait 1-5 minutes for certificate generation
- [ ] Verify: Green checkmark next to domain

### 2. Test HTTPS

- [ ] Visit: `https://sipekma.yourdomain.com`
- [ ] Browser shows padlock icon (secure)
- [ ] No certificate warnings

### 3. Force HTTPS

- [ ] Verify `public/.htaccess` has HTTPS redirect (already included)
- [ ] Update `.env`: `APP_URL=https://...`
- [ ] Clear config: `php artisan config:clear`

---

## ✅ Post-Deployment Testing

### 1. Homepage Load

- [ ] Visit: `https://sipekma.yourdomain.com`
- [ ] Homepage displays without errors
- [ ] No 500 Internal Server Error
- [ ] Check browser console: No JS/CSS errors

### 2. Assets Loading

- [ ] CSS applied correctly (not plain HTML)
- [ ] JavaScript working (menu collapse, modals, etc.)
- [ ] Images loading (logo, icons)
- [ ] Fonts loaded (no broken characters)

### 3. Authentication

- [ ] Access `/login` page
- [ ] Login form displays correctly
- [ ] Login with admin credentials:
  - Email: `admin@example.com` (check AdminUserSeeder)
  - Password: `password` (check seeder)
- [ ] Successful login redirects to dashboard
- [ ] Logout button works

### 4. Dashboard & Navigation

- [ ] Dashboard displays data correctly
- [ ] Side menu working (expand/collapse)
- [ ] All menu items clickable
- [ ] No broken links (404 errors)

### 5. Database Operations

**Kegiatan Management:**
- [ ] Create new kegiatan
- [ ] Edit existing kegiatan
- [ ] Delete kegiatan (soft delete)
- [ ] View kegiatan details
- [ ] Filter & search working

**Approval Workflow:**
- [ ] Submit kegiatan for approval
- [ ] Approve kegiatan (if admin)
- [ ] Reject kegiatan with reason
- [ ] Request revision
- [ ] View approval history

**Master Data:**
- [ ] CRUD operations for Jenis Kegiatan
- [ ] CRUD operations for Jenis Pendanaan
- [ ] CRUD operations for Prodi
- [ ] CRUD operations for Users

### 6. File Upload & Download

- [ ] Upload document (PDF, Word, etc.)
- [ ] File saved to `storage/app/public/`
- [ ] Download uploaded file
- [ ] View file in browser (if PDF)
- [ ] Delete uploaded file

### 7. Export Features

- [ ] Export kegiatan to Excel
- [ ] Excel file downloaded
- [ ] Excel format correct (columns, data)
- [ ] No errors opening Excel

### 8. Responsive Design

- [ ] Mobile view (iPhone, Android)
- [ ] Tablet view (iPad)
- [ ] Desktop view (1920x1080)
- [ ] Menu collapse on mobile
- [ ] Tables scrollable on mobile

### 9. Error Handling

- [ ] Access invalid URL → 404 page
- [ ] Submit empty form → validation errors displayed
- [ ] Try to access restricted page → redirect or 403
- [ ] Logout → redirect to login

### 10. Performance

- [ ] Page load time < 3 seconds
- [ ] No N+1 query issues (check logs)
- [ ] Assets cached (check browser Network tab)

---

## 📊 Monitoring & Maintenance

### Check Logs

- [ ] **Laravel Log**: `storage/logs/laravel.log` → no errors
- [ ] **cPanel Error Log**: cPanel → Errors → no critical errors
- [ ] **PHP Error Log**: cPanel → Metrics → Errors

### Backups

- [ ] **cPanel Backup**: Enable automatic backups
  - cPanel → Backup → Generate Backup
  - Schedule: Weekly or Daily
- [ ] **Database Backup**: Download SQL dump via phpMyAdmin
- [ ] **Files Backup**: Download ZIP via File Manager

### Security

- [ ] Change admin default password
- [ ] Update `.env`: Set `APP_DEBUG=false`
- [ ] Verify `.env` permissions: 644 (not writable by others)
- [ ] Remove any test/debug files (`deploy.php`, etc.)
- [ ] Check `storage/logs/` not publicly accessible

### Updates

- [ ] Monitor Laravel security updates
- [ ] Monitor PHP version (cPanel)
- [ ] Update dependencies periodically:
  ```bash
  composer update --no-dev
  npm update
  npm run build
  ```

---

## ❌ Troubleshooting

If issues occur, refer to **DEPLOYMENT.md** section "Troubleshooting" for:

- [ ] Error 500 solutions
- [ ] CSS/JS not loading fixes
- [ ] Database connection errors
- [ ] APP_KEY missing errors
- [ ] File upload errors
- [ ] Migration errors
- [ ] Session errors

---

## 🎉 Deployment Complete

- [ ] All checklist items ✅
- [ ] Application accessible via HTTPS
- [ ] All features tested and working
- [ ] No critical errors in logs
- [ ] Backups configured
- [ ] Admin password changed
- [ ] Monitoring in place

**Deployment Info:**
- **URL**: https://sipekma.yourdomain.com
- **Admin Email**: [change from default]
- **Admin Password**: [changed from default]
- **Deployment Date**: [date]
- **Deployed By**: [name]

---

## 📝 Notes

**Important Commands Reference:**

```bash
# Clear all caches
php artisan optimize:clear

# Optimize for production
php artisan optimize

# Maintenance mode ON
php artisan down --secret="secret-token-123"

# Maintenance mode OFF
php artisan up

# View Laravel info
php artisan about
```

**First Time Admin Login:**
1. Login with seeder credentials
2. Change password immediately
3. Update email to real email
4. Create other admin users if needed

**Regular Maintenance:**
- Check logs weekly
- Backup database weekly
- Monitor disk space
- Update dependencies monthly

---

**Checklist Version:** 1.0  
**Last Updated:** February 16, 2026  
**For:** SIPEKMA Laravel 11 cPanel Deployment
