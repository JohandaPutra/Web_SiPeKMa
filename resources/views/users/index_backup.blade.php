@extends('layouts/contentNavbarLayout')

@section('title', 'User Management')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">User Management /</span> Users
    </h4>

    <!-- DataTable with Buttons -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Manage Users</h5>
            <div class="d-flex justify-content-end mb-3">
                <!-- Tombol export -->
                <div id="export-buttons" class="me-2"></div>
                <!-- Tombol Add New User -->
                <a href="#" id="add-new-user-btn" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-0 me-sm-1"></i>
                    <span class="d-none d-sm-inline-block">Add New User</span>
                </a>
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
            <table id="users-table" class="table table-hover dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->updated_at }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i
                                                class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                            Delete</a>
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
            var table = $('#users-table').DataTable({
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
                    className: 'btn btn-outline-primary btn-sm dropdown-toggle me-2',
                    text: '<i class="bx bx-export me-1"></i> Export',
                    buttons: [{
                            extend: 'print',
                            text: '<i class="bx bx-printer me-1"></i> Print',
                            className: 'dropdown-item',
                        },
                        {
                            extend: 'csv',
                            text: '<i class="bx bx-file me-1"></i> Csv',
                            className: 'dropdown-item',
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bx bx-file me-1"></i> Excel',
                            className: 'dropdown-item',
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="bx bx-file me-1"></i> Pdf',
                            className: 'dropdown-item',
                        },
                        {
                            extend: 'copy',
                            text: '<i class="bx bx-copy me-1"></i> Copy',
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


            // Tambahkan event listener untuk tombol Add New User di card header
            $('#add-new-user-btn').on('click', function() {
                // Redirect to create page atau open modal
                window.location.href = "#";
                // Atau bisa juga membuka modal jika diperlukan
                // $('#addNewUserModal').modal('show');
            });
        });
    </script>
@endpush
