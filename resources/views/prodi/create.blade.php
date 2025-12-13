@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Program Studi')

@section('content')
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold text-primary mb-1">
                <i class="bx bx-buildings"></i> Tambah Program Studi
            </h4>
            <p class="text-muted mb-0">Tambah data program studi baru</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('prodi.index') }}" class="text-muted">Program Studi</a>
                </li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bx bx-plus-circle"></i> Form Tambah Program Studi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('prodi.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="kode_prodi" class="form-label">Kode Program Studi <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('kode_prodi') is-invalid @enderror"
                                   id="kode_prodi"
                                   name="kode_prodi"
                                   value="{{ old('kode_prodi') }}"
                                   placeholder="Contoh: SI, IF, STAT"
                                   maxlength="10"
                                   required>
                            @error('kode_prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kode unik untuk identifikasi program studi (maksimal 10 karakter)</small>
                        </div>

                        <div class="mb-3">
                            <label for="nama_prodi" class="form-label">Nama Program Studi <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('nama_prodi') is-invalid @enderror"
                                   id="nama_prodi"
                                   name="nama_prodi"
                                   value="{{ old('nama_prodi') }}"
                                   placeholder="Contoh: Sistem Informasi"
                                   maxlength="100"
                                   required>
                            @error('nama_prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('prodi.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
