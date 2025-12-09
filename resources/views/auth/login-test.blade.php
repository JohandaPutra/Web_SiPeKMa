@extends('layouts/blankLayout')

@section('title', 'Login - Testing')

@section('page-style')
<style>
.user-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}
</style>
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Login Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-text demo text-body fw-bold">SIPEKMA</span>
                        </a>
                    </div>

                    <h4 class="mb-2 text-center">Quick Login - Testing Mode 🧪</h4>
                    <p class="mb-4 text-center text-muted">Pilih user untuk login langsung</p>

                    @include('_partials/toast')

                    <!-- Quick Login Users -->
                    <div class="row g-3 mb-4">
                        @foreach($users as $user)
                        <div class="col-md-6">
                            <form action="{{ route('quick-login') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-outline-primary w-100 user-card p-3 text-start">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class='bx bx-user'></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $user->username }}</h6>
                                            <small class="text-muted">{{ $user->role->display_name }}</small>
                                            @if($user->prodi)
                                            <br><small class="badge bg-label-info">{{ $user->prodi->kode_prodi }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>

                    <div class="divider my-4">
                        <div class="divider-text">atau login manual</div>
                    </div>

                    <!-- Regular Login Form -->
                    <form id="formAuthentication" class="mb-3" action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" placeholder="Enter your email"
                                   value="{{ old('email') }}" autofocus>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                       aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Default password: <code>password</code></small>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                        </div>
                    </form>

                    <div class="alert alert-info">
                        <strong>Info Testing:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Semua user password: <strong>password</strong></li>
                            <li>Klik card user untuk quick login</li>
                            <li>Test role: Hima, Pembina, Kaprodi, Wadek III</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
