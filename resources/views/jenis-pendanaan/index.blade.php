@extends('layouts/contentNavbarLayout')

@section('title', 'Kelola Jenis Pendanaan')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Jenis Pendanaan</h5>
    <a href="{{ route('jenis-pendanaan.create') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i>Tambah Jenis Pendanaan
    </a>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible mx-4 mt-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  @if(session('error'))
  <div class="alert alert-danger alert-dismissible mx-4 mt-3" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th width="5%">No</th>
          <th>Nama Jenis Pendanaan</th>
          <th>Deskripsi</th>
          <th width="10%">Status</th>
          <th width="15%">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($jenisPendanaans as $index => $jenis)
        <tr>
          <td>{{ $jenisPendanaans->firstItem() + $index }}</td>
          <td><strong>{{ $jenis->nama }}</strong></td>
          <td>{{ $jenis->deskripsi ?? '-' }}</td>
          <td>
            @if($jenis->is_active)
              <span class="badge bg-success">Aktif</span>
            @else
              <span class="badge bg-secondary">Nonaktif</span>
            @endif
          </td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('jenis-pendanaan.edit', $jenis->id) }}">
                  <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                <form action="{{ route('jenis-pendanaan.destroy', $jenis->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis pendanaan ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="bx bx-trash me-1"></i> Hapus
                  </button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center">Tidak ada data jenis pendanaan</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card-footer">
    <div class="d-flex justify-content-between align-items-center">
      <div class="text-muted">
        Menampilkan {{ $jenisPendanaans->firstItem() ?? 0 }} - {{ $jenisPendanaans->lastItem() ?? 0 }} dari {{ $jenisPendanaans->total() }} data
      </div>
      <div>
        {{ $jenisPendanaans->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
