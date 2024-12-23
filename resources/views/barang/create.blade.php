<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">EDIT BARANG</h1>
        <div>
            @if ($tipe == 'stok')
                <a href="{{ route('barang.show', ['barang' => $stok->barang_id ?? $id]) }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Kembali
                </a>
            @else
                <a href="{{ route('barang.show', ['barang' => $id]) }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Kembali
                </a>
            @endif
        </div>

    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="border p-4 rounded-lg shadow-md">
            <livewire:update-barang-stok :tipe="$tipe" :id="$id" />
        </div>
    </div>
</x-body>
