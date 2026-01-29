@extends('layouts/contentNavbarLayout')

@section('title', 'Buat Usulan Kegiatan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
        <h4 class="fw-bold text-primary mb-1">
            <i class="bx bx-plus-circle"></i> Buat Usulan Kegiatan
        </h4>
        <p class="text-muted mb-0">Lengkapi form untuk mengajukan usulan kegiatan</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('kegiatan.index') }}" class="text-muted">Usulan Kegiatan</a>
            </li>
            <li class="breadcrumb-item active">Buat Baru</li>
        </ol>
    </nav>
</div>

@include('_partials/toast')

<div class="row">
    <div class="col-md-12">
        <form action="{{ route('kegiatan.store') }}" method="POST">
            @csrf
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Kegiatan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="nama_kegiatan">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                   id="nama_kegiatan" name="nama_kegiatan"
                                   value="{{ old('nama_kegiatan') }}"
                                   placeholder="Masukkan nama kegiatan">
                            @error('nama_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="jenis_kegiatan_id">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kegiatan_id') is-invalid @enderror"
                                    id="jenis_kegiatan_id" name="jenis_kegiatan_id">
                                <option value="">Pilih Jenis</option>
                                @foreach($jenisKegiatans as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_kegiatan_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_kegiatan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="deskripsi_kegiatan">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi_kegiatan') is-invalid @enderror"
                                      id="deskripsi_kegiatan" name="deskripsi_kegiatan"
                                      rows="4" placeholder="Jelaskan detail kegiatan yang akan dilaksanakan">{{ old('deskripsi_kegiatan') }}</textarea>
                            @error('deskripsi_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tempat_kegiatan">Tempat Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_kegiatan') is-invalid @enderror"
                                   id="tempat_kegiatan" name="tempat_kegiatan"
                                   value="{{ old('tempat_kegiatan') }}"
                                   placeholder="Contoh: Aula Kampus, Hotel X, dll">
                            @error('tempat_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="jenis_pendanaan_id">Jenis Pendanaan <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_pendanaan_id') is-invalid @enderror"
                                    id="jenis_pendanaan_id" name="jenis_pendanaan_id">
                                <option value="">Pilih Jenis Pendanaan</option>
                                @foreach($jenisPendanaans as $jenis)
                                    <option value="{{ $jenis->id }}" {{ old('jenis_pendanaan_id') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_pendanaan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                   id="tanggal_mulai" name="tanggal_mulai"
                                   value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tanggal_akhir">Tanggal Akhir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror"
                                   id="tanggal_akhir" name="tanggal_akhir"
                                   value="{{ old('tanggal_akhir') }}">
                            @error('tanggal_akhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('kegiatan.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Usulan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
