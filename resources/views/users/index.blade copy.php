@extends('layouts/contentNavbarLayout')

@section('title', 'User Management')

@section('content')
<div class="user-management-page">
  <!-- Header Section -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div class="mb-3 mb-md-0">
      <h4 class="fw-bold text-primary mb-1">
        <i class="bx bx-users me-2"></i>User Management
      </h4>
      <p class="text-muted mb-0">Manage and organize your users efficiently</p>
    </div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1 mb-0">
        <li class="breadcrumb-item">
          <a href="javascript:void(0);" class="text-muted">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Users</li>
      </ol>
    </nav>
  </div>

  <!-- DataTable Card -->
  <div class="card shadow-sm">
    <div class="card-header border-bottom">
      <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div class="card-title-wrapper">
          <h5 class="card-title fw-semibold mb-0">
            <i class="bx bx-table me-2 text-primary"></i>Users Data
          </h5>
          <p class="text-muted small mb-0 mt-1">Total users in the system</p>
        </div>
        <div class="d-flex align-items-center gap-2">
          <!-- Search & Filter Controls -->
          <div class="d-flex align-items-center gap-2">
            <div class="dt-search-wrapper"></div>
            <div class="dt-length-wrapper"></div>
          </div>
          <!-- Action Buttons -->
          <div class="d-flex align-items-center gap-2">
            <div id="export-buttons"></div>
            <a href="#" id="add-new-user-btn" class="btn btn-primary btn-sm waves-effect waves-light">
              <i class="bx bx-plus me-1"></i>
              <span class="d-none d-sm-inline-block">Add User</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body pt-0">
      <div class="table-responsive">
        <table id="users-table" class="table table-hover table-sm">
          <thead class="table-light">
            <tr>
              <th class="text-center" style="width: 60px;">
                <i class="bx bx-hash text-muted"></i>
              </th>
              <th>
                <i class="bx bx-user me-1 text-primary"></i>Username
              </th>
              <th>
                <i class="bx bx-envelope me-1 text-primary"></i>Email
              </th>
              <th>
                <i class="bx bx-calendar me-1 text-primary"></i>Created
              </th>
              <th>
                <i class="bx bx-time me-1 text-primary"></i>Updated
              </th>
              <th class="text-center" style="width: 80px;">
                <i class="bx bx-cog text-muted"></i>
              </th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
            <tr>
              <td class="text-center">
                <span class="badge bg-light text-dark fw-medium">{{ $loop->iteration }}</span>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-2">
                    <span class="avatar-initial rounded-circle bg-primary text-white">
                      {{ strtoupper(substr($user->username, 0, 1)) }}
                    </span>
                  </div>
                  <div>
                    <span class="fw-medium">{{ $user->username }}</span>
                  </div>
                </div>
              </td>
              <td>
                <span class="text-truncate d-block" style="max-width: 200px;">{{ $user->email }}</span>
              </td>
              <td>
                <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                <br>
                <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
              </td>
              <td>
                <small class="text-muted">{{ $user->updated_at->format('M d, Y') }}</small>
                <br>
                <small class="text-muted">{{ $user->updated_at->format('H:i') }}</small>
              </td>
              <td class="text-center">
                <div class="dropdown">
                  <button type="button" class="btn btn-icon btn-outline-secondary btn-sm dropdown-toggle hide-arrow waves-effect waves-light"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end">
                    <h6 class="dropdown-header">Actions</h6>
                    <a class="dropdown-item" href="javascript:void(0);">
                      <i class="bx bx-show me-2"></i>View
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);">
                      <i class="bx bx-edit-alt me-2"></i>Edit
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="javascript:void(0);">
                      <i class="bx bx-trash me-2"></i>Delete
                    </a>
                  </div>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-5">
                <div class="d-flex flex-column align-items-center">
                  <i class="bx bx-user-x display-4 text-muted mb-3"></i>
                  <h6 class="text-muted">No Users Found</h6>
                  <p class="text-muted small mb-0">There are no users to display at the moment.</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Initialize DataTable with enhanced configuration
    var table = $('#users-table').DataTable({
      responsive: true,
      pageLength: 10,
      lengthMenu: [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, "All"]
      ],
      dom: '<"d-flex justify-content-between align-items-center mb-3"<"dt-length-wrapper"l><"dt-search-wrapper"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"dataTables_info"i><"dataTables_paginate"p>>',
      language: {
        search: "",
        searchPlaceholder: "Search users...",
        lengthMenu: "_MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "No entries to show",
        infoFiltered: "(filtered from _MAX_ total entries)",
        emptyTable: "No users available",
        zeroRecords: "No matching users found",
        paginate: {
          previous: '<i class="bx bx-chevron-left"></i>',
          next: '<i class="bx bx-chevron-right"></i>',
          first: '<i class="bx bx-chevrons-left"></i>',
          last: '<i class="bx bx-chevrons-right"></i>'
        }
      },
      order: [
        [0, 'asc']
      ],
      columnDefs: [{
          targets: [0, 5],
          orderable: false
        },
        {
          targets: [3, 4],
          type: 'date'
        }
      ],
      drawCallback: function() {
        // Add custom styling after each draw
        $('.dataTables_paginate .pagination').addClass('pagination-sm');
      }
    });

    // Enhanced Export Buttons
    var exportButtons = new $.fn.dataTable.Buttons(table, {
      buttons: [{
        extend: 'collection',
        className: 'btn btn-outline-primary btn-sm dropdown-toggle waves-effect waves-light',
        text: '<i class="bx bx-export me-1"></i> Export',
        fade: 300,
        buttons: [{
            extend: 'print',
            text: '<i class="bx bx-printer me-2"></i>Print',
            className: 'dropdown-item',
            title: 'Users Data - ' + new Date().toLocaleDateString(),
            messageTop: 'Users Management System - Generated on ' + new Date().toLocaleString(),
            exportOptions: {
              columns: [1, 2, 3, 4] // Exclude NO and Actions columns
            }
          },
          {
            extend: 'csv',
            text: '<i class="bx bx-file me-2"></i>CSV',
            className: 'dropdown-item',
            filename: 'users_data_' + new Date().toISOString().slice(0, 10),
            exportOptions: {
              columns: [1, 2, 3, 4]
            }
          },
          {
            extend: 'excel',
            text: '<i class="bx bx-file me-2"></i>Excel',
            className: 'dropdown-item',
            filename: 'users_data_' + new Date().toISOString().slice(0, 10),
            title: 'Users Data',
            exportOptions: {
              columns: [1, 2, 3, 4]
            }
          },
          {
            extend: 'pdf',
            text: '<i class="bx bx-file me-2"></i>PDF',
            className: 'dropdown-item',
            filename: 'users_data_' + new Date().toISOString().slice(0, 10),
            title: 'Users Data',
            orientation: 'landscape',
            exportOptions: {
              columns: [1, 2, 3, 4]
            }
          },
          {
            extend: 'copy',
            text: '<i class="bx bx-copy me-2"></i>Copy',
            className: 'dropdown-item',
            exportOptions: {
              columns: [1, 2, 3, 4]
            }
          }
        ]
      }]
    });

    // Move buttons to designated containers
    exportButtons.container().appendTo('#export-buttons');

    // Custom styling for DataTable elements
    $('.dt-search-wrapper input[type="search"]').addClass('form-control form-control-sm').attr('placeholder', 'Search users...');
    $('.dt-length-wrapper select').addClass('form-select form-select-sm');

    // Style pagination
    $('.dataTables_paginate').addClass('d-flex justify-content-end');
    $('.dataTables_info').addClass('text-muted small');

    // Add loading state
    table.on('processing.dt', function(e, settings, processing) {
      if (processing) {
        $('.table-responsive').append('<div class="dt-loading-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
      } else {
        $('.dt-loading-overlay').remove();
      }
    });

    // Enhanced Add New User button functionality
    $('#add-new-user-btn').on('click', function(e) {
      e.preventDefault();
      // Add smooth transition effect
      $(this).addClass('btn-loading');

      // Simulate loading (replace with actual navigation)
      setTimeout(function() {
        // Replace with actual route
        // window.location.href = "{{ route('users.create') }}";
        alert('Navigate to Add User page');
        $('#add-new-user-btn').removeClass('btn-loading');
      }, 500);
    });

    // Add row click functionality for better UX
    $('#users-table tbody').on('click', 'tr', function(e) {
      if (!$(e.target).closest('.dropdown, .btn').length) {
        $(this).toggleClass('table-active');
      }
    });

    // Add tooltips to action buttons
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Custom search enhancement
    $('#users-table_filter input').on('keyup', function() {
      var value = this.value.toLowerCase();
      if (value === '') {
        $('.empty-state').hide();
      } else {
        var visibleRows = table.rows({
          search: 'applied'
        }).count();
        if (visibleRows === 0) {
          if (!$('.empty-state').length) {
            $('#users-table tbody').append(`
                                <tr class="empty-state">
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bx bx-search-alt-2 display-4 text-muted mb-2"></i>
                                        <h6 class="text-muted">No results found</h6>
                                        <p class="text-muted small">Try adjusting your search terms</p>
                                    </td>
                                </tr>
                            `);
          }
        } else {
          $('.empty-state').remove();
        }
      }
    });
  });

  // Add custom CSS for loading states and animations
  $('<style>')
    .prop('type', 'text/css')
    .html(`
                .btn-loading {
                    position: relative;
                    color: transparent !important;
                }
                .btn-loading::after {
                    content: "";
                    position: absolute;
                    width: 16px;
                    height: 16px;
                    top: 50%;
                    left: 50%;
                    margin-left: -8px;
                    margin-top: -8px;
                    border: 2px solid #ffffff;
                    border-radius: 50%;
                    border-top-color: transparent;
                    animation: spin 1s ease-in-out infinite;
                }
                @keyframes spin {
                    to { transform: rotate(360deg); }
                }
                .dt-loading-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(255, 255, 255, 0.8);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 10;
                }
                .table-hover tbody tr:hover {
                    background-color: #f8f9fa !important;
                    transform: translateY(-1px);
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    transition: all 0.2s ease;
                }
                .table-active {
                    background-color: #e3f2fd !important;
                }
                .avatar {
                    width: 32px;
                    height: 32px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 12px;
                    font-weight: 600;
                }
                .dropdown-menu {
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    border: 1px solid #e9ecef;
                }
            `)
    .appendTo('head');
</script>
@endpush