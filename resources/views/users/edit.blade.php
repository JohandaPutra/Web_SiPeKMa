@extends('layouts/contentNavbarLayout')

@section('title', 'Edit User')

@section('content')
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold text-primary mb-1">
                <i class="bx bx-edit"></i> Edit User
            </h4>
            <p class="text-muted mb-0">Update informasi user</p>
        </div>
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('users.index') }}" class="text-muted">Users</a>
                </li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </nav>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="bx bx-edit"></i> Form Edit User
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role_id') is-invalid @enderror"
                                id="role_id" name="role_id" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prodi -->
                        <div class="mb-3">
                            <label for="prodi_id" class="form-label">Prodi</label>
                            <select class="form-select @error('prodi_id') is-invalid @enderror"
                                id="prodi_id" name="prodi_id">
                                <option value="">-- Pilih Prodi (Opsional) --</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id', $user->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Kosongkan jika role adalah Admin/Wadek III</div>
                        </div>

                        <hr class="my-4">

                        <!-- Password Section -->
                        <div class="alert alert-info mb-3">
                            <i class="bx bx-info-circle"></i> Kosongkan field password jika tidak ingin mengubah password
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimal 8 karakter (Opsional)</div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control"
                                id="password_confirmation" name="password_confirmation">
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-x"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Password confirmation validation
        $('#password, #password_confirmation').on('keyup', function() {
            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();

            if (password === '' && confirmPassword === '') {
                return; // Skip validation if both empty
            }

            if (password !== confirmPassword && confirmPassword !== '') {
                $('#password_confirmation').addClass('is-invalid');
            } else {
                $('#password_confirmation').removeClass('is-invalid');
            }
        });
    });
</script>
@endpush
