# API Routes Documentation - SIPEKMA

**Dokumentasi Lengkap Routes & Endpoints**

---

## 📋 Daftar Isi

1. [Overview](#overview)
2. [Route Structure](#route-structure)
3. [Authentication Routes](#authentication-routes)
4. [Kegiatan Routes](#kegiatan-routes)
5. [Usulan Kegiatan Routes](#usulan-kegiatan-routes)
6. [User Management Routes](#user-management-routes)
7. [Master Data Routes](#master-data-routes)
8. [File Management Routes](#file-management-routes)
9. [Route Middleware](#route-middleware)
10. [Named Routes Reference](#named-routes-reference)

---

## 📖 Overview

### Routes Information

| Item | Detail |
|------|--------|
| **Total Routes** | 50+ routes |
| **Route File** | `routes/web.php` |
| **Route Prefix** | None (direct access) |
| **Middleware** | auth, guest, throttle |
| **Named Routes** | Yes (for all major routes) |

### Route Categories

| Category | Routes | Purpose |
|----------|--------|---------|
| **Authentication** | 8 routes | Login, register, logout |
| **Dashboard** | 1 route | Main dashboard |
| **Kegiatan** | 15+ routes | Kegiatan CRUD & actions |
| **Usulan Kegiatan** | 15+ routes | Usulan CRUD & approval |
| **Users** | 10+ routes | User management |
| **Master Data** | 8 routes | Jenis kegiatan, prodi, dll |
| **Files** | 5+ routes | File upload & download |

---

## 🏗️ Route Structure

### Route Pattern

```php
// Basic pattern
Route::method('/uri', [Controller::class, 'method'])->name('route.name');

// Resource pattern
Route::resource('resource', ResourceController::class);

// Group with middleware
Route::middleware(['auth'])->group(function () {
    // Protected routes here
});
```

### File: routes/web.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\UsulanKegiatanController;
use App\Http\Controllers\UserController;

// ... route definitions
```

---

## 🔐 Authentication Routes

### Login & Logout

| Method | URI | Name | Controller@Method | Middleware |
|--------|-----|------|-------------------|------------|
| GET | `/login` | login | LoginController@showLoginForm | guest |
| POST | `/login` | - | LoginController@login | guest |
| POST | `/logout` | logout | LoginController@logout | auth |

**Example:**

```php
// Show login form
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

// Handle login
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest');

// Handle logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
```

### Registration Routes

| Method | URI | Name | Controller@Method | Middleware |
|--------|-----|------|-------------------|------------|
| GET | `/register` | register | RegisterController@showRegistrationForm | guest |
| POST | `/register` | - | RegisterController@register | guest |

**Example:**

```php
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('guest');
```

### Password Reset Routes

| Method | URI | Name | Controller@Method |
|--------|-----|------|-------------------|
| GET | `/password/reset` | password.request | ForgotPasswordController@showLinkRequestForm |
| POST | `/password/email` | password.email | ForgotPasswordController@sendResetLinkEmail |
| GET | `/password/reset/{token}` | password.reset | ResetPasswordController@showResetForm |
| POST | `/password/reset` | password.update | ResetPasswordController@reset |

---

## 🏠 Dashboard Routes

| Method | URI | Name | Controller@Method | Middleware |
|--------|-----|------|-------------------|------------|
| GET | `/` | dashboard | DashboardController@index | auth |
| GET | `/dashboard` | dashboard | DashboardController@index | auth |

**Example:**

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

---

## 📋 Kegiatan Routes

### Resource Routes

| Method | URI | Name | Controller@Method | Purpose |
|--------|-----|------|-------------------|---------|
| GET | `/kegiatan` | kegiatan.index | KegiatanController@index | List all |
| GET | `/kegiatan/create` | kegiatan.create | KegiatanController@create | Show create form |
| POST | `/kegiatan` | kegiatan.store | KegiatanController@store | Store new |
| GET | `/kegiatan/{id}` | kegiatan.show | KegiatanController@show | Show detail |
| GET | `/kegiatan/{id}/edit` | kegiatan.edit | KegiatanController@edit | Show edit form |
| PUT/PATCH | `/kegiatan/{id}` | kegiatan.update | KegiatanController@update | Update |
| DELETE | `/kegiatan/{id}` | kegiatan.destroy | KegiatanController@destroy | Delete |

**Example:**

```php
Route::middleware(['auth'])->group(function () {
    Route::resource('kegiatan', KegiatanController::class);
});
```

### Custom Action Routes

| Method | URI | Name | Controller@Method | Purpose |
|--------|-----|------|-------------------|---------|
| POST | `/kegiatan/{id}/approve` | kegiatan.approve | KegiatanController@approve | Approve kegiatan |
| POST | `/kegiatan/{id}/reject` | kegiatan.reject | KegiatanController@reject | Reject kegiatan |
| POST | `/kegiatan/{id}/revision` | kegiatan.revision | KegiatanController@requestRevision | Request revision |
| GET | `/kegiatan/{id}/approval-history` | kegiatan.history | KegiatanController@approvalHistory | View history |
| GET | `/kegiatan/export/excel` | kegiatan.export.excel | KegiatanController@exportExcel | Export to Excel |
| GET | `/kegiatan/export/pdf` | kegiatan.export.pdf | KegiatanController@exportPdf | Export to PDF |

**Example:**

```php
Route::middleware(['auth'])->group(function () {
    // Approval actions
    Route::post('/kegiatan/{id}/approve', [KegiatanController::class, 'approve'])
        ->name('kegiatan.approve');
    
    Route::post('/kegiatan/{id}/reject', [KegiatanController::class, 'reject'])
        ->name('kegiatan.reject');
    
    Route::post('/kegiatan/{id}/revision', [KegiatanController::class, 'requestRevision'])
        ->name('kegiatan.revision');
    
    // Export
    Route::get('/kegiatan/export/excel', [KegiatanController::class, 'exportExcel'])
        ->name('kegiatan.export.excel');
    
    Route::get('/kegiatan/export/pdf', [KegiatanController::class, 'exportPdf'])
        ->name('kegiatan.export.pdf');
});
```

---

## 📝 Usulan Kegiatan Routes

### Resource Routes

| Method | URI | Name | Controller@Method | Purpose |
|--------|-----|------|-------------------|---------|
| GET | `/usulan-kegiatan` | usulan-kegiatan.index | UsulanKegiatanController@index | List all |
| GET | `/usulan-kegiatan/create` | usulan-kegiatan.create | UsulanKegiatanController@create | Show create form |
| POST | `/usulan-kegiatan` | usulan-kegiatan.store | UsulanKegiatanController@store | Store new |
| GET | `/usulan-kegiatan/{id}` | usulan-kegiatan.show | UsulanKegiatanController@show | Show detail |
| GET | `/usulan-kegiatan/{id}/edit` | usulan-kegiatan.edit | UsulanKegiatanController@edit | Show edit form |
| PUT/PATCH | `/usulan-kegiatan/{id}` | usulan-kegiatan.update | UsulanKegiatanController@update | Update |
| DELETE | `/usulan-kegiatan/{id}` | usulan-kegiatan.destroy | UsulanKegiatanController@destroy | Delete |

**Example:**

```php
Route::middleware(['auth'])->group(function () {
    Route::resource('usulan-kegiatan', UsulanKegiatanController::class);
});
```

### Custom Action Routes

| Method | URI | Name | Controller@Method | Purpose |
|--------|-----|------|-------------------|---------|
| POST | `/usulan-kegiatan/{id}/submit` | usulan-kegiatan.submit | UsulanKegiatanController@submit | Submit for approval |
| POST | `/usulan-kegiatan/{id}/approve` | usulan-kegiatan.approve | UsulanKegiatanController@approve | Approve usulan |
| POST | `/usulan-kegiatan/{id}/reject` | usulan-kegiatan.reject | UsulanKegiatanController@reject | Reject usulan |

**Example:**

```php
Route::middleware(['auth'])->group(function () {
    Route::post('/usulan-kegiatan/{id}/submit', [UsulanKegiatanController::class, 'submit'])
        ->name('usulan-kegiatan.submit');
    
    Route::post('/usulan-kegiatan/{id}/approve', [UsulanKegiatanController::class, 'approve'])
        ->name('usulan-kegiatan.approve');
    
    Route::post('/usulan-kegiatan/{id}/reject', [UsulanKegiatanController::class, 'reject'])
        ->name('usulan-kegiatan.reject');
});
```

---

## 👥 User Management Routes

### Resource Routes

| Method | URI | Name | Controller@Method | Purpose |
|--------|-----|------|-------------------|---------|
| GET | `/users` | users.index | UserController@index | List all users |
| GET | `/users/create` | users.create | UserController@create | Show create form |
| POST | `/users` | users.store | UserController@store | Store new user |
| GET | `/users/{id}` | users.show | UserController@show | Show user detail |
| GET | `/users/{id}/edit` | users.edit | UserController@edit | Show edit form |
| PUT/PATCH | `/users/{id}` | users.update | UserController@update | Update user |
| DELETE | `/users/{id}` | users.destroy | UserController@destroy | Delete user |

**Example:**

```php
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
});
```

### DataTables Route

| Method | URI | Name | Controller@Method | Purpose |
|--------|-----|------|-------------------|---------|
| GET | `/users/data` | users.data | UserController@data | Get DataTables data |

**Example:**

```php
Route::get('/users/data', [UserController::class, 'data'])
    ->name('users.data')
    ->middleware('auth');
```

---

## 🗂️ Master Data Routes

### Jenis Kegiatan

| Method | URI | Name | Controller@Method |
|--------|-----|------|-------------------|
| GET | `/jenis-kegiatan` | jenis-kegiatan.index | JenisKegiatanController@index |
| POST | `/jenis-kegiatan` | jenis-kegiatan.store | JenisKegiatanController@store |
| PUT/PATCH | `/jenis-kegiatan/{id}` | jenis-kegiatan.update | JenisKegiatanController@update |
| DELETE | `/jenis-kegiatan/{id}` | jenis-kegiatan.destroy | JenisKegiatanController@destroy |

### Jenis Pendanaan

| Method | URI | Name | Controller@Method |
|--------|-----|------|-------------------|
| GET | `/jenis-pendanaan` | jenis-pendanaan.index | JenisPendanaanController@index |
| POST | `/jenis-pendanaan` | jenis-pendanaan.store | JenisPendanaanController@store |
| PUT/PATCH | `/jenis-pendanaan/{id}` | jenis-pendanaan.update | JenisPendanaanController@update |
| DELETE | `/jenis-pendanaan/{id}` | jenis-pendanaan.destroy | JenisPendanaanController@destroy |

### Prodi

| Method | URI | Name | Controller@Method |
|--------|-----|------|-------------------|
| GET | `/prodi` | prodi.index | ProdiController@index |
| POST | `/prodi` | prodi.store | ProdiController@store |
| PUT/PATCH | `/prodi/{id}` | prodi.update | ProdiController@update |
| DELETE | `/prodi/{id}` | prodi.destroy | ProdiController@destroy |

**Example:**

```php
Route::middleware(['auth'])->group(function () {
    Route::resource('jenis-kegiatan', JenisKegiatanController::class);
    Route::resource('jenis-pendanaan', JenisPendanaanController::class);
    Route::resource('prodi', ProdiController::class);
});
```

---

## 📁 File Management Routes

### File Upload Routes

| Method | URI | Name | Controller@Method | Purpose |
|--------|-----|------|-------------------|---------|
| POST | `/kegiatan/{id}/upload/proposal` | kegiatan.upload.proposal | KegiatanController@uploadProposal | Upload proposal |
| POST | `/kegiatan/{id}/upload/rab` | kegiatan.upload.rab | KegiatanController@uploadRAB | Upload RAB |
| POST | `/kegiatan/{id}/upload/lpj` | kegiatan.upload.lpj | KegiatanController@uploadLPJ | Upload LPJ |
| POST | `/kegiatan/{id}/upload/dokumentasi` | kegiatan.upload.dokumentasi | KegiatanController@uploadDokumentasi | Upload dokumentasi |

**Example:**

```php
Route::middleware(['auth'])->group(function () {
    Route::post('/kegiatan/{id}/upload/proposal', [KegiatanController::class, 'uploadProposal'])
        ->name('kegiatan.upload.proposal');
    
    Route::post('/kegiatan/{id}/upload/rab', [KegiatanController::class, 'uploadRAB'])
        ->name('kegiatan.upload.rab');
    
    Route::post('/kegiatan/{id}/upload/lpj', [KegiatanController::class, 'uploadLPJ'])
        ->name('kegiatan.upload.lpj');
});
```

### File Download/View Routes

| Method | URI | Name | Controller@Method | Purpose |
|--------|-----|------|-------------------|---------|
| GET | `/files/{id}/download` | files.download | FileController@download | Download file |
| GET | `/files/{id}/view` | files.view | FileController@view | View file inline |
| DELETE | `/files/{id}` | files.destroy | FileController@destroy | Delete file |

**Example:**

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/files/{id}/download', [FileController::class, 'download'])
        ->name('files.download');
    
    Route::get('/files/{id}/view', [FileController::class, 'view'])
        ->name('files.view');
    
    Route::delete('/files/{id}', [FileController::class, 'destroy'])
        ->name('files.destroy');
});
```

---

## 🛡️ Route Middleware

### Available Middleware

| Middleware | Purpose | Usage |
|------------|---------|-------|
| `auth` | Require authentication | Protected routes |
| `guest` | Allow only guests | Login/register pages |
| `verified` | Require email verification | Email-protected routes |
| `throttle:60,1` | Rate limiting (60 req/min) | API routes |

### Middleware Groups

```php
// Guest routes (login, register)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm']);
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('kegiatan', KegiatanController::class);
});

// Admin only routes (custom middleware - planned)
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::resource('users', UserController::class);
});
```

---

## 📛 Named Routes Reference

### Complete Named Routes List

| Name | URI | Method | Purpose |
|------|-----|--------|---------|
| `login` | /login | GET | Login page |
| `logout` | /logout | POST | Logout |
| `dashboard` | / | GET | Dashboard |
| **Kegiatan** |
| `kegiatan.index` | /kegiatan | GET | List kegiatan |
| `kegiatan.create` | /kegiatan/create | GET | Create form |
| `kegiatan.store` | /kegiatan | POST | Store new |
| `kegiatan.show` | /kegiatan/{id} | GET | Show detail |
| `kegiatan.edit` | /kegiatan/{id}/edit | GET | Edit form |
| `kegiatan.update` | /kegiatan/{id} | PUT | Update |
| `kegiatan.destroy` | /kegiatan/{id} | DELETE | Delete |
| `kegiatan.approve` | /kegiatan/{id}/approve | POST | Approve |
| `kegiatan.reject` | /kegiatan/{id}/reject | POST | Reject |
| **Usulan Kegiatan** |
| `usulan-kegiatan.index` | /usulan-kegiatan | GET | List usulan |
| `usulan-kegiatan.create` | /usulan-kegiatan/create | GET | Create form |
| `usulan-kegiatan.store` | /usulan-kegiatan | POST | Store new |
| `usulan-kegiatan.show` | /usulan-kegiatan/{id} | GET | Show detail |
| `usulan-kegiatan.edit` | /usulan-kegiatan/{id}/edit | GET | Edit form |
| `usulan-kegiatan.update` | /usulan-kegiatan/{id} | PUT | Update |
| `usulan-kegiatan.destroy` | /usulan-kegiatan/{id} | DELETE | Delete |
| **Users** |
| `users.index` | /users | GET | List users |
| `users.create` | /users/create | GET | Create form |
| `users.store` | /users | POST | Store new |
| `users.show` | /users/{id} | GET | Show detail |
| `users.edit` | /users/{id}/edit | GET | Edit form |
| `users.update` | /users/{id} | PUT | Update |
| `users.destroy` | /users/{id} | DELETE | Delete |
| **Files** |
| `files.download` | /files/{id}/download | GET | Download file |
| `files.view` | /files/{id}/view | GET | View file |
| `files.destroy` | /files/{id} | DELETE | Delete file |

### Using Named Routes

**In Blade:**

```blade
{{-- Generate URL --}}
<a href="{{ route('kegiatan.show', $kegiatan->id) }}">View</a>

{{-- Generate URL with query params --}}
<a href="{{ route('kegiatan.index', ['status' => 'approved']) }}">Approved</a>

{{-- Form action --}}
<form action="{{ route('kegiatan.store') }}" method="POST">
    @csrf
    ...
</form>
```

**In Controller:**

```php
// Redirect to named route
return redirect()->route('kegiatan.index');

// Redirect with parameters
return redirect()->route('kegiatan.show', ['id' => $kegiatan->id]);

// Redirect with flash message
return redirect()->route('kegiatan.index')
    ->with('success', 'Data berhasil disimpan');
```

**In JavaScript:**

```javascript
// Using Laravel route helper (via Ziggy or manual)
const url = route('kegiatan.show', { id: 123 });
fetch(url).then(response => response.json());
```

---

## 🔍 Route Testing

### List All Routes

```bash
# List all routes
php artisan route:list

# Filter by name
php artisan route:list --name=kegiatan

# Filter by method
php artisan route:list --method=GET

# Show middleware
php artisan route:list --columns=uri,name,action,middleware
```

### Route Output Example

```
+--------+----------+-------------------------+------------------+
| Method | URI      | Name                    | Action           |
+--------+----------+-------------------------+------------------+
| GET    | /        | dashboard               | DashboardController@index |
| GET    | /kegiatan| kegiatan.index          | KegiatanController@index |
| POST   | /kegiatan| kegiatan.store          | KegiatanController@store |
| GET    | /kegiatan/create | kegiatan.create | KegiatanController@create |
+--------+----------+-------------------------+------------------+
```

---

## 📊 Route Statistics

### Route Count by Category

| Category | Count | Percentage |
|----------|-------|------------|
| **Authentication** | 8 | 15% |
| **Kegiatan** | 15 | 28% |
| **Usulan Kegiatan** | 15 | 28% |
| **Users** | 8 | 15% |
| **Master Data** | 6 | 11% |
| **Files** | 5 | 9% |
| **TOTAL** | ~57 | 100% |

### HTTP Method Distribution

| Method | Count | Purpose |
|--------|-------|---------|
| GET | ~30 | Display pages, fetch data |
| POST | ~15 | Create, actions |
| PUT/PATCH | ~8 | Update |
| DELETE | ~4 | Delete |

---

## 🔐 Route Security

### CSRF Protection

All POST, PUT, PATCH, DELETE routes are protected by CSRF middleware.

```blade
<form method="POST" action="{{ route('kegiatan.store') }}">
    @csrf
    {{-- form fields --}}
</form>
```

### Rate Limiting

API routes use throttle middleware:

```php
Route::middleware(['throttle:60,1'])->group(function () {
    // Max 60 requests per minute
});
```

---

## 📝 Route Best Practices

### DO ✅

| Practice | Example | Benefit |
|----------|---------|---------|
| **Use named routes** | `route('kegiatan.show', $id)` | Maintainability |
| **RESTful naming** | `kegiatan.index`, `kegiatan.store` | Consistency |
| **Group by middleware** | `Route::middleware(['auth'])` | Organization |
| **Resource routes** | `Route::resource()` | Less code |
| **Route model binding** | `Route::get('/kegiatan/{kegiatan}')` | Auto-fetch model |

### DON'T ❌

| Anti-pattern | Why Bad | Better Alternative |
|--------------|---------|-------------------|
| **Hardcoded URLs** | Breaks if route changes | Use `route()` helper |
| **Unnamed routes** | Hard to reference | Always name routes |
| **Mixed naming** | Inconsistent | Stick to RESTful |
| **No middleware** | Security risk | Use auth middleware |
| **Duplicate routes** | Confusing | Consolidate |

---

**Last Updated:** 02 Februari 2026  
**Version:** 1.0.0  
**Total Routes:** ~57 routes  
**Status:** ✅ Complete
