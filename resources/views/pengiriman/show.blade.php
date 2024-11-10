<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL PENGIRIMAN</h1>
        <div>
            <a href="{{ route('pengiriman-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <div>
            <x-card title="Data Umum" class="h-full">
                <table class="w-full">
                    <tr class="font-semibold">
                        <td class="w-1/3">Kode Barang Masuk</td>
                        <td>{{ $pengiriman->kode_pengiriman_stok }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Nama Vendor</td>
                        <td>{{ $pengiriman->pengirimanStok->first()->kontrakVendorStok->vendorStok->nama }}</td>
                    </tr>
                </table>
            </x-card>
        </div>
        <div>
            <x-card title="Status Persetujuan">
                <table class="w-full font-semibold">
                    <tr>
                        <td class="w-1/3">Penulis</td>
                        <td class="">{{ $pengiriman->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="w-1/3">Penanggung Jawab 1</td>
                        @if (auth()->user()->hasRole('superadmin'))
                            @if ($pengiriman->super_id === null)
                                <td><livewire:approval-button :id="$pengiriman->id" /></td>
                            @else
                                <td>{{ $pengiriman->super->name ?? 'Sudah Disetujui' }}</td>
                            @endif
                        @else
                            <td class="{{ $pengiriman->super_id === null ? 'font-normal' : '' }}">
                                {{ $pengiriman->super->name ?? 'Menunggu Persetujuan' }}
                            </td>
                        @endif
                    </tr>
                    <tr>
                        <td class="w-1/3">Penanggung Jawab 2</td>
                        @if (auth()->user()->hasRole('admin'))
                            @if ($pengiriman->admin_id === null)
                                <td><livewire:approval-button :id="$pengiriman->id" :role="'admin'" /></td>
                            @else
                                <td>{{ $pengiriman->admin->name ?? 'Sudah Disetujui' }}</td>
                            @endif
                        @else
                            <td class="{{ $pengiriman->admin_id === null ? 'font-normal' : '' }}">
                                {{ $pengiriman->admin->name ?? 'Menunggu Persetujuan' }}
                            </td>
                        @endif
                    </tr>
                </table>
            </x-card>
        </div>
    </div>
    <x-card title="Daftar Barang Yang DIterima">
        <table class="w-full  border-3 border-separate border-spacing-y-4 ">
            <thead>
                <tr class="text-white">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">NAMA BARANG</th>
                    {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KODE</th> --}}
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">LOKASI</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BAGIAN</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">POSISI</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH </th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BUKTI</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengiriman->pengirimanStok as $barang)
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="px-6 py-3 font-semibold">
                        </td>
                        <td class="px-6 py-3 font-semibold">
                            {{ $barang->merkStok->nama }}
                        </td>
                        <td class="px-6 py-3 font-semibold">
                            {{ $barang->lokasiStok->nama }}
                        </td>
                        <td class="px-6 py-3 font-semibold">
                            {{ $barang->bagianStok->nama ?? '---' }}
                        </td>
                        <td class="px-6 py-3 font-semibold">
                            {{ $barang->posisiStok->nama ?? '---' }}
                        </td>
                        <td class="px-6 py-3 font-semibold text-right">
                            {{ $barang->jumlah }}
                            {{ $barang->merkStok->barangStok->satuanBesar->nama }}
                        </td>
                        <td class="px-6 py-3 font-semibold text-center">
                            <button
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Lihat
                                Foto</button>
                        </td>
                        <td class="px-6 py-3 font-semibold"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
</x-body>
