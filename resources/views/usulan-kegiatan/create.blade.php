@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Usulan Kegiatan')

@section('content')
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h4 class="fw-bold text-primary mb-1">
                <i class="bx bx-plus-circle"></i> Tambah Usulan Kegiatan
            </h4>
            <p class="text-muted mb-0">Form pengajuan kegiatan baru</p>
        </div>
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard-analytics') }}" class="text-muted">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('usulan-kegiatan.index') }}" class="text-muted">Usulan Kegiatan</a>
                </li>
                <li class="breadcrumb-item active">Tambah Usulan</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <!-- Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom mb-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper icon-wrapper-sm bg-gradient-primary me-3">
                            <i class="bx bx-edit text-white icon-md"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-dark fw-semibold">Form Pengajuan</h5>
                            <p class="mb-0 small text-muted">Lengkapi data usulan kegiatan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('usulan-kegiatan.store') }}" method="POST">
                        @csrf

                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Informasi Dasar
                                </h6>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                    id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                                    placeholder="Contoh: Workshop Web Development">
                                @error('nama_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="deskripsi_kegiatan" class="form-label">Deskripsi Kegiatan <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('deskripsi_kegiatan') is-invalid @enderror" id="deskripsi_kegiatan"
                                    name="deskripsi_kegiatan" rows="4" placeholder="Jelaskan tujuan, manfaat, dan target peserta kegiatan">{{ old('deskripsi_kegiatan') }}</textarea>
                                @error('deskripsi_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_kegiatan') is-invalid @enderror"
                                    id="jenis_kegiatan" name="jenis_kegiatan">
                                    <option value="">Pilih Jenis Kegiatan</option>
                                    <option value="seminar" {{ old('jenis_kegiatan') == 'seminar' ? 'selected' : '' }}>
                                        Seminar</option>
                                    <option value="workshop" {{ old('jenis_kegiatan') == 'workshop' ? 'selected' : '' }}>
                                        Workshop</option>
                                    <option value="pelatihan" {{ old('jenis_kegiatan') == 'pelatihan' ? 'selected' : '' }}>
                                        Pelatihan</option>
                                    <option value="lomba" {{ old('jenis_kegiatan') == 'lomba' ? 'selected' : '' }}>Lomba
                                    </option>
                                    <option value="lainnya" {{ old('jenis_kegiatan') == 'lainnya' ? 'selected' : '' }}>
                                        Lainnya</option>
                                </select>
                                @error('jenis_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tempat_kegiatan" class="form-label">Tempat Kegiatan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tempat_kegiatan') is-invalid @enderror"
                                    id="tempat_kegiatan" name="tempat_kegiatan" value="{{ old('tempat_kegiatan') }}"
                                    placeholder="Contoh: Aula Universitas">
                                @error('tempat_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Jadwal & Pendanaan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="bx bx-calendar me-1"></i>
                                    Jadwal & Pendanaan
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                    id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror"
                                    id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                                @error('tanggal_akhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jenis_pendanaan" class="form-label">Jenis Pendanaan <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_pendanaan') is-invalid @enderror"
                                    id="jenis_pendanaan" name="jenis_pendanaan">
                                    <option value="">Pilih Jenis Pendanaan</option>
                                    <option value="mandiri" {{ old('jenis_pendanaan') == 'mandiri' ? 'selected' : '' }}>
                                        Mandiri</option>
                                    <option value="sponsor" {{ old('jenis_pendanaan') == 'sponsor' ? 'selected' : '' }}>
                                        Sponsor</option>
                                    <option value="hibah" {{ old('jenis_pendanaan') == 'hibah' ? 'selected' : '' }}>Hibah
                                    </option>
                                    <option value="internal" {{ old('jenis_pendanaan') == 'internal' ? 'selected' : '' }}>
                                        Internal</option>
                                </select>
                                @error('jenis_pendanaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status_kegiatan" class="form-label">Status Kegiatan</label>
                                <select class="form-select @error('status_kegiatan') is-invalid @enderror"
                                    id="status_kegiatan" name="status_kegiatan">
                                    <option value="draft"
                                        {{ old('status_kegiatan', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="dikirim"
                                        {{ old('status_kegiatan') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="review" {{ old('status_kegiatan') == 'review' ? 'selected' : '' }}>
                                        Review</option>
                                    <option value="disetujui" {{ old('status_kegiatan') == 'disetujui' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="ditolak" {{ old('status_kegiatan') == 'ditolak' ? 'selected' : '' }}>
                                        Ditolak</option>
                                </select>
                                @error('status_kegiatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('usulan-kegiatan.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-1"></i>
                                Kembali
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i>
                                    Simpan Usulan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Form -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Function to show dynamic toast
        function showToast(type, title, message) {
            const toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) return;

            const toastId = 'toast-' + Date.now();
            const iconMap = {
                'success': 'bx-check-circle',
                'error': 'bx-x-circle',
                'warning': 'bx-error',
                'info': 'bx-info-circle'
            };

            const toastHTML = `
                <div id="${toastId}" class="bs-toast toast fade bg-${type}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="toast-header bg-white">
                        <i class='bx ${iconMap[type]} me-2 text-${type}'></i>
                        <div class="me-auto fw-medium text-${type}">${title}</div>
                        <small class="text-muted">Sekarang</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body text-white">
                        ${message}
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();

            // Remove element after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        $(document).ready(function() {
            // Validasi tanggal
            $('#tanggal_mulai').on('change', function() {
                var startDate = $(this).val();
                $('#tanggal_akhir').attr('min', startDate);

                var endDate = $('#tanggal_akhir').val();
                if (endDate && endDate < startDate) {
                    $('#tanggal_akhir').val(startDate);
                }
            });

            $('#tanggal_akhir').on('change', function() {
                var endDate = $(this).val();
                var startDate = $('#tanggal_mulai').val();

                if (startDate && endDate < startDate) {
                    // Show toast warning instead of alert
                    showToast('warning', 'Peringatan', 'Tanggal akhir tidak boleh sebelum tanggal mulai!');
                    $(this).val(startDate);
                }
            });

            // Set minimum date to today
            var today = new Date().toISOString().split('T')[0];
            $('#tanggal_mulai').attr('min', today);
        });
    </script>
@endpush
