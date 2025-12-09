@extends('layouts/contentNavbarLayout')

@section('title', 'Upload RAB Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            <i class="bx bx-upload"></i> Upload RAB Kegiatan
        </h4>
        <p class="text-muted mb-0">Upload Rencana Anggaran Biaya untuk kegiatan yang proposalnya telah disetujui</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kegiatan.pendanaan.index') }}" class="text-muted">Pendanaan</a>
            </li>
            <li class="breadcrumb-item active">Upload RAB</li>
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
                        @if($kegiatan->tahap === 'pendanaan' && $kegiatan->status === 'revision')
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

                @if($kegiatan->tahap === 'pendanaan' && $kegiatan->status === 'revision' && $lastRevision)
                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="bx bx-error-circle me-1"></i> Revisi Diperlukan
                    </h6>
                    <div class="mb-2">
                        <strong>Dari:</strong> {{ $lastRevision->approver->username }}
                        ({{ match($lastRevision->approver->role->role_name) {
                            'pembina_hima' => 'Pembina Hima',
                            'kaprodi' => 'Kaprodi',
                            'wadek_iii' => 'Wadek III',
                            default => $lastRevision->approver->role->role_name
                        } }})
                    </div>
                    <div class="mb-0">
                        <strong>Catatan:</strong><br>
                        {{ $lastRevision->comment }}
                    </div>
                    <small class="text-muted">{{ $lastRevision->created_at->diffForHumans() }}</small>
                </div>
                @endif

                @if($existingFile)
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="bx bx-info-circle me-1"></i> File RAB Saat Ini
                    </h6>
                    <div class="d-flex align-items-center">
                        <i class="bx bx-file-blank me-2" style="font-size: 2rem;"></i>
                        <div class="flex-grow-1">
                            <strong>{{ $existingFile->file_name }}</strong>
                            <div class="text-muted small">{{ $existingFile->fileSizeFormatted }} | Diupload {{ $existingFile->created_at->diffForHumans() }}</div>
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
                    {{ $existingFile ? 'Upload Ulang RAB' : 'Upload RAB' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kegiatan.pendanaan.store', $kegiatan) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <!-- Total Anggaran -->
                    <div class="mb-4">
                        <label for="total_anggaran_display" class="form-label">
                            Total Anggaran yang Diajukan <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input
                                type="text"
                                class="form-control @error('total_anggaran') is-invalid @enderror"
                                id="total_anggaran_display"
                                value="{{ old('total_anggaran', $kegiatan->total_anggaran ? number_format($kegiatan->total_anggaran, 0, ',', '.') : '') }}"
                                placeholder="0"
                                autocomplete="off">
                            <!-- Hidden field untuk value asli tanpa format -->
                            <input
                                type="hidden"
                                name="total_anggaran"
                                id="total_anggaran"
                                value="{{ old('total_anggaran', $kegiatan->total_anggaran ? number_format($kegiatan->total_anggaran, 0, '', '') : '') }}"
                                required>
                            @error('total_anggaran')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <i class="bx bx-info-circle me-1"></i>
                            Masukkan total anggaran yang diajukan untuk kegiatan ini (gunakan angka saja)
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label for="file_rab" class="form-label">
                            File RAB (PDF) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="file"
                            class="form-control @error('file_rab') is-invalid @enderror"
                            id="file_rab"
                            name="file_rab"
                            accept=".pdf"
                            required>
                        @error('file_rab')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bx bx-file-blank me-1"></i>
                            Format: PDF | Ukuran maksimal: 5MB
                        </div>
                    </div>

                    <!-- File Preview Area -->
                    <div id="file-preview" class="mb-4" style="display: none;">
                        <div class="alert alert-secondary">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-file-blank me-2" style="font-size: 2rem;"></i>
                                <div class="flex-grow-1">
                                    <strong id="file-name">-</strong>
                                    <div class="text-muted small" id="file-size">-</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-label-danger" onclick="clearFile()">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="mb-3">Pastikan RAB Anda:</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check1">
                                <label class="form-check-label" for="check1">
                                    Berisi rincian anggaran yang detail dan jelas
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check2">
                                <label class="form-check-label" for="check2">
                                    Mencantumkan volume dan satuan untuk setiap item
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check3">
                                <label class="form-check-label" for="check3">
                                    Total anggaran sesuai dengan yang diinputkan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check4">
                                <label class="form-check-label" for="check4">
                                    Sudah direview dan disetujui oleh tim internal
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kegiatan.pendanaan.index') }}" class="btn btn-label-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-upload me-1"></i> Upload RAB
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
// Format currency input dengan pemisah ribuan
const displayInput = document.getElementById('total_anggaran_display');
const hiddenInput = document.getElementById('total_anggaran');

displayInput.addEventListener('input', function(e) {
    // Hapus semua karakter non-digit
    let value = e.target.value.replace(/[^\d]/g, '');

    // Update hidden field dengan value asli (tanpa format)
    hiddenInput.value = value;

    // Format display dengan pemisah ribuan (titik)
    if (value) {
        e.target.value = parseInt(value).toLocaleString('id-ID');
    } else {
        e.target.value = '';
    }
});

// Trigger format saat load jika ada value
if (displayInput.value) {
    let value = displayInput.value.replace(/[^\d]/g, '');
    hiddenInput.value = value;
    if (value) {
        displayInput.value = parseInt(value).toLocaleString('id-ID');
    }
}

// File preview
document.getElementById('file_rab').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = fileSize + ' MB';
        document.getElementById('file-preview').style.display = 'block';
    }
});

function clearFile() {
    document.getElementById('file_rab').value = '';
    document.getElementById('file-preview').style.display = 'none';
}

// Form validation
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const file = document.getElementById('file_rab').files[0];

    if (file) {
        const fileSize = file.size / (1024 * 1024); // Convert to MB

        if (fileSize > 5) {
            e.preventDefault();
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            return false;
        }

        if (file.type !== 'application/pdf') {
            e.preventDefault();
            alert('File harus berformat PDF.');
            return false;
        }
    }
});
</script>
@endsection
