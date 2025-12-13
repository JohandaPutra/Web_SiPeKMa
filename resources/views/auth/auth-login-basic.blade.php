@extends('layouts/blankLayout')

@section('title', 'Login - SIPEKMA')

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="row w-100">
      <!-- Panel Login -->
      <div class="col-lg-6 col-md-12 mb-4">
        <div class="card h-100">
          <div class="card-body p-4">
           <div class="text-center mb-4 d-flex align-items-center justify-content-center flex-wrap">
              <img src="{{ asset('assets/img/icons/brands/Logo-UNJA.png') }}" alt="SIPEKMA Logo" height="100" class="me-3">
              <div class="text-center">
                <h1 class="m-0 text-primary fw-bold">SIPEKMA</h1>
                <p class="m-0 text-muted">Sistem Inforamsi Pengelolaan Himpunan Mahasiswa</p>
              </div>
            </div>
            <form id="formAuthentication" method="POST" action="{{ route('login') }}">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Username</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan username Anda" required autofocus>
              </div>
              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" placeholder="********" required>
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                  <label class="form-check-label" for="remember-me">Ingat saya</label>
                </div>
                <a href="{{ url('auth/forgot-password-basic') }}">Lupa Password?</a>
              </div>
              <div class="mb-3">
                <button type="submit" class="btn btn-primary d-grid w-100">LOGIN</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Panel Informasi -->
      <div class="col-lg-6 d-none d-lg-block">
        <div class="card h-100 text-center bg-primary text-white d-flex align-items-center justify-content-center p-4">
          <div>
            <img src="{{ asset('assets/img/icons/brands/Logo-Sipekma.png') }}" alt="swan" height="220"  class="mb-2 rounded-circle">
            <h4 class="fw-bold mb-2">Saling Terhubung</h4>
            <p class="mb-0 mx-5 ">SIPEKMA adalah platform atau aplikasi yang dirancang untuk memfasilitasi dan mengelola proses pengajuan kegiatan yang dilakukan mahasiswa di lingkungan akademik atau kampus.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
