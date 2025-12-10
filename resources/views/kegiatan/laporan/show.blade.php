@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Laporan Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            Detail Laporan Kegiatan
        </h4>
        <p class="text-muted mb-0">Informasi lengkap Laporan Pertanggungjawaban (LPJ) kegiatan</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kegiatan.laporan.index') }}" class="text-muted">Laporan</a>
            </li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

@include('_partials/toast')

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Informasi Kegiatan -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Informasi Kegiatan</h5>
                <div>
                    <span class="badge bg-{{ $kegiatan->tahapBadge }} me-1">{{ ucfirst($kegiatan->tahap) }}</span>
                    <span class="badge bg-{{ $kegiatan->statusBadge }}">{{ ucfirst($kegiatan->status) }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Nama Kegiatan:</strong></div>
                    <div class="col-12 col-sm-8">{{ $kegiatan->nama_kegiatan }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Jenis Kegiatan:</strong></div>
                    <div class="col-12 col-sm-8">
                        <span class="badge bg-label-info">{{ ucfirst($kegiatan->jenis_kegiatan) }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Deskripsi:</strong></div>
                    <div class="col-12 col-sm-8">{{ $kegiatan->deskripsi_kegiatan }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Tempat:</strong></div>
                    <div class="col-12 col-sm-8">{{ $kegiatan->tempat_kegiatan }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Tanggal:</strong></div>
                    <div class="col-12 col-sm-8">
                        <span class="d-block d-sm-inline">{{ $kegiatan->tanggal_mulai->format('d M Y') }}</span>
                        <span class="d-block d-sm-inline">s/d {{ $kegiatan->tanggal_akhir->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Jenis Pendanaan:</strong></div>
                    <div class="col-12 col-sm-8">
                        <span class="badge bg-label-primary">{{ ucfirst($kegiatan->jenis_pendanaan) }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Total Anggaran:</strong></div>
                    <div class="col-12 col-sm-8">
                        @if($kegiatan->total_anggaran)
                        <h5 class="mb-0 text-success">Rp {{ number_format($kegiatan->total_anggaran, 0, ',', '.') }}</h5>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><strong>Prodi:</strong></div>
                    <div class="col-sm-8">
                        <span class="badge bg-label-secondary">{{ $kegiatan->prodi->nama_prodi }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- File LPJ -->
        @if($laporanFile)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-file-blank"></i> Dokumen LPJ (Laporan Pertanggungjawaban)
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <span class="badge bg-success p-3">
                            <i class="bx bx-file-blank" style="font-size: 1.5rem;"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">{{ $laporanFile->file_name }}</h6>
                        <div class="text-muted small">
                            <span class="me-3">
                                <i class="bx bx-calendar me-1"></i>
                                {{ $laporanFile->uploaded_at->format('d M Y H:i') }}
                            </span>
                            <span class="me-3">
                                <i class="bx bx-data me-1"></i>
                                {{ $laporanFile->fileSizeFormatted }}
                            </span>
                            <span>
                                <i class="bx bx-user me-1"></i>
                                {{ $laporanFile->uploader->username }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ asset('storage/' . $laporanFile->file_path) }}"
                           target="_blank"
                           class="btn btn-primary btn-sm">
                            <i class="bx bx-download me-1"></i> Download
                        </a>
                    </div>
                </div>

                <!-- PDF Viewer -->
                <div class="border rounded p-2 bg-light">
                    <iframe
                        src="{{ asset('storage/' . $laporanFile->file_path) }}"
                        width="100%"
                        height="600px"
                        style="border: none;">
                    </iframe>
                </div>
            </div>
        </div>
        @else
        <div class="card mb-4">
            <div class="card-body text-center py-5">
                <i class="bx bx-file-blank bx-lg text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada File LPJ</h5>
                <p class="text-muted">Silakan upload dokumen LPJ Anda.</p>
            </div>
        </div>
        @endif

        <!-- History Approval -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-history"></i> Riwayat Persetujuan Laporan
                </h5>
            </div>
            <div class="card-body">
                @php
                    $laporanHistories = $kegiatan->approvalHistories->where('tahap', 'laporan');
                @endphp

                @if($laporanHistories->count() > 0)
                <div class="timeline">
                    @foreach($laporanHistories->sortByDesc('approved_at') as $history)
                    <div class="timeline-item mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    @if($history->action === 'approved')
                                    <span class="badge bg-success p-2">
                                        <i class="bx bx-check"></i>
                                    </span>
                                    @elseif($history->action === 'rejected')
                                    <span class="badge bg-danger p-2">
                                        <i class="bx bx-x"></i>
                                    </span>
                                    @else
                                    <span class="badge bg-warning p-2">
                                        <i class="bx bx-revision"></i>
                                    </span>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1">
                                        {{ match($history->action) {
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'revision' => 'Minta Revisi',
                                            default => ucfirst($history->action)
                                        } }}
                                        oleh {{ $history->approver->role->display_name }}
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bx bx-calendar me-1"></i>
                                        {{ $history->approved_at->format('d M Y H:i') }}
                                    </small>
                                    @if($history->comment)
                                    <div class="alert alert-secondary mt-2 mb-0">
                                        <strong>Komentar:</strong> {{ $history->comment }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bx bx-history bx-lg text-muted mb-2"></i>
                    <p class="text-muted mb-0">Belum ada riwayat persetujuan</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Status Progress -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Status Approval Laporan</h5>
            </div>
            <div class="card-body">
                @php
                // Cek approval histories untuk tahap laporan
                $laporanApprovals = $kegiatan->approvalHistories->where('tahap', 'laporan');
                $laporanApprovedCount = $laporanApprovals->where('action', 'approved')->count();
                $laporanRejected = $laporanApprovals->where('action', 'rejected')->count() > 0;
                $laporanRejectedHistory = $laporanRejected ? $laporanApprovals->where('action', 'rejected')->first() : null;
                $laporanCompleted = $laporanApprovedCount >= 3 && !$laporanRejected;
                $laporanRevision = $kegiatan->tahap === 'laporan' && $kegiatan->status === 'revision';
                $laporanSubmitted = $kegiatan->tahap === 'laporan' && $kegiatan->status === 'submitted';
                $laporanDraft = $kegiatan->tahap === 'laporan' && $kegiatan->status === 'draft';
                @endphp

                @if($laporanRejected)
                <div class="alert alert-danger">
                    <i class="bx bx-x-circle me-2"></i>
                    <strong>Laporan Ditolak</strong>
                    @if($laporanRejectedHistory)
                    <p class="mb-0 mt-2">
                        Ditolak oleh <strong>{{ $laporanRejectedHistory->approver->role->display_name }}</strong>
                        pada {{ $laporanRejectedHistory->approved_at->format('d M Y H:i') }}
                    </p>
                    @if($laporanRejectedHistory->comment)
                    <div class="alert alert-light mt-2 mb-0">
                        <small><strong>Alasan:</strong> {{ $laporanRejectedHistory->comment }}</small>
                    </div>
                    @endif
                    @else
                    <p class="mb-0 mt-2">Silakan periksa riwayat persetujuan untuk informasi lebih lanjut.</p>
                    @endif
                </div>
                @elseif($laporanCompleted)
                <div class="alert alert-success">
                    <i class="bx bx-check-circle me-2"></i>
                    <strong>Laporan Disetujui!</strong>
                    <p class="mb-0 mt-2">Semua tahap persetujuan laporan telah selesai. Kegiatan telah selesai.</p>
                </div>
                @elseif($laporanRevision)
                <div class="alert alert-warning">
                    <i class="bx bx-error me-2"></i>
                    <strong>Perlu Revisi</strong>
                    <p class="mb-0 mt-2">Silakan cek komentar dan perbaiki LPJ.</p>
                </div>
                @elseif($laporanDraft)
                <div class="alert alert-secondary">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Draft</strong>
                    <p class="mb-0 mt-2">LPJ belum disubmit.</p>
                </div>
                @elseif($laporanSubmitted)
                <div class="alert alert-info">
                    <i class="bx bx-time me-2"></i>
                    <strong>Menunggu Persetujuan</strong>
                    <p class="mb-0 mt-2">
                        {{ match($kegiatan->current_approver_role) {
                            'pembina_hima' => 'Pembina Hima',
                            'kaprodi' => 'Kepala Program Studi',
                            'wadek_iii' => 'Wakil Dekan III',
                            default => '-'
                        } }}
                    </p>
                </div>
                @endif

                <!-- Progress Bar -->
                <div class="mt-3">
                    <label class="form-label mb-2">Progress Approval Laporan:</label>
                    @php
                    // Hitung progress berdasarkan approval yang sudah ada untuk tahap laporan
                    if ($laporanRejected) {
                        $progress = 0; // Ditolak, progress 0
                        $progressColor = 'danger';
                    } elseif ($laporanApprovedCount >= 3) {
                        $progress = 100; // Semua approval selesai
                        $progressColor = 'success';
                    } elseif ($laporanApprovedCount == 2) {
                        $progress = 66; // Pembina + Kaprodi approved, menunggu Wadek
                        $progressColor = 'success';
                    } elseif ($laporanApprovedCount == 1) {
                        $progress = 33; // Pembina approved, menunggu Kaprodi
                        $progressColor = 'success';
                    } elseif ($kegiatan->tahap === 'laporan' && $kegiatan->status === 'submitted') {
                        $progress = 10; // Sudah submit, menunggu Pembina
                        $progressColor = 'info';
                    } else {
                        $progress = 0; // Draft
                        $progressColor = 'secondary';
                    }
                    @endphp
                    <div class="progress">
                        <div class="progress-bar bg-{{ $progressColor }}" role="progressbar" style="width: {{ $progress }}%"
                             aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $progress }}%
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        Pembina → Kaprodi → Wadek III
                        @if($laporanApprovedCount > 0)
                        ({{ $laporanApprovedCount }}/3 Approved)
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Actions untuk Hima -->
        @if(Auth::user()->isHima() && $kegiatan->user_id === Auth::user()->id)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tindakan</h5>
            </div>
            <div class="card-body">
                <!-- Info jika laporan sudah selesai -->
                @if($laporanCompleted && !$laporanRejected)
                <div class="alert alert-success mb-2">
                    <strong><i class="bx bx-check-circle me-1"></i> Laporan Disetujui!</strong>
                    <p class="mb-2 small">Kegiatan telah selesai. Terima kasih atas partisipasinya.</p>
                </div>
                @elseif($laporanRejected)
                <div class="alert alert-danger mb-2">
                    <strong><i class="bx bx-x-circle me-1"></i> Laporan Ditolak</strong>
                    <p class="mb-2 small">Silakan periksa komentar reviewer untuk informasi lebih lanjut.</p>
                </div>
                @endif

                <!-- Tombol aksi hanya muncul jika laporan masih aktif (belum selesai) -->
                @if(!$laporanCompleted && !$laporanRejected)
                    @if($kegiatan->status === 'draft')
                        @if($laporanFile)
                        <!-- Submit Button -->
                        <form action="{{ route('kegiatan.laporan.submit', $kegiatan) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100"
                                    onclick="return confirm('Yakin ingin submit LPJ untuk persetujuan?')">
                                <i class="bx bx-send me-1"></i> Submit untuk Persetujuan
                            </button>
                        </form>

                        <!-- Edit Button -->
                        <a href="{{ route('kegiatan.laporan.upload', $kegiatan) }}" class="btn btn-primary w-100 mb-2">
                            <i class="bx bx-edit me-1"></i> Edit LPJ
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('kegiatan.laporan.delete', [$kegiatan, $laporanFile]) }}" method="POST" class="mb-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Yakin ingin menghapus LPJ ini?')">
                                <i class="bx bx-trash me-1"></i> Hapus LPJ
                            </button>
                        </form>
                        @else
                        <!-- Upload Button -->
                        <a href="{{ route('kegiatan.laporan.upload', $kegiatan) }}" class="btn btn-primary w-100 mb-2">
                            <i class="bx bx-upload me-1"></i> Upload LPJ
                        </a>
                        @endif

                    @elseif($kegiatan->status === 'revision')
                    <a href="{{ route('kegiatan.laporan.upload', $kegiatan) }}" class="btn btn-warning w-100 mb-2">
                        <i class="bx bx-edit me-1"></i> Upload Revisi LPJ
                    </a>
                    @endif

                    <hr class="my-3">
                @endif

                <a href="{{ route('kegiatan.laporan.index') }}" class="btn btn-label-secondary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
        @endif

        <!-- Actions untuk Approver -->
        @if(
            !Auth::user()->isHima() &&
            $kegiatan->current_approver_role === Auth::user()->role->name &&
            $kegiatan->tahap === 'laporan' &&
            $kegiatan->status === 'submitted'
        )
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tindakan Review</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="bx bx-check me-1"></i> Setujui
                </button>
                <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#revisionModal">
                    <i class="bx bx-revision me-1"></i> Minta Revisi
                </button>
                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bx bx-x me-1"></i> Tolak
                </button>

                <hr class="my-3">

                <a href="{{ route('kegiatan.laporan.index') }}" class="btn btn-label-secondary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
        @endif

        <!-- Tombol Kembali untuk role lain yang hanya view -->
        @if(
            (Auth::user()->isHima() && $kegiatan->user_id !== Auth::user()->id) ||
            (!Auth::user()->isHima() && $kegiatan->current_approver_role !== Auth::user()->role->name)
        )
        <div class="card">
            <div class="card-body">
                <a href="{{ route('kegiatan.laporan.index') }}" class="btn btn-label-secondary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Approval Modals (reuse from main kegiatan) -->
@include('kegiatan._partials.approval-modals')

@endsection

@section('page-style')
<style>
.timeline-item {
    position: relative;
    padding-left: 30px;
}
.timeline-indicator {
    position: absolute;
    left: 0;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
.timeline-indicator-success {
    background-color: #28a745;
}
.timeline-indicator-warning {
    background-color: #ffc107;
}
.timeline-indicator-danger {
    background-color: #dc3545;
}
</style>
@endsection
