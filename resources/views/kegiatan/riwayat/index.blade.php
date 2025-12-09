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
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Riwayat Kegiatan</li>
        </ol>
    </nav>
</div>

@include('_partials/toast')

<!-- Kegiatan Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Riwayat Kegiatan</h5>
        <span class="badge bg-label-primary">{{ $kegiatans->count() }} Kegiatan</span>
    </div>
    <div class="card-body">
        @if($kegiatans->count() > 0)
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Jenis Kegiatan</th>
                        <th>Tanggal Kegiatan</th>
                        <th>Program Studi</th>
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
                        <td>
                            <strong>{{ $kegiatan->nama_kegiatan }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-label-info">{{ ucfirst($kegiatan->jenis_kegiatan) }}</span>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }}
                            @if($kegiatan->tanggal_mulai != $kegiatan->tanggal_akhir)
                            <br><small class="text-muted">s/d {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir)->format('d M Y') }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-label-primary">{{ $kegiatan->prodi->nama_prodi }}</span>
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
                                <div class="progress flex-grow-1 me-2" style="height: 8px; width: 80px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $progress }}%" 
                                         aria-valuenow="{{ $progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
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
        @else
        <div class="text-center py-5">
            <i class="bx bx-time-five bx-lg text-muted mb-3"></i>
            <p class="text-muted mb-0">Belum ada riwayat kegiatan</p>
        </div>
        @endif
    </div>
</div>
@endsection
