<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">FORM BARANG DATANG</h1>
        <a href="{{ route('pengiriman-stok.index') }}"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
            Kembali
        </a>
    </div>

    {{-- Row 1: Nomor Kontrak + Upload --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <x-card title="Nomor Kontrak">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontrak</label>
                <input type="text" wire:model.live="nomor_kontrak"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" />
                @error('nomor_kontrak')
                <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
        </x-card>

        <x-card title="Unggah Surat Jalan">
            <livewire:upload-surat-jalan-material />
        </x-card>
    </div>

    @if ($kontrak)
    {{-- Row 2: Data Vendor + Input Barang --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <x-card title="Data Umum">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Vendor</label>
                <input type="text" readonly value="{{ $kontrak->vendorStok->nama }}"
                    class="w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Metode Pengadaan</label>
                <input type="text" readonly value="{{ $kontrak->metodePengadaan->nama }}"
                    class="w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tanggal Kontrak</label>
                <input type="text" readonly
                    value="{{ \Carbon\Carbon::parse($kontrak->tanggal_kontrak)->format('d M Y') }}"
                    class="w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Lokasi Pengiriman (Gudang)</label>
                <select wire:model.live="gudang_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Pilih Gudang</option>
                    @foreach ($listGudang as $gudang)
                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                    @endforeach
                </select>
                @error('gudang_id')
                <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengiriman</label>
                <input type="date" wire:model.live="tanggal_pengiriman"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                @error('tanggal_pengiriman')
                <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
        </x-card>

        <x-card title="Input Barang">
            <div class="mb-4">
                <label class="block mb-1 font-medium">Pilih Barang</label>
                <select wire:model.live="newBarangId"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangs as $barang)
                    <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Pilih Merk</label>
                <select wire:model.live="newMerkId"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">-- Pilih Merk --</option>
                    @foreach ($merks as $merk)
                    <option value="{{ $merk->id }}">{{ $merk->nama }} {{ $merk->tipe }} {{ $merk->ukuran }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Jumlah (maks: {{ $maxJumlah }})</label>
                <input type="number" wire:model.live="newJumlah" min="1" max="{{ $maxJumlah }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Pilih Bagian (Opsional)</label>
                <select wire:model.live="newBagianId"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">-- Pilih Bagian --</option>
                    @foreach ($bagians as $bagian)
                    <option value="{{ $bagian->id }}">{{ $bagian->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Pilih Posisi (Opsional)</label>
                <select wire:model.live="newPosisiId"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                    @disabled(!$newBagianId)>
                    <option value="">-- Pilih Posisi --</option>
                    @foreach ($posisis as $posisi)
                    <option value="{{ $posisi->id }}">{{ $posisi->nama }}</option>
                    @endforeach
                </select>
            </div>

            <button wire:click="addToList"
                class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-4 py-2 rounded">
                Tambah Ke Daftar
            </button>
        </x-card>
    </div>

    {{-- Row 3: Tabel List Barang --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card title="Daftar Barang" class="col-span-2">
            @if (count($list) > 0)
            <table class="w-full border border-gray-200 rounded-lg mt-3">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2">Barang</th>
                        <th class="px-4 py-2">Merk</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Bagian</th>
                        <th class="px-4 py-2">Posisi</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $item['merk']->barangStok->nama }}</td>
                        <td class="px-4 py-2">{{ $item['merk']->nama }} {{ $item['merk']->tipe }} {{
                            $item['merk']->ukuran }}</td>
                        <td class="px-4 py-2">{{ $item['jumlah'] }}</td>
                        <td class="px-4 py-2">{{ $item['bagian_id'] ?
                            \App\Models\BagianStok::find($item['bagian_id'])->nama : '-' }}</td>
                        <td class="px-4 py-2">{{ $item['posisi_id'] ?
                            \App\Models\PosisiStok::find($item['posisi_id'])->nama : '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="removeFromList({{ $loop->index }})"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold px-3 py-1 rounded text-sm">
                                <i class="fa fa-times-circle mr-1"></i> Batal
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button wire:click="save"
                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Simpan Pengiriman
            </button>
            @else
            <p class="text-gray-500 text-sm italic">Belum ada barang yang ditambahkan ke daftar.</p>
            @endif
        </x-card>
    </div>
    @endif
</div>