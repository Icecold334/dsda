<div>
    <table class="w-full border border-spacing-y-4">
        <thead>
            <tr class="text-white bg-primary-950">
                <th class="py-3 px-6 text-center font-semibold rounded-l-lg">Barang</th>
                <th class="py-3 px-6 text-center font-semibold">Spesifikasi</th>
                <th class="py-3 px-6 text-center font-semibold">Jumlah</th>
                <th class="py-3 px-6 text-center font-semibold">Lokasi Penerimaan</th>
                <th class="py-3 px-6 text-center font-semibold">Keterangan</th>
                <th class="py-3 px-6 text-center font-semibold">Bukti</th>
                <th class="py-3 px-6 text-center font-semibold rounded-r-lg">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $index => $item)
                <tr class="bg-gray-50 hover:bg-gray-200">
                    <td>{{ $item['barang'] }}</td>
                    <td>{{ implode(', ', $item['specifications']) }}</td>
                    <td>{{ $item['jumlah'] }} {{ $item['satuan'] }}</td>
                    <td>{{ $item['lokasi_penerimaan'] }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                    <td>
                        @if ($item['bukti'])
                            <a href="{{ asset('storage/' . $item['bukti']) }}" target="_blank"
                                class="text-blue-500">Lihat</a>
                        @endif
                    </td>
                    <td>
                        <button wire:click="removeFromList({{ $index }})"
                            class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="px-6 py-3">
                    <div class="flex space-x-2">
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                            type="text" wire:model.live="newBarang" wire:blur="blurBarang" placeholder="Cari Barang">
                        @if (!$barang_id)
                            <button wire:click="openBarangModal"
                                class="px-4 py-1 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Tambah</button>
                        @endif
                    </div>
                    @if ($barangSuggestions)
                        <ul
                            class="absolute z-10 w-96 bg-white border border-gray-300 rounded-lg mt-2 max-h-60 overflow-auto shadow-lg">
                            @foreach ($barangSuggestions as $suggestion)
                                <li wire:click="selectBarang('{{ $suggestion['id'] }}', '{{ $suggestion['nama'] }}')"
                                    class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                    {{ $suggestion['nama'] }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td>
                    @foreach (['merek' => 'Merek', 'tipe' => 'Tipe', 'ukuran' => 'Ukuran'] as $key => $label)
                        <input type="text" wire:model.live="specifications.{{ $key }}"
                            placeholder="{{ $label }}" class="w-full px-3 py-2 border rounded">
                    @endforeach
                </td>
                <td>
                    <input type="number" wire:model.live="newJumlah" placeholder="Jumlah"
                        class="w-full px-3 py-2 border rounded">
                </td>
                <td>
                    <input type="text" wire:model.live="newLokasiPenerimaan" placeholder="Lokasi Penerimaan"
                        class="w-full px-3 py-2 border rounded">
                </td>
                <td>
                    <textarea wire:model.live="newKeterangan" placeholder="Keterangan" class="w-full px-3 py-2 border rounded"></textarea>
                </td>
                <td>
                    <input type="file" wire:model.live="newBukti" class="w-full">
                </td>
                <td>
                    <button wire:click="addToList" class="bg-blue-500 text-white px-2 py-1 rounded">Tambah</button>
                </td>
            </tr>
        </tbody>
    </table>

    @if (true)
        @if ($vendor_id != null && count($list) > 0 && $dokumenCount > 0 && $nomor_kontrak && $tanggal_kontrak)
            <div class="flex justify-center"><button wire:click='saveKontrak'
                    class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>
            </div>
        @endif
        @if ($showBarangModal)
            {{-- @if (true) --}}
            <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-1/2 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tambah Barang Baru</h2>

                    <!-- Nama Barang -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Nama Barang</label>
                        <input type="text" wire:model.live="newBarangName"
                            class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Nama Barang">
                        @error('newBarangName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Satuan Besar -->
                    <div class="mb-4 relative">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Satuan Besar</label>
                        <input type="text" wire:model.live="newBarangSatuanBesar"
                            wire:input="fetchSuggestions('satuanBesar', $event.target.value)"
                            class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukkan Satuan Besar">
                        @if ($suggestions['satuanBesar'])
                            <ul
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto shadow-lg">
                                @foreach ($suggestions['satuanBesar'] as $suggestion)
                                    <li wire:click="selectSuggestion('satuanBesar', '{{ $suggestion }}')"
                                        class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                        {{ $suggestion }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @error('newBarangSatuanBesar')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Satuan Kecil -->
                    <div class="mb-4 relative">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Satuan Kecil
                            (Opsional)</label>
                        <input type="text" wire:model.live="newBarangSatuanKecil"
                            wire:input="fetchSuggestions('satuanKecil', $event.target.value)"
                            class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukkan Satuan Kecil">
                        @if ($suggestions['satuanKecil'])
                            <ul
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto shadow-lg">
                                @foreach ($suggestions['satuanKecil'] as $suggestion)
                                    <li wire:click="selectSuggestion('satuanKecil', '{{ $suggestion }}')"
                                        class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                        {{ $suggestion }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @error('newBarangSatuanKecil')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jumlah Satuan Kecil dalam Satuan Besar -->
                    @if ($newBarangSatuanKecil)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Jumlah Satuan
                                Kecil dalam
                                Satuan Besar</label>
                            <input type="number" wire:model.live="jumlahKecilDalamBesar"
                                class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Contoh: 12">
                            @error('jumlahKecilDalamBesar')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button wire:click="closeBarangModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">Batal</button>
                        <button wire:click="saveNewBarang"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan</button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
