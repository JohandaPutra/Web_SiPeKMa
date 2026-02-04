# SIPEKMA - Dokumentasi Proyek

**Sistem Informasi Pengelolaan Kegiatan Mahasiswa**

---

## 📖 Informasi Proyek

| Item | Detail |
|------|--------|
| **Nama Proyek** | SIPEKMA (Sistem Informasi Pengelolaan Kegiatan Mahasiswa) |
| **Tipe** | Web Application - Proyek Skripsi |
| **Framework** | Laravel 11 |
| **Template** | Sneat Bootstrap Admin (Free) |
| **Database** | MySQL 8.4+ |
| **Version** | 1.0.0 |
| **Last Update** | 02 Februari 2026 |
| **Status** | Development |

---

## 🎯 Deskripsi

SIPEKMA adalah sistem manajemen usulan kegiatan mahasiswa berbasis web yang memfasilitasi proses pengajuan, review, dan approval kegiatan dengan alur workflow yang terstruktur.

### Masalah yang Diselesaikan

| No | Masalah | Solusi SIPEKMA |
|----|---------|----------------|
| 1 | Proses manual memakan waktu lama | Digitalisasi proses approval |
| 2 | Sulit tracking status usulan | Real-time status monitoring |
| 3 | Dokumen sering hilang | Digital file management |
| 4 | Tidak ada history approval | Approval history logging |
| 5 | Laporan sulit dibuat | Auto-generate reports & export |

---

## ✨ Fitur Utama

### Workflow Kegiatan

| Tahap | Deskripsi | Role yang Terlibat |
|-------|-----------|-------------------|
| **1. Usulan** | Mahasiswa mengajukan usulan kegiatan | HIMA, BEM |
| **2. Proposal** | Upload proposal detail & RAB | HIMA, BEM |
| **3. Pendanaan** | Upload dokumen pendanaan | HIMA, BEM, Finance |
| **4. Laporan** | Upload laporan pertanggungjawaban | HIMA, BEM |

### Fitur Lengkap

| Kategori | Fitur | Status |
|----------|-------|--------|
| **Manajemen Kegiatan** | CRUD Usulan Kegiatan | ✅ |
| | Upload Proposal (PDF, DOCX) | ✅ |
| | Upload RAB Pendanaan (PDF, XLSX) | ✅ |
| | Upload Laporan (PDF, DOCX) | ✅ |
| | Preview File Online | ✅ |
| **Approval System** | Multi-level Approval Workflow | ✅ |
| | Approval History Tracking | ✅ |
| | Status Notification | ✅ |
| **User Management** | Role-based Access Control | ✅ |
| | User CRUD (Admin) | ✅ |
| **Master Data** | Jenis Kegiatan Management | ✅ |
| | Jenis Pendanaan Management | ✅ |
| | Program Studi Management | ✅ |
| **Reporting** | Export to Excel | ✅ |
| | Export to PDF | ✅ |
| | Riwayat Kegiatan | ✅ |
| **UI/UX** | Responsive Mobile Design | ✅ |
| | Toast Notifications | ✅ |
| | DataTables with Search & Filter | ✅ |

---

## 🚀 Quick Start

### Prerequisites

| Software | Version | Required |
|----------|---------|----------|
| PHP | 8.2+ | ✅ Wajib |
| Composer | 2.8+ | ✅ Wajib |
| Node.js | 24+ | ✅ Wajib |
| NPM | 11+ | ✅ Wajib |
| MySQL | 8.4+ | ✅ Wajib |
| Laragon | Latest | 🟡 Recommended |

### Installation Steps

| Step | Command | Purpose |
|------|---------|---------|
| 1 | `git clone [repo-url]` | Clone repository |
| 2 | `cd Web_SiPeKMa` | Masuk ke folder project |
| 3 | `composer install` | Install PHP dependencies |
| 4 | `npm install` | Install Node dependencies |
| 5 | `cp .env.example .env` | Copy environment file |
| 6 | `php artisan key:generate` | Generate app key |
| 7 | Edit `.env` | Configure database |
| 8 | `php artisan migrate` | Run migrations |
| 9 | `php artisan db:seed` | Seed database |
| 10 | `npm run dev` | Start Vite dev server |
| 11 | `php artisan serve --port=8001` | Start Laravel server |

### Database Configuration (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sipekma_2
DB_USERNAME=root
DB_PASSWORD=
```

### Access URLs

| Service | URL | Purpose |
|---------|-----|---------|
| **Laravel App** | http://127.0.0.1:8001 | Main application |
| **Vite Dev Server** | http://localhost:5174 | Hot reload assets |

---

## 📂 Struktur Proyek

### Folder Utama

| Folder | Purpose | Documentation |
|--------|---------|---------------|
| `app/` | Backend logic (Controllers, Models) | [FILE-STRUCTURE.md](FILE-STRUCTURE.md#folder-backend-app) |
| `config/` | Configuration files | [FILE-STRUCTURE.md](FILE-STRUCTURE.md#folder-konfigurasi-config) |
| `database/` | Migrations, seeders, factories | [FILE-STRUCTURE.md](FILE-STRUCTURE.md#folder-database-database) |
| `docs/` | **Project documentation** | This folder |
| `public/` | Entry point & public assets | [FILE-STRUCTURE.md](FILE-STRUCTURE.md#folder-public-assets-public) |
| `resources/` | Views (Blade), SCSS, JS | [FILE-STRUCTURE.md](FILE-STRUCTURE.md#folder-resources-resources) |
| `routes/` | Route definitions | [FILE-STRUCTURE.md](FILE-STRUCTURE.md#folder-routes-routes) |
| `storage/` | Logs, cache, uploads | [FILE-STRUCTURE.md](FILE-STRUCTURE.md#folder-storage-storage) |

### File Konfigurasi Penting

| File | Purpose |
|------|---------|
| `.env` | Environment configuration |
| `composer.json` | PHP dependencies |
| `package.json` | Node.js dependencies |
| `vite.config.js` | Vite bundler config |

---

## 📚 Dokumentasi Lengkap

### Dokumentasi Teknis

| Dokumen | Deskripsi | Status |
|---------|-----------|--------|
| [FILE-STRUCTURE.md](FILE-STRUCTURE.md) | **Struktur file & folder lengkap** (1,100+ lines) | ✅ Complete |
| [CSS-SCSS-GUIDE.md](CSS-SCSS-GUIDE.md) | **CSS/SCSS guidelines & best practices** (1,200+ lines) | ✅ Complete |
| [REFACTORING-LOG.md](REFACTORING-LOG.md) | **Change history & refactoring log** | ✅ Complete |
| [ARCHITECTURE.md](ARCHITECTURE.md) | Arsitektur sistem & design patterns | 🔄 In Progress |
| [JAVASCRIPT-GUIDE.md](JAVASCRIPT-GUIDE.md) | JavaScript patterns & conventions | 🔄 In Progress |
| [DATABASE.md](DATABASE.md) | Database schema & relationships | 🔄 In Progress |
| [API-ROUTES.md](API-ROUTES.md) | Routes documentation | 🔄 In Progress |
| [DEPLOYMENT.md](DEPLOYMENT.md) | Deployment procedures | ⏳ Planned |

### Dokumentasi AI

| Dokumen | Deskripsi | Location |
|---------|-----------|----------|
| copilot-instructions.md | **AI Copilot rules & guidelines** | `.github/` |

---

## 🛠️ Development Commands

### Laravel Commands

| Command | Purpose | Frequency |
|---------|---------|-----------|
| `php artisan serve` | Start development server (port 8000) | Daily |
| `php artisan serve --port=8001` | Start on custom port | Daily |
| `php artisan migrate` | Run pending migrations | When needed |
| `php artisan migrate:fresh` | Fresh migration (⚠️ drops all tables) | Development only |
| `php artisan db:seed` | Seed database | After fresh migration |
| `php artisan cache:clear` | Clear application cache | When needed |
| `php artisan config:clear` | Clear config cache | After config changes |
| `php artisan route:list` | List all routes | Reference |
| `php artisan pint` | Format code (Laravel Pint) | Before commit |

### NPM Commands

| Command | Purpose | When to Use |
|---------|---------|-------------|
| `npm install` | Install dependencies | Initial setup / after pull |
| `npm run dev` | **Development mode** (hot reload) | Daily development |
| `npm run build` | **Production build** | Before deployment |
| `npm run lint` | Check code quality | Before commit |

### Database Commands

| Command | Purpose | Caution |
|---------|---------|---------|
| `php artisan migrate` | Run migrations | ✅ Safe |
| `php artisan migrate:rollback` | Rollback last batch | ⚠️ Data loss |
| `php artisan migrate:fresh` | Drop all & re-migrate | ⚠️ Destructive |
| `php artisan migrate:fresh --seed` | Fresh + seed data | ⚠️ Destructive |
| `php artisan db:seed` | Run seeders | ✅ Safe |
| `php artisan db:seed --class=AdminUserSeeder` | Run specific seeder | ✅ Safe |

---

## 🏗️ Tech Stack

### Backend

| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel | 11.x | PHP Framework |
| PHP | 8.2+ | Programming Language |
| MySQL | 8.4+ | Database |
| Composer | 2.8+ | PHP Package Manager |

### Frontend

| Technology | Version | Purpose |
|------------|---------|---------|
| Bootstrap | 5.x | CSS Framework |
| SCSS | - | CSS Preprocessor |
| JavaScript | ES6+ | Programming Language |
| Vite | 5.4+ | Frontend Build Tool |
| Node.js | 24.x | JavaScript Runtime |

### Libraries & Packages

| Package | Purpose | Type |
|---------|---------|------|
| Yajra DataTables | Server-side tables | Backend |
| Laravel Pint | Code formatting | Dev Tool |
| Sneat Template | Admin template | Frontend |
| Boxicons | Icon library | Frontend |
| ApexCharts | Dashboard charts | Frontend |

---

## 👥 User Roles

### Role Hierarchy

| Role | Code | Permissions | Access Level |
|------|------|-------------|--------------|
| **Super Admin** | `super_admin` | Full access | 100% |
| **Admin** | `admin` | Manage users, master data | 80% |
| **BEM** | `bem` | Create & manage all kegiatan | 70% |
| **HIMA** | `hima` | Create & manage HIMA kegiatan | 60% |
| **Finance** | `finance` | View & approve pendanaan | 40% |

### Role-based Features

| Feature | Super Admin | Admin | BEM | HIMA | Finance |
|---------|-------------|-------|-----|------|---------|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| Create Usulan | ✅ | ✅ | ✅ | ✅ | ❌ |
| Approve Usulan | ✅ | ✅ | ✅ | ❌ | ❌ |
| Upload Proposal | ✅ | ✅ | ✅ | ✅ | ❌ |
| Approve Proposal | ✅ | ✅ | ✅ | ❌ | ❌ |
| Upload Pendanaan | ✅ | ✅ | ✅ | ✅ | ❌ |
| Approve Pendanaan | ✅ | ✅ | ✅ | ❌ | ✅ |
| Upload Laporan | ✅ | ✅ | ✅ | ✅ | ❌ |
| User Management | ✅ | ✅ | ❌ | ❌ | ❌ |
| Master Data | ✅ | ✅ | ❌ | ❌ | ❌ |
| Export Data | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 🔄 Workflow Diagram

### Status Flow

```
DRAFT
  ↓
SUBMITTED (Menunggu Review)
  ↓
REVIEW (Sedang Direview)
  ↓
APPROVED / REJECTED
```

### Tahap Kegiatan

```
1. USULAN
   ├─ Create usulan
   ├─ Upload detail
   └─ Submit for approval
   
2. PROPOSAL
   ├─ Upload proposal file (PDF/DOCX)
   ├─ Upload RAB
   └─ Wait approval
   
3. PENDANAAN
   ├─ Upload dokumen pendanaan
   ├─ Finance review
   └─ Approval
   
4. LAPORAN
   ├─ Upload LPJ (Laporan Pertanggungjawaban)
   ├─ Upload dokumentasi
   └─ Complete
```

---

## 📊 Database Schema Summary

### Main Tables

| Table | Records | Purpose |
|-------|---------|---------|
| `users` | 50+ | System users |
| `roles` | 5 | User roles |
| `prodis` | 15+ | Program studi |
| `kegiatans` | 100+ | Main kegiatan data |
| `approval_histories` | 200+ | Approval logs |
| `kegiatan_files` | 150+ | File attachments |
| `jenis_kegiatans` | 8 | Kegiatan types |
| `jenis_pendanaans` | 5 | Pendanaan types |

### Relationships

| Parent | Child | Type |
|--------|-------|------|
| `roles` → `users` | One to Many | role_id |
| `prodis` → `users` | One to Many | prodi_id |
| `users` → `kegiatans` | One to Many | user_id |
| `kegiatans` → `approval_histories` | One to Many | kegiatan_id |
| `kegiatans` → `kegiatan_files` | One to Many | kegiatan_id |
| `jenis_kegiatans` → `kegiatans` | One to Many | jenis_kegiatan_id |

---

## 🎨 UI/UX Features

### Responsive Design

| Breakpoint | Width | Optimization |
|------------|-------|--------------|
| Mobile | < 576px | Vertical layout, hidden breadcrumbs |
| Tablet | 576px - 991px | 2-column grid |
| Desktop | ≥ 992px | Full layout with sidebar |

### Design Patterns

| Pattern | Implementation | Benefit |
|---------|----------------|---------|
| **Utility-first CSS** | Custom SCSS classes | Reusability |
| **Component-based** | Reusable Blade components | DRY principle |
| **Mobile-first** | Progressive enhancement | Better mobile UX |
| **Gradient Design** | Custom gradient backgrounds | Modern look |
| **Icon-driven** | Boxicons integration | Visual clarity |

---

## 🐛 Debugging & Logs

### Log Locations

| Log Type | Location | Purpose |
|----------|----------|---------|
| **Application Logs** | `storage/logs/laravel.log` | Errors, warnings, info |
| **Daily Logs** | `storage/logs/laravel-YYYY-MM-DD.log` | Daily rotation |
| **Query Logs** | Enable in code | Database query debugging |
| **Vite Logs** | Terminal output | Asset compilation issues |

### Common Issues

| Issue | Solution | File to Check |
|-------|----------|---------------|
| White screen | Check `storage/logs/laravel.log` | Application logs |
| CSS not loading | Run `npm run dev` or `npm run build` | Vite compilation |
| Database error | Check `.env` DB config | Environment file |
| Route not found | Run `php artisan route:clear` | Route cache |
| View not found | Check blade file path | View files |

---

## � Export Documentation

### Convert to Word/PDF

Dokumentasi ini dapat diexport ke format Word (.docx) untuk keperluan presentasi atau sidang:

**Quick Export:**
```powershell
# Convert semua docs ke Word format
.\scripts\convert-docs.ps1

# Convert ke HTML format
.\scripts\convert-docs.ps1 -Format html
```

**Output:**
- `docs/pdf/01-README.docx`
- `docs/pdf/02-FILE-STRUCTURE.docx`
- `docs/pdf/03-CSS-SCSS-GUIDE.docx`
- `docs/pdf/04-JAVASCRIPT-GUIDE.docx`
- `docs/pdf/05-DATABASE.docx`
- `docs/pdf/06-ARCHITECTURE.docx`
- `docs/pdf/07-API-ROUTES.docx`
- `docs/pdf/08-DEPLOYMENT.docx`
- `docs/pdf/09-REFACTORING-LOG.docx`

**First Time Setup:**
```powershell
# Install Pandoc document converter
.\scripts\install-pandoc.ps1

# Restart terminal, then convert
.\scripts\convert-docs.ps1
```

**Features:**
- ✅ Auto-generate table of contents
- ✅ Section numbering
- ✅ Metadata (author, date, title)
- ✅ Preserves formatting & code blocks
- ✅ Ready to print

See [scripts/README.md](../scripts/README.md) for detailed instructions.

---

## �📦 Deployment

### Pre-deployment Checklist

| Task | Command | Status |
|------|---------|--------|
| ✅ Test all features | Manual testing | Required |
| ✅ Run code formatter | `php artisan pint` | Required |
| ✅ Clear caches | `php artisan cache:clear` | Required |
| ✅ Build assets | `npm run build` | Required |
| ✅ Optimize routes | `php artisan route:cache` | Optional |
| ✅ Optimize config | `php artisan config:cache` | Optional |
| ✅ Backup database | `mysqldump` | Critical |
| ✅ Update .env | Edit production values | Critical |

### Environment Settings

| Setting | Development | Production |
|---------|-------------|------------|
| `APP_ENV` | local | production |
| `APP_DEBUG` | true | false |
| `APP_URL` | http://localhost | https://yourdomain.com |
| `DB_HOST` | 127.0.0.1 | Production DB host |
| `LOG_LEVEL` | debug | error |

---

## 🔒 Security Considerations

### Built-in Laravel Security

| Feature | Status | Description |
|---------|--------|-------------|
| CSRF Protection | ✅ Enabled | All forms protected |
| Password Hashing | ✅ Bcrypt | Secure password storage |
| SQL Injection Prevention | ✅ Eloquent | Prepared statements |
| XSS Protection | ✅ Blade | Auto-escaping output |
| Session Security | ✅ Enabled | Secure session handling |

### Recommendations

| Task | Priority | Implementation |
|------|----------|----------------|
| Enable HTTPS | 🔴 High | Production deployment |
| Strong passwords | 🔴 High | Password validation rules |
| Regular backups | 🔴 High | Automated backup script |
| Update dependencies | 🟡 Medium | `composer update`, `npm update` |
| Code review | 🟡 Medium | Before deployment |

---

## 📈 Performance Optimization

### Current Optimizations

| Optimization | Status | Benefit |
|--------------|--------|---------|
| Eager Loading | ✅ Implemented | Reduce N+1 queries |
| Route Caching | ✅ Available | Faster routing |
| View Caching | ✅ Available | Compiled Blade views |
| Asset Minification | ✅ Vite build | Smaller file sizes |
| CSS Refactoring | ✅ Completed | Better cacheability |

### Future Improvements

| Task | Priority | Expected Impact |
|------|----------|-----------------|
| Database Indexing | 🟡 Medium | Faster queries |
| Redis Caching | 🟢 Low | Reduced DB load |
| CDN Integration | 🟢 Low | Faster asset delivery |
| Image Optimization | 🟡 Medium | Smaller images |

---

## 🧪 Testing

### Testing Approach

| Type | Coverage | Status |
|------|----------|--------|
| Manual Testing | 100% | ✅ Ongoing |
| Browser Testing | Chrome, Firefox, Edge | ✅ Done |
| Mobile Testing | iOS, Android | ✅ Done |
| Unit Testing | 0% | ⏳ Planned |
| Feature Testing | 0% | ⏳ Planned |

### Test Scenarios

| Scenario | Status |
|----------|--------|
| Create usulan kegiatan | ✅ Pass |
| Upload proposal file | ✅ Pass |
| Approval workflow | ✅ Pass |
| Export to Excel/PDF | ✅ Pass |
| Responsive mobile layout | ✅ Pass |
| Toast notifications | ✅ Pass |
| DataTables search & filter | ✅ Pass |

---

## 📞 Support & Contact

### Documentation

| Resource | Link |
|----------|------|
| Project Documentation | `docs/` folder |
| Laravel Documentation | https://laravel.com/docs/11.x |
| Bootstrap Documentation | https://getbootstrap.com/docs/5.3 |
| Vite Documentation | https://vitejs.dev |

### Repository

| Item | Value |
|------|-------|
| GitHub Repository | [Your Repo URL] |
| Branch | master/main |
| Last Commit | [Date] |

---

## 📝 Change Log

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2026-02-02 | Initial release with CSS refactoring |
| 0.9.0 | 2026-01-15 | Beta version with all features |
| 0.5.0 | 2025-12-20 | Alpha version with core features |

For detailed changes, see [REFACTORING-LOG.md](REFACTORING-LOG.md)

---

## 🎓 For Academic Review

### Proyek Skripsi

| Item | Detail |
|------|--------|
| **Mahasiswa** | [Your Name] |
| **NIM** | [Your NIM] |
| **Program Studi** | Teknik Informatika / Sistem Informasi |
| **Universitas** | [Your University] |
| **Pembimbing** | [Dosen Pembimbing] |
| **Tahun** | 2026 |

### Key Achievements

| Achievement | Metric |
|-------------|--------|
| Lines of Code | ~48,000 lines |
| Files Created | ~350 files |
| Features Implemented | 15+ major features |
| CSS Refactoring | 50+ inline styles → 0 |
| Documentation | 1,500+ lines |
| Development Time | 8 weeks |

---

**Last Updated:** 02 Februari 2026  
**Version:** 1.0.0  
**Status:** Active Development
