@extends('layouts/contentNavbarLayout')

@section('title', 'Riwayat Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            Riwayat Kegiatan
        </h4>
        <p class="text-muted mb-0">Lihat semua riwayat kegiatan dengan detail lengkap</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Riwayat Kegiatan</li>
        </ol>
    </nav>
</div>

@include('_partials/toast')

<!-- Filter Section -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('kegiatan.riwayat') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <!-- Search -->
                <div class="col-md-4 col-lg-4">
                    <label class="form-label fw-semibold text-muted small mb-2">Cari Kegiatan</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama kegiatan..." value="{{ request('search') }}">
                </div>

                <!-- Status -->
                <div class="col-md-3 col-lg-3">
                    <label class="form-label fw-semibold text-muted small mb-2">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="dikirim" {{ request('status') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="revisi" {{ request('status') === 'revisi' ? 'selected' : '' }}>Revisi</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Tahapan -->
                <div class="col-md-2 col-lg-2">
                    <label class="form-label fw-semibold text-muted small mb-2">Tahapan</label>
                    <select name="tahap" class="form-select">
                        <option value="">Semua Tahapan</option>
                        <option value="usulan" {{ request('tahap') === 'usulan' ? 'selected' : '' }}>Usulan</option>
                        <option value="proposal" {{ request('tahap') === 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="pendanaan" {{ request('tahap') === 'pendanaan' ? 'selected' : '' }}>Pendanaan</option>
                        <option value="laporan" {{ request('tahap') === 'laporan' ? 'selected' : '' }}>Laporan</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-md-3 col-lg-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                        <a href="{{ route('kegiatan.riwayat') }}" class="btn btn-outline-secondary flex-fill">
                            <i class="bx bx-reset me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Kegiatan Table -->
<div class="card">
    <div class="card-header">
        <!-- Desktop: Judul kiri, controls kanan -->
        <!-- Mobile: Judul atas full width, controls bawah dengan tampilan sejajar kiri-kanan -->
        <div class="row align-items-center">
            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <h5 class="mb-0">Daftar Riwayat Kegiatan</h5>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex justify-content-between justify-content-md-end align-items-center gap-2">
                    <form method="GET" action="{{ route('kegiatan.riwayat') }}" id="perPageForm" class="d-flex align-items-center gap-2">
                        <!-- Preserve existing filters -->
                        @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if(request('tahap'))
                        <input type="hidden" name="tahap" value="{{ request('tahap') }}">
                        @endif

                        <label class="form-label mb-0 text-nowrap d-none d-sm-inline">Tampilkan:</label>
                        <select name="per_page" class="form-select form-select-sm w-auto min-w-80px" onchange="this.form.submit()">
                            <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                            <option value="all" {{ request('per_page', 10) == 'all' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </form>

                    <!-- Export Buttons -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success btn-sm" onclick="exportData('xlsx')">
                            <i class="bx bx-spreadsheet me-1"></i>Excel
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="exportData('csv')">
                            <i class="bx bx-file me-1"></i>CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($kegiatans->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Jenis Kegiatan</th>
                        <th>Tanggal Kegiatan</th>
                        @if(Auth::check() && Auth::user()->isWadek())
                        <th>Program Studi</th>
                        @endif
                        <th>Berada di</th>
                        <th>Tahap</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($kegiatans as $index => $kegiatan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="td-kegiatan-name">
                            <div>
                                <strong class="d-block">{{ $kegiatan->nama_kegiatan }}</strong>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-label-info">{{ $kegiatan->jenisKegiatan->nama ?? '-' }}</span>
                        </td>
                        <td class="text-nowrap">
                            <div class="d-flex flex-column">
                                <span>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}</span>
                                @if($kegiatan->tanggal_mulai != $kegiatan->tanggal_akhir)
                                <small class="text-muted">s/d {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir)->format('d M Y') }}</small>
                                @endif
                            </div>
                        </td>
                        @if(Auth::check() && Auth::user()->isWadek())
                        <td>
                            <span class="badge bg-label-primary">{{ $kegiatan->prodi->nama_prodi }}</span>
                        </td>
                        @endif
                        <td>
                            @php
                            $currentApprover = 'Selesai';
                            if ($kegiatan->current_approver_role === 'completed') {
                                $currentApprover = '<span class="badge bg-success">Selesai</span>';
                            } elseif ($kegiatan->current_approver_role) {
                                $roleDisplayNames = [
                                    'Hima' => 'Hima',
                                    'Pembina Hima' => 'Pembina',
                                    'Kaprodi' => 'Kaprodi',
                                    'Wadek III' => 'Wadek III'
                                ];
                                $displayName = $roleDisplayNames[$kegiatan->current_approver_role] ?? $kegiatan->current_approver_role;
                                $currentApprover = '<span class="badge bg-info">' . $displayName . '</span>';
                            } else {
                                $currentApprover = '<span class="badge bg-secondary">Draft</span>';
                            }
                            @endphp
                            {!! $currentApprover !!}
                        </td>
                        <td>
                            <span class="badge bg-{{ $kegiatan->tahapBadge }}">{{ ucfirst($kegiatan->tahap) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $kegiatan->statusBadge }}">{{ ucfirst($kegiatan->status) }}</span>
                        </td>
                        <td>
                            @php
                            $progress = 0;
                            if ($kegiatan->current_approver_role === 'completed') {
                                $progress = 100;
                            } elseif ($kegiatan->tahap === 'laporan') {
                                $progress = 85;
                            } elseif ($kegiatan->tahap === 'pendanaan') {
                                $progress = 65;
                            } elseif ($kegiatan->tahap === 'proposal') {
                                $progress = 40;
                            } elseif ($kegiatan->tahap === 'usulan') {
                                $progress = 15;
                            }
                            @endphp
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2 progress-sm w-80px">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         aria-valuenow="{{ $progress }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100"
                                         style="width: {{ $progress }}%">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $progress }}%</small>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('kegiatan.riwayat.show', $kegiatan) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bx bx-show me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(is_object($kegiatans) && method_exists($kegiatans, 'hasPages') && $kegiatans->hasPages())
        <div class="mt-3 d-flex justify-content-end">
            <nav>
                {{ $kegiatans->onEachSide(1)->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
            </nav>
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bx bx-time-five bx-lg text-muted mb-3"></i>
            <p class="text-muted mb-0">Belum ada riwayat kegiatan</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('page-script')
<script>
function exportData(type) {
    // Get current filter values from form
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);

    // Build query string
    const params = new URLSearchParams();
    params.append('export_type', type);

    // Add all existing filters
    for (const [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }

    // Redirect to export URL
    window.location.href = '{{ route("kegiatan.riwayat.export") }}?' + params.toString();
}
</script>
@endsection
