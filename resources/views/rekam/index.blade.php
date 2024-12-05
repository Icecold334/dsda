<x-body>
    <div class="flex justify-between py-2 mb-3">
        {{-- @push('html')
            <!-- Main modal -->
            <div id="tipe2" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Menunggu Persetujuan
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="tipe2">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            @forelse ($waiting as $contract)
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Kontrak Nomor</th>
                                            <th scope="col" class="px-6 py-3">Tanggal Kontrak</th>
                                            <th scope="col" class="px-6 py-3">Vendor</th>
                                            <th scope="col" class="px-6 py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="px-6 py-4">{{ $contract->nomor_kontrak }}</td>
                                            <td class="px-6 py-4">{{ date('j F Y', $contract->tanggal_kontrak) }}</td>
                                            <td class="px-6 py-4">
                                                {{ $contract->vendorStok->nama ?? 'Vendor tidak dikenal' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('kontrak-vendor-stok.show', $contract) }}"
                                                    class="text-blue-600 hover:text-blue-900">Detail</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @empty
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    Tidak ada kontrak yang sedang menunggu persetujuan.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endpush --}}

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

        <h1 class="text-2xl font-bold text-primary-900 ">Kontrak Vendor Untuk
            {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
        </h1>
        <div>
            {{-- <button data-modal-target="tipe2" data-modal-toggle="tipe2"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                type="button">
                Menunggu Persetujuan
            </button> --}}
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
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">METODE PENGADAAN</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">STATUS</th> --}}
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
                                {{-- {{ $transactions->first()[0]->kontrakStok->type ? 'Pengadaan' : 'Penggunaan Langsung' }} --}}
                                {{ $transactions->first()[0]->kontrakStok->metodePengadaan->nama }}
                            </p>
                        </td>
                        {{-- <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800 text-center">
                                {{ $transactions->first()[0]->kontrakStok->metodePengadaan->nama }}

                            </p>
                        </td> --}}
                        {{-- <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800 text-center">
                                <span
                                    class="bg-{{ $transactions->first()[0]->kontrakStok->status === null ? 'warning' : ($transactions->first()[0]->kontrakStok->status ? 'success' : 'danger') }}-600 text-{{ $transactions->first()[0]->kontrakStok->status === null ? 'warning' : ($transactions->first()[0]->kontrakStok->status ? 'success' : 'danger') }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{ $transactions->first()[0]->kontrakStok->status === null ? 'diproses' : ($transactions->first()[0]->kontrakStok->status ? 'disetujui' : 'ditolak') }}</span>
                            </p>
                        </td> --}}

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
