<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">PERBARUI TRANSAKSI</h1>
        <div>
            <a href="{{ route('transaksi-darurat-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <div>
            <x-card title="Data Umum">
                <table class="w-full font-semibold">
                    <tr>
                        <td>Nama Vendor</td>
                        <td>{{ $transaksi->first()->vendorStok->nama }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Kontrak</td>
                        <td>{{ $transaksi->first()->nomor_kontrak ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Kontrak</td>
                        <td>{{ $transaksi->first()->tanggal_kontrak ? date('j F Y', $transaksi->first()->tanggal_kontrak) : '---' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Penulis</td>
                        <td>{{ $transaksi->first()->user->name }}</td>
                    </tr>

                </table>
            </x-card>
        </div>
        <div>
            <x-card title="dokumen kontrak">
                <livewire:upload-surat-kontrak>
            </x-card>
        </div>
    </div>

    <livewire:transaksi-darurat-list :transaksi="$transaksi" :vendor_id="$transaksi->first()->vendorStok->id">
</x-body>
