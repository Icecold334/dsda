<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Barang Belum Berkontrak</h1>
        <div>
            {{-- <a href="{{ route('kontrak-vendor-stok.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Rekam Kontrak Baru</a> --}}
        </div>
    </div>
    {{-- @dump($transaksiDarurat) --}}
    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA VENDOR</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KONTRAK ID</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DETAIL TRANSAKSI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $group)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6"></td> <!-- Displays the row number -->
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800">
                            {{ $group['transactions']->first()->vendorStok->nama ?? 'Unknown Vendor' }}</p>
                    </td>
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800">
                            {{ $group['kontrak_id'] !== 'null' ? date('j F Y', $group['transactions']->first()->kontrakRetrospektifStok->tanggal_kontrak) : '---' }}
                        </p>
                    </td>
                    <td class="py-3 px-6">
                        <table class="w-full text-sm border-spacing-y-2">
                            <thead class="text-primary-800">
                                <tr>
                                    <th class="w-1/3">Merk</th>
                                    <th class="w-1/3">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group['transactions'] as $item)
                                    <tr class="border-b-[1px] border-primary-800">
                                        <td>{{ $item->merkStok->nama }}</td>
                                        <td>{{ $item->jumlah }} {{ $item->merkStok->barangStok->satuanBesar->nama }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('kontrak-vendor-stok.show', ['kontrak_vendor_stok' => $group['vendor_id']]) }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>





</x-body>
