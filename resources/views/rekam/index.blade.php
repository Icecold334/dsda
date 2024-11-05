<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Kontrak Vendor</h1>
        <div>
            <a href="{{ route('kontrak-vendor-stok.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Rekam Kontrak Baru</a>
        </div>
    </div>

    <table class="w-full  border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold ">NAMA VENDOR</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">TANGGAL KONTRAK</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-left font-semibold">MERK & TIPE</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">PENYUSUTAN</th>
                @can('history_view')
                    <th class="py-3 px-6 bg-primary-950 text-left font-semibold">RIWAYAT TERAKHIR</th>
                @endcan --}}
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kontrakVendors as $kontrak)
                <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl ">

                    <td class="py-3 px-6">

                    </td>
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800">{{ $kontrak->vendorStok->nama }}</p>
                        {{-- <div>
                            <p class="text-sm text-gray-500">{{ $kontrak ?? 'Tidak Berkategori' }}</p>
                        </div> --}}
                    </td>
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800">{{ date('j F Y', $kontrak->tanggal_kontrak) }}</p>
                        {{-- <div>
                            <p class="text-sm text-gray-500">{{ $kontrak ?? 'Tidak Berkategori' }}</p>
                        </div> --}}
                    </td>
                    <td class="py-3 px-6">
                        <a href="{{ route('kontrak-vendor-stok.show', ['kontrak_vendor_stok' => $kontrak->vendorStok->id]) }}"
                            class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                            data-tooltip-target="tooltip-kontrak-{{ $kontrak->vendorStok->id }}">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <div id="tooltip-kontrak-{{ $kontrak->vendorStok->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat Detail Kontrak
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-body>
