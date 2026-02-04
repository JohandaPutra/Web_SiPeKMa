@extends('layouts/contentNavbarLayout')

@section('title', 'User Management')

@section('content')
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold text-primary mb-1">
                <i class="bx bx-users"></i>User Management
            </h4>
            <p class="text-muted mb-0">Manage and organize your users efficiently</p>
        </div>
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Users</li>
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
                        <h5 class="card-title mb-0" style="color: #495057; font-weight: 600;">Manage Users</h5>
                        <p class="mb-0 small text-muted">Total users: {{ count($users) }}</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <!-- Tombol Add New User -->
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus me-1"></i>
                        <span class="d-none d-sm-inline-block">Add User</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="dt-search"></div>
                <div class="dt-length"></div>
            </div>
            <div class="table-responsive">
                <table id="users-table" class="table table-hover nowrap" style="width:100%; white-space: nowrap;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">Username</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th>Role</th>
                        <th>Prodi</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td class="d-none d-md-table-cell">{{ $user->username }}</td>
                            <td class="d-none d-md-table-cell">{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-label-primary">{{ $user->role->name ?? '-' }}</span>
                            </td>
                            <td>{{ $user->prodi->nama_prodi ?? '-' }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bx bx-trash me-1"></i> Delete
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
    </div>
    <!--/ DataTable with Buttons -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#users-table').DataTable({
                scrollX: true,
                pageLength: 10,
                language: {
                    paginate: {
                        previous: '<i class="bx bx-chevron-left"></i>',
                        next: '<i class="bx bx-chevron-right"></i>'
                    }
                },
                order: [[1, 'asc']],
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"dt-search"f><"dt-length"l>>rtip',
                autoWidth: false
            });

            // Kostum style untuk select
            $('.dataTables_wrapper .dataTables_filter input').addClass('form-control form-control-sm').css('width', '160px');
            $('.dataTables_wrapper .dataTables_length select').addClass('form-select form-select-sm');
            
            // Custom label untuk show entries
            $('.dataTables_length label').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();
            $('.dataTables_length label').prepend('Show ');
            $('.dataTables_length label').append(' entries');
            
            // Custom label untuk search
            $('.dataTables_filter label').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();
            $('.dataTables_filter input').attr('placeholder', 'Search...');
        });
    </script>
@endpush
