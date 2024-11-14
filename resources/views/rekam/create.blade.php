<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Tambah Kontrak</h1>
        <div>
            {{-- <a href="{{ route('kontrak-vendor-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a> --}}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div>
            <x-card title="Pilih Vendor">
                <livewire:vendor-kontrak-form :vendors="$vendors" />
            </x-card>
        </div>
        <div>
            <x-card title="DOKUMEN kontrak">
                <livewire:upload-surat-kontrak />
            </x-card>
        </div>
    </div>
    <livewire:kontrak-list-form />

</x-body>
