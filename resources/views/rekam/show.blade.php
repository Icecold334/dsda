<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL KONTRAK</h1>
        <div>
            <a href="{{ route('kontrak-vendor-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div>
            <div class="border-2 rounded-lg px-4 py-2">
                <table class="w-full font-semibold">
                    <tr>
                        <td>Nama Vendor</td>
                        <td>{{ $kontrak->vendorStok->nama }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Kontrak</td>
                        <td>{{ $kontrak->nomor_kontrak }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Kontrak</td>
                        <td>{{ date('j F Y', $kontrak->tanggal_kontrak) }}</td>
                    </tr>
                    <tr>
                        <td>Penulis</td>
                        <td>{{ $kontrak->user->name }}</td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-2/5 rounded-l-lg">BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">MERK</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">SISA</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($kontrak->transaksiStok as $transaksi)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6 font-semibold">
                        {{ $transaksi->merkStok->barangStok->nama }}
                    </td>
                    <td class="py-3 px-6 font-semibold">
                        {{ $transaksi->merkStok->nama }}
                    </td>
                    <td class="py-3 px-6 font-semibold">
                        {{ $transaksi->jumlah }}
                        {{ $transaksi->merkStok->barangStok->satuanBesar->nama }}
                    </td>
                    <td class="text-center py-3 font-semibold ">
                        {{ max(0, $transaksi->jumlah - $kontrak->pengirimanStok->where('merk_id', $transaksi->merk_id)->sum('jumlah')) }}

                        {{ $transaksi->merkStok->barangStok->satuanBesar->nama }}

                    </td>
                    <td class="text-center py-3 ">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-body>
