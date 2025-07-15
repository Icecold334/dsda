<div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL PENGIRIMAN</h1>
        <div>
            <a href="{{ route('pengiriman-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <x-card title="Data Umum" class="h-full">
            <table class="w-full">
                <tr class="font-semibold">
                    <td>Nama Penyedia</td>
                    <td>{{ $pengiriman->pengirimanStok->first()->kontrakVendorStok->nama_penyedia ?? 'Tidak Diketahui'
                        }}</td>
                </tr>
                <tr class="font-semibold">
                    <td>Nomor Kontrak</td>
                    <td>{{ $pengiriman->pengirimanStok->first()->kontrakVendorStok->nomor_kontrak ?? 'Tidak Diketahui'
                        }}</td>
                </tr>
                <tr class="font-semibold">
                    <td class="w-1/3">Kode Barang Masuk</td>
                    <td>{{ $pengiriman->kode_pengiriman_stok }}</td>
                </tr>
                <tr class="font-semibold">
                    <td class="w-1/3">Status</td>
                    <td class="">
                        <span
                            class="bg-{{ $pengiriman->status === null ? 'warning' : ($pengiriman->status ? 'success' : 'danger') }}-400 text-{{ $pengiriman->status === null ? 'black' : 'white' }} text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-{{ $pengiriman->status === null ? 'warning' : ($pengiriman->status === true ? 'success' : 'danger') }}-900 dark:text-{{ $pengiriman->status === null ? 'warning' : ($pengiriman->status === true ? 'success' : 'danger') }}-300">
                            {{ $pengiriman->status === null ? 'diproses' : ($pengiriman->status ? 'disetujui' :
                            'ditolak') }}
                        </span>
                    </td>
                </tr>
                {{-- <tr class="font-semibold">
                    <td class="w-1/3">Jenis Pengiriman</td>
                    <td>{{ $pengiriman->pengirimanStok->first()->merkStok->barangStok->jenisStok->nama }}</td>
                </tr> --}}
            </table>
        </x-card>
        <x-card title="Surat Jalan & Foto Barang" class="h-full">
            <livewire:upload-surat-jalan-material :pengiriman="$pengiriman">
        </x-card>
    </div>
    <x-card title="Daftar Barang Yang DIterima">
        <livewire:list-pengiriman-form {{--
            :vendor_id="$pengiriman->pengirimanStok->first()->kontrakVendorStok->vendorStok->id" --}}
            :penulis="$pengiriman->penerima" :pj1="$pengiriman->pj1" :pj2="$pengiriman->pj2"
            :old="$pengiriman->pengirimanStok">
            @if (auth()->user()->unitKerja->hak)
            <livewire:approval-pengiriman :pengiriman="$pengiriman">
                @else
                <livewire:approval-pengiriman-material :pengiriman="$pengiriman">
                    @endif

    </x-card>
</div>