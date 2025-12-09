@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar Pendanaan Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            Pendanaan Kegiatan
        </h4>
        <p class="text-muted mb-0">Kelola dan review Rencana Anggaran Biaya (RAB) kegiatan</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Pendanaan Kegiatan</li>
        </ol>
    </nav>
</div>

@include('_partials/toast')

<!-- Pendanaan List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            Daftar Pendanaan
            <span class="badge bg-label-info ms-2">{{ $kegiatans->count() }}</span>
        </h5>
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
                        <th>Total Anggaran</th>
                        <th>File RAB</th>
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
                            @if($kegiatan->total_anggaran)
                            <strong class="text-success">Rp {{ number_format($kegiatan->total_anggaran, 0, ',', '.') }}</strong>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $rabFile = $kegiatan->getFileByTahap('pendanaan');
                            @endphp
                            @if($rabFile)
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
                            <a href="{{ route('kegiatan.pendanaan.show', $kegiatan) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                <i class="bx bx-show"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bx bx-money display-1 text-muted"></i>
            <h5 class="mt-3 text-muted">Belum ada pendanaan kegiatan</h5>
            <p class="text-muted">Kegiatan akan muncul di sini setelah proposal disetujui</p>
        </div>
        @endif
    </div>
</div>

@endsection
