# SIPEKMA - Struktur File & Folder

**Dokumen:** Struktur File dan Folder Proyek  
**Versi:** 1.0  
**Tanggal:** 02 Februari 2026  
**Proyek:** Sistem Informasi Pengelolaan Kegiatan Mahasiswa (SIPEKMA)

---

## Daftar Isi

1. [Struktur Root Directory](#struktur-root-directory)
2. [Folder Backend (app/)](#folder-backend-app)
3. [Folder Konfigurasi (config/)](#folder-konfigurasi-config)
4. [Folder Database (database/)](#folder-database-database)
5. [Folder Dokumentasi (docs/)](#folder-dokumentasi-docs)
6. [Folder Public Assets (public/)](#folder-public-assets-public)
7. [Folder Resources (resources/)](#folder-resources-resources)
8. [Folder Routes (routes/)](#folder-routes-routes)
9. [Folder Storage (storage/)](#folder-storage-storage)
10. [Folder Backup (backups/)](#folder-backup-backups)
11. [Lokasi File Penting](#lokasi-file-penting)
12. [Konvensi Penamaan](#konvensi-penamaan)

---

## Struktur Root Directory

### Overview Root Folder

| Folder/File | Tipe | Deskripsi |
|-------------|------|-----------|
| `.github/` | Folder | Konfigurasi GitHub & AI Copilot |
| `app/` | Folder | Logika aplikasi Laravel (Controllers, Models) |
| `bootstrap/` | Folder | Bootstrap Laravel framework |
| `config/` | Folder | File konfigurasi aplikasi |
| `database/` | Folder | Migrations, seeders, factories |
| `docs/` | Folder | Dokumentasi proyek |
| `public/` | Folder | Entry point & public assets |
| `resources/` | Folder | Views (Blade), SCSS, JavaScript |
| `routes/` | Folder | Definisi routes aplikasi |
| `storage/` | Folder | Logs, cache, file uploads |
| `tests/` | Folder | Unit & feature tests |
| `vendor/` | Folder | Dependencies Composer (PHP) |
| `node_modules/` | Folder | Dependencies NPM (Node.js) |
| `backups/` | Folder | Backup file refactoring |
| `.env` | File | Konfigurasi environment |
| `artisan` | File | Laravel CLI tool |
| `composer.json` | File | PHP dependencies |
| `package.json` | File | Node.js dependencies |
| `vite.config.js` | File | Konfigurasi Vite bundler |

### Total Estimasi File

| Kategori | Jumlah File |
|----------|-------------|
| PHP Files | ~150 |
| Blade Templates | ~100 |
| SCSS Files | ~30 |
| JavaScript Files | ~15 |
| Config Files | ~20 |
| Migration Files | ~25 |
| Total (excluding vendor) | **~340 files** |

---

## Folder Backend (app/)

### Struktur app/

| Sub-folder | Jumlah File | Deskripsi |
|------------|-------------|-----------|
| `DataTables/` | 1 | Yajra DataTables classes |
| `Exports/` | 1 | Export classes (Excel, PDF) |
| `Http/Controllers/` | 25+ | Application controllers |
| `Http/Middleware/` | 5+ | HTTP middleware |
| `Models/` | 10 | Eloquent models |
| `Providers/` | 2 | Service providers |

### Controllers Structure

| Folder/File | Purpose |
|-------------|---------|
| `Controllers/Auth/` | Authentication controllers |
| `Controllers/Kegiatan/KegiatanController.php` | Main kegiatan CRUD |
| `Controllers/Kegiatan/ProposalController.php` | Proposal upload & approval |
| `Controllers/Kegiatan/PendanaanController.php` | Pendanaan upload & approval |
| `Controllers/Kegiatan/LaporanController.php` | Laporan upload & approval |
| `Controllers/Kegiatan/RiwayatController.php` | Riwayat kegiatan |
| `Controllers/Master/JenisKegiatanController.php` | Master jenis kegiatan |
| `Controllers/Master/JenisPendanaanController.php` | Master jenis pendanaan |
| `Controllers/Master/ProdiController.php` | Master program studi |
| `Controllers/DashboardController.php` | Dashboard analytics |
| `Controllers/UsersController.php` | User management |
| `Controllers/UsulanKegiatanController.php` | Usulan kegiatan CRUD |

### Models Structure

| Model File | Database Table | Primary Key |
|------------|----------------|-------------|
| `ApprovalHistory.php` | approval_histories | id |
| `JenisKegiatan.php` | jenis_kegiatans | id |
| `JenisPendanaan.php` | jenis_pendanaans | id |
| `Kegiatan.php` | kegiatans | id |
| `KegiatanFile.php` | kegiatan_files | id |
| `Notification.php` | notifications | id |
| `Prodi.php` | prodis | id |
| `Role.php` | roles | id |
| `User.php` | users | id |
| `UsulanKegiatan.php` | usulan_kegiatans | id |

### Service Providers

| Provider | Purpose |
|----------|---------|
| `AppServiceProvider.php` | Application-level services |
| `MenuServiceProvider.php` | Load menu from JSON & share to views |

---

## Folder Konfigurasi (config/)

### File Konfigurasi

| File | Purpose | Key Config |
|------|---------|------------|
| `app.php` | Application settings | name, env, timezone, locale |
| `auth.php` | Authentication config | guards, providers |
| `database.php` | Database connections | mysql, sqlite |
| `datatables.php` | Yajra DataTables | engines, builders |
| `datatables-html.php` | DataTables HTML | export buttons |
| `filesystems.php` | Storage config | local, public, s3 |
| `session.php` | Session management | driver, lifetime |
| `variables.php` | **Template variables** | templateName, logo, URLs |

### Config Variables (variables.php) ⭐

| Variable | Value | Usage |
|----------|-------|-------|
| `templateName` | "Sneat" | Template branding |
| `creatorName` | "ThemeSelection" | Template creator |
| `creatorUrl` | https://themeselection.com | Creator URL |
| Custom variables | Logo paths, colors | Template customization |

---

## Folder Database (database/)

### Migrations (Chronological)

| No | Migration File | Table | Created |
|----|----------------|-------|---------|
| 1 | `0001_01_01_000000_create_roles_table.php` | roles | 2025 |
| 2 | `0001_01_01_000001_create_prodis_table.php` | prodis | 2025 |
| 3 | `0001_01_01_000002_create_users_table.php` | users | 2025 |
| 4 | `0001_01_01_000003_create_cache_table.php` | cache | 2025 |
| 5 | `0001_01_01_000004_create_jobs_table.php` | jobs | 2025 |
| 6 | `2025_12_07_145400_create_kegiatans_table.php` | kegiatans | Dec 2025 |
| 7 | `2025_12_07_145409_create_approval_histories_table.php` | approval_histories | Dec 2025 |
| 8 | `2025_12_08_044835_create_kegiatan_files_table.php` | kegiatan_files | Dec 2025 |
| ... | (20+ total migrations) | ... | ... |

### Seeders

| Seeder File | Purpose | Records |
|-------------|---------|---------|
| `RoleSeeder.php` | Seed user roles | 5 roles |
| `ProdiSeeder.php` | Seed program studi | 10+ prodi |
| `JenisKegiatanSeeder.php` | Seed jenis kegiatan | 8 types |
| `JenisPendanaanSeeder.php` | Seed jenis pendanaan | 5 types |
| `AdminUserSeeder.php` | Seed admin user | 1 admin |
| `DatabaseSeeder.php` | Master seeder | Calls all |

### Database Tables Summary

| Table Name | Purpose | Key Fields |
|------------|---------|------------|
| `roles` | User roles | id, name |
| `prodis` | Program studi | id, nama, fakultas |
| `users` | System users | id, username, email, role_id |
| `kegiatans` | Main kegiatan | id, nama_kegiatan, user_id, status |
| `approval_histories` | Approval logs | id, kegiatan_id, status, notes |
| `kegiatan_files` | File attachments | id, kegiatan_id, file_path, type |
| `jenis_kegiatans` | Kegiatan types | id, nama |
| `jenis_pendanaans` | Pendanaan types | id, nama |

---

## Folder Dokumentasi (docs/)

### Struktur Dokumentasi

| File | Purpose | Pages |
|------|---------|-------|
| `README.md` | Project overview & quick start | 2-3 |
| `FILE-STRUCTURE.md` | **File & folder structure** (this file) | 15+ |
| `ARCHITECTURE.md` | System architecture & design patterns | 10+ |
| `CSS-SCSS-GUIDE.md` | CSS/SCSS best practices | 8+ |
| `JAVASCRIPT-GUIDE.md` | JavaScript patterns | 6+ |
| `DATABASE.md` | Database schema & relationships | 8+ |
| `API-ROUTES.md` | Routes documentation | 10+ |
| `DEPLOYMENT.md` | Deployment procedures | 5+ |
| `REFACTORING-LOG.md` | Change history & refactoring log | 5+ |

### Documentation Coverage

| Topic | Status | File Location |
|-------|--------|---------------|
| Project Overview | ✅ Complete | `docs/README.md` |
| File Structure | ✅ Complete | `docs/FILE-STRUCTURE.md` |
| Architecture | 🔄 In Progress | `docs/ARCHITECTURE.md` |
| CSS Guidelines | ✅ Complete | `docs/CSS-SCSS-GUIDE.md` |
| JavaScript | 🔄 In Progress | `docs/JAVASCRIPT-GUIDE.md` |
| Database | 🔄 In Progress | `docs/DATABASE.md` |
| Routes | 🔄 In Progress | `docs/API-ROUTES.md` |
| Deployment | ⏳ Planned | `docs/DEPLOYMENT.md` |
| Refactoring Log | ✅ Complete | `docs/REFACTORING-LOG.md` |

---

## Folder Public Assets (public/)

### Public Folder Structure

| Sub-folder | Purpose | Size (Approx) |
|------------|---------|---------------|
| `assets/img/` | Images & illustrations | 2-5 MB |
| `assets/vendor/` | Vendor assets (if any) | 1-2 MB |
| `build/` | **Vite compiled output** | 5-10 MB |
| Root | index.php, robots.txt | <1 KB |

### Build Output (public/build/)

| File Type | Pattern | Purpose |
|-----------|---------|---------|
| CSS | `assets/*.css` | Compiled stylesheets |
| JavaScript | `assets/*.js` | Compiled scripts |
| Fonts | `assets/*.woff2` | Web fonts |
| Images | `assets/*.svg, *.png` | Optimized images |
| Manifest | `manifest.json` | Asset mapping |

### Important Assets

| Asset | Path | Usage |
|-------|------|-------|
| Logo SIPEKMA | `public/assets/img/icons/Logo-Sipekma.png` | App logo (navbar, login) |
| Illustrations | `public/assets/img/illustrations/` | Dashboard hero images |
| Compiled CSS | `public/build/assets/app-*.css` | Main stylesheet |
| Compiled JS | `public/build/assets/app-*.js` | Main JavaScript |

---

## Folder Resources (resources/)

### Resources Overview

| Sub-folder | Purpose | File Count |
|------------|---------|------------|
| `assets/js/` | JavaScript source files | 10+ |
| `assets/scss/` | SCSS source files | 30+ |
| `assets/vendor/` | Sneat template assets | 100+ |
| `menu/` | Menu configuration (JSON) | 1 |
| `views/` | Blade templates | 100+ |
| `css/` | Additional CSS | 2-3 |

### JavaScript Files (resources/assets/js/)

| File | Lines | Purpose |
|------|-------|---------|
| `main.js` | 200+ | Menu, sidebar, table scroll, mobile behavior |
| `config.js` | 50+ | App configuration |
| `dashboards-analytics.js` | 100+ | Dashboard specific logic |
| `form-basic-inputs.js` | 50+ | Form input handling |
| `ui-modals.js` | 50+ | Modal interactions |
| `ui-toasts.js` | 50+ | Toast notifications |

### SCSS Files (resources/assets/scss/)

#### Main SCSS

| File | Lines | Purpose |
|------|-------|---------|
| `app.scss` | 310+ | **Main entry point** (imports all) |

#### Custom SCSS (custom/) ⭐

| File | Lines | Purpose |
|------|-------|---------|
| `_variables-custom.scss` | 74 | **SIPEKMA variables** (colors, sizes) |
| `_components-custom.scss` | 174+ | **Reusable components** (gradients, cards) |
| `_utilities-custom.scss` | 195+ | **Utility classes** (icon sizes, spacing) |

### Menu Configuration

| File | Format | Lines | Purpose |
|------|--------|-------|---------|
| `menu/verticalMenu.json` | JSON | 150+ | **Sidebar menu structure** (JSON-driven) |

#### Menu JSON Structure Example

```json
{
  "name": "Dashboard",
  "icon": "menu-icon tf-icons bx bx-home-circle",
  "slug": "dashboard",
  "url": "/dashboard"
}
```

### Views Structure (resources/views/)

#### Layout Files

| File | Purpose |
|------|---------|
| `layouts/contentNavbarLayout.blade.php` | **Main layout** (navbar + content) |
| `layouts/sections/menu/verticalMenu.blade.php` | Sidebar menu renderer |
| `layouts/sections/navbar/navbar.blade.php` | Top navbar |
| `layouts/sections/footer/footer.blade.php` | Footer section |

#### Partials

| File | Purpose |
|------|---------|
| `_partials/toast.blade.php` | Toast notification component |

#### Feature Views by Module

| Module | Folder | Files | Purpose |
|--------|--------|-------|---------|
| Dashboard | `content/dashboard/` | 1 | Analytics dashboard |
| Kegiatan | `kegiatan/` | 10+ | Kegiatan management |
| - Proposal | `kegiatan/proposal/` | 5 | Proposal upload & approval |
| - Pendanaan | `kegiatan/pendanaan/` | 5 | Pendanaan upload & approval |
| - Laporan | `kegiatan/laporan/` | 5 | Laporan upload & approval |
| - Riwayat | `kegiatan/riwayat/` | 3 | Riwayat kegiatan |
| Usulan | `usulan-kegiatan/` | 4 | Usulan CRUD |
| Users | `users/` | 3 | User management |
| Master Data | `jenis-kegiatan/`, `jenis-pendanaan/`, `prodi/` | 9 | Master data CRUD |

---

## Folder Routes (routes/)

### Route Files

| File | Purpose | Routes Count |
|------|---------|--------------|
| `web.php` | **Web routes** | 50+ routes |
| `console.php` | Artisan commands | 2-3 |

### Route Groups (web.php)

| Group | Prefix | Middleware | Routes |
|-------|--------|------------|--------|
| Dashboard | `/dashboard` | auth | 1 |
| Kegiatan | `/kegiatan` | auth | 20+ |
| Usulan | `/usulan-kegiatan` | auth | 7 (resource) |
| Users | `/users` | auth, admin | 7 (resource) |
| Master | `/jenis-*`, `/prodi` | auth, admin | 21 (3 resources) |

### Named Routes Pattern

| Pattern | Example | Controller Method |
|---------|---------|-------------------|
| `{feature}.index` | `usulan-kegiatan.index` | `index()` |
| `{feature}.create` | `usulan-kegiatan.create` | `create()` |
| `{feature}.store` | `usulan-kegiatan.store` | `store()` |
| `{feature}.show` | `usulan-kegiatan.show` | `show($id)` |
| `{feature}.edit` | `usulan-kegiatan.edit` | `edit($id)` |
| `{feature}.update` | `usulan-kegiatan.update` | `update($id)` |
| `{feature}.destroy` | `usulan-kegiatan.destroy` | `destroy($id)` |

---

## Folder Storage (storage/)

### Storage Structure

| Sub-folder | Purpose | Access |
|------------|---------|--------|
| `app/public/` | **User uploads** (publicly accessible) | Symlinked to public/ |
| `app/private/` | Private files | Laravel only |
| `framework/cache/` | Framework cache | System |
| `framework/sessions/` | Session files | System |
| `framework/views/` | **Compiled Blade views** | System |
| `logs/` | **Application logs** | System |

### User Uploads (app/public/)

| Sub-folder | Purpose | File Types |
|------------|---------|------------|
| `uploads/proposals/` | Proposal documents | PDF, DOCX |
| `uploads/pendanaan/` | RAB documents | PDF, XLSX |
| `uploads/laporan/` | Laporan documents | PDF, DOCX |
| `uploads/temp/` | Temporary files | Various |

### Log Files

| File | Purpose | Rotation |
|------|---------|----------|
| `logs/laravel.log` | **Main application log** | Daily |
| `logs/laravel-YYYY-MM-DD.log` | Daily log files | Keep 14 days |

---

## Folder Backup (backups/)

### Backup Structure

| Backup Folder | Pattern | Purpose |
|---------------|---------|---------|
| `backups/refactor-css-{date}_{time}/` | Timestamped | CSS refactoring backups |
| Future backups | Similar pattern | Other major changes |

### Current Backups (2026-02-02)

| Backup Folder | Files | Size | Purpose |
|---------------|-------|------|---------|
| `refactor-css-2026-02-02_115910/` | 6 files | ~50 KB | CSS refactoring backup |

#### Backed Up Files

| File | Original Path | Backup Reason |
|------|---------------|---------------|
| `app.scss.backup` | `resources/assets/scss/app.scss` | Added custom imports |
| `dashboards-analytics.blade.php.backup` | `resources/views/content/dashboard/` | Removed 32 inline styles |
| `usulan-kegiatan-show.blade.php.backup` | `resources/views/usulan-kegiatan/` | Removed 12 inline styles + CSS |
| `usulan-kegiatan-index.blade.php.backup` | `resources/views/usulan-kegiatan/` | Removed 3 inline styles |
| `usulan-kegiatan-create.blade.php.backup` | `resources/views/usulan-kegiatan/` | Removed 3 inline styles |
| `usulan-kegiatan-edit.blade.php.backup` | `resources/views/usulan-kegiatan/` | Removed 3 inline styles |

---

## Lokasi File Penting

### Quick Reference Table

| Komponen | Path | Purpose |
|----------|------|---------|
| **AI Instructions** | `.github/copilot-instructions.md` | Rules for AI agent |
| **Main Layout** | `resources/views/layouts/contentNavbarLayout.blade.php` | Base template |
| **Menu Config** | `resources/menu/verticalMenu.json` | Sidebar menu (JSON) |
| **Custom CSS** | `resources/assets/scss/custom/*.scss` | SIPEKMA styles |
| **Main JS** | `resources/assets/js/main.js` | Core JavaScript |
| **Routes** | `routes/web.php` | Web routes |
| **Kegiatan Model** | `app/Models/Kegiatan.php` | Main business logic |
| **Dashboard** | `resources/views/content/dashboard/dashboards-analytics.blade.php` | Dashboard view |
| **Logo** | `public/assets/img/icons/Logo-Sipekma.png` | Application logo |
| **Compiled CSS** | `public/build/assets/app-*.css` | Production CSS |
| **Compiled JS** | `public/build/assets/app-*.js` | Production JS |
| **Uploads** | `storage/app/public/uploads/` | User files |
| **Logs** | `storage/logs/laravel.log` | Error logs |
| **Config** | `config/variables.php` | Template config |
| **Docs** | `docs/` | Project documentation |

### Development Files

| File | Path | Purpose |
|------|------|---------|
| `.env` | Root | Environment config |
| `composer.json` | Root | PHP dependencies |
| `package.json` | Root | Node dependencies |
| `vite.config.js` | Root | Vite bundler config |
| `artisan` | Root | Laravel CLI |

---

## Konvensi Penamaan

### PHP Files

| Type | Convention | Example |
|------|------------|---------|
| Controller | `{Feature}Controller.php` | `UsulanKegiatanController.php` |
| Model | `{EntityName}.php` (PascalCase) | `JenisKegiatan.php` |
| Middleware | `{Name}Middleware.php` | `CheckRole.php` |
| Provider | `{Name}ServiceProvider.php` | `MenuServiceProvider.php` |
| DataTable | `{Feature}DataTable.php` | `UsersDataTable.php` |
| Seeder | `{Table}Seeder.php` | `RoleSeeder.php` |

### Migration Files

| Pattern | Example |
|---------|---------|
| `YYYY_MM_DD_HHMMSS_create_{table}_table.php` | `2025_12_07_145400_create_kegiatans_table.php` |
| `YYYY_MM_DD_HHMMSS_add_{field}_to_{table}_table.php` | `2025_12_08_063709_add_total_anggaran_to_kegiatans_table.php` |
| `YYYY_MM_DD_HHMMSS_update_{table}_table.php` | `2025_12_08_082217_update_kegiatan_files_table.php` |

### View Files (Blade)

| Type | Pattern | Example |
|------|---------|---------|
| Feature views | `{feature}/{action}.blade.php` | `usulan-kegiatan/create.blade.php` |
| Layouts | `layouts/{name}Layout.blade.php` | `contentNavbarLayout.blade.php` |
| Sections | `layouts/sections/{name}/{file}.blade.php` | `layouts/sections/menu/verticalMenu.blade.php` |
| Partials | `_partials/{name}.blade.php` | `_partials/toast.blade.php` |

### SCSS Files

| Type | Pattern | Example |
|------|---------|---------|
| Main | `{name}.scss` | `app.scss` |
| Partials | `_{name}.scss` | `_variables-custom.scss` |
| Custom | `custom/_{name}-custom.scss` | `custom/_components-custom.scss` |

### JavaScript Files

| Type | Pattern | Example |
|------|---------|---------|
| Main | `{feature}.js` | `main.js` |
| Page-specific | `{page}-{feature}.js` | `dashboards-analytics.js` |
| UI components | `ui-{component}.js` | `ui-modals.js` |

### Routes

| Type | Pattern | Example |
|------|---------|---------|
| Resource | `{feature-name}.{action}` | `usulan-kegiatan.store` |
| Custom | `{feature}.{custom-action}` | `kegiatan.proposal.upload` |
| Nested | `{parent}.{child}.{action}` | `kegiatan.laporan.index` |

### Database Tables

| Type | Convention | Example |
|------|------------|---------|
| Main tables | `{plural_name}` (snake_case) | `kegiatans` |
| Pivot tables | `{table1}_{table2}` | `user_roles` |
| Master tables | `{plural_name}` | `jenis_kegiatans` |

### CSS Classes

| Type | Pattern | Example |
|------|---------|---------|
| Components | `.{component-name}` | `.icon-wrapper` |
| Utilities | `.{property}-{value}` | `.icon-md` |
| Gradients | `.bg-gradient-{name}` | `.bg-gradient-primary` |
| Custom | `.{prefix}-{name}` | `.rounded-custom-lg` |

---

## Cara Mencari File

### By Feature (Contoh: Usulan Kegiatan)

| Component | Path |
|-----------|------|
| Controller | `app/Http/Controllers/UsulanKegiatanController.php` |
| Model | `app/Models/UsulanKegiatan.php` |
| Migration | `database/migrations/*_create_usulan_kegiatans_table.php` |
| Seeder | `database/seeders/UsulanKegiatanSeeder.php` (if exists) |
| Routes | `routes/web.php` (search "usulan-kegiatan") |
| Views | `resources/views/usulan-kegiatan/` |
| Menu Entry | `resources/menu/verticalMenu.json` (search "Usulan") |

### By Type (Contoh: Styles)

| Type | Locations |
|------|-----------|
| Custom SCSS | `resources/assets/scss/custom/*.scss` |
| Sneat SCSS | `resources/assets/vendor/scss/` (don't modify) |
| Compiled CSS | `public/build/assets/*.css` |
| Additional CSS | `resources/css/*.css` |

### By Function (Contoh: Menu)

| Component | Path |
|-----------|------|
| Menu JSON | `resources/menu/verticalMenu.json` |
| Menu Provider | `app/Providers/MenuServiceProvider.php` |
| Menu Template | `resources/views/layouts/sections/menu/verticalMenu.blade.php` |
| Menu Styles | `resources/assets/vendor/scss/core/_menu.scss` (Sneat) |
| Custom Menu CSS | `resources/assets/scss/app.scss` (menu overrides) |

---

## Workflow File Access

### 1. Development Workflow

| Step | Action | Files Affected |
|------|--------|----------------|
| 1 | Edit source SCSS | `resources/assets/scss/custom/*.scss` |
| 2 | Run compiler | `npm run dev` (auto-watch) |
| 3 | View changes | Browser auto-refresh |
| 4 | Check output | `public/build/assets/*.css` |
| 5 | Commit changes | Git commit |

### 2. Adding New Feature Workflow

| Step | Action | Files to Create/Edit |
|------|--------|----------------------|
| 1 | Create migration | `database/migrations/{date}_create_{table}_table.php` |
| 2 | Run migration | `php artisan migrate` |
| 3 | Create model | `app/Models/{ModelName}.php` |
| 4 | Create controller | `app/Http/Controllers/{Feature}Controller.php` |
| 5 | Add routes | `routes/web.php` |
| 6 | Update menu | `resources/menu/verticalMenu.json` |
| 7 | Create views | `resources/views/{feature}/` |
| 8 | Add styles (optional) | `resources/assets/scss/custom/_components-custom.scss` |
| 9 | Test feature | Browser testing |
| 10 | Document | `docs/REFACTORING-LOG.md` |

### 3. Bug Fixing Workflow

| Step | Action | Files to Check |
|------|--------|----------------|
| 1 | Check logs | `storage/logs/laravel.log` |
| 2 | Identify issue | Controllers, Models, Views |
| 3 | Fix code | Relevant files |
| 4 | Clear cache | `php artisan cache:clear` |
| 5 | Test fix | Browser testing |
| 6 | Commit | Git commit with bug fix message |

---

## File Statistics Summary

### By Category

| Category | File Count | Total Lines (Approx) |
|----------|------------|----------------------|
| PHP (Backend) | ~150 | ~15,000 |
| Blade (Views) | ~100 | ~12,000 |
| SCSS (Styles) | ~30 | ~8,000 |
| JavaScript | ~15 | ~3,000 |
| Config | ~20 | ~2,000 |
| Migrations | ~25 | ~3,000 |
| Documentation | ~10 | ~5,000 |
| **Total** | **~350** | **~48,000** |

### By Folder Size

| Folder | Files | Est. Size | Purpose |
|--------|-------|-----------|---------|
| `vendor/` | 10,000+ | ~100 MB | Composer packages |
| `node_modules/` | 50,000+ | ~300 MB | NPM packages |
| `public/build/` | ~50 | ~10 MB | Compiled assets |
| `resources/assets/vendor/` | ~100 | ~5 MB | Sneat template |
| `storage/` | Variable | 1-50 MB | Logs, uploads, cache |
| `app/` | ~150 | ~2 MB | Application code |
| `resources/views/` | ~100 | ~1.5 MB | Blade templates |
| `database/` | ~40 | ~500 KB | Migrations, seeders |
| `docs/` | ~10 | ~200 KB | Documentation |
| **Total (with dependencies)** | **~60,000+** | **~420 MB** |
| **Total (without dependencies)** | **~500** | **~20 MB** |

---

## Related Documentation

Untuk informasi lebih detail, lihat dokumentasi terkait:

| Document | File | Purpose |
|----------|------|---------|
| Project Overview | `docs/README.md` | Getting started & overview |
| Architecture | `docs/ARCHITECTURE.md` | System design patterns |
| CSS/SCSS Guide | `docs/CSS-SCSS-GUIDE.md` | Styling guidelines |
| JavaScript Guide | `docs/JAVASCRIPT-GUIDE.md` | JS patterns |
| Database Schema | `docs/DATABASE.md` | Database structure |
| API Routes | `docs/API-ROUTES.md` | Route documentation |
| Deployment | `docs/DEPLOYMENT.md` | Deployment procedures |
| Refactoring Log | `docs/REFACTORING-LOG.md` | Change history |

---

## Catatan Penting

### Files yang TIDAK BOLEH Dimodifikasi

| Path | Reason |
|------|--------|
| `resources/assets/vendor/scss/` | Sneat template original (update via npm) |
| `resources/assets/vendor/js/` | Sneat template original |
| `vendor/` | Composer packages (managed automatically) |
| `node_modules/` | NPM packages (managed automatically) |
| `public/build/` | Vite output (auto-generated) |
| `storage/framework/` | Laravel cache (auto-managed) |

### Files yang HARUS Di-backup

| File | Backup Before | Reason |
|------|---------------|--------|
| `.env` | Any deployment | Contains sensitive config |
| `database/` | Major changes | Schema & seed data |
| Custom SCSS | Refactoring | CSS changes |
| Custom Views | Major UI changes | Template modifications |
| `routes/web.php` | Route restructure | Route definitions |

---

**Dokumen dibuat:** 02 Februari 2026  
**Versi:** 1.0  
**Maintenance:** Update saat ada perubahan struktur signifikan  
**Contact:** [Your Email]
