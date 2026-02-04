<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3 z-index-toast">
    @if (session('success'))
        <div class="bs-toast toast fade bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class='bx bx-bell me-2'></i>
                <div class="me-auto fw-medium">Berhasil</div>
                <small>now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bs-toast toast fade bg-danger" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="5000">
            <div class="toast-header bg-white">
                <i class='bx bx-x-circle me-2 text-danger'></i>
                <div class="me-auto fw-medium text-danger">Error</div>
                <small class="text-muted">now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-white">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="bs-toast toast fade bg-warning" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="5000">
            <div class="toast-header bg-white">
                <i class='bx bx-error me-2 text-warning'></i>
                <div class="me-auto fw-medium text-warning">Peringatan</div>
                <small class="text-muted">now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-white">
                {{ session('warning') }}
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="bs-toast toast fade bg-info" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="5000">
            <div class="toast-header bg-white">
                <i class='bx bx-info-circle me-2 text-info'></i>
                <div class="me-auto fw-medium text-info">Informasi</div>
                <small class="text-muted">now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-white">
                {{ session('info') }}
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto initialize all toasts
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            var toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
            });

            // Show all toasts
            toastList.forEach(function(toast) {
                toast.show();
            });
        });
    </script>
@endpush
