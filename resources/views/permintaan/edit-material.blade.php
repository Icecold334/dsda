<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Edit Permintaan Material</h1>
        <div>
            <a href="/permintaan/material"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali</a>
        </div>
    </div>

    {{-- Alert untuk status yang tidak bisa diedit --}}
    @if($permintaan->status !== null && $permintaan->status !== 4)
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Perhatian
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Permintaan dengan status "{{ $status['label'] }}" tidak dapat diedit. Hanya permintaan dengan
                            status "Draft" yang dapat diubah.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6">
        <div>
            <livewire:edit-form-permintaan-material :permintaan="$permintaan">
        </div>
        @if($permintaan->status !== 4)
            <div>
                <livewire:approval-material :permintaan="$permintaan">
            </div>
        @endif
    </div>

</x-body>