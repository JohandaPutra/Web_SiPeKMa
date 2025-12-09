@extends('layouts/contentNavbarLayout')

@section('title', 'Usulan Kegiatan')

@section('content')
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold text-primary mb-1">
                <i class="bx bx-bulb"></i> Usulan Kegiatan
            </h4>
            <p class="text-muted mb-0">Kelola usulan kegiatan mahasiswa</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Usulan Kegiatan</li>
            </ol>
        </nav>
    </div>

    <!-- DataTable with Buttons -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3"
                        style="background: linear-gradient(135deg, #f3541d 0%, #ff7849 100%); border-radius: 8px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="bx bx-table text-white" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0" style="color: #495057; font-weight: 600;">Data Usulan Kegiatan</h5>
                        <p class="mb-0 small text-muted">Total usulan: {{ count($usulanKegiatans) }}</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <!-- Tombol export -->
                    <div id="export-buttons"></div>
                    <!-- Tombol Add New -->
                    <a href="{{ route('usulan-kegiatan.create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus me-1"></i>
                        <span class="d-none d-sm-inline-block">Tambah Usulan</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center row mb-3">
                <div class="col-sm-12 col-md-6">
                    <div class="dt-length"></div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="dt-search"></div>
                </div>
            </div>
            <table id="usulan-table" class="table table-hover dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengaju</th>
                        <th>Nama Kegiatan</th>
                        <th>Jenis</th>
                        <th>Tempat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($usulanKegiatans as $usulan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2">
                                        <div class="avatar-initial bg-primary text-white rounded-circle">
                                            {{ substr($usulan->user->username, 0, 1) }}
                                        </div>
                                    </div>
                                    {{ $usulan->user->username }}
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="fw-medium">{{ $usulan->nama_kegiatan }}</span>
                                    <div class="text-muted small">{{ Str::limit($usulan->deskripsi_kegiatan, 50) }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($usulan->jenis_kegiatan) }}</span>
                            </td>
                            <td>{{ $usulan->tempat_kegiatan }}</td>
                            <td>
                                <div class="small">
                                    <strong>Mulai:</strong> {{ $usulan->tanggal_mulai->format('d/m/Y') }}<br>
                                    <strong>Akhir:</strong> {{ $usulan->tanggal_akhir->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $usulan->status_badge }}">{{ $usulan->status_label }}</span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('usulan-kegiatan.show', $usulan) }}">
                                            <i class="bx bx-show me-1"></i> Detail
                                        </a>
                                        <a class="dropdown-item" href="{{ route('usulan-kegiatan.edit', $usulan) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('usulan-kegiatan.destroy', $usulan) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Yakin ingin menghapus usulan ini?')">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!--/ DataTable with Buttons -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#usulan-table').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                }
            });

            // Tempatkan tombol di card header
            var exportButtons = new $.fn.dataTable.Buttons(table, {
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-primary btn-sm dropdown-toggle',
                    text: '<i class="bx bx-export me-1"></i> Export',
                    buttons: [{
                            extend: 'print',
                            text: '<i class="bx bx-printer me-1"></i> Print',
                            className: 'dropdown-item',
                        },
                        {
                            extend: 'csv',
                            text: '<i class="bx bx-file me-1"></i> CSV',
                            className: 'dropdown-item',
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bx bx-file me-1"></i> Excel',
                            className: 'dropdown-item',
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="bx bx-file me-1"></i> PDF',
                            className: 'dropdown-item',
                        }
                    ]
                }]
            });

            // Pindahkan komponen DataTable ke container yang sudah ditentukan
            exportButtons.container().appendTo('#export-buttons');

            // Kostum style untuk select
            $('.dataTables_wrapper .dataTables_filter input').addClass('form-control form-control-sm ms-2');
            $('.dataTables_wrapper .dataTables_length select').addClass('form-select form-select-sm');
        });
    </script>
@endpush
