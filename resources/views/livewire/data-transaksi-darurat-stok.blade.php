<div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Transaksi Belum Berkontrak
            @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <div>
            <a href="{{ route('transaksi-darurat-stok.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Rekam Kontrak Baru</a>
        </div>
    </div>

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

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA VENDOR</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DETAIL TRANSAKSI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedTransactions as $vendorId => $unitGroups)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6"></td> <!-- Displays the row number -->
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800">
                            {{ $unitGroups->first()->vendorStok->nama ?? 'Unknown Vendor' }}
                        </p>
                    </td>
                    <td class="py-3 px-6">
                        <table class="w-full text-sm border-spacing-y-2">
                            <thead class="text-primary-800">
                                <tr>
                                    <th class="w-1/3">Barang</th>
                                    <th class="w-1/3">Merk</th>
                                    <th class="w-1/3">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unitGroups as $transaction)
                                    <tr class="border-b-[1px] border-primary-800">
                                        <td>{{ $transaction->merkStok->barangStok->nama }}</td>
                                        <td>{{ $transaction->merkStok->nama ?? 'Unknown Merk' }}</td>
                                        <td>{{ $transaction->jumlah }}
                                            {{ $transaction->merkStok->barangStok->satuanBesar->nama }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td class="py-3 px-6 w-1/6 text-center">
                        <a href="{{ route('transaksi-darurat-stok.show', ['transaksi_darurat_stok' => $unitGroups->first()->vendorStok->id]) }}"
                            class="text-primary-950 px-3 py-3 mx-2 rounded-md border hover:bg-slate-300">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('transaksi-darurat-stok.edit', ['transaksi_darurat_stok' => $unitGroups->first()->vendorStok->id]) }}"
                            class="text-primary-950 px-3 py-3 mx-2 rounded-md border hover:bg-slate-300">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
