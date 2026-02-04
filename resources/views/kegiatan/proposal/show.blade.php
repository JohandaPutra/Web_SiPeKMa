@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Proposal Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            Detail Proposal Kegiatan
        </h4>
        <p class="text-muted mb-0">Informasi lengkap proposal kegiatan</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kegiatan.index') }}" class="text-muted">Usulan Kegiatan</a>
            </li>
            <li class="breadcrumb-item active">Detail Proposal</li>
        </ol>
    </nav>
</div>

@include('_partials/toast')

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Informasi Kegiatan -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h5 class="card-title mb-0">Informasi Kegiatan</h5>
                    <div class="d-flex gap-1 mt-2 mt-sm-0 ps-0 ps-sm-0">
                        <span class="badge bg-{{ $kegiatan->tahapBadge }}">{{ ucfirst($kegiatan->tahap) }}</span>
                        <span class="badge bg-{{ $kegiatan->statusBadge }}">{{ ucfirst($kegiatan->status) }}</span>
                    </div>
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
                        <span class="badge bg-label-info">{{ $kegiatan->jenisKegiatan->nama ?? '-' }}</span>
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
                        <span class="badge bg-label-primary">{{ $kegiatan->jenisPendanaan->nama ?? '-' }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Prodi:</strong></div>
                    <div class="col-12 col-sm-8">
                        <span class="badge bg-label-secondary">{{ $kegiatan->prodi->nama_prodi }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Proposal -->
        @if($proposalFile)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-file-blank"></i> Dokumen Proposal
                </h5>
            </div>
            <div class="card-body">
                {{-- OPSI 2: Icon & info sejajar, button di bawah (MOBILE) --}}
                <div class="d-md-none">
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-danger p-3">
                                <i class="bx bx-file-blank icon-file-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-break">{{ $proposalFile->file_name }}</h6>
                            <div class="text-muted small">
                                <div class="mb-1">
                                    <i class="bx bx-calendar me-1"></i>
                                    {{ $proposalFile->uploaded_at->format('d M Y H:i') }}
                                </div>
                                <div class="mb-1">
                                    <i class="bx bx-data me-1"></i>
                                    {{ $proposalFile->fileSizeFormatted }}
                                </div>
                                <div>
                                    <i class="bx bx-user me-1"></i>
                                    {{ $proposalFile->uploader->username }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('kegiatan.proposal.download', $proposalFile->id) }}"
                           class="btn btn-primary w-100">
                            <i class="bx bx-download me-1"></i> Download File Proposal
                        </a>
                    </div>
                </div>

                {{-- DESKTOP LAYOUT (unchanged) --}}
                <div class="d-none d-md-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <span class="badge bg-danger p-3">
                            <i class="bx bx-file-blank icon-file-lg"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1 text-break">{{ $proposalFile->file_name }}</h6>
                        <div class="text-muted small">
                            <span class="me-3 d-inline-block">
                                <i class="bx bx-calendar me-1"></i>
                                {{ $proposalFile->uploaded_at->format('d M Y H:i') }}
                            </span>
                            <span class="me-3 d-inline-block">
                                <i class="bx bx-data me-1"></i>
                                {{ $proposalFile->fileSizeFormatted }}
                            </span>
                            <span class="d-inline-block">
                                <i class="bx bx-user me-1"></i>
                                {{ $proposalFile->uploader->username }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ms-2">
                        <a href="{{ route('kegiatan.proposal.download', $proposalFile->id) }}"
                           class="btn btn-primary btn-sm">
                            <i class="bx bx-download me-1"></i> Download
                        </a>
                    </div>
                </div>

                <!-- PDF Viewer -->
                <div class="border rounded p-2 bg-light d-none d-md-block">
                    <iframe
                        src="{{ route('kegiatan.proposal.preview', $proposalFile->id) }}"
                        width="100%"
                        height="600px"
                        class="border-0">
                    </iframe>
                </div>
                <div class="alert alert-info d-md-none mb-0">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Preview tidak tersedia di mobile.</strong> Silakan gunakan tombol Download untuk melihat file.
                </div>
            </div>
        </div>
        @else
        <div class="card mb-4">
            <div class="card-body text-center py-5">
                <i class="bx bx-file-blank bx-lg text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada File Proposal</h5>
                <p class="text-muted">Silakan upload dokumen proposal Anda.</p>
            </div>
        </div>
        @endif

        <!-- History Approval -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-history"></i> Riwayat Persetujuan Proposal
                </h5>
            </div>
            <div class="card-body">
                @php
                    $proposalHistories = $kegiatan->approvalHistories->where('tahap', 'proposal');
                @endphp

                @if($proposalHistories->count() > 0)
                <div class="timeline">
                    @foreach($proposalHistories->sortByDesc('approved_at') as $history)
                    <div class="timeline-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="timeline-indicator timeline-indicator-{{ $history->actionBadge }}">
                                <i class='bx bx-{{ $history->action == "disetujui" ? "check" : ($history->action == "revisi" ? "revisi" : "x") }}'></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0">{{ $history->approver->role->display_name }}</h6>
                                    <small class="text-muted">{{ $history->approved_at->format('d M Y H:i') }}</small>
                                </div>
                                <span class="badge bg-{{ $history->actionBadge }} mb-2">
                                    {{ ucfirst($history->action) }}
                                </span>
                                @if($history->comment)
                                <div class="alert alert-secondary mb-0 mt-2">
                                    <small><strong>Komentar:</strong> {{ $history->comment }}</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="bx bx-info-circle bx-lg mb-2"></i>
                    <p>Belum ada riwayat persetujuan proposal</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <!-- Status Progress -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Status Approval Proposal</h5>
            </div>
            <div class="card-body">
                @php
                // Cek approval histories untuk tahap proposal
                $proposalApprovals = $kegiatan->approvalHistories->where('tahap', 'proposal');
                $proposalDisetujuiCount = $proposalApprovals->where('action', 'disetujui')->count();
                $proposalDitolak = $proposalApprovals->where('action', 'ditolak')->count() > 0;
                $proposalDitolakHistory = $proposalDitolak ? $proposalApprovals->where('action', 'ditolak')->first() : null;
                $proposalCompleted = $proposalDisetujuiCount >= 3 || ($kegiatan->tahap !== 'proposal' && !$proposalDitolak);
                $proposalRevisi = $kegiatan->tahap === 'proposal' && $kegiatan->status === 'revisi';
                $proposalDikirim = $kegiatan->tahap === 'proposal' && $kegiatan->status === 'dikirim';
                $proposalDraft = $kegiatan->tahap === 'proposal' && $kegiatan->status === 'draft';
                @endphp

                @if($proposalDitolak)
                <div class="alert alert-danger">
                    <i class="bx bx-x-circle me-2"></i>
                    <strong>Proposal Ditolak</strong>
                    @if($proposalDitolakHistory)
                    <p class="mb-0 mt-2">
                        Ditolak oleh <strong>{{ $proposalDitolakHistory->approver->role->display_name }}</strong>
                        pada {{ $proposalDitolakHistory->approved_at->format('d M Y H:i') }}
                    </p>
                    @if($proposalDitolakHistory->comment)
                    <div class="alert alert-light mt-2 mb-0">
                        <small><strong>Alasan:</strong> {{ $proposalDitolakHistory->comment }}</small>
                    </div>
                    @endif
                    @else
                    <p class="mb-0 mt-2">Silakan periksa riwayat persetujuan untuk informasi lebih lanjut.</p>
                    @endif
                </div>
                @elseif($proposalCompleted)
                <div class="alert alert-success">
                    <i class="bx bx-check-circle me-2"></i>
                    <strong>Proposal Disetujui!</strong>
                    @if($kegiatan->tahap !== 'proposal')
                    <p class="mb-0 mt-2">Semua tahap persetujuan proposal telah selesai. Kegiatan telah masuk tahap {{ ucfirst($kegiatan->tahap) }}.</p>
                    @else
                    <p class="mb-0 mt-2">Semua tahap persetujuan proposal telah selesai.</p>
                    @endif
                </div>
                @elseif($proposalRevisi)
                <div class="alert alert-warning">
                    <i class="bx bx-error me-2"></i>
                    <strong>Perlu Revisi</strong>
                    <p class="mb-0 mt-2">Silakan cek komentar dan perbaiki proposal.</p>
                </div>
                @elseif($proposalDraft)
                <div class="alert alert-secondary">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Draft</strong>
                    <p class="mb-0 mt-2">Proposal belum disubmit.</p>
                </div>
                @elseif($proposalDikirim)
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
                    <label class="form-label mb-2">Progress Approval Proposal:</label>
                    @php
                    // Hitung progress berdasarkan approval yang sudah ada untuk tahap proposal
                    if ($proposalDitolak) {
                        $progress = 0; // Ditolak, progress 0
                        $progressColor = 'danger';
                    } elseif ($proposalDisetujuiCount >= 3 || ($kegiatan->tahap !== 'proposal' && !$proposalDitolak)) {
                        $progress = 100; // Semua approval selesai atau sudah pindah tahap
                        $progressColor = 'success';
                    } elseif ($proposalDisetujuiCount == 2) {
                        $progress = 66; // Pembina + Kaprodi approved, menunggu Wadek
                        $progressColor = 'info';
                    } elseif ($proposalDisetujuiCount == 1) {
                        $progress = 33; // Pembina approved, menunggu Kaprodi
                        $progressColor = 'info';
                    } elseif ($kegiatan->tahap === 'proposal' && $kegiatan->status === 'dikirim') {
                        $progress = 10; // Sudah submit, menunggu Pembina
                        $progressColor = 'info';
                    } else {
                        $progress = 0; // Draft
                        $progressColor = 'secondary';
                    }
                    @endphp
                    <div class="progress">
                        <div class="progress-bar bg-{{ $progressColor }}" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress }}%">
                            {{ $progress }}%
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        Pembina → Kaprodi → Wadek III
                        @if($proposalDisetujuiCount > 0)
                        ({{ $proposalDisetujuiCount }}/3 Approved)
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Actions untuk Hima -->
        @if(Auth::user()->isHima() && $kegiatan->user_id === Auth::user()->id)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <!-- Info jika proposal sudah selesai (pindah ke tahap lain atau sudah disetujui) -->
                @if($proposalCompleted && !$proposalDitolak)
                <div class="alert alert-success mb-2">
                    <strong><i class="bx bx-check-circle me-1"></i> Proposal Disetujui!</strong>
                    @if($kegiatan->tahap === 'pendanaan')
                    <p class="mb-2 small">Kegiatan telah masuk tahap pendanaan. Silakan lihat detail pendanaan.</p>
                    <a href="{{ route('kegiatan.pendanaan.show', $kegiatan) }}" class="btn btn-sm btn-success">
                        Lihat Detail Pendanaan <i class="bx bx-right-arrow-alt ms-1"></i>
                    </a>
                    @elseif($kegiatan->tahap === 'laporan')
                    <p class="mb-2 small">Kegiatan telah masuk tahap laporan.</p>
                    <a href="{{ route('kegiatan.laporan.show', $kegiatan) }}" class="btn btn-sm btn-success">
                        Lihat Detail Laporan <i class="bx bx-right-arrow-alt ms-1"></i>
                    </a>
                    @else
                    <p class="mb-2 small">Semua tahap persetujuan proposal telah selesai.</p>
                    @endif
                </div>
                @elseif($proposalDitolak)
                <div class="alert alert-danger mb-2">
                    <strong><i class="bx bx-x-circle me-1"></i> Proposal Ditolak</strong>
                    <p class="mb-2 small">Silakan periksa komentar reviewer untuk informasi lebih lanjut.</p>
                </div>
                @endif

                <!-- Tombol aksi hanya muncul jika proposal masih aktif (belum selesai) -->
                @if(!$proposalCompleted && !$proposalDitolak)
                    @if($kegiatan->tahap === 'proposal' && !$proposalFile && $kegiatan->status === 'draft')
                    <a href="{{ route('kegiatan.proposal.upload', $kegiatan) }}" class="btn btn-primary w-100 mb-2">
                        <i class="bx bx-upload me-1"></i> Upload Proposal
                    </a>
                    @endif

                    @if($proposalFile && in_array($kegiatan->status, ['draft', 'revisi']))
                    <!-- Submit Button -->
                    <form action="{{ route('kegiatan.proposal.submit', $kegiatan) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Yakin ingin submit proposal ini?')">
                            <i class="bx bx-send me-1"></i> Submit Proposal
                        </button>
                    </form>

                    <!-- Edit Button -->
                    <a href="{{ route('kegiatan.proposal.upload', $kegiatan) }}" class="btn btn-{{ $kegiatan->status === 'revisi' ? 'warning' : 'primary' }} w-100 mb-2">
                        <i class="bx bx-edit me-1"></i> {{ $kegiatan->status === 'revisi' ? 'Edit & Upload Ulang Proposal' : 'Edit Proposal' }}
                    </a>

                    <!-- Hapus Button (hanya untuk draft) -->
                    @if($kegiatan->status === 'draft')
                    <form action="{{ route('kegiatan.proposal.delete', [$kegiatan, $proposalFile]) }}" method="POST" class="mb-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Yakin ingin menghapus proposal ini?')">
                            <i class="bx bx-trash me-1"></i> Hapus Proposal
                        </button>
                    </form>
                    @endif
                    @endif

                    <hr class="my-3">
                @endif

                <a href="{{ route('kegiatan.proposal.index') }}" class="btn btn-label-secondary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
        @endif

        <!-- Actions untuk Approver (Pembina, Kaprodi, Wadek, Super Admin, Admin) -->
        @php
            $isSuperAdmin = Auth::user()->isSuperAdmin();
            $isRegularAdmin = Auth::user()->isRegularAdmin();
            $isSuperAdminOrAdmin = $isSuperAdmin || $isRegularAdmin;
            
            $canApprove = (
                (!Auth::user()->isHima() && $kegiatan->current_approver_role === Auth::user()->role->name) ||
                $isSuperAdminOrAdmin
            ) && $kegiatan->tahap === 'proposal' && $kegiatan->status === 'dikirim';
        @endphp
        
        @if($canApprove)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tindakan Review</h5>
                @if($isSuperAdmin)
                    <small class="text-muted">Anda bertindak sebagai <strong>Wadek III</strong> (Approval Langsung)</small>
                @elseif($isRegularAdmin)
                    @php
                        $currentApproverDisplay = match($kegiatan->current_approver_role) {
                            'pembina_hima' => 'Pembina Hima',
                            'kaprodi' => 'Kaprodi',
                            'wadek_iii' => 'Wadek III',
                            default => 'Approver',
                        };
                    @endphp
                    <small class="text-muted">Anda bertindak sebagai <strong>{{ $currentApproverDisplay }}</strong></small>
                @endif
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="bx bx-check me-1"></i> Setujui
                </button>
                <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#RevisiModal">
                    <i class="bx bx-Revisi me-1"></i> Minta Revisi
                </button>
                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bx bx-x me-1"></i> Tolak
                </button>

                <hr class="my-3">

                <a href="{{ route('kegiatan.proposal.index') }}" class="btn btn-label-secondary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
        @endif

        <!-- Tombol Kembali untuk role lain yang hanya view -->
        @if(
            (Auth::user()->isHima() && $kegiatan->user_id !== Auth::user()->id) ||
            (!Auth::user()->isHima() && !$isSuperAdminOrAdmin && $kegiatan->current_approver_role !== Auth::user()->role->name)
        )
        <div class="card">
            <div class="card-body">
                <a href="{{ route('kegiatan.proposal.index') }}" class="btn btn-label-secondary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('kegiatan.approve', $kegiatan) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Proposal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menyetujui proposal kegiatan ini?</p>
                    <div class="mb-3">
                        <label class="form-label">Komentar (Opsional)</label>
                        <textarea class="form-control" name="comment" rows="3"
                                  placeholder="Tambahkan komentar jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Setujui</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Revisi Modal -->
<div class="modal fade" id="RevisiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('kegiatan.revisi', $kegiatan) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Minta Revisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Komentar Revisi <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="comment" rows="4" required
                                  placeholder="Jelaskan bagian yang perlu direvisi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('kegiatan.tolak', $kegiatan) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Tolak Proposal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>Perhatian!</strong> Tindakan ini akan menolak proposal kegiatan secara permanen.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="comment" rows="4" required
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Tolak Proposal</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
