<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DAFTAR STOK</h1>
        <div>
            {{-- <a href="{{ route('aset.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Aset</a> --}}
        </div>
    </div>
    <table class="w-full  border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">NAMA BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">KODE</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">MERK</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">JUMLAH</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">LOKASI</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $barang)
                <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6"></td>
                    <td class="py-3 px-6 font-semibold">
                        {{ $barang->nama }}
                    </td>
                    <td class="py-3 px-6 font-semibold">
                        {{ $barang->kode_barang }}
                    </td>
                    <td class="py-3 px-6">
                        {{-- Check if there are stock entries for this barang --}}
                        @foreach ($stoks[$barang->id] as $stok)
                            <div>
                                {{ $stok->merkStok->nama }}
                            </div>
                        @endforeach
                    </td>
                    <td class="py-3 px-6">
                        @foreach ($stoks[$barang->id] as $stok)
                            <div>
                                {{ $stok->jumlah }}
                                {{ $stok->merkStok->barangStok->satuanBesar->nama }}
                            </div>
                        @endforeach
                    </td>
                    <td class="py-3 px-6">
                        @foreach ($stoks[$barang->id] as $stok)
                            <div>
                                {{ $stok->lokasiStok->nama }}
                            </div>
                        @endforeach
                    </td>
                    <td class="py-3 px-6">
                        <a href="{{ route('stok.show', ['stok' => $barang->id]) }}"
                            class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                            data-tooltip-target="tooltip-stok-{{ $barang->id }}">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <div id="tooltip-stok-{{ $barang->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat Detail Stok
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
            @empty
                {{-- <tr>
                    <td colspan="7" class="text-center">Tidak ada data barang yang memiliki stok</td>
                </tr> --}}
            @endforelse

        </tbody>
    </table>
</x-body>
