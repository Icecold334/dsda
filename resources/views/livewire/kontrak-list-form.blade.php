<div>
    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/5 rounded-l-lg">BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">SPESIFIKASI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12">PPN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">HARGA SATUAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        {{-- @if ($vendor_id && $jenis_id && $metode_id) --}}
        @if (true)
            <tbody>
                @foreach ($list as $index => $item)
                    <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl ">
                        <td class="px-6 py-3">
                            <input
                                class="bg-gray-50 border  cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                type="text" value="{{ $item['barang'] }}" placeholder="Cari Barang" disabled>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex space-x-2">
                                @foreach ($item['specifications'] as $key => $isi)
                                    <input
                                        class="bg-gray-50 border  cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                        type="text" value="{{ $isi }}" disabled placeholder="-">
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex"><input
                                    class="bg-gray-50 border  cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    type="number" value="{{ $item['jumlah'] }}" disabled placeholder="Jumlah">
                                <div
                                    class="bg-gray-50 border  cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block max-w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    {{ $item['satuan'] }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <select wire:model.live='list.{{ $index }}.ppn' disabled
                                class="bg-gray-50 border border-gray-300 cursor-not-allowed text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="0">Sudah Termasuk PPN</option>
                                <option value="11">11%</option>
                                <option value="12">12%</option>
                            </select>
                        <td class="px-6 py-3">
                            <div class="flex">
                                <div
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block max-w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    Rp
                                </div>
                                <input
                                    class="bg-gray-50 border cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    type="text" placeholder="Harga Satuan" value="{{ $item['harga'] }}" disabled>

                            </div>
                        </td>

                        </td>
                        <td class="px-6 py-3">
                            <button wire:click="removeFromList({{ $index }})"
                                class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 me-2 mb-2 transition duration-200">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl ">
                    <td class="px-6 py-3">
                        <div class="flex space-x-2">
                            {{-- @if ($jenis_id == 3)
                                <div
                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-center text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block max-w-72 p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    Kategori
                                </div>
                            @endif --}}
                            <input
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm 
                                rounded-lg
                                focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                {{-- {{ $jenis_id == 3 ? '' : 'rounded-l-lg' }} {{ $barang_id ? 'rounded-lg' : '' }} --}} type="text" wire:model.live="newBarang" wire:blur="blurBarang"
                                wire:focus='focusBarang' placeholder="Cari Barang">
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
                    <td class="px-6 py-3">
                        <div class="flex space-x-2 ">
                            @foreach (['merek' => 'Merek', 'tipe' => 'Tipe', 'ukuran' => 'Ukuran'] as $key => $label)
                                <input
                                    class="bg-gray-50 border {{ !$barang_id ? 'cursor-not-allowed' : '' }} border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    type="text" wire:model.live="specifications.{{ $key }}"
                                    @disabled(!$barang_id)
                                    wire:focus="updateSpecification('{{ $key }}', $event.target.value)"
                                    wire:blur="blurSpecification('{{ $key }}')"
                                    placeholder="{{ $label }}">
                                @if (count($suggestions[$key]) > 0)
                                    <ul
                                        class="absolute z-10 w-96 bg-white border border-gray-300 rounded-lg mt-12 max-h-60 overflow-auto shadow-lg">
                                        @foreach ($suggestions[$key] as $suggestion)
                                            <li class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                                                wire:click="selectSpecification('{{ $key }}', '{{ $suggestion }}')">
                                                {{ $suggestion }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex">
                            <input
                                class="bg-gray-50 border {{ empty($specifications['merek']) && empty($specifications['tipe']) && empty($specifications['ukuran']) ? 'cursor-not-allowed' : '' }} border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                type="number" wire:model.live="jumlah" placeholder="Jumlah"
                                @if (empty($specifications['merek']) && empty($specifications['tipe']) && empty($specifications['ukuran'])) disabled @endif>
                            <div
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block max-w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                {{ !$barang_id ? 'Satuan' : App\Models\BarangStok::find($barang_id)->satuanBesar->nama }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        <select wire:model.live='newPpn'
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="0">Sudah Termasuk PPN</option>
                            <option value="11">11%</option>
                            <option value="12">12%</option>
                        </select>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex">
                            <div
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block max-w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                Rp
                            </div>
                            <input id="newHarga"
                                class="bg-gray-50 border {{ !$jumlah ? 'cursor-not-allowed' : '' }} border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                type="text" placeholder="Harga Satuan" oninput="formatRupiah(this)"
                                value="{{ $newHarga }}" @if (!$jumlah) disabled @endif>
                            @push('scripts')
                                <script type="module">
                                    window.formatRupiah = function(param) {
                                        let angka = param.value
                                        const numberString = angka.replace(/[^,\d]/g, '').toString();
                                        const split = numberString.split(',');
                                        let sisa = split[0].length % 3;
                                        let rupiah = split[0].substr(0, sisa);
                                        const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                                        if (ribuan) {
                                            const separator = sisa ? '.' : '';
                                            rupiah += separator + ribuan.join('.');
                                        }

                                        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                                        @this.set('newHarga', rupiah);
                                        return param.value = rupiah;
                                    }
                                </script>
                            @endpush

                        </div>
                    </td>

                    <td class="px-6 py-3">
                        @if (($specifications['merek'] || $specifications['tipe'] || $specifications['ukuran']) && $jumlah && $newHarga)
                            <button wire:click="addToList" onclick="removeHarga()" wire:loading.attr="disabled"
                                class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 me-2 mb-2 transition duration-200">
                                <i class="fa-solid fa-circle-check"></i>
                            </button>
                        @endif
                        @push('scripts')
                            <script type="module">
                                window.removeHarga = function() {
                                    return document.getElementById('newHarga').value = '';
                                }
                            </script>
                        @endpush
                    </td>
                </tr>
                <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td colspan="4 " class="px-6 py-3 font-semibold text-right">
                        Total
                    </td>
                    <td colspan="2 " class="px-6 py-3 font-semibold text-right">
                        Rp {{ $total }}
                    </td>
                </tr>
            </tbody>
        @else
            <tbody>
                <tr>
                    <td colspan="6">
                        <div class="font-semibold text-center">Lengkapi Data Diatas</div>
                    </td>
                </tr>
            </tbody>
        @endif

    </table>
    <div wire:loading wire:target='saveKontrak'>
        <livewire:loading />
    </div>
    @if ($vendor_id && $jenis_id && $metode_id)
        {{-- @if (true) --}}
        @if (
            $vendor_id != null &&
                count($list) > 0 &&
                $dokumenCount > 0 &&
                $nomor_kontrak &&
                $tanggal_kontrak &&
                str_replace('.', '', $nominal_kontrak) == str_replace('.', '', $total))
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

                    @if ($jenis_id == 3)
                        <!-- Kategori Untuk Barang Umum -->
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Kategori
                                Barang</label>
                            <input type="text" wire:model.live="newKategori"
                                wire:input="fetchSuggestions('kategori', $event.target.value)"
                                wire:blur="blurSpecification('kategori')"
                                class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Masukkan Kategori">
                            @if ($suggestions['kategori'])
                                <ul
                                    class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto shadow-lg">
                                    @foreach ($suggestions['kategori'] as $suggestion)
                                        <li wire:click="selectSuggestion('kategori', '{{ $suggestion }}')"
                                            class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                            {{ $suggestion }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @error('newKategori')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Satuan Besar -->
                    <div class="mb-4 relative">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Satuan Besar</label>
                        <input type="text" wire:model.live="newBarangSatuanBesar"
                            wire:input="fetchSuggestions('satuanBesar', $event.target.value)"
                            wire:blur="blurSpecification('satuanBesar')"
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
                            wire:blur="blurSpecification('satuanKecil')"
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
