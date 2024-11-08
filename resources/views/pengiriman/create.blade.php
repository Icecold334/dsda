<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">FORM BARANG DATANG</h1>
        <div>
            <a href="{{ route('pengiriman-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-2">
        <div>
            <x-card title="Data Umum" class="mb-3">
                <livewire:vendor-kontrak-form :vendors="$vendors" />
            </x-card>
            <x-card title="Daftar Barang">
                <livewire:list-kontrak-barang>
            </x-card>
        </div>
    </div>
</x-body>
