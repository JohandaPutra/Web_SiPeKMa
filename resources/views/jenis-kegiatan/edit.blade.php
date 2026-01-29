@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Jenis Kegiatan')

@section('content')
<nav aria-label="breadcrumb" class="d-none d-md-block">
  <ol class="breadcrumb breadcrumb-style1 mb-0">
    <li class="breadcrumb-item">
      <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('jenis-kegiatan.index') }}">Jenis Kegiatan</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
  </ol>
</nav>

<div class="card mt-4">
  <div class="card-header">
    <h5 class="mb-0">Edit Jenis Kegiatan: {{ $jenisKegiatan->nama }}</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('jenis-kegiatan.update', $jenisKegiatan->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="nama" class="form-label">Nama Jenis Kegiatan <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror"
               id="nama" name="nama" value="{{ old('nama', $jenisKegiatan->nama) }}"
               placeholder="Contoh: Seminar, Workshop, Pelatihan" required>
        @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                  id="deskripsi" name="deskripsi" rows="4"
                  placeholder="Deskripsi singkat tentang jenis kegiatan ini (opsional)">{{ old('deskripsi', $jenisKegiatan->deskripsi) }}</textarea>
        @error('deskripsi')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                 {{ old('is_active', $jenisKegiatan->is_active) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Status Aktif</label>
        </div>
        <small class="text-muted">Jenis kegiatan yang aktif dapat dipilih saat membuat kegiatan baru</small>
      </div>

      <div class="d-flex justify-content-between">
        <a href="{{ route('jenis-kegiatan.index') }}" class="btn btn-secondary">
          <i class="bx bx-arrow-back me-1"></i>Kembali
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-save me-1"></i>Update
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
