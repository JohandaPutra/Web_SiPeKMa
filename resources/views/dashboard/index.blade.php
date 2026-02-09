@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="row mb-6">
        <div class="col-12">
            <div class="card border-0 shadow-lg overflow-hidden bg-gradient-hero hero-card">
                <!-- Floating Elements -->
                <div class="floating-icon-top-right">
                    <i class="bx bx-rocket icon-hero text-primary"></i>
                </div>
                <div class="floating-icon-bottom-left">
                    <i class="bx bx-star icon-xl text-warning"></i>
                </div>

                <div class="card-body px-10 py-8 position-relative">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="text-dark">
                                <div class="mb-10">
                                    <span class="badge bg-primary text-white px-3 py-2 rounded-4 fw-semibold">
                                        ✨ Dashboard Kegiatan
                                    </span>
                                </div>
                                <h1 class="fw-bold mb-3 display-5 text-primary">Mulai Kegiatan Impianmu!</h1>
                                <p class="fs-5 mb-4 text-dark pe-5 mb-4">
                                    Transformasikan ide brilliant menjadi aksi nyata. Platform terintegrasi untuk mengelola
                                    seluruh siklus kegiatan mahasiswa.
                                </p>
                                @if(Auth::user()->isHima())
                                <div class="d-flex gap-3">
                                    <a href="{{ route('kegiatan.create') }}"
                                        class="btn btn-primary btn-lg px-4 py-3 shadow fw-semibold rounded-5">
                                        <i class="bx bx-plus-circle me-2"></i>Buat Usulan
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-5 text-center d-none-mobile-hero">
                            <div class="position-relative">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block">
                                    <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" class="img-fluid img-drop-shadow animate-float"
                                        style="max-width: 220px;"
                                        alt="Ilustrasi Dashboard">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-card">
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-3">
                            <h3 class="fw-bold text-primary mb-1">{{ $totalUsulan }}</h3>
                            <small class="text-muted text-nowrap">Kegiatan</small>
                        </div>
                        <div class="col-3">
                            <h3 class="fw-bold text-success mb-1">{{ $proposalDisetujui }}</h3>
                            <small class="text-muted text-nowrap">Proposal</small>
                        </div>
                        <div class="col-3">
                            <h3 class="fw-bold text-warning mb-1">{{ $pendanaan }}</h3>
                            <small class="text-muted text-nowrap">Pendanaan</small>
                        </div>
                        <div class="col-3">
                            <h3 class="fw-bold text-info mb-1">{{ $laporan }}</h3>
                            <small class="text-muted text-nowrap">Laporan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-2 fs-3">🎯 Menu Utama</h2>
            <p class="text-muted mb-4">Akses cepat ke semua fitur pengelolaan kegiatan</p>
        </div>
    </div>

    <div class="row g-4 mb-6">
        <!-- Card 1 - Usulan Kegiatan -->
        <div class="col-lg-3 col-md-6 col-6">
            <div class="card h-100 border-0 card-hover-lift position-relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="d-none d-md-block floating-icon-top-right-lg">
                    <i class="bx bx-bulb icon-xxl text-white"></i>
                </div>

                <div class="card-body text-center p-3 p-md-4 position-relative bg-gradient-primary">
                    <div class="mb-2 mb-md-3">
                        <div class="bg-white bg-opacity-25 rounded-circle mx-auto icon-wrapper-md shadow-sm">
                            <i class="bx bx-bulb text-primary icon-md text-shadow"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-white mb-2 fs-6 fs-md-5">Usulan Kegiatan</h6>
                    <p class="text-white opacity-85 small mb-2 mb-md-3 d-none d-md-block">Sampaikan ide kreatif untuk kegiatan baru</p>
                    <a href="{{ route('kegiatan.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Card 2 - Proposal Kegiatan -->
        <div class="col-lg-3 col-md-6 col-6">
            <div class="card h-100 border-0 card-hover-lift position-relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="d-none d-md-block floating-icon-top-right-lg">
                    <i class="bx bx-file-blank icon-xxl text-white"></i>
                </div>

                <div class="card-body text-center p-3 p-md-4 position-relative bg-gradient-primary">
                    <div class="mb-2 mb-md-3">
                        <div class="bg-white bg-opacity-25 rounded-circle mx-auto icon-wrapper-md shadow-sm">
                            <i class="bx bx-file-blank text-primary icon-md text-shadow"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-white mb-2 fs-6 fs-md-5">Proposal Kegiatan</h6>
                    <p class="text-white opacity-85 small mb-2 mb-md-3 d-none d-md-block">Buat proposal detail dan profesional</p>
                   
                    <a href="{{ route('kegiatan.proposal.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Card 3 - Pendanaan Kegiatan -->
        <div class="col-lg-3 col-md-6 col-6">
            <div class="card h-100 border-0 card-hover-lift position-relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="d-none d-md-block floating-icon-top-right-lg">
                    <i class="bx bx-money icon-xxl text-white"></i>
                </div>

                <div class="card-body text-center p-3 p-md-4 position-relative bg-gradient-primary">
                    <div class="mb-2 mb-md-3">
                        <div class="bg-white bg-opacity-25 rounded-circle mx-auto icon-wrapper-md shadow-sm">
                            <i class="bx bx-money text-primary icon-md text-shadow"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-white mb-2 fs-6 fs-md-5">Pendanaan Kegiatan</h6>
                    <p class="text-white opacity-85 small mb-2 mb-md-3 d-none d-md-block">Kelola anggaran dan dana kegiatan</p>
                    
                    <a href="{{ route('kegiatan.pendanaan.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <!-- Card 4 - Laporan Kegiatan -->
        <div class="col-lg-3 col-md-6 col-6">
            <div class="card h-100 border-0 card-hover-lift position-relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="d-none d-md-block floating-icon-top-right-lg">
                    <i class="bx bx-bar-chart-alt-2 icon-xxl text-white"></i>
                </div>

                <div class="card-body text-center p-3 p-md-4 position-relative bg-gradient-primary">
                    <div class="mb-2 mb-md-3">
                        <div class="bg-white bg-opacity-25 rounded-circle mx-auto icon-wrapper-md shadow-sm">
                            <i class="bx bx-bar-chart-alt-2 text-primary icon-md text-shadow"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-white mb-2 fs-6 fs-md-5">Laporan Kegiatan</h6>
                    <p class="text-white opacity-85 small mb-2 mb-md-3 d-none d-md-block">Generate laporan comprehensive</p>
                  
                    <a href="{{ route('kegiatan.laporan.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

@endsection
