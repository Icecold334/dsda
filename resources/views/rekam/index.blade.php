<x-body>
    {{-- <div class="flex justify-between py-2 mb-3">


        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    });
                });
            </script>
        @endif

        <h1 class="text-2xl font-bold text-primary-900 ">Kontrak Vendor
            @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <div> --}}
            {{-- <button data-modal-target="tipe2" data-modal-toggle="tipe2"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                type="button">
                Menunggu Persetujuan
            </button> --}}
            {{-- <a href="{{ route('kontrak-vendor-stok.create') }}"
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
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">METODE PENGADAAN</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">STATUS</th> --}}
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedTransactions as $index => $transaction) --}}
                {{-- @foreach ($kontrakGroups as $kontrakId => $transaction) --}}
                {{-- <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6"></td> <!-- Displays the row number -->
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800">
                            {{ $transaction->vendorStok->nama ?? 'Unknown Vendor' }}</p>
                    </td>
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800">
                            {{ $transaction->nomor_kontrak }}</p>
                    </td>
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800">
                            {{ $transaction->transaksiStok->first()->merkStok->barangStok->jenisStok->nama }}</p>
                    </td>
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800">
                            {{ $transaction->tanggal_kontrak ? date('j F Y', $transaction->tanggal_kontrak) : '---' }}
                        </p>
                    </td>
                    <td class="py-3 px-6">
                        <table class="w-full text-sm border-spacing-y-2">
                            <thead class="text-primary-800">
                                <tr>
                                    <th class="w-1/3">Barang</th>
                                    <th class="w-1/3">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction->transaksiStok as $tran)
                                    <tr class="border-b-[1px] border-primary-800">
                                        <td class="border-r-4 px-2">
                                            {{ $tran->merkStok->barangStok->nama }}
                                        </td>
                                        <td class="px-2">{{ $tran->jumlah }}
                                            {{ $tran->merkStok->barangStok->satuanBesar->nama }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>

                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800 text-center">
                            {{ $transaction->metodePengadaan->nama }}
                        </p>
                    </td>

                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('kontrak-vendor-stok.show', ['kontrak_vendor_stok' => $transaction->id]) }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-kontrak-{{ $transaction->id }}">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <div id="tooltip-kontrak-{{ $transaction->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat Detail Kontrak
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr> --}}
                {{-- @endforeach --}}
            {{-- @endforeach
        </tbody>
    </table> --}}

    <div>
        <livewire:data-kontrak-vendor-stok />
    </div>
</x-body>
