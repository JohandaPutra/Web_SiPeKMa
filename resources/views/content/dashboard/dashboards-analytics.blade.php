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
            <div class="card border-0 shadow-lg overflow-hidden"
                style="background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 25%, #fef3c7 50%, #fed7aa 75%, #fce7f3 100%); min-height: 200px; position: relative;">
                <!-- Floating Elements -->
                <div style="position: absolute; top: 20px; right: 20px; opacity: 0.15;">
                    <i class="bx bx-rocket" style="font-size: 8rem; color: #3b82f6;"></i>
                </div>
                <div style="position: absolute; bottom: 10px; left: 10px; opacity: 0.15;">
                    <i class="bx bx-star" style="font-size: 4rem; color: #f59e0b;"></i>
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
                                        class="btn btn-primary btn-lg px-4 py-3 shadow fw-semibold"
                                        style="border-radius: 5px;">
                                        <i class="bx bx-plus-circle me-2"></i>Buat Usulan
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-5 text-center">
                            <div class="position-relative">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block">
                                    <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" class="img-fluid"
                                        style="max-width: 220px; filter: drop-shadow(0 10px 30px rgba(59, 130, 246, 0.3));"
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
            <div class="card border-0 shadow-sm"
                style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0;">
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-3">
                            <h3 class="fw-bold text-primary mb-1">{{ $totalUsulan }}</h3>
                            <small class="text-muted">Usulan Kegiatan</small>
                        </div>
                        <div class="col-3">
                            <h3 class="fw-bold text-success mb-1">{{ $proposalDisetujui }}</h3>
                            <small class="text-muted">Proposal</small>
                        </div>
                        <div class="col-3">
                            <h3 class="fw-bold text-warning mb-1">{{ $pendanaan }}</h3>
                            <small class="text-muted">Pendanaan</small>
                        </div>
                        <div class="col-3">
                            <h3 class="fw-bold text-info mb-1">{{ $laporan }}</h3>
                            <small class="text-muted">Laporan</small>
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
            <div class="card h-100 border-0 shadow-hover position-relative overflow-hidden"
                style="transition: all 0.4s ease;">
                <!-- Background Pattern -->
                <div class="d-none d-md-block" style="position: absolute; top: -20px; right: -20px; opacity: 0.15;">
                    <i class="bx bx-bulb" style="font-size: 6rem; color: rgba(255,255,255,0.5);"></i>
                </div>

                <div class="card-body text-center p-3 p-md-4 position-relative"
                    style="background: linear-gradient(135deg, #f3541d 0%, #ff7849 100%);">
                    <div class="mb-2 mb-md-3">
                        <div class="bg-white bg-opacity-25 rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 50px; height: 50px;">
                            <i class="bx bx-bulb text-primary"
                                style="font-size: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.2);"></i>
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
            <div class="card h-100 border-0 shadow-hover position-relative overflow-hidden"
                style="transition: all 0.4s ease;">
                <!-- Background Pattern -->
                <div class="d-none d-md-block" style="position: absolute; top: -20px; right: -20px; opacity: 0.15;">
                    <i class="bx bx-file-blank" style="font-size: 6rem; color: rgba(255,255,255,0.5);"></i>
                </div>

                <div class="card-body text-center p-3 p-md-4 position-relative"
                    style="background: linear-gradient(135deg, #f3541d 0%, #ff7849 100%);">
                    <div class="mb-2 mb-md-3">
                        <div class="bg-white bg-opacity-25 rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 50px; height: 50px;">
                            <i class="bx bx-file-blank text-primary"
                                style="font-size: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.2);"></i>
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
            <div class="card h-100 border-0 shadow-hover position-relative overflow-hidden"
                style="transition: all 0.4s ease;">
                <!-- Background Pattern -->
                <div class="d-none d-md-block" style="position: absolute; top: -20px; right: -20px; opacity: 0.15;">
                    <i class="bx bx-money" style="font-size: 6rem; color: rgba(255,255,255,0.5);"></i>
                </div>

                <div class="card-body text-center p-3 p-md-4 position-relative"
                    style="background: linear-gradient(135deg, #f3541d 0%, #ff7849 100%);">
                    <div class="mb-2 mb-md-3">
                        <div class="bg-white bg-opacity-25 rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 50px; height: 50px;">
                            <i class="bx bx-money text-primary"
                                style="font-size: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.2);"></i>
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
            <div class="card h-100 border-0 shadow-hover position-relative overflow-hidden"
                style="transition: all 0.4s ease;">
                <!-- Background Pattern -->
                <div class="d-none d-md-block" style="position: absolute; top: -20px; right: -20px; opacity: 0.15;">
                    <i class="bx bx-bar-chart-alt-2" style="font-size: 6rem; color: rgba(255,255,255,0.5);"></i>
                </div>

                <div class="card-body text-center p-3 p-md-4 position-relative"
                    style="background: linear-gradient(135deg, #f3541d 0%, #ff7849 100%);">
                    <div class="mb-2 mb-md-3">
                        <div class="bg-white bg-opacity-25 rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 50px; height: 50px;">
                            <i class="bx bx-bar-chart-alt-2 text-primary"
                                style="font-size: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.2);"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-white mb-2 fs-6 fs-md-5">Laporan Kegiatan</h6>
                    <p class="text-white opacity-85 small mb-2 mb-md-3 d-none d-md-block">Generate laporan comprehensive</p>
                  
                    <a href="{{ route('kegiatan.laporan.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for enhanced effects -->
    <style>
        .shadow-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
        }

        .shadow-hover {
            cursor: pointer;
        }

        .shadow-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .shadow-hover:hover::before {
            opacity: 1;
        }

        .text-white-75 {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .card-body img {
            animation: float 3s ease-in-out infinite;
        }

        .badge {
            backdrop-filter: blur(10px);
        }

        /* Hide illustration on mobile */
        @media (max-width: 991px) {
            .col-lg-5.text-center {
                display: none !important;
            }
            .col-lg-7 {
                max-width: 100%;
                flex: 0 0 100%;
            }
        }
    </style>

@endsection
