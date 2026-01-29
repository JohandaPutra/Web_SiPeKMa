@extends('layouts/contentNavbarLayout')

@section('title', 'Add New User')

@section('content')
    <div class="user-management-page">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="mb-3 mb-md-0">
                <h4 class="fw-bold text-primary mb-1">
                    <i class="bx bx-user-plus me-2"></i>Add New User
                </h4>
                <p class="text-muted mb-0">Create a new user account with required details</p>
            </div>
            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb breadcrumb-style1 mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('users.index') }}" class="text-muted">Users</a>
                    </li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>
        </div>

        <!-- Alert Messages -->
        @include('_partials.alerts')

        <!-- Create User Form -->
        <div class="row">
            <div class="col-xl-8 col-lg-10 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded-circle bg-primary">
                                    <i class="bx bx-user text-white"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="card-title fw-semibold mb-0 text-primary">User Information</h5>
                                <p class="text-muted small mb-0">Fill in the details below to create a new user</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.store') }}" method="POST" id="createUserForm">
                            @csrf

                            <div class="row g-3">
                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-medium">
                                        <i class="bx bx-user me-1 text-primary"></i>Full Name
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
                                        placeholder="Enter full name (optional)">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">If left empty, username will be used as display name</div>
                                </div>

                                <!-- Username -->
                                <div class="col-md-6">
                                    <label for="username" class="form-label fw-medium">
                                        <i class="bx bx-at me-1 text-primary"></i>Username
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username" value="{{ old('username') }}"
                                        placeholder="Enter username" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Username must be unique and contain no spaces</div>
                                </div>

                                <!-- Email -->
                                <div class="col-12">
                                    <label for="email" class="form-label fw-medium">
                                        <i class="bx bx-envelope me-1 text-primary"></i>Email Address
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}"
                                        placeholder="Enter email address" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="col-md-6">
                                    <label for="role_id" class="form-label fw-medium">
                                        <i class="bx bx-shield me-1 text-primary"></i>Role
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('role_id') is-invalid @enderror"
                                        id="role_id" name="role_id" required>
                                        <option value="">-- Pilih Role --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Prodi -->
                                <div class="col-md-6">
                                    <label for="prodi_id" class="form-label fw-medium">
                                        <i class="bx bx-buildings me-1 text-primary"></i>Prodi
                                    </label>
                                    <select class="form-select @error('prodi_id') is-invalid @enderror"
                                        id="prodi_id" name="prodi_id">
                                        <option value="">-- Pilih Prodi (Opsional) --</option>
                                        @foreach ($prodis as $prodi)
                                            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                                {{ $prodi->nama_prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('prodi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Kosongkan jika role adalah Admin/Wadek III</div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-medium">
                                        <i class="bx bx-lock me-1 text-primary"></i>Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Enter password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bx bx-hide" id="toggleIcon"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Password must be at least 8 characters long</div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-medium">
                                        <i class="bx bx-lock-alt me-1 text-primary"></i>Confirm Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Confirm password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                            <i class="bx bx-hide" id="toggleIconConfirm"></i>
                                        </button>
                                    </div>
                                    <div id="passwordMatch" class="form-text"></div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('users.index') }}"
                                            class="btn btn-outline-secondary waves-effect">
                                            <i class="bx bx-arrow-back me-1"></i>Back to Users
                                        </a>
                                        <div class="d-flex gap-2">
                                            <button type="reset" class="btn btn-outline-warning waves-effect">
                                                <i class="bx bx-refresh me-1"></i>Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light"
                                                id="submitBtn">
                                                <i class="bx bx-plus me-1"></i>Create User
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const icon = $('#toggleIcon');

                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('bx-hide').addClass('bx-show');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('bx-show').addClass('bx-hide');
                }
            });

            // Toggle confirm password visibility
            $('#togglePasswordConfirm').on('click', function() {
                const passwordField = $('#password_confirmation');
                const icon = $('#toggleIconConfirm');

                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('bx-hide').addClass('bx-show');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('bx-show').addClass('bx-hide');
                }
            });

            // Password confirmation validation
            function checkPasswordMatch() {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();
                const matchDiv = $('#passwordMatch');

                if (confirmPassword === '') {
                    matchDiv.text('').removeClass('text-success text-danger');
                    return;
                }

                if (password === confirmPassword) {
                    matchDiv.text('✓ Passwords match').removeClass('text-danger').addClass('text-success');
                } else {
                    matchDiv.text('✗ Passwords do not match').removeClass('text-success').addClass('text-danger');
                }
            }

            $('#password, #password_confirmation').on('keyup', checkPasswordMatch);

            // Username validation (no spaces, lowercase)
            $('#username').on('input', function() {
                let value = $(this).val();
                // Remove spaces and convert to lowercase
                value = value.replace(/\s/g, '').toLowerCase();
                $(this).val(value);
            });

            // Form submission with loading state
            $('#createUserForm').on('submit', function() {
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true);
                submitBtn.html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Creating...'
                    );
            });

            // Auto-generate username from name (optional)
            $('#name').on('input', function() {
                const name = $(this).val();
                const usernameField = $('#username');

                // Only auto-generate if username is empty and name is provided
                if (name && usernameField.val() === '') {
                    const username = name.toLowerCase()
                        .replace(/\s+/g, '')
                        .replace(/[^a-z0-9]/g, '');
                    usernameField.val(username);
                }
            });

            // Form validation styling - make name optional
            $('input[required]').on('blur', function() {
                if ($(this).val() === '') {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Handle name field separately (not required)
            $('#name').on('blur', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-invalid is-valid');
                }
            });

            // Email validation
            $('#email').on('blur', function() {
                const email = $(this).val();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email && !emailRegex.test(email)) {
                    $(this).addClass('is-invalid');
                } else if (email) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });
        });
    </script>
@endpush
