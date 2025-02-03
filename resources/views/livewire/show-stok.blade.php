<div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL BARANG</h1>
        <div>
            <a href="{{ route('stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 mb-3">
        <x-card title="Data umum">
            <table class="w-full">
                <tr class="font-semibold">
                    <td>Nama Barang</td>
                    <td>{{ $barang->nama }}</td>
                </tr>
                <tr class="font-semibold">
                    <td>Kode Barang</td>
                    <td>{{ $barang->kode_barang }}</td>
                </tr>
                <tr class="font-semibold">
                    <td>Jenis Barang</td>
                    <td>{{ $barang->jenisStok->nama }}</td>
                </tr>
                <tr class="font-semibold">
                    <td>Deskripsi Barang</td>
                    <td>{{ $barang->deskripsi }}</td>
                </tr>
            </table>
        </x-card>
    </div>

    <x-card title="detail stok">
        <table class="w-full">
            <thead class="text-primary-600">
                <tr>
                    <th class="w-1/4">Spesifikasi (Merk/Tipe/Ukuran)</th>
                    <th class="w-1/6">Jumlah</th>
                    <th class="w-1/6">Jenis</th>
                    <th class="w-1/6">Lokasi</th>
                    <th class="w-1/6">Bagian</th>
                    <th class="w-1/6">Posisi</th>
                    <th class=""></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stok as $item)
                    <tr class=" border-b-2  border-primary-500 ">
                        <td>
                            <table class="w-full">
                                <tr>
                                    <td class="w-1/3 px-3  {{ $item['merk']->nama ?? 'text-center' }}">
                                        {{ $item['merk']->nama ?? '-' }}</td>
                                    <td
                                        class="w-1/3 px-3 border-x-2 border-primary-500 {{ $item['merk']->tipe ?? 'text-center' }}">
                                        {{ $item['merk']->tipe ?? '-' }}</td>
                                    <td class="w-1/3 px-3 {{ $item['merk']->ukuran ?? 'text-center' }}">
                                        {{ $item['merk']->ukuran ?? '-' }}</td>
                                </tr>
                            </table>
                        </td>
                        <td class=" font-semibold">{{ $item['jumlah'] }}
                            {{ $item['merk']->barangStok->satuanBesar->nama }}
                        </td>
                        <td class="text-center">
                            <span
                                class="bg-{{ $item['stok'] ? 'primary' : 'warning' }}-600 text-{{ $item['stok'] ? 'primary' : 'warning' }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full ">
                                {{ $item['stok'] ? 'Stok' : 'Penggunaan Langsung' }}</span>

                        </td>
                        <td>{{ $item['lokasi']->nama ?? $item['lokasi'] }}</td>
                        <td class="{{ $item['bagian'] == null ? 'text-center' : '' }}">
                            {{ $item['bagian']->nama ?? '---' }}</td>
                        <td class="{{ $item['posisi'] == null ? 'text-center' : '' }}">
                            {{ $item['posisi']->nama ?? '---' }}</td>
                        <td>
                            @can('stok_show_detail')
                                @if ($item['stok'])
                                    <button wire:click="historyStok({{ $item['id'] }})"
                                        class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                        data-tooltip-target="tooltip-item-{{ $item['id'] }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <div id="tooltip-item-{{ $item['id'] }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Lihat Riwayat Keluar/Masuk Barang
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                @endif
                            @endcan


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
    @if ($noteModalVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-1/2">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold">Riwayat Keluar Masuk Barang</h3>
                    <button wire:click="$set('noteModalVisible', false)" class="text-gray-500 hover:text-gray-800">
                        &times;
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4 space-y-4">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200 text-center">

                                <th colspan="2" class="border px-4 py-2">Jumlah</th>
                                <th class="border px-4 py-2">Tanggal</th>
                                {{-- <th class="border px-4 py-2">Posisi</th>
                                <th class="border px-4 py-2">Jumlah Disetujui</th>
                                <th class="border px-4 py-2">Catatan</th> --}}
                            </tr>
                        </thead>
                        {{-- @dd($selectedItemHistory) --}}
                        <tbody>
                            @forelse ($selectedItemHistory as $data)
                                <tr>
                                    <td class="border px-4 py-2 w-1/12">
                                        <span
                                            class="{{ $data['type'] === 'out' ? 'text-danger-600' : ($data['type'] === 'in' ? 'text-success-600' : 'text-warning-700') }}">
                                            <i
                                                class="{{ $data['type'] === 'out' ? 'fa-solid fa-arrow-right-from-bracket' : ($data['type'] === 'in' ? 'fa-solid fa-arrow-right-to-bracket' : 'fa-regular fa-hourglass-half') }}"></i>
                                        </span>
                                    </td>

                                    <td class="border px-4 py-2">{{ $data['jumlah'] }}
                                        {{ $data['merk']->merkStok->barangStok->satuanBesar->nama }}</td>
                                    <td class="border px-4 py-2">
                                        {{ Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</td>
                                </tr>
                            @empty
                                <tr>

                                    <td colspan="3" class="border text-center font-semibold px-4 py-2">Belum ada
                                        riwayat pengiriman/permintaan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end p-4 border-t">
                    <button wire:click="$set('noteModalVisible', false)"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
