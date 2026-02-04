@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Usulan Kegiatan')

@section('content')
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold text-primary mb-1">
                <i class="bx bx-show"></i> Detail Usulan Kegiatan
            </h4>
        </div>
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('usulan-kegiatan.index') }}" class="text-muted">Usulan Kegiatan</a>
                </li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Status Card -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4 bg-white rounded-custom-lg">
                    <div class="mb-3">
                        <i class="bx bx-bulb text-primary display-4"></i>
                    </div>
                    <h3 class="text-primary fw-bold mb-2">{{ $usulanKegiatan->nama_kegiatan }}</h3>
                    <span class="badge bg-{{ $usulanKegiatan->status_badge }} fs-6 px-3 py-2">
                        {{ $usulanKegiatan->status_label }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Detail Cards -->
        <div class="col-lg-8">
            <!-- Informasi Kegiatan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom mb-5">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper icon-wrapper-sm bg-primary me-3">
                            <i class="bx bx-info-circle text-white icon-md"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-dark fw-semibold">Informasi Kegiatan</h5>
                            <p class="mb-0 small text-muted">Detail lengkap usulan kegiatan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label text-muted">Nama Kegiatan</label>
                        </div>
                        <div class="col-sm-8">
                            <p class="mb-0 fw-medium">{{ $usulanKegiatan->nama_kegiatan }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label text-muted">Deskripsi</label>
                        </div>
                        <div class="col-sm-8">
                            <p class="mb-0">{{ $usulanKegiatan->deskripsi_kegiatan }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label text-muted">Jenis Kegiatan</label>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-info">{{ ucfirst($usulanKegiatan->jenis_kegiatan) }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label text-muted">Tempat Kegiatan</label>
                        </div>
                        <div class="col-sm-8">
                            <p class="mb-0">{{ $usulanKegiatan->tempat_kegiatan }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label text-muted">Jenis Pendanaan</label>
                        </div>
                        <div class="col-sm-8">
                            <span class="badge bg-success">{{ ucfirst($usulanKegiatan->jenis_pendanaan) }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="form-label text-muted">Status</label>
                        </div>
                        <div class="col-sm-8">
                            <span
                                class="badge bg-{{ $usulanKegiatan->status_badge }}">{{ $usulanKegiatan->status_label }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Kegiatan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom mb-5">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper icon-wrapper-sm bg-gradient-info me-3">
                            <i class="bx bx-calendar text-white icon-md"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-dark fw-semibold">Jadwal Kegiatan</h5>
                            <p class="mb-0 small text-muted">Timeline pelaksanaan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-xs me-2">
                                    <div class="avatar-initial bg-success text-white rounded-circle">
                                        <i class="bx bx-play"></i>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted">Tanggal Mulai</small>
                                    <div class="fw-medium">{{ $usulanKegiatan->tanggal_mulai->format('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-xs me-2">
                                    <div class="avatar-initial bg-warning text-white rounded-circle">
                                        <i class="bx bx-stop"></i>
                                    </div>
                                </div>
                                <div>
                                    <small class="text-muted">Tanggal Akhir</small>
                                    <div class="fw-medium">{{ $usulanKegiatan->tanggal_akhir->format('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bx bx-info-circle me-2"></i>
                                <div>
                                    <strong>Durasi Kegiatan:</strong>
                                    {{ $usulanKegiatan->tanggal_mulai->diffInDays($usulanKegiatan->tanggal_akhir) + 1 }}
                                    hari
                                    ({{ $usulanKegiatan->tanggal_mulai->format('d/m/Y') }} -
                                    {{ $usulanKegiatan->tanggal_akhir->format('d/m/Y') }})
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Pengaju -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom mb-5">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper icon-wrapper-sm bg-gradient-purple me-3">
                            <i class="bx bx-user text-white icon-md"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-dark fw-semibold">Pengaju</h5>
                            <p class="mb-0 small text-muted">Informasi pengusul</p>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mx-auto mb-3">
                        <div class="avatar-initial bg-primary text-white rounded-circle fs-2">
                            {{ substr($usulanKegiatan->user->username, 0, 1) }}
                        </div>
                    </div>
                    <h5 class="mb-1">{{ $usulanKegiatan->user->username }}</h5>
                    <p class="text-muted small mb-3">{{ $usulanKegiatan->user->email }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-label-primary">Pengusul</span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom mb-5">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper icon-wrapper-sm bg-gradient-success me-3">
                            <i class="bx bx-time text-white icon-md"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-dark fw-semibold">Timeline</h5>
                            <p class="mb-0 small text-muted">Riwayat usulan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-point bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Usulan Dibuat</h6>
                                <small class="text-muted">{{ $usulanKegiatan->created_at->format('d F Y, H:i') }}</small>
                            </div>
                        </div>
                        @if ($usulanKegiatan->updated_at != $usulanKegiatan->created_at)
                            <div class="timeline-item">
                                <div class="timeline-point bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Terakhir Diperbarui</h6>
                                    <small
                                        class="text-muted">{{ $usulanKegiatan->updated_at->format('d F Y, H:i') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom mb-5">
                    <h5 class="card-title mb-0 text-dark fw-semibold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('usulan-kegiatan.edit', $usulanKegiatan) }}" class="btn btn-warning">
                            <i class="bx bx-edit-alt me-1"></i>
                            Edit Usulan
                        </a>
                        <a href="{{ route('usulan-kegiatan.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i>
                            Kembali ke Daftar
                        </a>
                        <form action="{{ route('usulan-kegiatan.destroy', $usulanKegiatan) }}" method="POST"
                            class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-warning w-100"
                                onclick="return confirm('Yakin ingin menghapus usulan ini?')">
                                <i class="bx bx-trash me-1"></i>
                                Hapus Usulan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
