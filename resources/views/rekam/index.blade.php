<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Kontrak Vendor</h1>
        <div>
            <a href="{{ route('kontrak-vendor-stok.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Rekam Kontrak Baru</a>
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA VENDOR</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NOMOR KONTRAK</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KATEGORI BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TANGGAL KONTRAK</th>
                <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold">DETAIL TRANSAKSI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">JENIS PENGADAAN</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">METODE PENGADAAN</th> --}}
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">STATUS</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedTransactions as $vendorId => $kontrakGroups)
                @foreach ($kontrakGroups as $kontrakId => $transactions)
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="py-3 px-6"></td> <!-- Displays the row number -->
                        <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800">
                                {{ $transactions->first()[0]->vendorStok->nama ?? 'Unknown Vendor' }}</p>
                        </td>
                        <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800">
                                {{ $transactions->first()[0]->kontrakStok->nomor_kontrak }}</p>
                        </td>
                        <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800">
                                {{ $transactions->first()[0]->merkStok->barangStok->jenisStok->nama }}</p>
                        </td>
                        <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800">
                                {{ $kontrakId ? date('j F Y', App\Models\KontrakVendorStok::find($kontrakId)->tanggal_kontrak) : '---' }}
                            </p>
                        </td>
                        <td class="py-3 px-6">
                            <table class="w-full text-sm border-spacing-y-2">
                                <thead class="text-primary-800">
                                    <tr>
                                        <th class="w-1/3">Barang</th>
                                        <th class="w-1/3">Spesifikasi</th>
                                        <th class="w-1/3">Jumlah</th>
                                        {{-- <th class="w-1/3">Tanggal</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr class="border-b-[1px] border-primary-800">
                                            <td>{{ $transaction[0]->merkStok->barangStok->nama }}</td>
                                            <td>{{ $transaction[0]->merkStok->nama ?? 'Unknown Merk' }}</td>
                                            <td>{{ $transaction[0]->jumlah }}
                                                {{ $transaction[0]->merkStok->barangStok->satuanBesar->nama }}</td>
                                            {{-- <td>{{ date('j F Y', $transaction[0]->tanggal) }}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>

                        <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800 text-center">
                                {{ $transactions->first()[0]->kontrakStok->type ? 'Pengadaan' : 'Penggunaan Langsung' }}
                            </p>
                        </td>
                        {{-- <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800 text-center">
                                {{ $transactions->first()[0]->kontrakStok->metodePengadaan->nama }}

                            </p>
                        </td> --}}
                        <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800 text-center">
                                <span
                                    class="bg-{{ $transactions->first()[0]->kontrakStok->status === null ? 'warning' : ($transactions->first()[0]->kontrakStok->status ? 'success' : 'danger') }}-600 text-{{ $transactions->first()[0]->kontrakStok->status === null ? 'warning' : ($transactions->first()[0]->kontrakStok->status ? 'success' : 'danger') }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{ $transactions->first()[0]->kontrakStok->status === null ? 'diproses' : ($transactions->first()[0]->kontrakStok->status ? 'disetujui' : 'ditolak') }}</span>
                            </p>
                        </td>

                        <td class="py-3 px-6 text-center">
                            <a href="{{ route('kontrak-vendor-stok.show', ['kontrak_vendor_stok' => $kontrakId]) }}"
                                class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-kontrak-{{ $transactions->first()[0]->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <div id="tooltip-kontrak-{{ $transactions->first()[0]->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Lihat Detail Kontrak
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</x-body>
