# Architecture Documentation - SIPEKMA

**Dokumentasi Arsitektur Sistem & Design Patterns**

---

## 📋 Daftar Isi

1. [System Overview](#system-overview)
2. [Architecture Pattern](#architecture-pattern)
3. [Project Structure](#project-structure)
4. [Design Patterns](#design-patterns)
5. [Request Lifecycle](#request-lifecycle)
6. [Authentication & Authorization](#authentication--authorization)
7. [File Upload System](#file-upload-system)
8. [Frontend Architecture](#frontend-architecture)
9. [State Management](#state-management)
10. [Security Architecture](#security-architecture)

---

## 🏗️ System Overview

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────┐
│                      CLIENT LAYER                        │
│  (Browser: Chrome, Firefox, Edge, Mobile Browsers)      │
└──────────────────────┬──────────────────────────────────┘
                       │ HTTP/HTTPS
                       ▼
┌─────────────────────────────────────────────────────────┐
│                   WEB SERVER LAYER                       │
│              Apache/Nginx + PHP-FPM                      │
│              (Laragon on Development)                    │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│                APPLICATION LAYER (Laravel 11)            │
│  ┌───────────────────────────────────────────────────┐  │
│  │  Routing → Middleware → Controller → Service     │  │
│  │           ↓                  ↓                    │  │
│  │       Validation        Business Logic            │  │
│  │           ↓                  ↓                    │  │
│  │        Model ←──────────→ Database                │  │
│  │           ↓                                       │  │
│  │       View (Blade)                                │  │
│  └───────────────────────────────────────────────────┘  │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│                  DATABASE LAYER                          │
│                MySQL 8.4 (db_sipekma_2)                 │
└─────────────────────────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│                 STORAGE LAYER                            │
│          File System (storage/app/uploads)               │
└─────────────────────────────────────────────────────────┘
```

### Technology Stack Summary

| Layer | Technology | Version | Purpose |
|-------|-----------|---------|---------|
| **Frontend** | Bootstrap 5 | 5.x | UI Framework |
| **Styling** | SCSS + Vite | 5.4 | CSS preprocessing & bundling |
| **Backend** | Laravel | 11.x | PHP Framework |
| **Language** | PHP | 8.2+ | Server-side language |
| **Database** | MySQL | 8.4 | Data persistence |
| **ORM** | Eloquent | Laravel | Database abstraction |
| **Template** | Blade | Laravel | View engine |
| **Assets** | Vite | 5.4 | Module bundler |
| **Server** | Laragon | Latest | Local development |

---

## 🎯 Architecture Pattern

### MVC (Model-View-Controller)

SIPEKMA menggunakan **MVC pattern** yang diimplementasikan oleh Laravel:

```
┌──────────────┐         ┌──────────────┐         ┌──────────────┐
│    MODEL     │◄────────│  CONTROLLER  │────────►│     VIEW     │
│              │         │              │         │              │
│ - Kegiatan   │         │ - KegiatanController  │ - index.blade│
│ - User       │         │ - UserController      │ - show.blade │
│ - Role       │         │ - AuthController      │ - create.blade│
│              │         │              │         │              │
│ Business     │         │ Request      │         │ Presentation │
│ Logic        │         │ Handling     │         │ Logic        │
└──────┬───────┘         └──────┬───────┘         └──────────────┘
       │                        │
       ▼                        ▼
┌──────────────────────────────────────┐
│           DATABASE                    │
│         (MySQL 8.4)                   │
└───────────────────────────────────────┘
```

### Layer Responsibilities

| Layer | Responsibility | Example |
|-------|---------------|---------|
| **Model** | Data structure, business logic, database interaction | `Kegiatan::where('status', 'approved')->get()` |
| **View** | Presentation, HTML rendering, user interface | `kegiatan/index.blade.php` |
| **Controller** | Request handling, coordination, response | `KegiatanController@index` |

---

## 📁 Project Structure

### Folder Organization

```
Web_SiPeKMa/
│
├── app/                          # APPLICATION CORE
│   ├── Http/
│   │   ├── Controllers/          # Request handlers
│   │   │   ├── Auth/             # Authentication controllers
│   │   │   ├── KegiatanController.php
│   │   │   ├── UsulanKegiatanController.php
│   │   │   └── UserController.php
│   │   └── Middleware/           # Request filters
│   │       ├── Authenticate.php
│   │       └── CheckRole.php
│   │
│   ├── Models/                   # Eloquent models
│   │   ├── User.php
│   │   ├── Kegiatan.php
│   │   ├── Role.php
│   │   └── ApprovalHistory.php
│   │
│   ├── DataTables/               # Yajra DataTables
│   │   └── UsersDataTable.php
│   │
│   └── Providers/                # Service providers
│       ├── AppServiceProvider.php
│       └── MenuServiceProvider.php
│
├── config/                       # CONFIGURATION
│   ├── app.php                   # Application config
│   ├── database.php              # Database config
│   └── variables.php             # Template variables
│
├── database/                     # DATABASE LAYER
│   ├── migrations/               # Database schema
│   ├── seeders/                  # Database seeders
│   └── factories/                # Model factories
│
├── resources/                    # FRONTEND RESOURCES
│   ├── views/                    # Blade templates
│   │   ├── layouts/              # Layout templates
│   │   ├── content/              # Page content
│   │   │   └── dashboard/
│   │   ├── kegiatan/             # Kegiatan views
│   │   └── usulan-kegiatan/      # Usulan views
│   │
│   ├── assets/                   # Raw assets
│   │   ├── scss/                 # SCSS files
│   │   │   ├── app.scss          # Main SCSS
│   │   │   └── custom/           # SIPEKMA custom styles
│   │   └── js/                   # JavaScript files
│   │
│   └── menu/                     # Menu configuration
│       └── verticalMenu.json     # Navigation structure
│
├── routes/                       # ROUTING
│   ├── web.php                   # Web routes
│   └── console.php               # Console routes
│
├── storage/                      # STORAGE
│   ├── app/
│   │   └── uploads/              # File uploads
│   ├── logs/                     # Application logs
│   └── framework/                # Framework cache
│
└── public/                       # PUBLIC ASSETS
    ├── index.php                 # Entry point
    ├── assets/                   # Template assets
    └── build/                    # Compiled assets (Vite)
```

### Key Directories Purpose

| Directory | Purpose | Access |
|-----------|---------|--------|
| `app/` | Application logic | Private |
| `config/` | Configuration files | Private |
| `database/` | Database management | Private |
| `resources/` | Raw resources (views, SCSS, JS) | Private |
| `routes/` | Route definitions | Private |
| `storage/` | File storage & logs | Private |
| `public/` | Publicly accessible files | Public |

---

## 🎨 Design Patterns

### 1. Repository Pattern (Planned)

**Purpose:** Abstraction layer antara business logic dan data access

**Current Implementation:** Direct Eloquent usage in controllers

**Planned Implementation:**

```php
// Interface
interface KegiatanRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

// Implementation
class KegiatanRepository implements KegiatanRepositoryInterface
{
    public function getAll()
    {
        return Kegiatan::with('user', 'jenisKegiatan')->latest()->get();
    }
    
    public function findById($id)
    {
        return Kegiatan::with('user', 'approvalHistories')->findOrFail($id);
    }
    
    // ... other methods
}

// Controller usage
class KegiatanController extends Controller
{
    protected $kegiatanRepo;
    
    public function __construct(KegiatanRepositoryInterface $kegiatanRepo)
    {
        $this->kegiatanRepo = $kegiatanRepo;
    }
    
    public function index()
    {
        $kegiatans = $this->kegiatanRepo->getAll();
        return view('kegiatan.index', compact('kegiatans'));
    }
}
```

**Benefits:**
- ✅ Separation of concerns
- ✅ Easier testing (mockable)
- ✅ Reusable data access logic
- ✅ Cleaner controllers

---

### 2. Service Layer Pattern (Planned)

**Purpose:** Business logic layer antara controller dan model

**Planned Implementation:**

```php
class KegiatanService
{
    protected $kegiatanRepo;
    
    public function __construct(KegiatanRepositoryInterface $kegiatanRepo)
    {
        $this->kegiatanRepo = $kegiatanRepo;
    }
    
    public function approveKegiatan($id, $userId)
    {
        // Business logic untuk approval
        $kegiatan = $this->kegiatanRepo->findById($id);
        
        // Validasi business rules
        if ($kegiatan->status !== 'submitted') {
            throw new BusinessException('Kegiatan tidak dapat disetujui');
        }
        
        // Update status
        $kegiatan->update(['status' => 'approved']);
        
        // Create approval history
        ApprovalHistory::create([
            'kegiatan_id' => $id,
            'user_id' => $userId,
            'action' => 'approve',
        ]);
        
        // Send notification (future)
        // event(new KegiatanApproved($kegiatan));
        
        return $kegiatan;
    }
}
```

---

### 3. Factory Pattern

**Purpose:** Create test data dengan Faker

**Implementation:**

```php
// database/factories/UserFactory.php
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role_id' => Role::factory(),
            'prodi_id' => Prodi::factory(),
        ];
    }
}

// Usage
User::factory()->count(10)->create();
```

---

### 4. Observer Pattern (Planned)

**Purpose:** Auto-trigger actions on model events

**Planned Implementation:**

```php
// app/Observers/KegiatanObserver.php
class KegiatanObserver
{
    public function created(Kegiatan $kegiatan)
    {
        // Auto-create initial approval history
        ApprovalHistory::create([
            'kegiatan_id' => $kegiatan->id,
            'user_id' => $kegiatan->user_id,
            'action' => 'submit',
        ]);
    }
    
    public function updated(Kegiatan $kegiatan)
    {
        // Log status changes
        if ($kegiatan->isDirty('status')) {
            Log::info("Kegiatan {$kegiatan->id} status changed to {$kegiatan->status}");
        }
    }
}

// Register in AppServiceProvider
public function boot()
{
    Kegiatan::observe(KegiatanObserver::class);
}
```

---

### 5. Singleton Pattern

**Purpose:** Menu service provider untuk share menu data ke semua views

**Implementation:**

```php
// app/Providers/MenuServiceProvider.php
class MenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load menu JSON once
        $verticalMenuJson = file_get_contents(
            resource_path('menu/verticalMenu.json')
        );
        $verticalMenuData = json_decode($verticalMenuJson);
        
        // Share to all views
        View::share('menuData', [$verticalMenuData]);
    }
}
```

**Benefit:** Menu JSON hanya di-load sekali, tidak per-request

---

### 6. Strategy Pattern (File Upload)

**Purpose:** Different upload strategies untuk different file types

**Planned Implementation:**

```php
interface FileUploadStrategy
{
    public function upload(UploadedFile $file): string;
    public function validate(UploadedFile $file): bool;
}

class ProposalUploadStrategy implements FileUploadStrategy
{
    public function upload(UploadedFile $file): string
    {
        return $file->store('proposals', 'public');
    }
    
    public function validate(UploadedFile $file): bool
    {
        return $file->extension() === 'pdf' && $file->getSize() <= 5_000_000;
    }
}

class RABUploadStrategy implements FileUploadStrategy
{
    public function upload(UploadedFile $file): string
    {
        return $file->store('rab', 'public');
    }
    
    public function validate(UploadedFile $file): bool
    {
        return in_array($file->extension(), ['xlsx', 'xls', 'pdf']) 
            && $file->getSize() <= 3_000_000;
    }
}
```

---

## 🔄 Request Lifecycle

### Complete Request Flow

```
1. CLIENT REQUEST
   │
   ▼
2. public/index.php (Entry Point)
   │ - Load autoloader
   │ - Bootstrap Laravel
   │
   ▼
3. HTTP KERNEL
   │ - Load service providers
   │ - Run global middleware
   │
   ▼
4. ROUTING
   │ - Match request to route
   │ - Apply route middleware
   │
   ▼
5. CONTROLLER
   │ - Validate request
   │ - Call service/model
   │ - Prepare response
   │
   ▼
6. VIEW/JSON
   │ - Render Blade template
   │ - or Return JSON response
   │
   ▼
7. RESPONSE
   │ - Send to client
   │
   ▼
8. TERMINATE MIDDLEWARE
   │ - Cleanup tasks
   │
   ▼
9. END
```

### Example: View Kegiatan Detail

**URL:** `GET /kegiatan/1`

**Flow:**

```
1. Browser Request: GET http://localhost:8001/kegiatan/1

2. public/index.php
   ├─ Load autoloader
   └─ Bootstrap Laravel application

3. app/Http/Kernel.php
   ├─ Load MenuServiceProvider (menu data)
   ├─ Load AppServiceProvider
   └─ Run global middleware: EncryptCookies, VerifyCsrfToken

4. routes/web.php
   ├─ Match route: Route::resource('kegiatan', KegiatanController::class)
   ├─ Route name: kegiatan.show
   ├─ Method: KegiatanController@show
   └─ Apply middleware: auth (if configured)

5. app/Http/Controllers/KegiatanController.php
   public function show($id)
   {
       // Query database
       $kegiatan = Kegiatan::with(['user', 'approvalHistories.user', 'files'])
           ->findOrFail($id);
       
       // Return view
       return view('kegiatan.show', compact('kegiatan'));
   }

6. resources/views/kegiatan/show.blade.php
   ├─ Load layout: layouts/contentNavbarLayout.blade.php
   ├─ Render breadcrumb
   ├─ Display kegiatan data
   └─ Show approval timeline

7. Response sent to browser
   ├─ HTTP 200 OK
   ├─ Content-Type: text/html
   └─ Body: Rendered HTML

8. Browser renders page
   ├─ Load CSS from /build/assets/app-*.css
   ├─ Load JS from /build/assets/app-*.js
   └─ Display to user
```

---

## 🔐 Authentication & Authorization

### Authentication Flow

```
┌─────────────┐
│   LOGIN     │
└──────┬──────┘
       │
       ▼
┌──────────────────────┐
│  Validate Credentials│
│  (email + password)  │
└──────┬───────────────┘
       │
       ▼
┌──────────────────────┐
│   Check Database     │
│   users table        │
└──────┬───────────────┘
       │
       ├─ Valid ──────────► Start Session
       │                    │
       │                    ▼
       │              Store user_id
       │              in session
       │                    │
       │                    ▼
       │              Redirect to
       │              Dashboard
       │
       └─ Invalid ─────────► Show Error
                             "Email/Password
                             salah"
```

### Authorization (Role-Based)

**Roles:**

| Role | Code | Level | Access |
|------|------|-------|--------|
| Super Admin | `super_admin` | 5 | Full access |
| Admin | `admin` | 4 | Manage users & master data |
| BEM | `bem` | 3 | Create & manage BEM kegiatans |
| HIMA | `hima` | 2 | Create & manage HIMA kegiatans |
| Finance | `finance` | 1 | View & approve pendanaan |

**Authorization Check:**

```php
// In Controller
public function create()
{
    // Check if user can create kegiatan
    if (!in_array(auth()->user()->role->name, ['super_admin', 'admin', 'bem', 'hima'])) {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses');
    }
    
    return view('kegiatan.create');
}

// Using Gate (planned)
Gate::define('create-kegiatan', function ($user) {
    return in_array($user->role->name, ['super_admin', 'admin', 'bem', 'hima']);
});

// In Controller
public function create()
{
    $this->authorize('create-kegiatan');
    return view('kegiatan.create');
}
```

### Middleware

**Available Middleware:**

| Middleware | Purpose | Usage |
|------------|---------|-------|
| `auth` | Ensure user is authenticated | `Route::middleware('auth')` |
| `guest` | Ensure user is guest (not authenticated) | Login/register routes |
| `verified` | Ensure email is verified | Protected routes |
| `throttle` | Rate limiting | API routes |

**Custom Middleware (Planned):**

```php
// app/Http/Middleware/CheckRole.php
class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!in_array($request->user()->role->name, $roles)) {
            abort(403, 'Unauthorized action');
        }
        
        return $next($request);
    }
}

// Usage in routes
Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware('check-role:super_admin,admin');
```

---

## 📁 File Upload System

### Upload Architecture

```
┌─────────────────┐
│  USER UPLOAD    │
│  (Browser)      │
└────────┬────────┘
         │ POST multipart/form-data
         ▼
┌─────────────────────────┐
│   CONTROLLER            │
│   - Validate file       │
│   - Check size/type     │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────┐
│   STORE FILE            │
│   storage/app/uploads/  │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────┐
│   SAVE TO DB            │
│   kegiatan_files table  │
│   - file_name           │
│   - file_path           │
│   - file_size           │
│   - mime_type           │
└─────────────────────────┘
```

### File Storage Structure

```
storage/app/
├── uploads/
│   ├── proposals/
│   │   ├── 2026/
│   │   │   ├── 01/
│   │   │   └── 02/
│   │   │       └── uuid_proposal.pdf
│   │
│   ├── rab/
│   │   └── 2026/
│   │       └── 02/
│   │           └── uuid_rab.xlsx
│   │
│   └── lpj/
│       └── 2026/
│           └── 02/
│               └── uuid_lpj.pdf
```

### File Upload Implementation

```php
// Controller method
public function storeProposal(Request $request, $id)
{
    // Validasi file
    $request->validate([
        'file_proposal' => 'required|file|mimes:pdf,docx|max:5120', // 5MB max
    ], [
        'file_proposal.required' => 'File proposal wajib diupload',
        'file_proposal.mimes' => 'File harus berformat PDF atau DOCX',
        'file_proposal.max' => 'File maksimal 5MB',
    ]);
    
    $kegiatan = Kegiatan::findOrFail($id);
    $file = $request->file('file_proposal');
    
    // Generate unique filename
    $filename = Str::uuid() . '_' . $file->getClientOriginalName();
    
    // Store file dengan folder structure
    $path = $file->storeAs(
        'uploads/proposals/' . date('Y/m'),
        $filename
    );
    
    // Save to database
    KegiatanFile::create([
        'kegiatan_id' => $kegiatan->id,
        'file_type' => 'proposal',
        'file_name' => $file->getClientOriginalName(),
        'file_path' => $path,
        'file_size' => $file->getSize(),
        'mime_type' => $file->getMimeType(),
        'uploaded_by' => auth()->id(),
    ]);
    
    return redirect()->back()->with('success', 'Proposal berhasil diupload');
}
```

### File Validation Rules

| File Type | Extensions | Max Size | Validation |
|-----------|-----------|----------|------------|
| **Proposal** | pdf, docx | 5 MB | Document files |
| **RAB** | pdf, xlsx, xls | 3 MB | Spreadsheet or PDF |
| **LPJ** | pdf, docx | 5 MB | Document files |
| **Dokumentasi** | jpg, jpeg, png, pdf | 2 MB | Images or PDF |

---

## 🎨 Frontend Architecture

### Asset Pipeline (Vite)

```
resources/assets/
├── scss/
│   ├── app.scss           # Main entry
│   ├── variables.scss     # Sneat variables
│   └── custom/
│       ├── _variables-custom.scss
│       ├── _components-custom.scss
│       └── _utilities-custom.scss
│
└── js/
    └── app.js             # Main JS
```

**Compilation Flow:**

```
1. Development:
   npm run dev
   │
   ├─ Vite starts dev server (localhost:5174)
   ├─ Watch for file changes
   ├─ Compile SCSS → CSS
   ├─ Bundle JS
   └─ Hot reload on save

2. Production:
   npm run build
   │
   ├─ Minify CSS
   ├─ Minify JS
   ├─ Hash filenames (app.abc123.css)
   └─ Output to public/build/
```

### Component Architecture

**Blade Components:**

```
resources/views/
├── layouts/
│   ├── contentNavbarLayout.blade.php    # Main layout
│   └── sections/
│       ├── navbar/
│       ├── menu/
│       └── footer/
│
├── _partials/
│   ├── toast.blade.php                   # Reusable toast
│   └── breadcrumb.blade.php              # Reusable breadcrumb
│
└── components/                            # Custom components
    ├── card.blade.php
    └── timeline.blade.php
```

**Usage:**

```blade
{{-- layouts/contentNavbarLayout.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    @vite(['resources/assets/scss/app.scss', 'resources/assets/js/app.js'])
</head>
<body>
    @include('layouts.sections.navbar.navbar')
    @include('layouts.sections.menu.verticalMenu')
    
    <div class="content-wrapper">
        @yield('content')
    </div>
    
    @include('_partials.toast')
</body>
</html>
```

---

## 📊 State Management

### Session State

**Laravel Session:**

```php
// Store data
session(['key' => 'value']);

// Retrieve data
$value = session('key');

// Flash data (one-time)
session()->flash('success', 'Data berhasil disimpan');

// Check existence
if (session()->has('key')) {
    // ...
}
```

### Flash Messages

**Implementation:**

```php
// Controller
return redirect()->route('kegiatan.index')
    ->with('success', 'Kegiatan berhasil disimpan');

// View (using toast partial)
@if(session('success'))
    <div class="toast show" role="alert">
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
@endif
```

**Message Types:**

| Type | Color | Icon | Usage |
|------|-------|------|-------|
| `success` | Green | ✓ | Successful action |
| `error` | Red | ✗ | Error/failure |
| `warning` | Yellow | ⚠ | Warning message |
| `info` | Blue | ℹ | Information |

---

## 🔒 Security Architecture

### Security Layers

```
┌─────────────────────────────────────┐
│        LAYER 1: HTTPS               │
│    (SSL/TLS Encryption)             │
└────────────────┬────────────────────┘
                 │
┌────────────────┴────────────────────┐
│        LAYER 2: AUTHENTICATION      │
│    (Session-based Auth)             │
└────────────────┬────────────────────┘
                 │
┌────────────────┴────────────────────┐
│        LAYER 3: AUTHORIZATION       │
│    (Role-based Access Control)      │
└────────────────┬────────────────────┘
                 │
┌────────────────┴────────────────────┐
│        LAYER 4: INPUT VALIDATION    │
│    (Request Validation)             │
└────────────────┬────────────────────┘
                 │
┌────────────────┴────────────────────┐
│        LAYER 5: CSRF PROTECTION     │
│    (@csrf directive)                │
└────────────────┬────────────────────┘
                 │
┌────────────────┴────────────────────┐
│        LAYER 6: SQL INJECTION       │
│    (Eloquent ORM)                   │
└────────────────┬────────────────────┘
                 │
┌────────────────┴────────────────────┐
│        LAYER 7: XSS PROTECTION      │
│    (Blade {{ }} escaping)           │
└─────────────────────────────────────┘
```

### Security Features

| Feature | Implementation | Status |
|---------|---------------|--------|
| **Password Hashing** | bcrypt (Laravel default) | ✅ Active |
| **CSRF Protection** | @csrf token in forms | ✅ Active |
| **SQL Injection Prevention** | Eloquent ORM | ✅ Active |
| **XSS Prevention** | Blade {{ }} auto-escaping | ✅ Active |
| **Session Security** | Encrypted sessions | ✅ Active |
| **File Upload Validation** | MIME type & size check | ✅ Active |
| **Rate Limiting** | Throttle middleware | 🔄 Planned |
| **HTTPS Enforcement** | Middleware redirect | 🔄 Planned for production |

### Input Sanitization

```php
// Validation with sanitization
$validated = $request->validate([
    'nama_kegiatan' => 'required|string|max:255',
    'email' => 'required|email',
    'total_anggaran' => 'required|numeric|min:0',
]);

// Laravel automatically sanitizes:
// - Trim whitespace
// - Remove null bytes
// - Validate types
```

---

## 🚀 Performance Optimization

### Caching Strategy

| Type | Implementation | Cache Time |
|------|---------------|------------|
| **Config Cache** | `php artisan config:cache` | Until cleared |
| **Route Cache** | `php artisan route:cache` | Until cleared |
| **View Cache** | Auto by Laravel | Until file changes |
| **Query Results** | `Cache::remember()` | Configurable |

### Database Optimization

```php
// Eager Loading (prevent N+1 queries)
$kegiatans = Kegiatan::with(['user', 'jenisKegiatan', 'files'])
    ->latest()
    ->paginate(10);

// Instead of:
$kegiatans = Kegiatan::all();
foreach ($kegiatans as $kegiatan) {
    echo $kegiatan->user->name; // N+1 query!
}
```

### Asset Optimization

| Optimization | Tool | Result |
|--------------|------|--------|
| **CSS Minification** | Vite build | ~40% smaller |
| **JS Minification** | Vite build | ~50% smaller |
| **Gzip Compression** | Server config | ~70% smaller |
| **Browser Caching** | Cache headers | Faster reload |

---

## 📈 Scalability Considerations

### Horizontal Scaling (Future)

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│  Server 1   │     │  Server 2   │     │  Server 3   │
│  (Laravel)  │     │  (Laravel)  │     │  (Laravel)  │
└──────┬──────┘     └──────┬──────┘     └──────┬──────┘
       │                    │                    │
       └────────────────────┴────────────────────┘
                            │
                   ┌────────┴────────┐
                   │  Load Balancer   │
                   │    (Nginx)       │
                   └────────┬─────────┘
                            │
                   ┌────────┴─────────┐
                   │  Shared Database  │
                   │     (MySQL)       │
                   └───────────────────┘
```

### Vertical Scaling

| Resource | Current | Recommended (Production) |
|----------|---------|--------------------------|
| **CPU** | 2 cores | 4+ cores |
| **RAM** | 4 GB | 8+ GB |
| **Storage** | 50 GB | 100+ GB SSD |
| **Database** | Shared | Dedicated server |

---

**Last Updated:** 02 Februari 2026  
**Version:** 1.0.0  
**Architecture Version:** 1.0  
**Status:** ✅ Documentation Complete
