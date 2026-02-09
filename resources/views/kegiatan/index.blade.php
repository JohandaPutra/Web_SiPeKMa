@extends('layouts/contentNavbarLayout')

@section('title', 'Usulan Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            Usulan Kegiatan
        </h4>
        <p class="text-muted mb-0">Kelola usulan kegiatan mahasiswa</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Usulan Kegiatan</li>
        </ol>
    </nav>
</div>

@include('_partials/toast')

{{-- Card Daftar Usulan Kegiatan --}}
<div class="card">
    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <h5 class="card-title mb-0">Daftar Usulan Kegiatan</h5>
                @if(Auth::user()->isHima())
                <a href="{{ route('kegiatan.create') }}" class="btn btn-primary btn-sm d-none d-md-inline-flex">
                    <i class="bx bx-plus me-1"></i> Buat Usulan Baru
                </a>
                @endif
            </div>
            <form method="GET" action="{{ route('kegiatan.index') }}" class="d-flex align-items-center gap-2">
                <label class="form-label mb-0 text-nowrap d-none d-sm-block">Tampilkan:</label>
                <select name="per_page" class="form-select form-select-sm" style="width: 100px;" onchange="this.form.submit()">
                    <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="all" {{ request('per_page', 10) == 'all' ? 'selected' : '' }}>Semua</option>
                </select>
            </form>
        </div>
        @if(Auth::user()->isHima())
        <div class="mt-3 d-md-none">
            <a href="{{ route('kegiatan.create') }}" class="btn btn-primary w-100">
                <i class="bx bx-plus me-1"></i> Buat Usulan Baru
            </a>
        </div>
        @endif
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
                        @if(!Auth::user()->isHima())
                        <th>{{ Auth::user()->isWadek() ? 'Diajukan Oleh' : 'Diajukan Tanggal' }}</th>
                        @endif
                        <th>Status</th>
                        <th>Tahap Approval</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kegiatans as $index => $kegiatan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="td-kegiatan-name">
                            <div>
                                <strong class="d-block">{{ $kegiatan->nama_kegiatan }}</strong>
                                <small class="text-muted">{{ Str::limit($kegiatan->deskripsi_kegiatan, 50) }}</small>
                            </div>
                        </td>
                        <td><span class="badge bg-label-info">{{ $kegiatan->jenisKegiatan->nama ?? '-' }}</span></td>
                        <td>
                            <small>
                                {{ $kegiatan->tanggal_mulai->format('d M Y') }}<br>
                                s/d {{ $kegiatan->tanggal_akhir->format('d M Y') }}
                            </small>
                        </td>
                        @if(!Auth::user()->isHima())
                        <td>
                            @if(Auth::user()->isWadek())
                                {{ $kegiatan->user->name }}
                            @else
                                <small>{{ $kegiatan->created_at->format('d M Y') }}</small>
                            @endif
                        </td>
                        @endif
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
                            <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-sm btn-info">
                                <i class="bx bx-show"></i> Detail
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
            <i class="bx bx-folder-open bx-lg text-muted mb-3"></i>
            <p class="text-muted">
                @if(Auth::user()->isHima())
                Belum ada usulan kegiatan. Klik tombol "Buat Usulan Baru" untuk membuat usulan.
                @else
                Belum ada usulan kegiatan yang perlu di-review.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
