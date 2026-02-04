@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Usulan Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            <i class="bx bx-file"></i> Detail Usulan Kegiatan
        </h4>
        <p class="text-muted mb-0">Informasi lengkap usulan kegiatan</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kegiatan.index') }}" class="text-muted">Usulan Kegiatan</a>
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
                        <span class="badge bg-label-warning">{{ $kegiatan->jenisPendanaan->nama ?? '-' }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Diajukan Oleh:</strong></div>
                    <div class="col-12 col-sm-8">{{ $kegiatan->user->name }}</div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Dibuat:</strong></div>
                    <div class="col-12 col-sm-8">{{ $kegiatan->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- History Approval -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-history"></i> Riwayat Persetujuan Usulan
                </h5>
            </div>
            <div class="card-body">
                @php
                    $usulanHistories = $kegiatan->approvalHistories->where('tahap', 'usulan');
                @endphp

                @if($usulanHistories->count() > 0)
                <div class="timeline">
                    @foreach($usulanHistories->sortByDesc('approved_at') as $history)
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
                    <p>Belum ada riwayat persetujuan usulan</p>
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
                <h5 class="card-title mb-0">Status Approval</h5>
            </div>
            <div class="card-body">
                @php
                // Cek apakah usulan sudah disetujui semua level (pindah ke tahap selanjutnya)
                $usulanApprovals = $kegiatan->approvalHistories->where('tahap', 'usulan');
                $usulanDisetujuiCount = $usulanApprovals->where('action', 'disetujui')->count();
                $usulanCompleted = $usulanDisetujuiCount >= 3 || $kegiatan->tahap !== 'usulan';
                $usulanDitolak = $usulanApprovals->where('action', 'ditolak')->count() > 0;
                $usulanDitolakHistory = $usulanDitolak ? $usulanApprovals->where('action', 'ditolak')->first() : null;
                $usulanRevisi = $usulanApprovals->where('action', 'revisi')->count() > 0 &&
                                  $kegiatan->tahap === 'usulan' &&
                                  $kegiatan->status === 'revisi';
                @endphp

                @if($usulanCompleted && $kegiatan->tahap !== 'usulan')
                <div class="alert alert-success">
                    <i class="bx bx-check-circle me-2"></i>
                    <strong>Usulan Disetujui!</strong>
                    <p class="mb-0 mt-2">Semua tahap persetujuan usulan telah selesai. Kegiatan telah masuk tahap {{ ucfirst($kegiatan->tahap) }}.</p>
                </div>
                @elseif($kegiatan->current_approver_role === 'completed' && $kegiatan->tahap === 'usulan')
                <div class="alert alert-success">
                    <i class="bx bx-check-circle me-2"></i>
                    <strong>Usulan Disetujui!</strong>
                    <p class="mb-0 mt-2">Semua tahap persetujuan usulan telah selesai.</p>
                </div>
                @elseif($usulanDitolak)
                <div class="alert alert-danger">
                    <i class="bx bx-x-circle me-2"></i>
                    <strong>Usulan Ditolak</strong>
                    @if($usulanDitolakHistory)
                    <p class="mb-0 mt-2">
                        Ditolak oleh <strong>{{ $usulanDitolakHistory->approver->role->display_name }}</strong>
                        pada {{ $usulanDitolakHistory->approved_at->format('d M Y H:i') }}
                    </p>
                    @if($usulanDitolakHistory->comment)
                    <div class="alert alert-light mt-2 mb-0">
                        <small><strong>Alasan:</strong> {{ $usulanDitolakHistory->comment }}</small>
                    </div>
                    @endif
                    @endif
                </div>
                @elseif($usulanRevisi)
                <div class="alert alert-warning">
                    <i class="bx bx-error me-2"></i>
                    <strong>Perlu Revisi</strong>
                    <p class="mb-0 mt-2">Silakan cek komentar dan perbaiki usulan.</p>
                </div>
                @elseif($kegiatan->status === 'draft' && $kegiatan->tahap === 'usulan')
                <div class="alert alert-secondary">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Draft</strong>
                    <p class="mb-0 mt-2">Usulan belum disubmit.</p>
                </div>
                @elseif($kegiatan->status === 'dikirim' && $kegiatan->tahap === 'usulan')
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
                @else
                <div class="alert alert-info">
                    <i class="bx bx-time me-2"></i>
                    <strong>Sedang Diproses</strong>
                    <p class="mb-0 mt-2">Usulan sedang dalam proses persetujuan.</p>
                </div>
                @endif

                <!-- Progress Bar -->
                <div class="mt-3">
                    <label class="form-label mb-2">Progress Approval Usulan:</label>
                    @php
                    // Hitung progress berdasarkan approval yang sudah ada untuk tahap usulan
                    $usulanApprovals = $kegiatan->approvalHistories->where('tahap', 'usulan')->where('action', 'disetujui');
                    $disetujuiCount = $usulanApprovals->count();

                    if ($disetujuiCount >= 3 || $kegiatan->tahap !== 'usulan') {
                        $progress = 100; // Semua approval selesai atau sudah pindah tahap
                    } elseif ($disetujuiCount == 2) {
                        $progress = 66; // Pembina + Kaprodi approved, menunggu Wadek
                    } elseif ($disetujuiCount == 1) {
                        $progress = 33; // Pembina approved, menunggu Kaprodi
                    } elseif ($kegiatan->status === 'dikirim') {
                        $progress = 10; // Sudah submit, menunggu Pembina
                    } else {
                        $progress = 0; // Draft
                    }
                    @endphp
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress }}%">
                            {{ $progress }}%
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        Pembina → Kaprodi → Wadek III
                        @if($disetujuiCount > 0)
                        ({{ $disetujuiCount }}/3 Disetujui)
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
                @php
                // Hitung approval count untuk cek status usulan
                $usulanDisetujuiHistories = $kegiatan->approvalHistories->where('tahap', 'usulan')->where('action', 'disetujui');
                $disetujuiCount = $usulanDisetujuiHistories->count();
                // Cek apakah usulan sudah selesai (ada approval atau sudah pindah tahap)
                $usulanSelesai = $disetujuiCount >= 3 || $kegiatan->tahap !== 'usulan';
                @endphp

                <!-- Info dan tombol jika usulan sudah pindah ke tahap proposal -->
                @if($kegiatan->tahap === 'proposal')
                <div class="alert alert-success mb-3">
                    <strong><i class="bx bx-check-circle me-1"></i> Usulan Disetujui!</strong>
                    <p class="mb-2 small">Kegiatan telah masuk tahap proposal. Silakan lihat detail proposal.</p>
                    <a href="{{ route('kegiatan.proposal.show', $kegiatan) }}" class="btn btn-sm btn-success">
                        Lihat Detail Proposal <i class="bx bx-right-arrow-alt ms-1"></i>
                    </a>
                </div>
                @endif

                <!-- Tombol untuk status draft atau Revisi -->
                @if($kegiatan->tahap === 'usulan' && in_array($kegiatan->status, ['draft', 'revisi']))
                <!-- Submit Button -->
                <form action="{{ route('kegiatan.submit', $kegiatan) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success w-100"
                            onclick="return confirm('Yakin ingin submit usulan ini?')">
                        <i class="bx bx-send me-1"></i> Submit Usulan
                    </button>
                </form>

                <!-- Edit Button -->
                <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="btn btn-primary w-100 mb-2">
                    <i class="bx bx-edit me-1"></i> Edit Usulan
                </a>

                <!-- Hapus Button (hanya untuk draft yang belum selesai) -->
                @if($kegiatan->status === 'draft' && !$usulanSelesai)
                <form action="{{ route('kegiatan.destroy', $kegiatan) }}" method="POST" class="mb-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirm('Yakin ingin menghapus usulan ini?')">
                        <i class="bx bx-trash me-1"></i> Hapus Usulan
                    </button>
                </form>
                @endif
                @endif

                <a href="{{ route('kegiatan.index') }}" class="btn btn-label-secondary w-100">
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
            ) && $kegiatan->status === 'dikirim';
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
                <!-- Approve Button -->
                <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="bx bx-check me-1"></i> Setujui
                </button>

                <!-- Revisi Button -->
                <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#RevisiModal">
                    <i class="bx bx-Revisi me-1"></i> Minta Revisi
                </button>

                <!-- Reject Button -->
                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bx bx-x me-1"></i> Tolak
                </button>

                <hr class="my-3">

                <a href="{{ route('kegiatan.index') }}" class="btn btn-label-secondary w-100">
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
                <a href="{{ route('kegiatan.index') }}" class="btn btn-label-secondary w-100">
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
                    <h5 class="modal-title">Setujui Usulan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menyetujui usulan kegiatan ini?</p>
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
                    <h5 class="modal-title text-white">Tolak Usulan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>Perhatian!</strong> Tindakan ini akan menolak usulan kegiatan secara permanen.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="comment" rows="4" required
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Tolak Usulan</button>
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
