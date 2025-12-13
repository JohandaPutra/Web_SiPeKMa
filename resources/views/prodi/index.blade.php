@extends('layouts/contentNavbarLayout')

@section('title', 'Kelola Program Studi')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Program Studi</h5>
    <a href="{{ route('prodi.create') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i>Tambah Prodi
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
          <th>Kode</th>
          <th>Nama Program Studi</th>
          <th>Fakultas</th>
          <th width="15%">Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($prodis as $index => $prodi)
        <tr>
          <td>{{ $prodis->firstItem() + $index }}</td>
          <td><strong>{{ $prodi->kode }}</strong></td>
          <td>{{ $prodi->nama }}</td>
          <td>{{ $prodi->fakultas }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('prodi.edit', $prodi->id) }}">
                  <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                <form action="{{ route('prodi.destroy', $prodi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus prodi ini?')">
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
          <td colspan="5" class="text-center">Tidak ada data program studi</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card-footer">
    <div class="d-flex justify-content-between align-items-center">
      <div class="text-muted">
        Menampilkan {{ $prodis->firstItem() ?? 0 }} - {{ $prodis->lastItem() ?? 0 }} dari {{ $prodis->total() }} data
      </div>
      <div>
        {{ $prodis->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
                            <th width="60">#</th>
                            <th>Kode Prodi</th>
                            <th>Nama Program Studi</th>
                            <th width="120" class="text-center">Jumlah User</th>
                            <th width="180" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($prodis as $prodi)
                            <tr>
                                <td>{{ $prodis->firstItem() + $loop->index }}</td>
                                <td><span class="badge bg-label-info">{{ $prodi->kode_prodi }}</span></td>
                                <td>{{ $prodi->nama_prodi }}</td>
                                <td class="text-center">
                                    <span class="badge bg-label-primary">{{ $prodi->users_count }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('prodi.edit', $prodi->id) }}"
                                           class="btn btn-sm btn-icon btn-outline-warning"
                                           data-bs-toggle="tooltip" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $prodi->id }}"
                                                title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $prodi->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus program studi <strong>{{ $prodi->nama_prodi }}</strong>?</p>
                                            @if($prodi->users_count > 0)
                                                <div class="alert alert-warning">
                                                    <i class="bx bx-error-circle"></i>
                                                    Program studi ini memiliki <strong>{{ $prodi->users_count }} user</strong> terkait.
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('prodi.destroy', $prodi->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bx bx-data bx-lg text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada data program studi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $prodis->firstItem() ?? 0 }} - {{ $prodis->lastItem() ?? 0 }} dari {{ $prodis->total() }} data
                </div>
                <div>
                    {{ $prodis->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
