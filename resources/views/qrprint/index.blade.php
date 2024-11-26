<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">CETAK QR-CODE</h1>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-3">
        <!-- Kolom Kiri -->
        <div>
            <x-card title="Pilih Aset" class="mb-3">
                <livewire:pilih-aset />
            </x-card>
        </div>

        <!-- Kolom Kanan -->
        <div>
            <x-card title="Daftar Cetak QR-Code" class="mb-3">
                <livewire:daftar-cetak />
            </x-card>
        </div>
    </div>
</x-body>
