<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 uppercase">BUAT ADENDUM RAB</h1>
        <div>
            <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                href="{{ route('rab.show', $rabId) }}">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>
    </div>

    <livewire:adendum-rab :rabId="$rabId" />

    @push('scripts')
    <script>
        // Inline fallback listener to ensure SweetAlert toast fires on this page
        (() => {
            const bindHandler = () => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                const handler = (params) => {
                    const msg = params && typeof params === 'object' && 'message' in params
                        ? params.message
                        : Array.isArray(params) && params.length > 0
                            ? (params[0]?.message ?? params[0])
                            : 'Terjadi kesalahan';

                    // #region agent log
                    fetch('http://127.0.0.1:7242/ingest/43b03c8c-2c2c-459e-8e69-b6f08dd9dc05', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            sessionId: 'debug-session',
                            runId: 'post-fix-frontend',
                            hypothesisId: 'H8',
                            location: 'rab/adendum.blade.php:swal-error',
                            message: 'fallback handler fired',
                            data: { params, resolvedMessage: msg },
                            timestamp: Date.now()
                        })
                    }).catch(() => {});
                    // #endregion

                    Toast.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: typeof msg === 'string' ? msg : 'Jumlah baru tidak boleh kurang dari jumlah yang telah digunakan.',
                    });
                };

                if (window.Livewire) {
                    window.Livewire.on('swal-error', handler);
                }
                window.addEventListener('swal-error', (e) => handler(e.detail || e));
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', bindHandler, { once: true });
            } else {
                bindHandler();
            }
        })();
    </script>
    @endpush
</x-body>

