# SIPEKMA - Development Phases Documentation

## Status Pengembangan: 13 Desember 2025

---

## 📋 PHASE PENGEMBANGAN SISTEM

### ✅ **PHASE 1: DATABASE RESTRUCTURE & AUTHENTICATION** (SELESAI 100%)

**Implementasi:**
- ✅ Struktur database dengan 9 tabel utama
  - `roles` - 5 role sistem (admin, hima, pembina_hima, kaprodi, wadek_iii)
  - `prodis` - 4 program studi
  - `users` - dengan relasi role & prodi
  - `kegiatans` - tabel utama kegiatan
  - `jenis_kegiatans` - master data jenis (7 jenis)
  - `jenis_pendanaans` - master data pendanaan (5 jenis)
  - `approval_histories` - tracking persetujuan
  - `kegiatan_files` - management file upload
  
- ✅ Custom Authentication System
  - LoginController dengan support username/email
  - Middleware: RoleMiddleware, AdminMiddleware, AdminOrWadekMiddleware
  - Session-based authentication
  - Bcrypt password hashing

- ✅ Role-Based Access Control (RBAC)
  - Helper methods di User model: `isAdmin()`, `isHima()`, `isPembina()`, `isKaprodi()`, `isWadek()`
  - Route middleware protection
  - Menu visibility berdasarkan role

**Status Enum:**
- `status_kegiatan`: draft, dikirim, disetujui, revisi, ditolak
- `tahap_kegiatan`: usulan, proposal, pendanaan, laporan

---

### ✅ **PHASE 2: MANAGEMENT USER & PRODI** (SELESAI 100%)

**Implementasi:**
- ✅ **User Management**
  - UsersController dengan full CRUD
  - UsersDataTable (Yajra DataTables)
  - Export Excel, CSV, PDF
  - Server-side processing
  - Access: admin & wadek_iii
  - Views: `resources/views/users/`

- ✅ **Program Studi Management** *(BARU DITAMBAHKAN)*
  - ProdiController dengan full CRUD
  - Pagination Laravel (10 items per page)
  - Fields: kode, nama, fakultas, deskripsi
  - Access: admin & wadek_iii
  - Views: `resources/views/prodi/` (index, create, edit)
  - Routes: `Route::resource('prodi', ProdiController::class)`

**Menu Structure:**
```
PENGATURAN (admin & wadek_iii only)
├── Kelola User
│   ├── Prodi (prodi.index)
│   └── User (users.index)
└── Kelola Kegiatan
    ├── Jenis Kegiatan (jenis-kegiatan.index)
    └── Jenis Pendanaan (jenis-pendanaan.index)
```

---

### ✅ **PHASE 3: MANAGEMENT JENIS KEGIATAN & PENDANAAN** (SELESAI 100%)

**Implementasi:**
- ✅ **Jenis Kegiatan Management** *(BARU DITAMBAHKAN)*
  - JenisKegiatanController dengan full CRUD
  - Pagination Laravel
  - Fields: nama, deskripsi, is_active (toggle status)
  - Access: admin & wadek_iii
  - Views: `resources/views/jenis-kegiatan/` (index, create, edit)
  - Routes: `Route::resource('jenis-kegiatan', JenisKegiatanController::class)`
  - **Data Master:** Seminar, Workshop, Pelatihan, Lomba, Webinar, Study Tour, Lainnya

- ✅ **Jenis Pendanaan Management** *(BARU DITAMBAHKAN)*
  - JenisPendanaanController dengan full CRUD
  - Pagination Laravel
  - Fields: nama, deskripsi, is_active (toggle status)
  - Access: admin & wadek_iii
  - Views: `resources/views/jenis-pendanaan/` (index, create, edit)
  - Routes: `Route::resource('jenis-pendanaan', JenisPendanaanController::class)`
  - **Data Master:** Mandiri, Sponsor, Hibah, Internal Kampus, Kombinasi

**Integration:**
- ✅ Dropdown Jenis Kegiatan di form create/edit kegiatan
- ✅ Dropdown Jenis Pendanaan di form create/edit kegiatan
- ✅ Display di riwayat kegiatan
- ✅ Eager loading `->with(['jenisKegiatan', 'jenisPendanaan'])`

---

### ✅ **CORE FEATURES** (SELESAI 100%)

**4-Stage Workflow Kegiatan:**
- ✅ **Tahap Usulan** - HIMA membuat usulan
- ✅ **Tahap Proposal** - Upload proposal setelah disetujui Pembina
- ✅ **Tahap Pendanaan** - Upload dokumen pendanaan setelah disetujui Kaprodi
- ✅ **Tahap Laporan** - Upload laporan kegiatan setelah disetujui Wadek III

**Progressive Approval System:**
- ✅ Pembina HIMA → approve/revisi/tolak usulan
- ✅ Kaprodi → approve/revisi/tolak proposal
- ✅ Wadek III → approve/revisi/tolak pendanaan & laporan
- ✅ Approval history tracking di database
- ✅ Status badge display (draft, dikirim, disetujui, revisi, ditolak)

**File Management:**
- ✅ File upload untuk setiap tahap
- ✅ Validation: PDF only, max 2MB
- ✅ Storage: `storage/app/kegiatan/{tahap}/`
- ✅ Display file links di detail view

**UI Components:**
- ✅ Toast notification system (Bootstrap)
- ✅ Menu JSON-driven (`resources/menu/verticalMenu.json`)
- ✅ Breadcrumb navigation
- ✅ Responsive table dengan dropdown action buttons
- ✅ Bootstrap 5 modal confirmations

---

### ❌ **PHASE 4: NOTIFIKASI IN-APP** (BELUM DIMULAI - 0%)

**Rencana Implementasi:**

#### Database:
```php
// Migration: create_notifications_table.php
Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('type'); // approval_request, approval_response, revision_needed
    $table->string('title');
    $table->text('message');
    $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatans');
    $table->boolean('is_read')->default(false);
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
});
```

#### Backend:
- [ ] NotificationController (index, markAsRead, markAllAsRead)
- [ ] Notification Model dengan relationships
- [ ] NotificationService untuk create notifications
- [ ] Event listeners untuk trigger notifikasi:
  - `KegiatanSubmitted` → notif ke Pembina
  - `KegiatanApproved` → notif ke HIMA & next approver
  - `KegiatanRevised` → notif ke HIMA
  - `KegiatanRejected` → notif ke HIMA

#### Frontend:
- [ ] Bell icon di navbar dengan badge counter
- [ ] Dropdown notification panel
- [ ] Mark as read functionality
- [ ] Real-time update (optional: Pusher/WebSocket)
- [ ] Notification list page (`/notifications`)

#### Routes:
```php
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});
```

**Prioritas:** HIGH - Critical missing feature untuk user experience

---

### 🔄 **PHASE 5: EXPORT & REPORTING** (30% SELESAI)

**Sudah Implementasi:**
- ✅ User export (Excel, CSV, PDF) via Yajra DataTables
- ✅ Export buttons di users table

**Belum Implementasi:**
- [ ] **Kegiatan Export**
  - [ ] Export riwayat kegiatan (Excel, CSV, PDF)
  - [ ] Filter by: tanggal, status, tahap, prodi, jenis kegiatan
  - [ ] Custom column selection
  
- [ ] **Reporting Dashboard**
  - [ ] Dashboard statistics untuk Wadek III:
    - Total kegiatan per status
    - Total kegiatan per prodi
    - Total kegiatan per jenis
    - Chart progress approval
  - [ ] Filter by date range
  - [ ] Export dashboard to PDF

- [ ] **Custom Reports**
  - [ ] Laporan bulanan per prodi
  - [ ] Laporan tahunan kegiatan
  - [ ] Laporan pendanaan
  - [ ] Report template dengan Laravel DomPDF

**Prioritas:** HIGH - Stakeholder requirement

---

### 🔄 **PHASE 6: SEARCH & FILTER + UI IMPROVEMENTS** (40% SELESAI)

**Sudah Implementasi:**
- ✅ Basic DataTables search di users table
- ✅ Sorting columns
- ✅ Pagination

**Belum Implementasi:**
- [ ] **Advanced Search & Filter**
  - [ ] Multi-field search form:
    - Nama kegiatan
    - PIC
    - Tanggal mulai/selesai (date range picker)
    - Status kegiatan (multi-select)
    - Tahap kegiatan
    - Prodi
    - Jenis kegiatan
    - Jenis pendanaan
  - [ ] Saved filter presets
  - [ ] Clear all filters button

- [ ] **UI Enhancements**
  - [ ] Loading states dengan spinner
  - [ ] Empty states dengan illustrations
  - [ ] Better error messages
  - [ ] Confirmation sweet alerts
  - [ ] Drag & drop file upload
  - [ ] File preview before upload

**Prioritas:** MEDIUM - UX enhancement

---

## 🎯 NEXT SPRINT RECOMMENDATION

### Sprint 1: Phase 4 - Notifikasi (Estimasi: 3-5 hari)
1. Buat migration & model Notification
2. Implement NotificationService & Events
3. Buat UI bell icon & dropdown
4. Test notification flow end-to-end

### Sprint 2: Phase 5 - Export & Reporting (Estimasi: 4-6 hari)
1. Kegiatan export (Excel, CSV, PDF)
2. Dashboard statistics untuk Wadek III
3. Custom report templates
4. Export dashboard to PDF

### Sprint 3: Phase 6 - Search & Filter (Estimasi: 2-3 hari)
1. Advanced filter form
2. Date range picker integration
3. Saved filter presets
4. UI improvements

---

## 📊 PROGRESS SUMMARY

| Phase | Status | Completion |
|-------|--------|------------|
| Phase 1: Database & Auth | ✅ Done | 100% |
| Phase 2: User & Prodi Mgmt | ✅ Done | 100% |
| Phase 3: Jenis Data Mgmt | ✅ Done | 100% |
| **Phase 4: Notifikasi** | ❌ Not Started | **0%** |
| Phase 5: Export & Reporting | 🔄 In Progress | 30% |
| Phase 6: Search & Filter | 🔄 In Progress | 40% |

**Overall Progress: 61.67%**

---

## 📝 TECHNICAL NOTES

### Seeder Data (Testing):
- `php artisan db:seed --class=SistemInformasiTestSeeder`
- 9 kegiatan realistis dengan varied jenis & approval histories
- 4 users per role untuk Sistem Informasi

### Naming Conventions:
- Routes: `kebab-case` (jenis-kegiatan, jenis-pendanaan)
- Controllers: `PascalCase` (ProdiController, JenisKegiatanController)
- Views folder: `kebab-case` (jenis-kegiatan/, jenis-pendanaan/)
- Database columns: `snake_case` (is_active, created_at)

### Code Standards:
- Laravel Pint untuk code formatting: `php artisan pint`
- Eager loading untuk optimize queries
- Form validation di controller
- Blade components untuk reusability
- Toast notifications untuk user feedback

---

**Dokumentasi ini akan di-update seiring progress pengembangan.**
**Last Updated: 13 Desember 2025**
