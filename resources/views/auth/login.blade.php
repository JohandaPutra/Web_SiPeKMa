@extends('layouts/blankLayout')

@section('title', 'Login - SIPEKMA')

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
  <style>
    body {
      background: url('{{ asset("assets/img/illustrations/baground-login.png") }}') no-repeat center center fixed;
      background-size: cover;
      position: relative;
      min-height: 100vh;.p
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(10, 25, 47, 0.75);
      z-index: 0;
    }

    .authentication-wrapper {
      position: relative;
      z-index: 1;
    }

    .card {
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      border: none;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.98);
    }

    .card.bg-primary {
      background: linear-gradient(135deg, rgba(105, 108, 255, 0.95) 0%, rgba(76, 78, 217, 0.95) 100%) !important;
      backdrop-filter: blur(10px);
    }
  </style>
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="row w-100">

      <!-- Panel Login -->
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card h-100">
          <div class="card-body p-4">
            <!-- Logo & Title -->
            <div class="text-center mb-4 d-flex align-items-center justify-content-center flex-wrap">
              <img src="{{ asset('assets/img/icons/Logo-UNJA.png') }}" alt="Logo UNJA" height="100" class="me-3">
              <div class="text-center">
                <h1 class="m-0 text-primary fw-bold">SIPEKMA</h1>
                <p class="m-0 text-muted">Sistem Informasi Pengelolaan Kegiatan Mahasiswa</p>
              </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Login Form -->
            <form id="formAuthentication" method="POST" action="{{ route('login.post') }}">
              @csrf

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email"
                       placeholder="Masukkan Email Anda"
                       value="{{ old('email') }}"
                       required autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                         name="password"
                         placeholder="********"
                         required>
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
                @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember" name="remember">
                  <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <a href="#">Lupa Password?</a>
              </div>

              <div class="mb-3">
                <button type="submit" class="btn btn-primary d-grid w-100">LOGIN</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Panel Informasi - Hidden on mobile -->
      <div class="col-lg-6 d-none d-lg-block">
        <div class="card h-100 text-center bg-primary text-white d-flex align-items-center justify-content-center p-4">
          <div>
            <img src="{{ asset('assets/img/icons/Logo-Sipekma.png') }}" alt="SIPEKMA Logo" height="220" class="mb-2 rounded-circle">
            <h4 class="fw-bold mb-2">Saling Terhubung</h4>
            <p class="mb-0 mx-5">SIPEKMA adalah platform atau aplikasi yang dirancang untuk memfasilitasi dan mengelola proses pengajuan kegiatan yang dilakukan mahasiswa di lingkungan akademik atau kampus.</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
