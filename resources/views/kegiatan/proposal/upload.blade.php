@extends('layouts/contentNavbarLayout')

@section('title', 'Upload Proposal Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            <i class="bx bx-upload"></i> Upload Proposal Kegiatan
        </h4>
        <p class="text-muted mb-0">Upload dokumen proposal untuk kegiatan yang telah disetujui</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kegiatan.index') }}" class="text-muted">Usulan Kegiatan</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kegiatan.show', $kegiatan) }}" class="text-muted">Detail</a>
            </li>
            <li class="breadcrumb-item active">Upload Proposal</li>
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
                            @if($kegiatan->status === 'revisi')
                            <small>Proposal perlu revisi. Silakan upload dokumen proposal yang telah diperbaiki.</small>
                            @else
                            <small>Usulan telah disetujui. Silakan upload dokumen proposal Anda.</small>
                            @endif
                        </p>
                    </div>
                </div>

                @if($kegiatan->status === 'revisi')
                @php
                    $lastRevisiHistory = $kegiatan->approvalHistories
                        ->where('tahap', 'proposal')
                        ->where('action', 'revisi')
                        ->sortByDesc('approved_at')
                        ->first();
                @endphp
                @if($lastRevisiHistory && $lastRevisiHistory->comment)
                <div class="alert alert-warning mb-3">
                    <strong><i class="bx bx-error-circle me-1"></i> Catatan Revisi:</strong>
                    <p class="mb-0 mt-2">{{ $lastRevisiHistory->comment }}</p>
                    <small class="text-muted">
                        - {{ $lastRevisiHistory->approver->role->display_name }} ({{ $lastRevisiHistory->approved_at->format('d M Y H:i') }})
                    </small>
                </div>
                @endif
                @endif

                <div class="alert alert-info mb-0">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Informasi:</strong> File yang diupload harus berformat <strong>PDF</strong> dengan ukuran maksimal <strong>5MB</strong>.
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Upload Proposal</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kegiatan.proposal.store', $kegiatan) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label" for="file">
                            File Proposal <span class="text-danger">*</span>
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
                            <h6 class="mb-3">Pastikan Proposal Anda:</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check1">
                                <label class="form-check-label" for="check1">
                                    Berisi latar belakang kegiatan yang jelas
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check2">
                                <label class="form-check-label" for="check2">
                                    Mencantumkan tujuan dan target peserta
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check3">
                                <label class="form-check-label" for="check3">
                                    Memiliki timeline pelaksanaan yang detail
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="check4">
                                <label class="form-check-label" for="check4">
                                    Rancangan anggaran yang terperinci (jika ada)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-upload me-1"></i> Upload Proposal
                        </button>
                        <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-label-secondary">
                            <i class="bx bx-x me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script>
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('file-preview');

    if (file) {
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = formatFileSize(file.size);
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
});

function clearFile() {
    document.getElementById('file').value = '';
    document.getElementById('file-preview').style.display = 'none';
}

function formatFileSize(bytes) {
    if (bytes >= 1048576) {
        return (bytes / 1048576).toFixed(2) + ' MB';
    } else if (bytes >= 1024) {
        return (bytes / 1024).toFixed(2) + ' KB';
    }
    return bytes + ' bytes';
}
</script>
@endsection
