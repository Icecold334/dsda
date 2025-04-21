<div class="space-y-3">
    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
        <input type="date" wire:model.live="tanggal_masuk"
            class="mt-1 block w-full border rounded px-2 py-1 text-sm shadow-sm">
        @error('tanggal_masuk')
            <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Keluar</label>
        <input type="date" wire:model.live="tanggal_keluar"
            class="mt-1 block w-full border rounded px-2 py-1 text-sm shadow-sm">
        @error('tanggal_keluar')
            <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <button wire:click="simpan" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded text-sm">
        Simpan Tanggal
    </button>

    @push('scripts')
        <script type="module">
            document.addEventListener('success', function(e) {
                feedback('Berhasil!', e.detail[0],
                    'success')

            })
            document.addEventListener('error', function(e) {
                feedback('Gagal!', e.detail[0],
                    'error')

            })
        </script>
    @endpush
</div>
