@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Riwayat Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            Detail Riwayat Kegiatan
        </h4>
        <p class="text-muted mb-0">Informasi lengkap dari usulan hingga laporan</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kegiatan.riwayat') }}" class="text-muted">Riwayat</a>
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
                        <span class="d-block d-sm-inline">{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}</span>
                        <span class="d-block d-sm-inline">s/d {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir)->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Jenis Pendanaan:</strong></div>
                    <div class="col-12 col-sm-8">
                        <span class="badge bg-label-warning">{{ $kegiatan->jenisPendanaan->nama ?? '-' }}</span>
                    </div>
                </div>
                @if($kegiatan->total_anggaran)
                <div class="row mb-3">
                    <div class="col-12 col-sm-4 mb-1 mb-sm-0"><strong>Total Anggaran:</strong></div>
                    <div class="col-12 col-sm-8">
                        <strong class="text-success">Rp {{ number_format($kegiatan->total_anggaran, 0, ',', '.') }}</strong>
                    </div>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Program Studi:</strong></div>
                    <div class="col-sm-8">
                        <span class="badge bg-label-primary">{{ $kegiatan->prodi->nama_prodi }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tahapan Kegiatan -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tahapan Kegiatan</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Usulan -->
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center h-100">
                            <i class="bx bx-bulb bx-lg text-primary mb-2"></i>
                            <h6 class="mb-2">Usulan</h6>
                            @php
                            $usulanApprovals = isset($approvalsByTahap['usulan']) ? $approvalsByTahap['usulan'] : collect();
                            $usulanDisetujuiCount = $usulanApprovals->where('action', 'disetujui')->count();
                            $usulanDitolak = $usulanApprovals->where('action', 'ditolak')->count() > 0;

                            if ($usulanDitolak) {
                                $usulanStatus = 'Ditolak';
                                $usulanBadge = 'danger';
                            } elseif ($usulanDisetujuiCount >= 3 || $kegiatan->tahap !== 'usulan') {
                                $usulanStatus = 'Disetujui';
                                $usulanBadge = 'success';
                            } elseif ($kegiatan->tahap === 'usulan' && $kegiatan->status === 'dikirim') {
                                $usulanStatus = 'Progress';
                                $usulanBadge = 'info';
                            } elseif ($kegiatan->tahap === 'usulan' && $kegiatan->status === 'revisi') {
                                $usulanStatus = 'Revisi';
                                $usulanBadge = 'warning';
                            } else {
                                $usulanStatus = 'Draft';
                                $usulanBadge = 'secondary';
                            }
                            @endphp
                            <span class="badge bg-{{ $usulanBadge }} mb-2">{{ $usulanStatus }}</span><br>
                            <a href="{{ route('kegiatan.show', $kegiatan) }}"
                               class="btn btn-sm btn-{{ $usulanBadge }}">
                                <i class="bx bx-show me-1"></i> Lihat Detail
                            </a>
                        </div>
                    </div>

                    <!-- Proposal -->
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center h-100">
                            <i class="bx bx-file bx-lg text-success mb-2"></i>
                            <h6 class="mb-2">Proposal</h6>
                            @php
                            $proposalApprovals = isset($approvalsByTahap['proposal']) ? $approvalsByTahap['proposal'] : collect();
                            $proposalDisetujuiCount = $proposalApprovals->where('action', 'disetujui')->count();
                            $proposalDitolak = $proposalApprovals->where('action', 'ditolak')->count() > 0;

                            // Cek ditolak dulu sebagai prioritas tertinggi
                            if ($proposalDitolak) {
                                $proposalStatus = 'Ditolak';
                                $proposalBadge = 'danger';
                                $proposalDisabled = false;
                            } elseif ($proposalDisetujuiCount >= 3 || ($kegiatan->tahap !== 'proposal' && $kegiatan->tahap !== 'usulan')) {
                                // Tahap proposal sudah selesai/disetujui (pindah ke pendanaan/laporan)
                                $proposalStatus = 'Disetujui';
                                $proposalBadge = 'success';
                                $proposalDisabled = false;
                            } elseif (!$proposalFile && in_array($kegiatan->tahap, ['usulan', 'proposal'])) {
                                // Belum upload dan masih di tahap usulan/proposal
                                $proposalStatus = 'Belum Upload';
                                $proposalBadge = 'secondary';
                                $proposalDisabled = true;
                            } elseif ($kegiatan->tahap === 'proposal' && $kegiatan->status === 'dikirim') {
                                $proposalStatus = 'Progress';
                                $proposalBadge = 'info';
                                $proposalDisabled = false;
                            } elseif ($kegiatan->tahap === 'proposal' && $kegiatan->status === 'revisi') {
                                $proposalStatus = 'Revisi';
                                $proposalBadge = 'warning';
                                $proposalDisabled = false;
                            } else {
                                $proposalStatus = 'Draft';
                                $proposalBadge = 'secondary';
                                $proposalDisabled = false;
                            }
                            @endphp
                            <span class="badge bg-{{ $proposalBadge }} mb-2">{{ $proposalStatus }}</span><br>
                            @if($proposalDisabled)
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="bx bx-show me-1"></i> Lihat Detail
                            </button>
                            @else
                            <a href="{{ route('kegiatan.proposal.show', $kegiatan) }}"
                               class="btn btn-sm btn-{{ $proposalBadge }}">
                                <i class="bx bx-show me-1"></i> Lihat Detail
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Pendanaan (RAB) -->
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center h-100">
                            <i class="bx bx-money bx-lg text-warning mb-2"></i>
                            <h6 class="mb-2">Pendanaan</h6>
                            @php
                            $pendanaanApprovals = isset($approvalsByTahap['pendanaan']) ? $approvalsByTahap['pendanaan'] : collect();
                            $pendanaanDisetujuiCount = $pendanaanApprovals->where('action', 'disetujui')->count();
                            $pendanaanDitolak = $pendanaanApprovals->where('action', 'ditolak')->count() > 0;

                            // Cek ditolak dulu sebagai prioritas tertinggi
                            if ($pendanaanDitolak) {
                                $pendanaanStatus = 'Ditolak';
                                $pendanaanBadge = 'danger';
                                $pendanaanDisabled = false;
                            } elseif ($pendanaanDisetujuiCount >= 3 || $kegiatan->tahap === 'laporan') {
                                // Tahap pendanaan sudah selesai/disetujui (pindah ke laporan)
                                $pendanaanStatus = 'Disetujui';
                                $pendanaanBadge = 'success';
                                $pendanaanDisabled = false;
                            } elseif (!$rabFile && in_array($kegiatan->tahap, ['usulan', 'proposal', 'pendanaan'])) {
                                // Belum upload dan masih di tahap sebelum atau saat pendanaan
                                $pendanaanStatus = 'Belum Upload';
                                $pendanaanBadge = 'secondary';
                                $pendanaanDisabled = true;
                            } elseif ($kegiatan->tahap === 'pendanaan' && $kegiatan->status === 'dikirim') {
                                $pendanaanStatus = 'Progress';
                                $pendanaanBadge = 'info';
                                $pendanaanDisabled = false;
                            } elseif ($kegiatan->tahap === 'pendanaan' && $kegiatan->status === 'revisi') {
                                $pendanaanStatus = 'Revisi';
                                $pendanaanBadge = 'warning';
                                $pendanaanDisabled = false;
                            } else {
                                $pendanaanStatus = 'Draft';
                                $pendanaanBadge = 'secondary';
                                $pendanaanDisabled = false;
                            }
                            @endphp
                            <span class="badge bg-{{ $pendanaanBadge }} mb-2">{{ $pendanaanStatus }}</span><br>
                            @if($pendanaanDisabled)
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="bx bx-show me-1"></i> Lihat Detail
                            </button>
                            @else
                            <a href="{{ route('kegiatan.pendanaan.show', $kegiatan) }}"
                               class="btn btn-sm btn-{{ $pendanaanBadge }}">
                                <i class="bx bx-show me-1"></i> Lihat Detail
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Laporan (LPJ) -->
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center h-100">
                            <i class="bx bx-file-blank bx-lg text-info mb-2"></i>
                            <h6 class="mb-2">Laporan</h6>
                            @php
                            $laporanApprovals = isset($approvalsByTahap['laporan']) ? $approvalsByTahap['laporan'] : collect();
                            $laporanDisetujuiCount = $laporanApprovals->where('action', 'disetujui')->count();
                            $laporanDitolak = $laporanApprovals->where('action', 'ditolak')->count() > 0;

                            // Cek ditolak dulu sebagai prioritas tertinggi
                            if ($laporanDitolak) {
                                $laporanStatus = 'Ditolak';
                                $laporanBadge = 'danger';
                                $laporanDisabled = false;
                            } elseif ($laporanDisetujuiCount >= 3) {
                                // Laporan sudah disetujui lengkap (selesai)
                                $laporanStatus = 'Disetujui';
                                $laporanBadge = 'success';
                                $laporanDisabled = false;
                            } elseif (!$laporanFile && in_array($kegiatan->tahap, ['usulan', 'proposal', 'pendanaan'])) {
                                // Belum sampai tahap laporan, atau belum upload file
                                $laporanStatus = 'Belum Upload';
                                $laporanBadge = 'secondary';
                                $laporanDisabled = true;
                            } elseif (!$laporanFile && $kegiatan->tahap === 'laporan') {
                                // Belum upload dan masih di tahap laporan
                                $laporanStatus = 'Belum Upload';
                                $laporanBadge = 'secondary';
                                $laporanDisabled = true;
                            } elseif ($kegiatan->tahap === 'laporan' && $kegiatan->status === 'dikirim') {
                                $laporanStatus = 'Progress';
                                $laporanBadge = 'info';
                                $laporanDisabled = false;
                            } elseif ($kegiatan->tahap === 'laporan' && $kegiatan->status === 'revisi') {
                                $laporanStatus = 'Revisi';
                                $laporanBadge = 'warning';
                                $laporanDisabled = false;
                            } else {
                                $laporanStatus = 'Draft';
                                $laporanBadge = 'secondary';
                                $laporanDisabled = true;
                            }
                            @endphp
                            <span class="badge bg-{{ $laporanBadge }} mb-2">{{ $laporanStatus }}</span><br>
                            @if($laporanDisabled)
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="bx bx-show me-1"></i> Lihat Detail
                            </button>
                            @else
                            <a href="{{ route('kegiatan.laporan.show', $kegiatan) }}"
                               class="btn btn-sm btn-{{ $laporanBadge }}">
                                <i class="bx bx-show me-1"></i> Lihat Detail
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="bx bx-info-circle me-1"></i>
                    <small>Klik <strong>"Lihat Detail"</strong> untuk melihat informasi lengkap file, riwayat persetujuan, dan tahapan setiap kegiatan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Progress Kegiatan -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Progress Kegiatan</h5>
            </div>
            <div class="card-body">
                @php
                $stages = [
                    'usulan' => ['label' => 'Usulan', 'icon' => 'bx-bulb', 'color' => 'primary'],
                    'proposal' => ['label' => 'Proposal', 'icon' => 'bx-file', 'color' => 'success'],
                    'pendanaan' => ['label' => 'Pendanaan', 'icon' => 'bx-money', 'color' => 'warning'],
                    'laporan' => ['label' => 'Laporan', 'icon' => 'bx-file-blank', 'color' => 'info'],
                ];
                $currentStageIndex = array_search($kegiatan->tahap, array_keys($stages));
                @endphp

                <div class="timeline">
                    @foreach($stages as $key => $stage)
                    @php
                    $stageIndex = array_search($key, array_keys($stages));
                    $isPassed = $stageIndex < $currentStageIndex;
                    $isCurrent = $key === $kegiatan->tahap;
                    $isCompleted = $kegiatan->current_approver_role === 'completed' && $key === 'laporan';
                    @endphp

                    <div class="timeline-item {{ $isPassed || $isCurrent || $isCompleted ? 'timeline-item-active' : '' }} mb-3">
                        <div class="d-flex align-items-center">
                            <div class="timeline-indicator
                                {{ $isPassed || $isCompleted ? 'bg-success' : ($isCurrent ? 'bg-' . $stage['color'] : 'bg-secondary') }}
                                text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="bx {{ $stage['icon'] }}"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">{{ $stage['label'] }}</h6>
                                <div>
                                    @if($isCompleted && $key === 'laporan')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($isPassed)
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($isCurrent)
                                        @if($kegiatan->status === 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif($kegiatan->status === 'dikirim')
                                            <span class="badge bg-info">Dalam Review</span>
                                        @elseif($kegiatan->status === 'revisi')
                                            <span class="badge bg-warning">Perlu Revisi</span>
                                        @elseif($kegiatan->status === 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-primary">Aktif</span>
                                        @endif
                                    @else
                                        <small class="text-muted">Menunggu</small>
                                    @endif
                                </div>
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @php
                $overallProgress = 0;
                if ($kegiatan->current_approver_role === 'completed') {
                    $overallProgress = 100;
                } elseif ($kegiatan->tahap === 'laporan') {
                    $overallProgress = 85;
                } elseif ($kegiatan->tahap === 'pendanaan') {
                    $overallProgress = 65;
                } elseif ($kegiatan->tahap === 'proposal') {
                    $overallProgress = 40;
                } elseif ($kegiatan->tahap === 'usulan') {
                    $overallProgress = 15;
                }
                @endphp

                <div class="mt-4">
                    <label class="form-label mb-2">Progress Keseluruhan:</label>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $overallProgress }}%"
                             aria-valuenow="{{ $overallProgress }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">{{ $overallProgress }}% Selesai</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="card">
            <div class="card-body">
                <a href="{{ route('kegiatan.riwayat') }}" class="btn btn-label-secondary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar Riwayat
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-style')
<style>
.timeline-item-active .timeline-indicator {
    box-shadow: 0 0 0 4px rgba(40, 199, 111, 0.1);
}
</style>
@endsection
