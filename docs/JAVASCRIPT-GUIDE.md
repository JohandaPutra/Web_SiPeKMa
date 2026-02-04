# JavaScript Guide - SIPEKMA

**Panduan Lengkap JavaScript & Frontend Logic**

---

## 📋 Daftar Isi

1. [Overview](#overview)
2. [JavaScript Architecture](#javascript-architecture)
3. [DataTables Implementation](#datatables-implementation)
4. [Form Validation](#form-validation)
5. [Toast Notifications](#toast-notifications)
6. [File Upload Handling](#file-upload-handling)
7. [AJAX Requests](#ajax-requests)
8. [Event Handling](#event-handling)
9. [Modal Management](#modal-management)
10. [Best Practices](#best-practices)

---

## 📖 Overview

### JavaScript Stack

| Technology | Version | Purpose |
|-----------|---------|---------|
| **Vanilla JavaScript** | ES6+ | Core scripting |
| **jQuery** | 3.7.x | DOM manipulation (dari Sneat) |
| **DataTables** | 2.x | Server-side tables |
| **Bootstrap JS** | 5.x | Modal, dropdown, toast |
| **Vite** | 5.4 | Module bundler |
| **Boxicons** | - | Icon library |

### JavaScript Files Structure

```
resources/assets/js/
├── app.js                    # Main entry point
├── datatable-init.js         # DataTables initialization
├── form-validation.js        # Form validation logic
├── toast.js                  # Toast notification handler
└── utils.js                  # Utility functions

public/build/
└── assets/
    └── app-[hash].js         # Compiled & minified
```

---

## 🏗️ JavaScript Architecture

### Module Pattern

```javascript
// Pattern yang digunakan: IIFE (Immediately Invoked Function Expression)

(function() {
    'use strict';
    
    // Private variables
    const config = {
        apiUrl: '/api',
        timeout: 5000
    };
    
    // Private methods
    function privateMethod() {
        // Internal logic
    }
    
    // Public API
    window.MyModule = {
        publicMethod: function() {
            privateMethod();
        }
    };
})();
```

### Event Delegation

```javascript
// ✅ GOOD: Event delegation (efficient)
document.addEventListener('click', function(e) {
    if (e.target.matches('.btn-delete')) {
        handleDelete(e.target);
    }
});

// ❌ BAD: Individual event listeners
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', handleDelete);
});
```

---

## 📊 DataTables Implementation

### Basic DataTables Setup

**File:** `resources/views/users/index.blade.php`

```javascript
$(document).ready(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role.name', name: 'role.name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/id.json'
        }
    });
});
```

### DataTables Configuration Options

| Option | Value | Purpose |
|--------|-------|---------|
| `processing` | true | Show loading indicator |
| `serverSide` | true | Server-side processing |
| `ajax` | route URL | Data source |
| `columns` | array | Column mapping |
| `language` | Indonesian | Localization |
| `pageLength` | 10 | Rows per page |
| `order` | [[0, 'desc']] | Default sorting |
| `responsive` | true | Mobile responsive |

### Complete DataTables Example

```javascript
$(document).ready(function() {
    const table = $('#kegiatans-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('kegiatan.data') }}",
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', error);
                showToast('error', 'Gagal memuat data');
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                width: '5%'
            },
            { 
                data: 'nama_kegiatan', 
                name: 'nama_kegiatan',
                render: function(data, type, row) {
                    return `<a href="/kegiatan/${row.id}" class="text-primary">
                        ${data}
                    </a>`;
                }
            },
            { 
                data: 'user.name', 
                name: 'user.name' 
            },
            { 
                data: 'tanggal_mulai', 
                name: 'tanggal_mulai',
                render: function(data) {
                    return moment(data).format('DD MMM YYYY');
                }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function(data, type, row) {
                    const badges = {
                        'draft': 'secondary',
                        'submitted': 'info',
                        'approved': 'success',
                        'rejected': 'danger',
                        'revision': 'warning'
                    };
                    return `<span class="badge bg-${badges[data]}">${row.status_label}</span>`;
                }
            },
            { 
                data: 'action', 
                name: 'action',
                orderable: false,
                searchable: false,
                width: '15%'
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/id.json',
            emptyTable: "Belum ada data kegiatan",
            loadingRecords: "Memuat data...",
            processing: "Memproses...",
            zeroRecords: "Data tidak ditemukan"
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[3, 'desc']], // Sort by tanggal_mulai
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    });
    
    // Reload table on custom event
    window.addEventListener('kegiatan-updated', function() {
        table.ajax.reload(null, false); // Keep current page
    });
});
```

### DataTables Custom Rendering

```javascript
// Render button actions
{
    data: 'action',
    render: function(data, type, row) {
        let buttons = '';
        
        // View button
        buttons += `<a href="/kegiatan/${row.id}" 
                       class="btn btn-sm btn-icon btn-info" 
                       title="Lihat Detail">
                        <i class="bx bx-show"></i>
                    </a> `;
        
        // Edit button (if allowed)
        if (row.can_edit) {
            buttons += `<a href="/kegiatan/${row.id}/edit" 
                           class="btn btn-sm btn-icon btn-warning" 
                           title="Edit">
                            <i class="bx bx-edit"></i>
                        </a> `;
        }
        
        // Delete button (if allowed)
        if (row.can_delete) {
            buttons += `<button onclick="deleteKegiatan(${row.id})" 
                               class="btn btn-sm btn-icon btn-danger" 
                               title="Hapus">
                            <i class="bx bx-trash"></i>
                        </button>`;
        }
        
        return buttons;
    }
}
```

### DataTables Export Buttons

```javascript
$('#kegiatans-table').DataTable({
    // ... other options
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'excel',
            text: '<i class="bx bx-file"></i> Excel',
            className: 'btn btn-success btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3, 4] // Export specified columns
            }
        },
        {
            extend: 'pdf',
            text: '<i class="bx bxs-file-pdf"></i> PDF',
            className: 'btn btn-danger btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
            }
        },
        {
            extend: 'print',
            text: '<i class="bx bx-printer"></i> Print',
            className: 'btn btn-info btn-sm'
        }
    ]
});
```

---

## ✅ Form Validation

### Client-Side Validation

**HTML5 Validation:**

```html
<form id="kegiatan-form" novalidate>
    <div class="mb-3">
        <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
        <input type="text" 
               class="form-control" 
               id="nama_kegiatan" 
               name="nama_kegiatan"
               required
               minlength="5"
               maxlength="255">
        <div class="invalid-feedback">
            Nama kegiatan wajib diisi (minimal 5 karakter)
        </div>
    </div>
    
    <div class="mb-3">
        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
        <input type="date" 
               class="form-control" 
               id="tanggal_mulai" 
               name="tanggal_mulai"
               required
               min="2026-01-01">
        <div class="invalid-feedback">
            Tanggal mulai wajib diisi
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
```

**JavaScript Validation:**

```javascript
(function() {
    'use strict';
    
    // Fetch form
    const form = document.getElementById('kegiatan-form');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            // Prevent default submission
            event.preventDefault();
            event.stopPropagation();
            
            // Check validity
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                
                // Find first invalid field and focus
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
                
                return false;
            }
            
            // Custom validation
            if (!validateDateRange()) {
                showToast('error', 'Tanggal selesai harus setelah tanggal mulai');
                return false;
            }
            
            // If valid, submit via AJAX or normal
            submitForm();
        }, false);
    }
    
    // Custom date range validation
    function validateDateRange() {
        const startDate = new Date(document.getElementById('tanggal_mulai').value);
        const endDate = new Date(document.getElementById('tanggal_selesai').value);
        
        return endDate >= startDate;
    }
    
    // Submit form
    function submitForm() {
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showToast('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Terjadi kesalahan saat menyimpan data');
        });
    }
})();
```

### Real-time Validation

```javascript
// Validate on input
document.getElementById('email').addEventListener('input', function(e) {
    const email = e.target.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (emailRegex.test(email)) {
        e.target.classList.remove('is-invalid');
        e.target.classList.add('is-valid');
    } else {
        e.target.classList.remove('is-valid');
        e.target.classList.add('is-invalid');
    }
});

// Validate file size
document.getElementById('file_upload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const maxSize = 5 * 1024 * 1024; // 5MB
    
    if (file && file.size > maxSize) {
        e.target.classList.add('is-invalid');
        showToast('error', 'File maksimal 5MB');
        e.target.value = ''; // Clear file
    } else {
        e.target.classList.remove('is-invalid');
        e.target.classList.add('is-valid');
    }
});
```

---

## 🔔 Toast Notifications

### Toast HTML Structure

**File:** `resources/views/_partials/toast.blade.php`

```html
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    @if(session('success'))
    <div class="toast show align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bx bx-check-circle me-2"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                    data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div class="toast show align-items-center text-white bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bx bx-error me-2"></i>
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                    data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>
```

### Toast JavaScript

```javascript
// Auto-hide toast after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast.show');
    
    toasts.forEach(toast => {
        setTimeout(() => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000); // 5 seconds
    });
});

// Function to show toast dynamically
function showToast(type, message) {
    const colors = {
        success: 'bg-success',
        error: 'bg-danger',
        warning: 'bg-warning',
        info: 'bg-info'
    };
    
    const icons = {
        success: 'bx-check-circle',
        error: 'bx-error',
        warning: 'bx-error-circle',
        info: 'bx-info-circle'
    };
    
    const toastHtml = `
        <div class="toast align-items-center text-white ${colors[type]} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bx ${icons[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Find or create toast container
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    // Add toast to container
    container.insertAdjacentHTML('beforeend', toastHtml);
    
    // Get the newly added toast
    const toastElement = container.lastElementChild;
    const bsToast = new bootstrap.Toast(toastElement);
    
    // Show toast
    bsToast.show();
    
    // Remove from DOM after hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

// Usage examples
// showToast('success', 'Data berhasil disimpan');
// showToast('error', 'Terjadi kesalahan');
// showToast('warning', 'Perhatian!');
// showToast('info', 'Informasi');
```

---

## 📁 File Upload Handling

### File Upload Preview

```javascript
document.getElementById('file_proposal').addEventListener('change', function(e) {
    const file = e.target.files[0];
    
    if (file) {
        // Show file info
        const fileInfo = document.getElementById('file-info');
        fileInfo.innerHTML = `
            <div class="alert alert-info">
                <i class="bx bx-file"></i>
                <strong>${file.name}</strong>
                <br>
                <small>${formatFileSize(file.size)}</small>
            </div>
        `;
        
        // Preview image if image file
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').innerHTML = `
                    <img src="${e.target.result}" 
                         class="img-fluid rounded" 
                         style="max-height: 200px">
                `;
            };
            reader.readAsDataURL(file);
        }
    }
});

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
```

### Drag & Drop Upload

```javascript
const dropZone = document.getElementById('drop-zone');

// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Highlight drop zone when dragging
['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight() {
    dropZone.classList.add('border-primary', 'bg-light');
}

function unhighlight() {
    dropZone.classList.remove('border-primary', 'bg-light');
}

// Handle dropped files
dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    handleFiles(files);
}

function handleFiles(files) {
    ([...files]).forEach(uploadFile);
}

function uploadFile(file) {
    const formData = new FormData();
    formData.append('file', file);
    
    fetch('/upload', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        showToast('success', `File ${file.name} berhasil diupload`);
    })
    .catch(error => {
        showToast('error', `Gagal upload ${file.name}`);
    });
}
```

---

## 🌐 AJAX Requests

### Basic AJAX with Fetch API

```javascript
function fetchKegiatanData(id) {
    fetch(`/api/kegiatan/${id}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Data received:', data);
        displayKegiatanData(data);
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('error', 'Gagal memuat data');
    });
}
```

### POST Request with CSRF Token

```javascript
function submitKegiatanData(data) {
    fetch('/kegiatan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Data berhasil disimpan');
            // Trigger DataTables reload
            window.dispatchEvent(new Event('kegiatan-updated'));
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Terjadi kesalahan');
    });
}
```

### AJAX Form Submission

```javascript
document.getElementById('ajax-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    
    fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            form.reset();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        showToast('error', 'Terjadi kesalahan saat menyimpan');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Simpan';
    });
});
```

### Loading State Management

```javascript
function showLoading(show = true) {
    const loader = document.getElementById('page-loader');
    
    if (show) {
        if (!loader) {
            const loaderHtml = `
                <div id="page-loader" class="position-fixed top-0 start-0 w-100 h-100 
                     d-flex align-items-center justify-content-center" 
                     style="background: rgba(0,0,0,0.5); z-index: 9999">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', loaderHtml);
        }
    } else {
        if (loader) {
            loader.remove();
        }
    }
}

// Usage
showLoading(true);
fetch('/api/data')
    .then(response => response.json())
    .then(data => {
        // Process data
    })
    .finally(() => {
        showLoading(false);
    });
```

---

## 🎯 Event Handling

### Button Click Events

```javascript
// Delete confirmation
function deleteKegiatan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')) {
        fetch(`/kegiatan/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'Kegiatan berhasil dihapus');
                // Reload DataTable
                $('#kegiatans-table').DataTable().ajax.reload();
            } else {
                showToast('error', data.message);
            }
        })
        .catch(error => {
            showToast('error', 'Terjadi kesalahan');
        });
    }
}

// Approve kegiatan
function approveKegiatan(id) {
    if (confirm('Setujui kegiatan ini?')) {
        showLoading(true);
        
        fetch(`/kegiatan/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'Kegiatan berhasil disetujui');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast('error', data.message);
            }
        })
        .finally(() => {
            showLoading(false);
        });
    }
}
```

### Custom Events

```javascript
// Create custom event
const kegiatanUpdated = new CustomEvent('kegiatan-updated', {
    detail: { kegiatanId: 123 }
});

// Dispatch event
window.dispatchEvent(kegiatanUpdated);

// Listen for custom event
window.addEventListener('kegiatan-updated', function(e) {
    console.log('Kegiatan updated:', e.detail.kegiatanId);
    
    // Reload DataTable
    $('#kegiatans-table').DataTable().ajax.reload();
});
```

---

## 🪟 Modal Management

### Bootstrap Modal

```javascript
// Show modal programmatically
function showEditModal(id) {
    // Fetch data
    fetch(`/kegiatan/${id}`)
        .then(response => response.json())
        .then(data => {
            // Populate modal fields
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-nama').value = data.nama_kegiatan;
            document.getElementById('edit-tanggal').value = data.tanggal_mulai;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });
}

// Hide modal
function hideModal(modalId) {
    const modalEl = document.getElementById(modalId);
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
}

// Modal event listeners
const modalEl = document.getElementById('editModal');
modalEl.addEventListener('shown.bs.modal', function() {
    // Focus first input when modal shown
    this.querySelector('input').focus();
});

modalEl.addEventListener('hidden.bs.modal', function() {
    // Clear form when modal hidden
    this.querySelector('form').reset();
});
```

---

## 📚 Utility Functions

### Common Utilities

```javascript
// Utility object
const Utils = {
    // Format currency
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    },
    
    // Format date
    formatDate: function(date) {
        return new Date(date).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    },
    
    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Copy to clipboard
    copyToClipboard: function(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('success', 'Disalin ke clipboard');
        }).catch(() => {
            showToast('error', 'Gagal menyalin');
        });
    },
    
    // Escape HTML
    escapeHtml: function(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
};

// Usage
console.log(Utils.formatCurrency(1000000)); // Rp 1.000.000
console.log(Utils.formatDate('2026-02-02')); // 2 Februari 2026
```

---

## ✅ Best Practices

### DO ✅

| Practice | Example | Benefit |
|----------|---------|---------|
| **Use const/let** | `const items = []` | Block scoping |
| **Event delegation** | Delegate to parent | Performance |
| **Error handling** | try/catch, .catch() | Graceful errors |
| **Loading states** | Show spinner | UX feedback |
| **Debounce search** | Wait before search | Reduce requests |
| **CSRF token** | Include in AJAX | Security |
| **Semantic naming** | `submitForm()` | Readable code |
| **Comments** | Explain complex logic | Maintainability |

### DON'T ❌

| Anti-pattern | Why Bad | Better Alternative |
|--------------|---------|-------------------|
| **Global variables** | Namespace pollution | Module pattern |
| **Inline handlers** | Hard to maintain | addEventListener() |
| **eval()** | Security risk | JSON.parse() |
| **Synchronous AJAX** | Blocks UI | Async/await |
| **No error handling** | Silent failures | Try/catch |
| **Magic numbers** | Unclear meaning | Named constants |
| **Deep nesting** | Hard to read | Early returns |

---

## 🔧 Development Tools

### Browser DevTools

| Tool | Purpose | Shortcut |
|------|---------|----------|
| **Console** | Debug logs, test code | F12 → Console |
| **Network** | Monitor requests | F12 → Network |
| **Debugger** | Set breakpoints | F12 → Sources |
| **Elements** | Inspect DOM | F12 → Elements |
| **Performance** | Profile performance | F12 → Performance |

### VS Code Extensions

| Extension | Purpose |
|-----------|---------|
| **ESLint** | Code linting |
| **Prettier** | Code formatting |
| **JavaScript (ES6) code snippets** | Code snippets |
| **Path Intellisense** | File path autocomplete |

---

**Last Updated:** 02 Februari 2026  
**Version:** 1.0.0  
**JavaScript Version:** ES6+  
**Status:** ✅ Complete
