 @extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Jenis Pendanaan')

@section('content')
<nav aria-label="breadcrumb" class="d-none d-md-block">
  <ol class="breadcrumb breadcrumb-style1 mb-0">
    <li class="breadcrumb-item">
      <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{ route('jenis-pendanaan.index') }}">Jenis Pendanaan</a>
    </li>
    <li class="breadcrumb-item active">Tambah</li>
  </ol>
</nav>

<div class="card mt-4">
  <div class="card-header">
    <h5 class="mb-0">Tambah Jenis Pendanaan Baru</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('jenis-pendanaan.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="nama" class="form-label">Nama Jenis Pendanaan <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror"
               id="nama" name="nama" value="{{ old('nama') }}"
               placeholder="Contoh: Mandiri, Sponsor, Hibah" required>
        @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                  id="deskripsi" name="deskripsi" rows="4"
                  placeholder="Deskripsi singkat tentang jenis pendanaan ini (opsional)">{{ old('deskripsi') }}</textarea>
        @error('deskripsi')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                 {{ old('is_active', true) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Status Aktif</label>
        </div>
        <small class="text-muted">Jenis pendanaan yang aktif dapat dipilih saat membuat kegiatan baru</small>
      </div>

      <div class="d-flex justify-content-between">
        <a href="{{ route('jenis-pendanaan.index') }}" class="btn btn-secondary">
          <i class="bx bx-arrow-back me-1"></i>Kembali
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-save me-1"></i>Simpan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
