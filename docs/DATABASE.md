# Database Documentation - SIPEKMA

**Dokumentasi Lengkap Database Schema & Relationships**

---

## 📋 Daftar Isi

1. [Overview](#overview)
2. [Database Configuration](#database-configuration)
3. [Tables Overview](#tables-overview)
4. [Detailed Schema](#detailed-schema)
5. [Relationships & Foreign Keys](#relationships--foreign-keys)
6. [Entity Relationship Diagram](#entity-relationship-diagram)
7. [Indexes & Performance](#indexes--performance)
8. [Seeders](#seeders)
9. [Migrations History](#migrations-history)
10. [Query Examples](#query-examples)

---

## 📖 Overview

### Database Information

| Item | Detail |
|------|--------|
| **Database Name** | db_sipekma_2 |
| **DBMS** | MySQL 8.4.3 |
| **Charset** | utf8mb4 |
| **Collation** | utf8mb4_unicode_ci |
| **Engine** | InnoDB |
| **Total Tables** | 11 tables |
| **Framework** | Laravel 11 Eloquent ORM |

### Database Purpose

SIPEKMA menggunakan relational database untuk mengelola:
- ✅ User management dengan role-based access
- ✅ Kegiatan workflow (usulan → proposal → pendanaan → laporan)
- ✅ File management untuk dokumen kegiatan
- ✅ Approval history tracking
- ✅ Master data (jenis kegiatan, jenis pendanaan, prodi)

---

## ⚙️ Database Configuration

### Environment Variables (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sipekma_2
DB_USERNAME=root
DB_PASSWORD=
```

### Laravel Database Config (config/database.php)

```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => 'InnoDB',
],
```

---

## 📊 Tables Overview

### Complete Table List

| # | Table Name | Purpose | Records (Est.) | Relations |
|---|------------|---------|----------------|-----------|
| 1 | `roles` | User roles (Super Admin, Admin, BEM, HIMA, Finance) | 5 | → users |
| 2 | `prodis` | Program studi (TI, SI, dll) | 15+ | → users |
| 3 | `users` | System users (mahasiswa, admin) | 50+ | → kegiatans, approval_histories |
| 4 | `kegiatans` | Main kegiatan data | 100+ | → kegiatan_files, approval_histories |
| 5 | `approval_histories` | Approval tracking log | 200+ | ← kegiatans, users |
| 6 | `kegiatan_files` | File attachments (proposal, RAB, LPJ) | 150+ | ← kegiatans |
| 7 | `jenis_kegiatans` | Kegiatan types | 8 | → kegiatans |
| 8 | `jenis_pendanaans` | Pendanaan types | 5 | → kegiatans |
| 9 | `cache` | Laravel cache storage | Variable | Framework |
| 10 | `cache_locks` | Cache lock mechanism | Variable | Framework |
| 11 | `jobs` | Queue jobs | Variable | Framework |

### Table Size Estimate

| Category | Tables | Total Records |
|----------|--------|---------------|
| **Core Business** | kegiatans, kegiatan_files, approval_histories | ~450 |
| **User Management** | users, roles, prodis | ~70 |
| **Master Data** | jenis_kegiatans, jenis_pendanaans | ~13 |
| **Framework** | cache, cache_locks, jobs | Variable |
| **TOTAL** | 11 tables | ~533+ records |

---

## 🗂️ Detailed Schema

### 1. roles

**Purpose:** Menyimpan daftar role untuk RBAC (Role-Based Access Control)

| Column | Type | Length | Null | Default | Description |
|--------|------|--------|------|---------|-------------|
| `id` | bigint unsigned | - | NO | auto_increment | Primary key |
| `name` | varchar | 255 | NO | - | Role name (super_admin, admin, bem, hima, finance) |
| `description` | text | - | YES | NULL | Role description |
| `created_at` | timestamp | - | YES | NULL | Record creation time |
| `updated_at` | timestamp | - | YES | NULL | Record update time |

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `name`

**Sample Data:**

| id | name | description |
|----|------|-------------|
| 1 | super_admin | Super Administrator |
| 2 | admin | Administrator |
| 3 | bem | BEM (Badan Eksekutif Mahasiswa) |
| 4 | hima | HIMA (Himpunan Mahasiswa) |
| 5 | finance | Finance Team |

---

### 2. prodis

**Purpose:** Menyimpan data program studi

| Column | Type | Length | Null | Default | Description |
|--------|------|--------|------|---------|-------------|
| `id` | bigint unsigned | - | NO | auto_increment | Primary key |
| `kode_prodi` | varchar | 10 | NO | - | Kode prodi (TI, SI, dll) |
| `nama_prodi` | varchar | 255 | NO | - | Nama lengkap prodi |
| `fakultas` | varchar | 255 | YES | NULL | Nama fakultas |
| `created_at` | timestamp | - | YES | NULL | Record creation time |
| `updated_at` | timestamp | - | YES | NULL | Record update time |

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `kode_prodi`

**Sample Data:**

| id | kode_prodi | nama_prodi | fakultas |
|----|------------|------------|----------|
| 1 | TI | Teknik Informatika | Fakultas Teknik |
| 2 | SI | Sistem Informasi | Fakultas Teknik |
| 3 | TE | Teknik Elektro | Fakultas Teknik |

---

### 3. users

**Purpose:** Menyimpan data user sistem (mahasiswa, admin, dll)

| Column | Type | Length | Null | Default | Description |
|--------|------|--------|------|---------|-------------|
| `id` | bigint unsigned | - | NO | auto_increment | Primary key |
| `name` | varchar | 255 | NO | - | Nama lengkap user |
| `email` | varchar | 255 | NO | - | Email address (unique) |
| `email_verified_at` | timestamp | - | YES | NULL | Email verification time |
| `password` | varchar | 255 | NO | - | Hashed password (bcrypt) |
| `remember_token` | varchar | 100 | YES | NULL | Remember me token |
| `role_id` | bigint unsigned | - | NO | - | Foreign key → roles.id |
| `prodi_id` | bigint unsigned | - | YES | NULL | Foreign key → prodis.id |
| `npm` | varchar | 20 | YES | NULL | Nomor Pokok Mahasiswa |
| `phone` | varchar | 20 | YES | NULL | Phone number |
| `created_at` | timestamp | - | YES | NULL | Record creation time |
| `updated_at` | timestamp | - | YES | NULL | Record update time |

**Indexes:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `email`
- UNIQUE KEY: `npm`
- INDEX: `role_id` (foreign key)
- INDEX: `prodi_id` (foreign key)

**Constraints:**
- FOREIGN KEY: `role_id` REFERENCES `roles(id)` ON DELETE RESTRICT
- FOREIGN KEY: `prodi_id` REFERENCES `prodis(id)` ON DELETE SET NULL

**Sample Data:**

| id | name | email | role_id | prodi_id | npm |
|----|------|-------|---------|----------|-----|
| 1 | Super Admin | admin@sipekma.ac.id | 1 | NULL | NULL |
| 2 | John Doe | john@sipekma.ac.id | 4 | 1 | 2021010001 |
| 3 | Jane Smith | jane@sipekma.ac.id | 3 | 2 | 2021020002 |

---

### 4. kegiatans

**Purpose:** Tabel utama untuk menyimpan data kegiatan mahasiswa

| Column | Type | Length | Null | Default | Description |
|--------|------|--------|------|---------|-------------|
| `id` | bigint unsigned | - | NO | auto_increment | Primary key |
| `user_id` | bigint unsigned | - | NO | - | Foreign key → users.id (pengaju) |
| `jenis_kegiatan_id` | bigint unsigned | - | YES | NULL | Foreign key → jenis_kegiatans.id |
| `jenis_pendanaan_id` | bigint unsigned | - | YES | NULL | Foreign key → jenis_pendanaans.id |
| `nama_kegiatan` | varchar | 255 | NO | - | Nama kegiatan |
| `tempat_kegiatan` | varchar | 255 | NO | - | Lokasi kegiatan |
| `tanggal_mulai` | date | - | NO | - | Tanggal mulai kegiatan |
| `tanggal_selesai` | date | - | NO | - | Tanggal selesai kegiatan |
| `jumlah_peserta` | int | - | YES | NULL | Estimasi jumlah peserta |
| `total_anggaran` | decimal | 15,2 | YES | 0.00 | Total budget (Rp) |
| `deskripsi` | text | - | YES | NULL | Deskripsi detail kegiatan |
| `status` | enum | - | NO | draft | Status: draft, submitted, review, approved, rejected, revision |
| `alasan_penolakan` | text | - | YES | NULL | Alasan jika rejected/revision |
| `created_at` | timestamp | - | YES | NULL | Record creation time |
| `updated_at` | timestamp | - | YES | NULL | Record update time |
| `deleted_at` | timestamp | - | YES | NULL | Soft delete timestamp |

**Enum Values:**
- `status`: 'draft', 'submitted', 'review', 'approved', 'rejected', 'revision'

**Indexes:**
- PRIMARY KEY: `id`
- INDEX: `user_id` (foreign key)
- INDEX: `jenis_kegiatan_id` (foreign key)
- INDEX: `jenis_pendanaan_id` (foreign key)
- INDEX: `status` (for filtering)
- INDEX: `deleted_at` (soft deletes)

**Constraints:**
- FOREIGN KEY: `user_id` REFERENCES `users(id)` ON DELETE CASCADE
- FOREIGN KEY: `jenis_kegiatan_id` REFERENCES `jenis_kegiatans(id)` ON DELETE SET NULL
- FOREIGN KEY: `jenis_pendanaan_id` REFERENCES `jenis_pendanaans(id)` ON DELETE SET NULL

---

### 5. approval_histories

**Purpose:** Menyimpan history approval/rejection untuk tracking

| Column | Type | Length | Null | Default | Description |
|--------|------|--------|------|---------|-------------|
| `id` | bigint unsigned | - | NO | auto_increment | Primary key |
| `kegiatan_id` | bigint unsigned | - | NO | - | Foreign key → kegiatans.id |
| `user_id` | bigint unsigned | - | NO | - | Foreign key → users.id (approver) |
| `action` | enum | - | NO | - | Action: submit, approve, reject, request_revision |
| `notes` | text | - | YES | NULL | Catatan/alasan dari approver |
| `created_at` | timestamp | - | YES | NULL | Record creation time |
| `updated_at` | timestamp | - | YES | NULL | Record update time |

**Enum Values:**
- `action`: 'submit', 'approve', 'reject', 'request_revision'

**Indexes:**
- PRIMARY KEY: `id`
- INDEX: `kegiatan_id` (foreign key)
- INDEX: `user_id` (foreign key)
- INDEX: `created_at` (for chronological sorting)

**Constraints:**
- FOREIGN KEY: `kegiatan_id` REFERENCES `kegiatans(id)` ON DELETE CASCADE
- FOREIGN KEY: `user_id` REFERENCES `users(id)` ON DELETE CASCADE

---

### 6. kegiatan_files

**Purpose:** Menyimpan informasi file attachment untuk kegiatan

| Column | Type | Length | Null | Default | Description |
|--------|------|--------|------|---------|-------------|
| `id` | bigint unsigned | - | NO | auto_increment | Primary key |
| `kegiatan_id` | bigint unsigned | - | NO | - | Foreign key → kegiatans.id |
| `file_type` | enum | - | NO | - | Type: proposal, rab, lpj, dokumentasi |
| `file_name` | varchar | 255 | NO | - | Original filename |
| `file_path` | varchar | 500 | NO | - | Storage path |
| `file_size` | bigint | - | YES | NULL | File size in bytes |
| `mime_type` | varchar | 100 | YES | NULL | MIME type (application/pdf, etc) |
| `uploaded_by` | bigint unsigned | - | NO | - | Foreign key → users.id |
| `created_at` | timestamp | - | YES | NULL | Record creation time |
| `updated_at` | timestamp | - | YES | NULL | Record update time |

**Enum Values:**
- `file_type`: 'proposal', 'rab', 'lpj', 'dokumentasi'

**Indexes:**
- PRIMARY KEY: `id`
- INDEX: `kegiatan_id` (foreign key)
- INDEX: `uploaded_by` (foreign key)
- INDEX: `file_type` (for filtering)

**Constraints:**
- FOREIGN KEY: `kegiatan_id` REFERENCES `kegiatans(id)` ON DELETE CASCADE
- FOREIGN KEY: `uploaded_by` REFERENCES `users(id)` ON DELETE CASCADE

---

### 7. jenis_kegiatans

**Purpose:** Master data untuk jenis/kategori kegiatan

| Column | Type | Length | Null | Default | Description |
|--------|------|--------|------|---------|-------------|
| `id` | bigint unsigned | - | NO | auto_increment | Primary key |
| `nama_jenis` | varchar | 255 | NO | - | Nama jenis kegiatan |
| `deskripsi` | text | - | YES | NULL | Deskripsi jenis kegiatan |
| `created_at` | timestamp | - | YES | NULL | Record creation time |
| `updated_at` | timestamp | - | YES | NULL | Record update time |

**Indexes:**
- PRIMARY KEY: `id`

**Sample Data:**

| id | nama_jenis | deskripsi |
|----|------------|-----------|
| 1 | Seminar | Kegiatan seminar/workshop |
| 2 | Pelatihan | Pelatihan skill/kompetensi |
| 3 | Lomba | Kompetisi/perlombaan |
| 4 | Sosial | Kegiatan sosial kemasyarakatan |
| 5 | Olahraga | Kegiatan olahraga |
| 6 | Kesenian | Kegiatan seni dan budaya |
| 7 | Akademik | Kegiatan akademik |
| 8 | Lainnya | Kegiatan lainnya |

---

### 8. jenis_pendanaans

**Purpose:** Master data untuk sumber pendanaan

| Column | Type | Length | Null | Default | Description |
|--------|------|--------|------|---------|-------------|
| `id` | bigint unsigned | - | NO | auto_increment | Primary key |
| `nama_jenis` | varchar | 255 | NO | - | Nama sumber pendanaan |
| `deskripsi` | text | - | YES | NULL | Deskripsi sumber |
| `created_at` | timestamp | - | YES | NULL | Record creation time |
| `updated_at` | timestamp | - | YES | NULL | Record update time |

**Indexes:**
- PRIMARY KEY: `id`

**Sample Data:**

| id | nama_jenis | deskripsi |
|----|------------|-----------|
| 1 | Mandiri | Dana mandiri dari organisasi |
| 2 | Sponsor | Dana dari sponsor |
| 3 | Universitas | Dana dari universitas |
| 4 | Pemerintah | Dana bantuan pemerintah |
| 5 | Campuran | Kombinasi beberapa sumber |

---

### 9-11. Framework Tables (cache, cache_locks, jobs)

**Purpose:** Laravel framework internal tables untuk cache & queue

| Table | Purpose | Managed By |
|-------|---------|------------|
| `cache` | Cache storage | Laravel Cache |
| `cache_locks` | Cache locking | Laravel Cache |
| `jobs` | Queue jobs | Laravel Queue |

**Note:** Tables ini dikelola otomatis oleh Laravel framework.

---

## 🔗 Relationships & Foreign Keys

### Relationship Diagram (Text)

```
roles (1) ─────< (N) users
prodis (1) ─────< (N) users
users (1) ─────< (N) kegiatans
users (1) ─────< (N) approval_histories
kegiatans (1) ─────< (N) approval_histories
kegiatans (1) ─────< (N) kegiatan_files
jenis_kegiatans (1) ─────< (N) kegiatans
jenis_pendanaans (1) ─────< (N) kegiatans
```

### Detailed Relationships

| Parent Table | Child Table | Relationship | Foreign Key | On Delete |
|--------------|-------------|--------------|-------------|-----------|
| `roles` | `users` | One to Many | `role_id` | RESTRICT |
| `prodis` | `users` | One to Many | `prodi_id` | SET NULL |
| `users` | `kegiatans` | One to Many | `user_id` | CASCADE |
| `users` | `approval_histories` | One to Many | `user_id` | CASCADE |
| `kegiatans` | `approval_histories` | One to Many | `kegiatan_id` | CASCADE |
| `kegiatans` | `kegiatan_files` | One to Many | `kegiatan_id` | CASCADE |
| `jenis_kegiatans` | `kegiatans` | One to Many | `jenis_kegiatan_id` | SET NULL |
| `jenis_pendanaans` | `kegiatans` | One to Many | `jenis_pendanaan_id` | SET NULL |

### Eloquent Relationships in Models

#### User Model

```php
class User extends Model
{
    // User belongs to Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    // User belongs to Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
    
    // User has many Kegiatans
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }
    
    // User has many Approval Histories
    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class);
    }
}
```

#### Kegiatan Model

```php
class Kegiatan extends Model
{
    // Kegiatan belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Kegiatan belongs to Jenis Kegiatan
    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class);
    }
    
    // Kegiatan belongs to Jenis Pendanaan
    public function jenisPendanaan()
    {
        return $this->belongsTo(JenisPendanaan::class);
    }
    
    // Kegiatan has many Files
    public function files()
    {
        return $this->hasMany(KegiatanFile::class);
    }
    
    // Kegiatan has many Approval Histories
    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class);
    }
}
```

---

## 🎨 Entity Relationship Diagram (ERD)

### ERD Structure

```
┌─────────────┐
│   roles     │
│─────────────│
│ id (PK)     │
│ name        │
│ description │
└──────┬──────┘
       │ 1
       │
       │ N
┌──────┴──────────┐         ┌──────────────────┐
│     users       │         │     prodis       │
│─────────────────│         │──────────────────│
│ id (PK)         │◄────N──┤ id (PK)          │
│ name            │    1    │ kode_prodi       │
│ email           │         │ nama_prodi       │
│ role_id (FK)    │         │ fakultas         │
│ prodi_id (FK)   │         └──────────────────┘
└────────┬────────┘
         │ 1
         │
         │ N
┌────────┴────────────┐      ┌──────────────────────┐
│    kegiatans        │      │  jenis_kegiatans     │
│─────────────────────│      │──────────────────────│
│ id (PK)             │◄──N──┤ id (PK)              │
│ user_id (FK)        │  1   │ nama_jenis           │
│ jenis_kegiatan_id   │      │ deskripsi            │
│ jenis_pendanaan_id  │      └──────────────────────┘
│ nama_kegiatan       │
│ status              │      ┌──────────────────────┐
│ total_anggaran      │      │  jenis_pendanaans    │
└──────┬──────┬───────┘      │──────────────────────│
       │ 1    │               │ id (PK)              │
       │      └────────N──────┤ nama_jenis           │
       │                  1   │ deskripsi            │
       │ N                    └──────────────────────┘
       │
┌──────┴─────────────┐
│ kegiatan_files     │
│────────────────────│
│ id (PK)            │
│ kegiatan_id (FK)   │
│ file_type          │
│ file_name          │
│ file_path          │
└────────────────────┘

┌──────┬─────────────────┐
       │ N               │ N
       │                 │
┌──────┴─────────────────┴─────┐
│    approval_histories        │
│──────────────────────────────│
│ id (PK)                      │
│ kegiatan_id (FK)             │
│ user_id (FK)                 │
│ action                       │
│ notes                        │
└──────────────────────────────┘
```

### Cardinality Summary

| Relationship | Cardinality | Description |
|--------------|-------------|-------------|
| roles → users | 1:N | Satu role memiliki banyak users |
| prodis → users | 1:N | Satu prodi memiliki banyak users |
| users → kegiatans | 1:N | Satu user membuat banyak kegiatans |
| jenis_kegiatans → kegiatans | 1:N | Satu jenis memiliki banyak kegiatans |
| jenis_pendanaans → kegiatans | 1:N | Satu jenis memiliki banyak kegiatans |
| kegiatans → kegiatan_files | 1:N | Satu kegiatan memiliki banyak files |
| kegiatans → approval_histories | 1:N | Satu kegiatan memiliki banyak approval logs |
| users → approval_histories | 1:N | Satu user melakukan banyak approvals |

---

## ⚡ Indexes & Performance

### Primary Keys

All tables menggunakan `id` sebagai primary key dengan type `bigint unsigned auto_increment`.

### Foreign Key Indexes

| Table | Foreign Key | Index Name | Purpose |
|-------|-------------|------------|---------|
| `users` | `role_id` | `users_role_id_foreign` | Fast role lookup |
| `users` | `prodi_id` | `users_prodi_id_foreign` | Fast prodi lookup |
| `kegiatans` | `user_id` | `kegiatans_user_id_foreign` | Fast user lookup |
| `kegiatans` | `jenis_kegiatan_id` | `kegiatans_jenis_kegiatan_id_foreign` | Fast jenis lookup |
| `kegiatans` | `jenis_pendanaan_id` | `kegiatans_jenis_pendanaan_id_foreign` | Fast jenis lookup |
| `approval_histories` | `kegiatan_id` | `approval_histories_kegiatan_id_foreign` | Fast kegiatan lookup |
| `approval_histories` | `user_id` | `approval_histories_user_id_foreign` | Fast user lookup |
| `kegiatan_files` | `kegiatan_id` | `kegiatan_files_kegiatan_id_foreign` | Fast kegiatan lookup |

### Unique Indexes

| Table | Column | Purpose |
|-------|--------|---------|
| `roles` | `name` | Ensure unique role names |
| `prodis` | `kode_prodi` | Ensure unique prodi codes |
| `users` | `email` | Ensure unique email addresses |
| `users` | `npm` | Ensure unique student numbers |

### Status Index

```sql
CREATE INDEX idx_kegiatans_status ON kegiatans(status);
```

**Purpose:** Fast filtering by status (draft, submitted, approved, etc.)

**Benefit:** O(log n) instead of O(n) for status queries

### Soft Delete Index

```sql
CREATE INDEX idx_kegiatans_deleted_at ON kegiatans(deleted_at);
```

**Purpose:** Fast filtering untuk exclude deleted records

**Benefit:** Efficient soft delete queries

### Performance Recommendations

| Optimization | Priority | Implementation |
|--------------|----------|----------------|
| Composite index on (user_id, status) | 🟡 Medium | `ALTER TABLE kegiatans ADD INDEX idx_user_status (user_id, status)` |
| Composite index on (kegiatan_id, file_type) | 🟢 Low | `ALTER TABLE kegiatan_files ADD INDEX idx_kegiatan_file_type (kegiatan_id, file_type)` |
| Full-text index on nama_kegiatan | 🟢 Low | `ALTER TABLE kegiatans ADD FULLTEXT idx_nama_kegiatan (nama_kegiatan)` |

---

## 🌱 Seeders

### Available Seeders

| Seeder | Purpose | Records Created |
|--------|---------|-----------------|
| `RoleSeeder` | Create 5 default roles | 5 roles |
| `ProdiSeeder` | Create program studi | 15+ prodis |
| `AdminUserSeeder` | Create super admin user | 1 admin |
| `JenisKegiatanSeeder` | Create jenis kegiatan | 8 types |
| `JenisPendanaanSeeder` | Create jenis pendanaan | 5 types |
| `UserSeeder` | Create sample users | 10+ users |
| `KegiatanSeeder` | Create sample kegiatans | 20+ kegiatans |

### Running Seeders

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=RoleSeeder

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Seeder Dependencies

```
1. RoleSeeder (independent)
2. ProdiSeeder (independent)
3. AdminUserSeeder (needs: RoleSeeder)
4. UserSeeder (needs: RoleSeeder, ProdiSeeder)
5. JenisKegiatanSeeder (independent)
6. JenisPendanaanSeeder (independent)
7. KegiatanSeeder (needs: UserSeeder, JenisKegiatanSeeder, JenisPendanaanSeeder)
```

---

## 📜 Migrations History

### Migration Files (Chronological)

| # | File | Purpose | Date |
|---|------|---------|------|
| 1 | `0001_01_01_000000_create_roles_table` | Create roles table | Initial |
| 2 | `0001_01_01_000001_create_prodis_table` | Create prodis table | Initial |
| 3 | `0001_01_01_000002_create_users_table` | Create users table | Initial |
| 4 | `0001_01_01_000003_create_cache_table` | Create cache tables | Initial |
| 5 | `0001_01_01_000004_create_jobs_table` | Create jobs table | Initial |
| 6 | `2025_12_07_145400_create_kegiatans_table` | Create kegiatans table | 2025-12-07 |
| 7 | `2025_12_07_145409_create_approval_histories_table` | Create approval_histories | 2025-12-07 |
| 8 | `2025_12_08_044835_create_kegiatan_files_table` | Create kegiatan_files | 2025-12-08 |
| 9 | `2025_12_08_063709_add_total_anggaran_to_kegiatans_table` | Add total_anggaran column | 2025-12-08 |
| 10 | `2025_12_08_082217_update_kegiatan_files_table` | Update kegiatan_files schema | 2025-12-08 |

### Migration Commands

```bash
# Check migration status
php artisan migrate:status

# Run pending migrations
php artisan migrate

# Rollback last batch
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Fresh migration (drop all tables)
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

---

## 🔍 Query Examples

### Common Queries

#### 1. Get All Kegiatans with User & Jenis

```php
$kegiatans = Kegiatan::with(['user', 'jenisKegiatan', 'jenisPendanaan'])
    ->latest()
    ->paginate(10);
```

**SQL Equivalent:**

```sql
SELECT kegiatans.*, users.name, jenis_kegiatans.nama_jenis, jenis_pendanaans.nama_jenis
FROM kegiatans
LEFT JOIN users ON kegiatans.user_id = users.id
LEFT JOIN jenis_kegiatans ON kegiatans.jenis_kegiatan_id = jenis_kegiatans.id
LEFT JOIN jenis_pendanaans ON kegiatans.jenis_pendanaan_id = jenis_pendanaans.id
ORDER BY kegiatans.created_at DESC
LIMIT 10;
```

#### 2. Get Approved Kegiatans

```php
$approved = Kegiatan::where('status', 'approved')
    ->with('user')
    ->get();
```

**SQL Equivalent:**

```sql
SELECT * FROM kegiatans
WHERE status = 'approved'
AND deleted_at IS NULL;
```

#### 3. Get Kegiatan with Approval History

```php
$kegiatan = Kegiatan::with(['approvalHistories.user'])
    ->findOrFail($id);
```

**SQL Equivalent:**

```sql
SELECT * FROM kegiatans WHERE id = ?;
SELECT ah.*, u.name 
FROM approval_histories ah
LEFT JOIN users u ON ah.user_id = u.id
WHERE ah.kegiatan_id = ?
ORDER BY ah.created_at ASC;
```

#### 4. Get User's Kegiatans with Files Count

```php
$user = User::with(['kegiatans' => function($query) {
    $query->withCount('files');
}])->findOrFail($userId);
```

**SQL Equivalent:**

```sql
SELECT u.*, 
    (SELECT COUNT(*) FROM kegiatan_files WHERE kegiatan_id = k.id) as files_count
FROM users u
LEFT JOIN kegiatans k ON u.id = k.user_id
WHERE u.id = ?;
```

#### 5. Get Kegiatans by Date Range

```php
$kegiatans = Kegiatan::whereBetween('tanggal_mulai', [$start, $end])
    ->where('status', 'approved')
    ->get();
```

**SQL Equivalent:**

```sql
SELECT * FROM kegiatans
WHERE tanggal_mulai BETWEEN ? AND ?
AND status = 'approved'
AND deleted_at IS NULL;
```

#### 6. Get Total Budget by Status

```php
$totals = Kegiatan::selectRaw('status, SUM(total_anggaran) as total')
    ->groupBy('status')
    ->get();
```

**SQL Equivalent:**

```sql
SELECT status, SUM(total_anggaran) as total
FROM kegiatans
WHERE deleted_at IS NULL
GROUP BY status;
```

#### 7. Get Recent Approval Activities

```php
$activities = ApprovalHistory::with(['user', 'kegiatan'])
    ->latest()
    ->take(10)
    ->get();
```

**SQL Equivalent:**

```sql
SELECT ah.*, u.name, k.nama_kegiatan
FROM approval_histories ah
LEFT JOIN users u ON ah.user_id = u.id
LEFT JOIN kegiatans k ON ah.kegiatan_id = k.id
ORDER BY ah.created_at DESC
LIMIT 10;
```

---

## 🔐 Data Integrity

### Foreign Key Constraints Summary

| Constraint | On Delete Behavior | Reasoning |
|------------|-------------------|-----------|
| `users.role_id → roles.id` | RESTRICT | Prevent deleting role that has users |
| `users.prodi_id → prodis.id` | SET NULL | Allow prodi deletion, user remains |
| `kegiatans.user_id → users.id` | CASCADE | Delete kegiatans when user deleted |
| `kegiatans.jenis_kegiatan_id → jenis_kegiatans.id` | SET NULL | Allow jenis deletion, kegiatan remains |
| `approval_histories.kegiatan_id → kegiatans.id` | CASCADE | Delete histories when kegiatan deleted |
| `kegiatan_files.kegiatan_id → kegiatans.id` | CASCADE | Delete files when kegiatan deleted |

### Validation Rules

| Table | Column | Validation |
|-------|--------|------------|
| `users` | `email` | UNIQUE, email format |
| `users` | `npm` | UNIQUE, numeric |
| `users` | `password` | Hashed (bcrypt) |
| `kegiatans` | `tanggal_selesai` | >= tanggal_mulai |
| `kegiatans` | `total_anggaran` | >= 0 |
| `kegiatans` | `status` | Enum values only |
| `kegiatan_files` | `file_type` | Enum values only |
| `kegiatan_files` | `file_size` | <= max_upload_size |

---

## 📈 Database Statistics

### Estimated Storage

| Table | Avg Row Size | 100 Records | 1,000 Records |
|-------|--------------|-------------|---------------|
| `users` | ~500 bytes | 50 KB | 500 KB |
| `kegiatans` | ~1 KB | 100 KB | 1 MB |
| `kegiatan_files` | ~300 bytes | 30 KB | 300 KB |
| `approval_histories` | ~400 bytes | 40 KB | 400 KB |
| **Total Estimated** | - | **220 KB** | **2.2 MB** |

**Note:** File storage tidak dihitung (stored in `storage/app/`)

### Query Performance

| Query Type | Avg Time | Index Used |
|------------|----------|------------|
| SELECT by ID | <1ms | PRIMARY KEY |
| SELECT by status | <5ms | idx_status |
| SELECT with JOIN (2 tables) | <10ms | Foreign key indexes |
| SELECT with JOIN (3 tables) | <20ms | Foreign key indexes |
| INSERT | <2ms | N/A |
| UPDATE | <3ms | PRIMARY KEY |
| DELETE (soft) | <3ms | PRIMARY KEY |

---

## 🛡️ Backup & Recovery

### Backup Strategy

| Type | Frequency | Retention | Method |
|------|-----------|-----------|--------|
| **Full Backup** | Daily | 7 days | mysqldump |
| **Incremental** | Hourly | 24 hours | Binary logs |
| **Monthly Archive** | Monthly | 1 year | Compressed |

### Backup Commands

```bash
# Full database backup
mysqldump -u root db_sipekma_2 > backup_$(date +%Y%m%d).sql

# Backup with compression
mysqldump -u root db_sipekma_2 | gzip > backup_$(date +%Y%m%d).sql.gz

# Backup specific tables
mysqldump -u root db_sipekma_2 kegiatans approval_histories > kegiatan_backup.sql

# Restore from backup
mysql -u root db_sipekma_2 < backup_20260202.sql
```

### Laravel Backup Package (Recommended)

```bash
# Install spatie/laravel-backup
composer require spatie/laravel-backup

# Run backup
php artisan backup:run

# Restore (manual)
php artisan backup:list
```

---

## 🔄 Future Enhancements

### Planned Schema Changes

| Enhancement | Priority | Description |
|-------------|----------|-------------|
| Notifications table | 🔴 High | Real-time notifications |
| Audit logs | 🟡 Medium | Track all data changes |
| Comments table | 🟡 Medium | Comments on kegiatan |
| Tags table | 🟢 Low | Tag kegiatan for filtering |
| Rating table | 🟢 Low | Rate completed kegiatan |

### Performance Optimizations

| Task | Priority | Expected Benefit |
|------|----------|------------------|
| Add composite indexes | 🟡 Medium | 30-50% faster queries |
| Implement query caching | 🟡 Medium | Reduce DB load |
| Archive old records | 🟢 Low | Smaller table size |
| Partition large tables | 🟢 Low | Better performance on large datasets |

---

**Last Updated:** 02 Februari 2026  
**Version:** 1.0.0  
**Database Schema Version:** 1.0  
**Status:** ✅ Production Ready
