# SIPEKMA - Panduan AI Coding Agent

## ⚠️ ATURAN PENTING - WAJIB DIBACA

**SEBELUM melakukan perubahan apapun pada project ini, WAJIB mengajukan pertanyaan konfirmasi terlebih dahulu jika:**

1. **Detail implementasi kurang jelas** - Tanyakan spesifikasi lengkap (nama field, tipe data, validasi, dll)
2. **Ada beberapa opsi pendekatan** - Tanyakan pendekatan mana yang diinginkan
3. **Perubahan berdampak besar** - Konfirmasi sebelum mengubah struktur database, routes, atau arsitektur
4. **Naming convention tidak jelas** - Tanyakan penamaan yang diinginkan untuk fitur/file/variable
5. **Desain UI/UX tidak dispesifikasi** - Tanyakan layout, warna, icon, atau style yang diinginkan
6. **Logika bisnis ambigu** - Pastikan alur workflow dan aturan validasi yang tepat

**Format konfirmasi yang baik:**

- Ringkas permintaan user
- List hal-hal yang perlu dikonfirmasi dengan opsi jika memungkinkan
- Tunggu approval sebelum eksekusi

**Contoh:** "Saya akan membuat fitur X. Sebelum mulai, mohon konfirmasi: 1) Apakah field Y bertipe Z atau A? 2) Apakah validasi B diperlukan? 3) Icon mana yang Anda inginkan untuk menu ini?"

**SETELAH melakukan perubahan, WAJIB memberikan summary lengkap dengan format:**

### 1. **Summary Tabel Perbandingan**
Buat tabel dengan kolom: Issue, Sebelum, Sesudah, Impact/Fix

| Issue | Sebelum | Sesudah | Impact/Fix |
|-------|---------|---------|------------|
| Judul terpotong di desktop | max-width: 350px | max-width: 400px | +50px, no truncation |
| Spacing terlalu besar | `<br>` tag (16px) | margin: 0.125rem (2px) | -14px spacing |
| Missing wrapper | `<strong>` direct in `<td>` | `<div>` wrapper + `.d-block` | Better structure |

**Wajib include:**
- Issue yang diperbaiki (problem statement)
- Kondisi SEBELUM (value/code)
- Kondisi SESUDAH (value/code)
- Impact kuantitatif (angka, improvement, dll)

### 2. **Changes Detail**
Sebutkan file dan line yang diubah dengan penjelasan singkat:

**File Modified:**
- `resources/assets/scss/custom/_utilities-custom.scss` (lines 273-290)
  - Changed max-width desktop: 350px → 400px
  - Changed max-width mobile: 200px → 220px
  - Added tablet breakpoint (280px)
  - Reduced margin-bottom: 0.25rem → 0.125rem
  - Added hyphens: auto for better word breaking

- `resources/views/kegiatan/riwayat/index.blade.php` (lines 147-149)
  - Added `<div>` wrapper inside `<td class="td-kegiatan-name">`
  - Added `.d-block` class to `<strong>` tag

**Wajib include:**
- Nama file lengkap (dengan path)
- Line numbers yang diubah
- Penjelasan singkat perubahan (bullet points)
- TIDAK PERLU code snippet (untuk mengurangi workload)

### 3. **Expected Results**
Jelaskan hasil yang diharapkan setelah perubahan diterapkan:

**Desktop (>768px):**
- ✅ Judul tidak terpotong (max-width 400px)
- ✅ Spacing compact (2px instead of 4px)
- ✅ Better word breaking dengan hyphenation

**Mobile (<768px):**
- ✅ Width lebih lebar (220px vs 200px)
- ✅ No horizontal overflow
- ✅ Text wrap properly

**Performance:**
- ✅ Reduced HTML size (removed `<br>` tags)
- ✅ Better CSS cacheability

**Wajib include:**
- Hasil visual yang diharapkan (per breakpoint jika responsive)
- Performance improvements (jika ada)
- User experience improvements
- Testing instructions (apa yang perlu di-verify)

---

## Gambaran Umum Proyek

SIPEKMA (Sistem Informasi Usulan Kegiatan) adalah sistem manajemen usulan kegiatan berbasis Laravel 11 yang dibangun menggunakan template admin Sneat Bootstrap. Ini adalah proyek skripsi untuk mengelola usulan kegiatan mahasiswa dengan alur persetujuan.

## Stack Teknologi

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Bootstrap 5, SCSS via Vite
- **Database**: MySQL
- **Package Utama**: Yajra DataTables, Livewire (penggunaan minimal)
- **Template**: Sneat Bootstrap Admin (versi gratis)

## Pola Arsitektur

### Sistem Menu

Navigasi menggunakan **JSON-driven**, bukan berbasis route:

- Struktur menu berada di `resources/menu/verticalMenu.json`
- `MenuServiceProvider` memuat JSON dan membagikan `$menuData` ke semua view
- Template Blade di `resources/views/layouts/sections/menu/verticalMenu.blade.php` merender menu secara rekursif
- **Pola**: Item menu menggunakan field `slug` (string atau array) untuk mencocokkan state aktif dengan nama route
- **Menu selalu terbuka**: Set `"alwaysopen": true` untuk submenu yang terbuka secara default
- Saat menambah fitur baru: update `verticalMenu.json` terlebih dahulu, kemudian buat routes/controllers

### Sistem Notifikasi Toast

Implementasi custom Bootstrap toast untuk feedback pengguna:

- Include `@include('_partials/toast')` di layouts untuk mengaktifkan
- Flash messages: `->with('success', 'message')`, `->with('error', 'message')`, dll.
- Tipe yang didukung: `success`, `error`, `warning`, `info`
- Tampil otomatis dengan delay 5 detik, tidak perlu JavaScript di controllers

### Integrasi DataTables

Menggunakan Yajra DataTables untuk tabel server-side:

- Buat class DataTable di `app/DataTables/` (lihat `UsersDataTable.php` sebagai template)
- Controllers memanggil `$dataTable->render('view.name')`
- Tombol export (Excel, CSV, PDF) dikonfigurasi di method `html()` DataTable
- Frontend menggunakan package `laravel-datatables-vite` untuk kompilasi asset

### Konvensi Model

- **Field enum**: Gunakan database enum dengan match expression untuk logika tampilan
  ```php
  // Contoh dari model UsulanKegiatan
  public function getStatusBadgeAttribute(): string {
    return match($this->status_kegiatan) {
      'draft' => 'secondary',
      'approved' => 'success',
      // ...
    };
  }
  ```
- **Relationships**: Selalu eager-load dengan `->with('relation')` di controllers
- **Date casting**: Gunakan `protected $casts = ['field' => 'date']` untuk field tanggal

### Struktur View

- Layouts: `resources/views/layouts/contentNavbarLayout.blade.php` (utama)
- Partials: `resources/views/_partials/` untuk komponen reusable
- Feature views: Diorganisir berdasarkan folder fitur (misal: `usulan-kegiatan/`, `users/`)
- **Variabel template**: Akses via `config('variables.templateName')` dll. (didefinisikan di `config/variables.php`)

## Alur Kerja Development

### Kompilasi Asset (Vite)

```powershell
npm run dev      # Development dengan hot reload
npm run build    # Production build
```

Konfigurasi Vite otomatis mendeteksi:

- Page JS: `resources/assets/js/*.js`
- Vendor JS: `resources/assets/vendor/js/*.js` dan `resources/assets/vendor/libs/**/*.js`
- SCSS: `resources/assets/vendor/scss/**/!(_)*.scss` (kecuali partial yang diawali `_`)
- Fonts: `resources/assets/vendor/fonts/!(_)*.scss`

### Alur Kerja Database

```powershell
php artisan migrate           # Jalankan migrations
php artisan db:seed          # Seed database
php artisan migrate:fresh    # Fresh migration (menghapus semua data)
```

Penamaan migration: `YYYY_MM_DD_HHMMSS_description_table.php`

### Development Server

```powershell
php artisan serve    # http://localhost:8000
```

### Kualitas Kode

```powershell
php artisan pint    # Laravel Pint untuk formatting kode
```

## Pola Khusus Proyek

### Pola Default User

Controllers menggunakan helper `getDefaultUser()` untuk testing tanpa autentikasi:

```php
private function getDefaultUser() {
  return User::first() ?? User::create([...]);
}
```

**Catatan**: Ini adalah pola development. Hapus saat mengimplementasikan autentikasi yang proper.

### Validasi Form

Validasi Laravel standar di method controller:

- Definisikan rules di method `store()/update()`
- Gunakan `after_or_equal` untuk validasi rentang tanggal (lihat `UsulanKegiatanController`)
- Return dengan `->withInput()` pada error untuk mempertahankan data form

### Navigasi Breadcrumb

Setiap halaman konten menyertakan markup breadcrumb:

```blade
<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-style1 mb-0">
    <li class="breadcrumb-item"><a href="...">Dashboard</a></li>
    <li class="breadcrumb-item active">Current Page</li>
  </ol>
</nav>
```

### Manajemen Status

Kegiatan memiliki alur status (`draft` → `submitted` → `review` → `approved`/`rejected`):

- Gunakan field enum di database untuk type safety
- Buat method helper badge di model untuk tampilan UI
- Validasi transisi state di logika controller

---

## Konvensi Bahasa & Coding Standards

### Prinsip Hybrid Approach

Proyek ini menggunakan **hybrid approach** yang menggabungkan best practices internasional dengan kebutuhan lokal:

- **Database & Code Layer**: English (mengikuti Laravel & PSR standards)
- **Display & User Interface**: Bahasa Indonesia (optimal UX untuk pengguna lokal)
- **Comments & Documentation**: Bahasa Indonesia (konteks akademik & tim lokal)

**Filosofi**: "Code like a professional, document for your team, display for your users"

---

### 🇮🇩 Bahasa Indonesia - WAJIB untuk:

#### 1. Comments & Penjelasan Kode

**Semua comments dalam kode HARUS menggunakan Bahasa Indonesia:**

```php
// ✅ BENAR: Comments Indonesia
// Validasi data usulan kegiatan sebelum disimpan
public function store(Request $request)
{
    // Cek apakah user memiliki hak akses
    if (!auth()->user()->can('create-usulan')) {
        return back()->with('error', 'Akses ditolak');
    }
    
    // Simpan data usulan ke database
    $usulan = UsulanKegiatan::create($validatedData);
    
    return redirect()->route('usulan-kegiatan.index')
        ->with('success', 'Usulan berhasil disimpan');
}

// ❌ SALAH: Comments English untuk project lokal
// Validate proposal data before saving
public function store(Request $request) { ... }
```

**Docblock juga menggunakan Bahasa Indonesia:**

```php
/**
 * Mengambil semua usulan kegiatan yang sudah disetujui
 * 
 * Method ini melakukan query ke database untuk mendapatkan
 * data usulan dengan status 'approved' beserta relasi user
 * 
 * @return Collection
 */
public function getApprovedUsulan(): Collection
{
    // Ambil data usulan dengan eager loading
    $approved = UsulanKegiatan::where('status', 'approved')
        ->with('user', 'jenisKegiatan')
        ->latest()
        ->get();
    
    return $approved;
}
```

#### 2. Git Commit Messages

**Format commit menggunakan Bahasa Indonesia:**

```bash
# Format Standar
<type>: <subjek ringkas dalam bahasa Indonesia>

[body opsional dengan penjelasan detail]

[footer opsional untuk referensi issue]
```

**Tipe Commit yang Digunakan:**

| Tipe | Penggunaan | Contoh |
|------|------------|--------|
| `feat` | Fitur baru | `feat: tambah fitur upload proposal` |
| `fix` | Perbaikan bug | `fix: perbaiki validasi file size` |
| `refactor` | Refactoring code | `refactor: pindahkan inline CSS ke SCSS` |
| `style` | Format code | `style: format UsulanKegiatanController` |
| `docs` | Update dokumentasi | `docs: tambah DATABASE.md` |
| `test` | Tambah/update test | `test: tambah unit test untuk approval` |
| `chore` | Maintenance | `chore: update dependencies` |
| `perf` | Performance | `perf: optimasi query N+1` |

**Contoh Commit Lengkap:**

```bash
feat: tambah fitur approval usulan kegiatan

- Implementasi method approve() dan reject() di controller
- Tambah pencatatan approval history ke database
- Update status usulan ke approved/rejected
- Tambah validation sebelum melakukan approval
- Tambah flash message sukses/error untuk user feedback

Resolves #12
```

```bash
fix: perbaiki bug validasi tanggal kegiatan

Sebelumnya validasi tanggal_selesai tidak memeriksa apakah
tanggal tersebut setelah tanggal_mulai. Sekarang ditambahkan
rule 'after_or_equal:tanggal_mulai' untuk memastikan konsistensi.

Fixes #24
```

```bash
refactor: pindahkan inline CSS ke external SCSS

- Buat file _variables-custom.scss untuk SIPEKMA variables
- Buat file _components-custom.scss untuk reusable components
- Buat file _utilities-custom.scss untuk utility classes
- Hapus 53 inline styles dari blade files
- Hapus 94 baris internal CSS

Impact: Improve cacheability & maintainability
```

#### 3. Display Layer (UI/UX)

**Semua yang dilihat user WAJIB Bahasa Indonesia:**

```blade
{{-- Status Labels --}}
<span class="badge bg-success">Disetujui</span>
<span class="badge bg-danger">Ditolak</span>
<span class="badge bg-warning">Revisi</span>
<span class="badge bg-info">Diajukan</span>

{{-- Button Labels --}}
<button class="btn btn-success">Setujui</button>
<button class="btn btn-danger">Tolak</button>
<button class="btn btn-primary">Simpan</button>
<button class="btn btn-secondary">Batal</button>

{{-- Form Labels --}}
<label for="nama_kegiatan">Nama Kegiatan</label>
<label for="tanggal_mulai">Tanggal Mulai</label>
<label for="tempat_kegiatan">Tempat Kegiatan</label>

{{-- Table Headers --}}
<thead>
    <tr>
        <th>No</th>
        <th>Nama Kegiatan</th>
        <th>Status</th>
        <th>Tanggal</th>
        <th>Aksi</th>
    </tr>
</thead>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        {{-- Contoh: "Data berhasil disimpan" --}}
    </div>
@endif

{{-- Validation Messages --}}
@error('nama_kegiatan')
    <div class="text-danger">{{ $message }}</div>
    {{-- Contoh: "Nama kegiatan wajib diisi" --}}
@enderror
```

**Flash Messages dari Controller:**

```php
// Success message
return redirect()->back()
    ->with('success', 'Usulan berhasil disimpan');

// Error message
return redirect()->back()
    ->with('error', 'Terjadi kesalahan saat menyimpan data');

// Warning message
return redirect()->back()
    ->with('warning', 'Data belum lengkap, silakan lengkapi terlebih dahulu');

// Info message
return redirect()->back()
    ->with('info', 'Usulan Anda sedang dalam proses review');
```

---

### 🇬🇧 Bahasa English - WAJIB untuk:

#### 1. Technical Code

**File names, class names, method names HARUS English:**

```php
// ✅ BENAR: Laravel convention
class UsulanKegiatanController extends Controller
{
    public function getApprovedUsulan(): Collection
    {
        $approvedData = UsulanKegiatan::approved()->get();
        return $approvedData;
    }
}

// ❌ SALAH: Campur bahasa atau full Indonesia
class PengendaliUsulanKegiatan extends Pengendali
{
    public function ambilUsulanDisetujui(): Collection { ... }
}
```

**Variable names menggunakan camelCase English:**

```php
// ✅ BENAR
$usulData = $request->validated();
$approvedList = UsulanKegiatan::approved()->get();
$fileUpload = $request->file('dokumen');

// ❌ SALAH
$data_usul = $request->validated();
$daftar_disetujui = UsulanKegiatan::approved()->get();
```

#### 2. Database Structure

**Tables, columns, dan enum values HARUS English:**

```php
// ✅ BENAR: Migration dengan English
Schema::create('kegiatans', function (Blueprint $table) {
    $table->id();
    $table->string('nama_kegiatan'); // Domain terms boleh Indonesia
    $table->enum('status', [
        'draft',
        'submitted',
        'approved',
        'rejected',
        'revision'
    ]); // Enum values HARUS English
    $table->foreignId('user_id')->constrained(); // Foreign key English
    $table->timestamps(); // created_at, updated_at (framework requirement)
    $table->softDeletes(); // deleted_at (framework requirement)
});

// ❌ SALAH: Enum Indonesia
$table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak']);
// Ini akan menyebabkan inkonsistensi dengan framework methods
```

**Query tetap clean dengan English:**

```php
// ✅ BENAR: Konsisten English
$kegiatans = Kegiatan::where('status', 'approved')
    ->with('user')
    ->latest()
    ->paginate(10);

// ❌ SALAH: Campur bahasa
$kegiatans = Kegiatan::where('status', 'disetujui') // Indonesia
    ->with('user') // English method
    ->latest() // English method
    ->paginate(10); // English method
```

#### 3. Routes & Configuration

**Route names dan config keys English/kebab-case:**

```php
// ✅ BENAR: routes/web.php
Route::resource('usulan-kegiatan', UsulanKegiatanController::class);
Route::post('kegiatan/{id}/approve', [KegiatanController::class, 'approve'])
    ->name('kegiatan.approve');

// ✅ BENAR: Config keys
config('app.name'); // English key
config('database.default'); // English key
```

---

### 🔄 Hybrid Approach: Status & Enum

**Ini adalah pola PALING PENTING dalam project:**

#### Database Layer (English)

```php
// Migration: enum values HARUS English
Schema::create('kegiatans', function (Blueprint $table) {
    $table->enum('status', [
        'draft',      // Draft
        'submitted',  // Diajukan
        'approved',   // Disetujui
        'rejected',   // Ditolak
        'revision'    // Revisi
    ]);
});
```

**Alasan menggunakan English di database:**
1. ✅ Konsisten dengan Laravel framework (`created_at`, `updated_at`, `deleted_at`)
2. ✅ Konsisten dengan foreign keys (`user_id`, `kegiatan_id`)
3. ✅ Standard untuk API (JSON biasanya English)
4. ✅ Package third-party expect English
5. ✅ Lebih pendek dan efficient
6. ✅ Future-proof jika perlu multi-language

#### Model Layer (Mapping)

```php
class Kegiatan extends Model
{
    // Constants untuk status (English)
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_REVISION = 'revision';
    
    // Mapping untuk display Indonesia
    const STATUS_LABELS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_SUBMITTED => 'Diajukan',
        self::STATUS_APPROVED => 'Disetujui',
        self::STATUS_REJECTED => 'Ditolak',
        self::STATUS_REVISION => 'Revisi',
    ];
    
    // Badge colors untuk setiap status
    const STATUS_BADGES = [
        self::STATUS_DRAFT => 'secondary',
        self::STATUS_SUBMITTED => 'info',
        self::STATUS_APPROVED => 'success',
        self::STATUS_REJECTED => 'danger',
        self::STATUS_REVISION => 'warning',
    ];
    
    /**
     * Accessor untuk mendapatkan label status dalam Bahasa Indonesia
     * 
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? 'Tidak Diketahui';
    }
    
    /**
     * Accessor untuk mendapatkan badge class untuk status
     * 
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        return self::STATUS_BADGES[$this->status] ?? 'secondary';
    }
    
    /**
     * Scope untuk filter kegiatan yang sudah disetujui
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
    
    /**
     * Scope untuk filter kegiatan draft
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }
}
```

**Constants untuk actions juga menggunakan mapping:**

```php
class ApprovalHistory extends Model
{
    // Action constants (English)
    const ACTION_SUBMIT = 'submit';
    const ACTION_APPROVE = 'approve';
    const ACTION_REJECT = 'reject';
    const ACTION_REQUEST_REVISION = 'request_revision';
    
    // Mapping action ke Bahasa Indonesia
    const ACTION_LABELS = [
        self::ACTION_SUBMIT => 'Mengajukan Usulan',
        self::ACTION_APPROVE => 'Menyetujui Usulan',
        self::ACTION_REJECT => 'Menolak Usulan',
        self::ACTION_REQUEST_REVISION => 'Meminta Revisi',
    ];
    
    /**
     * Accessor untuk label action dalam Bahasa Indonesia
     * 
     * @return string
     */
    public function getActionLabelAttribute(): string
    {
        return self::ACTION_LABELS[$this->action] ?? 'Aksi Tidak Diketahui';
    }
}
```

#### Controller Layer (Logic English, Messages Indonesia)

```php
class KegiatanController extends Controller
{
    /**
     * Setujui usulan kegiatan
     * 
     * Method ini akan mengubah status kegiatan menjadi approved
     * dan mencatat history approval ke database
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function approve($id)
    {
        // Cari kegiatan berdasarkan ID
        $kegiatan = Kegiatan::findOrFail($id);
        
        // Validasi: hanya bisa approve jika status submitted
        if ($kegiatan->status !== Kegiatan::STATUS_SUBMITTED) {
            return back()->with('error', 'Kegiatan tidak dapat disetujui');
        }
        
        // Update status ke approved (database: English)
        $kegiatan->update([
            'status' => Kegiatan::STATUS_APPROVED
        ]);
        
        // Catat history approval
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan->id,
            'user_id' => auth()->id(),
            'action' => ApprovalHistory::ACTION_APPROVE,
        ]);
        
        // Flash message Indonesia untuk user
        return redirect()
            ->route('kegiatan.show', $id)
            ->with('success', 'Kegiatan berhasil disetujui');
    }
    
    /**
     * Tolak usulan kegiatan dengan alasan
     * 
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        // Validasi input alasan penolakan
        $validated = $request->validate([
            'alasan' => 'required|string|min:10',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter',
        ]);
        
        // Cari kegiatan
        $kegiatan = Kegiatan::findOrFail($id);
        
        // Update status (English di database)
        $kegiatan->update([
            'status' => Kegiatan::STATUS_REJECTED,
            'alasan_penolakan' => $validated['alasan'],
        ]);
        
        // Catat history
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan->id,
            'user_id' => auth()->id(),
            'action' => ApprovalHistory::ACTION_REJECT,
            'notes' => $validated['alasan'],
        ]);
        
        // Message Indonesia
        return redirect()
            ->route('kegiatan.show', $id)
            ->with('success', 'Kegiatan berhasil ditolak');
    }
}
```

#### Display Layer (Indonesia)

```blade
{{-- resources/views/kegiatan/show.blade.php --}}

<div class="card">
    <div class="card-header">
        <h5>Detail Kegiatan</h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <td width="200"><strong>Nama Kegiatan</strong></td>
                <td>{{ $kegiatan->nama_kegiatan }}</td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>
                    {{-- Otomatis tampil dalam Bahasa Indonesia via accessor --}}
                    <span class="badge bg-{{ $kegiatan->status_badge }}">
                        {{ $kegiatan->status_label }}
                        {{-- Output: "Disetujui", "Ditolak", dll. --}}
                    </span>
                </td>
            </tr>
            <tr>
                <td><strong>Pengaju</strong></td>
                <td>{{ $kegiatan->user->name }}</td>
            </tr>
        </table>
        
        {{-- Action buttons dengan label Indonesia --}}
        <div class="mt-3">
            @if($kegiatan->status === 'submitted')
                <button class="btn btn-success" onclick="approve()">
                    <i class="bx bx-check"></i> Setujui
                </button>
                <button class="btn btn-danger" onclick="reject()">
                    <i class="bx bx-x"></i> Tolak
                </button>
                <button class="btn btn-warning" onclick="requestRevision()">
                    <i class="bx bx-edit"></i> Minta Revisi
                </button>
            @endif
        </div>
        
        {{-- Timeline approval history --}}
        <div class="mt-4">
            <h6>Riwayat Approval</h6>
            <div class="timeline">
                @foreach($kegiatan->approvalHistories as $history)
                    <div class="timeline-item">
                        <span class="timeline-point bg-{{ $history->getActionBadge() }}"></span>
                        <div class="timeline-content">
                            <p class="mb-1">
                                <strong>{{ $history->action_label }}</strong>
                                {{-- Output: "Menyetujui Usulan", "Menolak Usulan" --}}
                            </p>
                            <small class="text-muted">
                                oleh {{ $history->user->name }} •
                                {{ $history->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
```

---

### 📋 Quick Reference Table

**Kapan menggunakan bahasa apa:**

| Element | Language | Example |
|---------|----------|---------|
| **Code Comments** | 🇮🇩 Indonesia | `// Validasi data usulan` |
| **Docblocks** | 🇮🇩 Indonesia | `/** Mengambil data usulan */` |
| **Git Commits** | 🇮🇩 Indonesia | `feat: tambah fitur upload` |
| **Flash Messages** | 🇮🇩 Indonesia | `"Data berhasil disimpan"` |
| **Validation Messages** | 🇮🇩 Indonesia | `"File wajib diisi"` |
| **Button Labels** | 🇮🇩 Indonesia | `"Simpan"`, `"Setujui"` |
| **Form Labels** | 🇮🇩 Indonesia | `"Nama Kegiatan"` |
| **Status Display** | 🇮🇩 Indonesia | `"Disetujui"`, `"Ditolak"` |
| **File Names** | 🇬🇧 English | `UsulanKegiatanController.php` |
| **Class Names** | 🇬🇧 English | `class UsulanKegiatan` |
| **Method Names** | 🇬🇧 English | `getApprovedUsulan()` |
| **Variable Names** | 🇬🇧 English | `$usulData`, `$approvedList` |
| **Table Names** | 🇬🇧 English | `kegiatans`, `users` |
| **Column Names** | 🇬🇧 English | `status`, `created_at` |
| **Enum Values** | 🇬🇧 English | `'draft'`, `'approved'` |
| **Route Names** | 🇬🇧 English | `usulan-kegiatan.index` |
| **Config Keys** | 🇬🇧 English | `app.name` |

### ⚠️ Exception Rules

**Domain-specific column names boleh Indonesia:**

```php
// ✅ ACCEPTABLE: Domain terms yang spesifik Indonesia
$table->string('nama_kegiatan');
$table->string('tempat_kegiatan');
$table->decimal('total_anggaran');
$table->text('alasan_penolakan');

// Tapi framework columns HARUS English
$table->timestamps(); // created_at, updated_at
$table->foreignId('user_id'); // bukan id_pengguna
$table->softDeletes(); // deleted_at
```

### ❌ Common Mistakes

**Hindari kesalahan ini:**

```php
// ❌ SALAH: Enum Indonesia di database
$table->enum('status', ['disetujui', 'ditolak']);

// ❌ SALAH: Method name Indonesia
public function ambilDataUsulan() { ... }

// ❌ SALAH: Variable Indonesia
$data_usul = UsulanKegiatan::all();

// ❌ SALAH: Comments English untuk local project
// Get all approved proposals
public function index() { ... }

// ❌ SALAH: Git commit English
feat: add proposal upload feature

// ❌ SALAH: Foreign key Indonesia
$table->foreignId('id_pengguna'); // HARUS user_id

// ✅ BENAR: Hybrid approach
$table->enum('status', ['draft', 'approved', 'rejected']);
public function getApprovedUsulan(): Collection { ... }
$usulData = UsulanKegiatan::approved()->get();
// Ambil semua usulan yang sudah disetujui
return view('usulan.index', ['data' => $usulData]);
```

---

## Poin Integrasi Kritis

### Asset Template Sneat

- Logo: `public/assets/img/icons/Logo-Sipekma.png`
- Kustomisasi template via `config/variables.php` (branding, URLs, dll.)
- Asset template inti di `resources/assets/vendor/` - hindari modifikasi langsung
- Style kustom: Tambahkan ke `resources/assets/scss/app.scss`

### Penamaan Route

- Gunakan named routes secara konsisten: `route('usulan-kegiatan.index')`
- Penamaan resource RESTful: `feature-name.action` (misal: `usulan-kegiatan.create`)
- Menu JSON mereferensi nama route ini di field `slug`

## Tugas Umum

### Menambah Fitur CRUD Baru

1. Buat migration: `php artisan make:migration create_table_name`
2. Buat model: `php artisan make:model ModelName`
3. Buat controller: `php artisan make:controller FeatureController --resource`
4. Tambahkan routes ke `routes/web.php`: `Route::resource('feature-name', FeatureController::class)`
5. Update `resources/menu/verticalMenu.json` dengan entry menu baru
6. Buat folder view: `resources/views/feature-name/` dengan `index.blade.php`, dll.
7. (Opsional) Buat DataTable: `php artisan datatables:make FeatureDataTable`

### Kustomisasi Menu

Edit `resources/menu/verticalMenu.json`:

```json
{
  "name": "Feature Name",
  "icon": "menu-icon tf-icons bx bx-icon-name",
  "slug": ["route.name1", "route.name2"],  // Array untuk multiple routes
  "submenu": [...]  // Optional nested items
}
```

### Menambah Notifikasi Toast

Di controller:

```php
return redirect()->route('...')->with('success', 'Operation completed!');
```

Toast otomatis tampil jika `@include('_partials/toast')` ada di layout.

## Referensi Lokasi File

- **Controllers**: `app/Http/Controllers/` (spesifik fitur di subfolder)
- **Models**: `app/Models/`
- **DataTables**: `app/DataTables/`
- **Views**: `resources/views/`
- **Routes**: `routes/web.php`
- **Migrations**: `database/migrations/`
- **Config**: `config/` (lihat `variables.php` untuk pengaturan spesifik app)
- **Frontend Assets**: `resources/assets/` (dikompilasi oleh Vite)

## Keanehan yang Diketahui

- Ada beberapa migration duplikat (misal: `add_name_to_users_table.php` muncul dua kali) - abaikan versi lama
- Ada file controller backup (`UsulanKegiatanController_backup.php`) - ini adalah artifact development
- Sistem menu memerlukan update JSON manual - tidak ada auto-discovery dari routes
- Environment Windows PowerShell: Gunakan `;` untuk chaining command, bukan `&&`
