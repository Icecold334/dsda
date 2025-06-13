<div class="space-y-6">
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 uppercase">DATA STOK {{ $lokasi->nama }}</h1>
        <div>
            <a href="{{ route('stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    {{-- Row pertama: Informasi Gudang --}}
    <div class="grid grid-cols-2 gap-6">
        <x-card title="Informasi Gudang">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $lokasi->nama }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $lokasi->alamat ?? '-' }}</td>
                </tr>
            </table>

        </x-card>

    </div>
    <div class="flex justify-end mb-4">
        <div class="relative w-full max-w-sm">
            <input type="text" wire:model.live="search"
                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500"
                placeholder="Cari kode/nama/spesifikasi...">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M9 3a6 6 0 014.472 10.028l4.25 4.25a1 1 0 01-1.414 1.414l-4.25-4.25A6 6 0 119 3zM5 9a4 4 0 118 0 4 4 0 01-8 0z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>
    <x-card title="Daftar Barang">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-primary-700 text-white ">
                    <th class="px-4 py-2 text-center">Kode Barang</th>
                    <th class="px-4 py-2 text-center">Nama Barang</th>
                    <th class="px-4 py-2 text-center">Spesifikasi</th>
                    <th class="px-4 py-2 text-center">Jumlah</th>
                    <th class="px-4 py-2 text-center"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($barangStok as $item)
                <tr class="bg-gray-50 hover:bg-gray-200">
                    <td class="px-4 py-2">{{ $item['kode'] }}</td>
                    <td class="px-4 py-2">{{ $item['nama'] }}</td>
                    <td class="px-4 py-2">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($item['spesifikasi'] as $spec => $jumlah)
                            <li>{{ $spec }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <ul class="text-sm">
                            @foreach($item['spesifikasi'] as $spec => $jumlah)
                            <li>{{ $jumlah }} {{ $item['satuan'] }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <button class="text-blue-600 hover:text-blue-900"
                            wire:click="showRiwayat({{ $item['id'] }},'{{ $item['nama'] }}')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-400">Tidak ada data stok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    {{-- Modal Riwayat Barang --}}
    @if ($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-8xl p-6 relative">
            <button wire:click="closeModal"
                class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
            <h3 class="text-xl font-semibold mb-4">Riwayat Stok: {{ $modalBarangNama }}</h3>

            <table class="w-full border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">Kode Transaksi</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Tipe</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Merk</th>
                        <th class="px-4 py-2">Tipe</th>
                        <th class="px-4 py-2">Ukuran</th>
                        <th class="px-4 py-2">Bagian</th>
                        <th class="px-4 py-2">Posisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modalRiwayat as $item)
                    <tr>
                        <td class="px-4 py-2 text-center">{{ $item['kode'] }} </td>
                        <td class="px-4 py-2 text-center">
                            {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('l, d F Y') }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            @php
                            $bgColor = match($item['tipe']) {
                            'Pemasukan' => 'bg-blue-600 text-white',
                            'Pengeluaran' => 'bg-gray-600 text-white',
                            'Penyesuaian' => 'bg-yellow-400 text-black',
                            'Pengajuan' => 'bg-red-400 text-white',
                            default => 'bg-slate-300 text-black',
                            }; @endphp

                            <span class="{{ $bgColor }} text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                {{ $item['tipe'] }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">{{ $item['jumlah'] }}</td>
                        <td class="px-4 py-2">{{ $item['merk'] }} </td>
                        <td class="px-4 py-2">{{ $item['tipe_merk'] }}</td>
                        <td class="px-4 py-2">{{ $item['ukuran'] }}</td>
                        <td class="px-4 py-2">{{ $item['bagian'] }}</td>
                        <td class="px-4 py-2">{{ $item['posisi'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-400">Tidak ada riwayat transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>