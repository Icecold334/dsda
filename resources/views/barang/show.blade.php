<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL BARANG</h1>
        <div>
            <a href="{{ route('barang.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 mb-3">
        <x-card title="Data umum">
            <table class="w-full">
                <tr class="font-semibold">
                    <td>Nama Barang</td>
                    <td>{{ $barang->nama }}</td>
                    <td class="justify-end">
                        <a href="umum/{{ $barang->id }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-barang-{{ $barang['id'] }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <div id="tooltip-barang-{{ $barang['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Ubah Data
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
                <tr class="font-semibold">
                    <td>Kode Barang</td>
                    <td>{{ $barang->kode_barang }}</td>
                </tr>
                <tr class="font-semibold">
                    <td>Jenis Barang</td>
                    <td>{{ $barang->jenisStok->nama }}</td>
                </tr>
                <tr class="font-semibold">
                    <td>Deskripsi Barang</td>
                    <td>{{ $barang->deskripsi }}</td>
                </tr>
            </table>
        </x-card>
    </div>

    <x-card title="detail stok">
        <table class="w-full">
            <thead class="text-primary-600">
                <tr>
                    <th>Spesifikasi (Merk/Tipe/Ukuran)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barang->merkStok as $merk)
                    <tr class=" border-b-2 border-primary-500 ">
                        <td>
                            <table class="w-full">
                                <tr>
                                    <td class="w-1/3 px-3  {{ $merk->nama ?? ('-' ?? 'text-center') }}">
                                        {{ $merk->nama ?? ('-' ?? '-') }}</td>
                                    <td
                                        class="w-1/3 px-3 border-x-2 border-primary-500 {{ $merk->tipe ?? 'text-center' }}">
                                        {{ $merk->tipe ?? '-' }}</td>
                                    <td class="w-1/3 px-3 {{ $merk->ukuran ?? 'text-center' }}">
                                        {{ $merk->ukuran ?? '-' }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <a href="stok/{{ $merk->id }}"
                                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                                            data-tooltip-target="tooltip-merk-{{ $merk['id'] }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <div id="tooltip-merk-{{ $merk['id'] }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Ubah Data
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
</x-body>
