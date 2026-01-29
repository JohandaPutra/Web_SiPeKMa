@extends('layouts/contentNavbarLayout')

@section('title', 'Upload LPJ Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            <i class="bx bx-upload"></i> Upload LPJ Kegiatan
        </h4>
        <p class="text-muted mb-0">Upload Laporan Pertanggungjawaban untuk kegiatan yang pendanaannya telah disetujui</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kegiatan.laporan.index') }}" class="text-muted">Laporan</a>
            </li>
            <li class="breadcrumb-item active">Upload LPJ</li>
        </ol>
    </nav>
</div>

@include('_partials/toast')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Info Kegiatan -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        @if($kegiatan->status === 'revisi')
                        <span class="badge bg-warning p-3">
                            <i class="bx bx-error" style="font-size: 1.5rem;"></i>
                        </span>
                        @else
                        <span class="badge bg-success p-3">
                            <i class="bx bx-check-circle" style="font-size: 1.5rem;"></i>
                        </span>
                        @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1">{{ $kegiatan->nama_kegiatan }}</h5>
                        <p class="text-muted mb-0">
                            <i class="bx bx-calendar me-1"></i>
                            {{ $kegiatan->tanggal_mulai->format('d M Y') }} - {{ $kegiatan->tanggal_akhir->format('d M Y') }}
                        </p>
                    </div>
                </div>

                @if($kegiatan->status === 'revisi')
                @php
                    $lastRevisi = $kegiatan->approvalHistories
                        ->where('tahap', 'laporan')
                        ->where('action', 'revisi')
                        ->sortByDesc('approved_at')
                        ->first();
                @endphp
                @if($lastRevisi)
                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="bx bx-error-circle me-1"></i> Revisi Diperlukan
                    </h6>
                    <div class="mb-2">
                        <strong>Dari:</strong> {{ $lastRevisi->approver->name }}
                        ({{ match($lastRevisi->approver->role->name) {
                            'pembina_hima' => 'Pembina Hima',
                            'kaprodi' => 'Kaprodi',
                            'wadek_iii' => 'Wadek III',
                            default => $lastRevisi->approver->role->name
                        } }})
                    </div>
                    <div class="mb-0">
                        <strong>Catatan:</strong><br>
                        {{ $lastRevisi->comment }}
                    </div>
                    <small class="text-muted">{{ $lastRevisi->approved_at->diffForHumans() }}</small>
                </div>
                @endif
                @endif

                @php
                    $existingFile = $kegiatan->getFileByTahap('laporan');
                @endphp
                @if($existingFile)
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="bx bx-info-circle me-1"></i> File LPJ Saat Ini
                    </h6>
                    <div class="d-flex align-items-center">
                        <i class="bx bx-file-blank me-2" style="font-size: 2rem;"></i>
                        <div class="flex-grow-1">
                            <strong>{{ $existingFile->file_name }}</strong>
                            <div class="text-muted small">{{ $existingFile->fileSizeFormatted }} | Diupload {{ $existingFile->uploaded_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">File ini akan diganti dengan file baru yang Anda upload</small>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Upload Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-upload me-1"></i>
                    {{ $existingFile ? 'Upload Ulang LPJ' : 'Upload LPJ' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kegiatan.laporan.store', $kegiatan) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label for="file" class="form-label">
                            File LPJ (PDF) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="file"
                            class="form-control @error('file') is-invalid @enderror"
                            id="file"
                            name="file"
                            accept=".pdf"
                            required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <ul class="mb-0">
                                <li>Format file harus PDF</li>
                                <li>Ukuran maksimal 10 MB</li>
                                <li>LPJ harus berisi laporan lengkap kegiatan yang telah dilaksanakan</li>
                            </ul>
                        </div>
                    </div>

                    <!-- File Preview Area -->
                    <div id="filePreview" class="mb-4" style="display: none;">
                        <div class="alert alert-success">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-check-circle me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <strong>File dipilih:</strong>
                                    <div id="fileName" class="text-muted small"></div>
                                    <div id="fileSize" class="text-muted small"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Penting -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">
                            <i class="bx bx-info-circle me-1"></i> Informasi Penting
                        </h6>
                        <ul class="mb-0">
                            <li>Pastikan LPJ berisi dokumentasi kegiatan yang lengkap</li>
                            <li>Sertakan laporan realisasi anggaran jika ada</li>
                            <li>File yang diupload akan menggantikan file sebelumnya (jika ada)</li>
                            <li>Setelah upload, jangan lupa klik tombol <strong>Submit</strong> di halaman detail untuk memulai proses persetujuan</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kegiatan.laporan.show', $kegiatan) }}" class="btn btn-label-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bx bx-upload me-1"></i> Upload LPJ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script>
// File preview
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    if (file) {
        preview.style.display = 'block';
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';

        // Validate file size
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 10 MB.');
            e.target.value = '';
            preview.style.display = 'none';
        }
    } else {
        preview.style.display = 'none';
    }
});

// Form submission
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengupload...';
});
</script>
@endsection
