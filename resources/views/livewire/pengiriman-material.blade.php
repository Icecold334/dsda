<div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">FORM BARANG DATANG</h1>
        <div>
            {{-- <a href="{{ route('pengiriman-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
            --}}
        </div>
    </div>

    <div class="grid grid-col-1 lg:grid-cols-2 gap-6 mb-3">
        {{-- <div> --}}
            <x-card title="Data Umum" class="mb-3 ">
                <livewire:vendor-kirim-form />
            </x-card>
            <x-card title='Unggah Surat jalan'>
                <livewire:upload-surat-jalan-material>
            </x-card>
            {{--
        </div> --}}
    </div>
    <x-card title="Detail item barang">
        <livewire:list-pengiriman-form>
            <livewire:list-pengiriman-material />
    </x-card>
</div>