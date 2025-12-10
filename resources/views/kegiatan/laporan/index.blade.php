@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar Laporan Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            Laporan Kegiatan
        </h4>
        <p class="text-muted mb-0">Kelola dan review Laporan Pertanggungjawaban (LPJ) kegiatan</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Laporan Kegiatan</li>
        </ol>
    </nav>
</div>

@include('_partials/toast')

<!-- Laporan List -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            Daftar Laporan
        </h5>
        <form method="GET" action="{{ route('kegiatan.laporan.index') }}" class="d-flex align-items-center gap-2">
            <label class="form-label mb-0 text-nowrap">Tampilkan:</label>
            <select name="per_page" class="form-select form-select-sm" style="width: 100px;" onchange="this.form.submit()">
                <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                <option value="all" {{ request('per_page', 10) == 'all' ? 'selected' : '' }}>Semua</option>
            </select>
        </form>
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
                        <th>Jenis Pendanaan</th>
                        <th>File LPJ</th>
                        <th>Status</th>
                        <th>Tahap Approval</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kegiatans as $index => $kegiatan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $kegiatan->nama_kegiatan }}</strong>
                            <br><small class="text-muted">{{ Str::limit($kegiatan->deskripsi_kegiatan, 50) }}</small>
                        </td>
                        <td><span class="badge bg-label-info">{{ $kegiatan->jenis_kegiatan }}</span></td>
                        <td>
                            <small>
                                {{ $kegiatan->tanggal_mulai->format('d M Y') }}<br>
                                s/d {{ $kegiatan->tanggal_akhir->format('d M Y') }}
                            </small>
                        </td>
                        <td><span class="badge bg-label-primary">{{ ucfirst($kegiatan->jenis_pendanaan) }}</span></td>
                        <td>
                            @php
                                $laporanFile = $kegiatan->getFileByTahap('laporan');
                            @endphp
                            @if($laporanFile)
                            <span class="badge bg-success">
                                <i class="bx bx-check-circle"></i> Uploaded
                            </span>
                            @else
                            <span class="badge bg-secondary">
                                <i class="bx bx-x-circle"></i> Belum
                            </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $kegiatan->statusBadge }}">
                                {{ ucfirst($kegiatan->status) }}
                            </span>
                        </td>
                        <td>
                            @if($kegiatan->current_approver_role === 'completed')
                            <span class="badge bg-success">✓ Selesai</span>
                            @elseif($kegiatan->current_approver_role)
                            <span class="badge bg-warning">
                                {{ match($kegiatan->current_approver_role) {
                                    'pembina_hima' => 'Pembina Hima',
                                    'kaprodi' => 'Kaprodi',
                                    'wadek_iii' => 'Wadek III',
                                    default => '-'
                                } }}
                            </span>
                            @else
                            <span class="badge bg-secondary">Belum Submit</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('kegiatan.laporan.show', $kegiatan) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                <i class="bx bx-show"></i>
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
            <i class="bx bx-file-blank display-1 text-muted"></i>
            <h5 class="mt-3 text-muted">Belum ada laporan kegiatan</h5>
            <p class="text-muted">Kegiatan akan muncul di sini setelah pendanaan disetujui</p>
        </div>
        @endif
    </div>
</div>

@endsection
