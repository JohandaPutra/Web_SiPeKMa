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
