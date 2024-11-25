<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL PERMINTAAN</h1>
        <div>
            <a href="{{ route('permintaan-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="data umum" class="mb-3">
                <table class="w-full">
                    <tr class="font-semibold">
                        <td>Kode Permintaan</td>
                        <td>{{ $permintaan->kode_permintaan }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Tanggal Permintaan</td>
                        <td>{{ date('j F Y', $permintaan->tanggal_permintaan) }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Unit Kerja</td>
                        <td>{{ $permintaan->unit->nama }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Sub-Unit</td>
                        <td>{{ $permintaan->subUnit->nama ?? '---' }}</td>
                    </tr>
                </table>
            </x-card>
            <x-card title="keterangan" class="mb-3">
                <div class="font-normal">
                    {{ $permintaan->keterangan }}
                </div>
            </x-card>

        </div>
        <div>
            <x-card title="daftar permintaan">
                <table class="w-full">
                    <thead>
                        <tr class="text-primary-600">
                            <th>Barang</th>
                            {{-- <th>Lokasi</th> --}}
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permintaan->permintaanStok as $item)
                            <tr class=" border-b-2 border-primary-500 ">
                                <td class="px-8">
                                    <div class="font-semibold">
                                        {{ $item->merkStok->BarangStok->nama }}
                                    </div>
                                    <div>
                                        {{ $item->merkStok->nama }} | {{ $item->merkStok->tipe ?? '---' }}|
                                        {{ $item->merkStok->ukuran ?? '---' }}
                                    </div>
                                </td>
                                {{-- <td class="px-8">
                                    <div class="font-semibold">
                                        {{ $item->lokasiStok->nama }}
                                    </div>
                                </td> --}}
                                <td class="px-8">
                                    <div class="font-semibold">
                                        {{ $item->jumlah }}
                                        {{ $item->merkStok->barangStok->satuanBesar->nama }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>
            {{-- <x-card title="Status persetujuan"></x-card> --}}
        </div>
    </div>
</x-body>
