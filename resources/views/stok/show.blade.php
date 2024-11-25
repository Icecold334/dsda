<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL BARANG</h1>
        <div>
            <a href="{{ route('stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 mb-3">
        <x-card title="Data umum">
            <table class="w-full">
                <tr class="font-semibold">
                    <td>Nama Barang</td>
                    <td>{{ $barang->nama }}</td>
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
                    <th>Stok</th>
                    <th>Lokasi</th>
                    <th>Bagian</th>
                    <th>Posisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stok as $item)
                    <tr class=" border-b-2 border-primary-500 ">
                        <td>
                            <table class="w-full">
                                <tr>
                                    <td class="w-1/3 px-3  {{ $item->merkStok->nama ?? 'text-center' }}">
                                        {{ $item->merkStok->nama ?? '-' }}</td>
                                    <td
                                        class="w-1/3 px-3 border-x-2 border-primary-500 {{ $item->merkStok->tipe ?? 'text-center' }}">
                                        {{ $item->merkStok->tipe ?? '-' }}</td>
                                    <td class="w-1/3 px-3 {{ $item->merkStok->ukuran ?? 'text-center' }}">
                                        {{ $item->merkStok->ukuran ?? '-' }}</td>
                                </tr>
                            </table>
                        </td>
                        <td class=" font-semibold">{{ $item->jumlah }}
                            {{ $item->merkStok->barangStok->satuanBesar->nama }}
                        </td>
                        <td>{{ $item->lokasiStok->nama }}</td>
                        <td class="{{ $item->barangStok == null ? 'text-center' : '' }}">
                            {{ $item->bagianStok->nama ?? '---' }}</td>
                        <td class="{{ $item->posisiStok == null ? 'text-center' : '' }}">
                            {{ $item->posisiStok->nama ?? '---' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
</x-body>
