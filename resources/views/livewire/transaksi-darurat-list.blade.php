<div>
    @if ($vendor_id && $jenis_id)
        <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
            <thead>
                <tr class="text-white">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold  rounded-l-lg">BARANG</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">SPESIFIKASI (MERK/UKURAN/DLL)</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12 ">JUMLAH</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">KETERANGAN</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">LOKASI PENERIMAAN</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">BUKTI</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $index => $item)
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="py-3 px-6">
                            <select wire:change="updateList({{ $index }}, 'barang_id', $event.target.value)"
                                class="bg-gray-50 border border-gray-300 {{ $item['editable'] ? '' : 'cursor-not-allowed' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                {{ $item['editable'] ? '' : 'disabled' }}>
                                <option value="0">Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}"
                                        @if ($item['barang_id'] == $barang->id) selected @endif>
                                        {{ $barang->nama }} - {{ $barang->jenisStok->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="py-3 px-6">
                            <select wire:change="updateList({{ $index }}, 'merk_id', $event.target.value)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 {{ $item['editable'] ? '' : 'cursor-not-allowed' }} text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                {{ $item['editable'] ? '' : 'disabled' }}>
                                <option value="0">Pilih Merk</option>
                                @foreach ($item['merks'] as $merk)
                                    <option value="{{ $merk->id }}"
                                        @if ($item['merk_id'] == $merk->id) selected @endif>
                                        {{ $merk->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex items-center">
                                <input type="number" wire:model.live="list.{{ $index }}.jumlah"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 {{ $item['editable'] ? '' : 'cursor-not-allowed' }} text-sm rounded-l-lg p-2.5"
                                    min="1" placeholder="Jumlah" {{ $item['editable'] ? '' : 'disabled' }}>
                                <span
                                    class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $item['merk_id'] ? optional(App\Models\MerkStok::find($item['merk_id'])->barangStok->satuanBesar)->nama : 'Satuan' }}
                                </span>
                            </div>
                        </td>

                        <td class="py-3 px-6">
                            <textarea wire:model.live="list.{{ $index }}.keterangan"
                                class="bg-gray-50 border border-gray-300 {{ $item['editable'] ? '' : 'cursor-not-allowed' }} text-gray-900 text-sm rounded-lg p-2.5 w-full"
                                placeholder="Keterangan" {{ $item['editable'] ? '' : 'disabled' }}></textarea>
                        </td>
                        <td class="py-3 px-6">
                            <textarea wire:model.live="list.{{ $index }}.lokasi_penerimaan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 {{ $item['editable'] ? '' : 'cursor-not-allowed' }} text-sm rounded-lg p-2.5 w-full"
                                placeholder="Lokasi Penerimaan" {{ $item['editable'] ? '' : 'disabled' }}></textarea>
                        </td>
                        <td class="py-3 px-6">
                            <input type="file"
                                wire:change="updateList({{ $index }}, 'bukti', $event.target.value)"
                                wire:model.live="list.{{ $index }}.bukti" class="hidden"
                                id="upload-bukti-{{ $index }}">

                            @if (isset($item['bukti']))
                                <!-- Display uploaded proof preview with remove icon -->
                                <div class="relative inline-block">
                                    <a href="{{ is_string($item['bukti']) ? asset('storage/buktiTransaksi/' . $item['bukti']) : $item['bukti']->temporaryUrl() }}"
                                        download="{{ is_string($item['bukti']) ? is_string($item['bukti']) : $item['bukti']->getClientOriginalName() }}">
                                        <img src="{{ is_string($item['bukti']) ? asset('storage/buktiTransaksi/' . $item['bukti']) : $item['bukti']->temporaryUrl() }}"
                                            alt="Bukti" class="w-16 h-16 rounded-md">
                                    </a>
                                    <button wire:click="removePhoto({{ $index }})"
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 text-xs">
                                        &times;
                                    </button>
                                </div>
                            @else
                                <!-- Show upload button if no file is selected -->
                                <button type="button"
                                    onclick="document.getElementById('upload-bukti-{{ $index }}').click()"
                                    class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                    Unggah Bukti
                                </button>
                            @endif

                            @error("list.{$index}.bukti")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </td>
                        <td class="text-center py-3">
                            @if ($item['editable'])
                                <button wire:click="removeFromList({{ $index }})"
                                    class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                @endforelse

                {{-- Add New Item Row --}}
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6">
                        <div>
                            <!-- Barang and Merk Input with Suggestions -->
                            <div class="">
                                <div class="flex">
                                    <input type="text" wire:model.live="newBarang" wire:blur="blurBarang"
                                        placeholder="Cari atau Tambah Barang"
                                        class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-l-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    @if ($newBarangId == null && $newBarang)
                                        <button wire:click="openBarangModal"
                                            class="   px-4 py-1 text-sm font-medium text-white bg-blue-500 rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Tambah</button>
                                    @endif
                                </div>

                                <!-- Suggestions List -->
                            </div>
                            @if ($barangSuggestions)
                                <ul
                                    class="absolute z-50 w-72 bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto shadow-lg">
                                    @foreach ($barangSuggestions as $suggestion)
                                        <li wire:click="selectBarang({{ $suggestion->id }}, '{{ $suggestion->nama }}')"
                                            class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                            {{ $suggestion->nama }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <!-- Modal for Adding New Barang -->
                            @if ($showBarangModal)
                                <div
                                    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
                                    <div class="bg-white p-6 rounded-lg shadow-lg w-1/2 dark:bg-gray-800">
                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tambah
                                            Barang Baru</h2>

                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-gray-900 dark:text-gray-300">Nama
                                                Barang</label>
                                            <input type="text" wire:model="newBarangName"
                                                class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="Nama Barang">
                                            @error('newBarangName')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-gray-900 dark:text-gray-300">Satuan
                                                Besar</label>
                                            <select wire:model.live="newBarangSatuanBesar"
                                                class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                <option value="">Pilih Satuan</option>
                                                @foreach ($satuanBesarOptions as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('newBarangSatuanBesar')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-gray-900 dark:text-gray-300">Satuan
                                                Kecil</label>
                                            <select wire:model.live="newBarangSatuanKecil"
                                                class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                <option value="">Pilih Satuan</option>
                                                @foreach ($satuanKecilOptions as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('newBarangSatuanKecil')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end space-x-4">
                                            <button wire:click="closeBarangModal"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">Batal</button>
                                            <button wire:click="saveNewBarang"
                                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <div>
                            <div class="">
                                <div class="flex">
                                    <input type="text" wire:model.live="newMerk" wire:blur="blurMerk"
                                        placeholder="Cari atau Tambah Spesifikasi"
                                        class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-l-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    @if ($newMerkId == null && $newMerk)
                                        <button wire:click="createNewMerk"
                                            class="px-4 py-1 text-sm font-medium text-white bg-blue-500 rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Tambah</button>
                                    @endif
                                </div>

                                <!-- Suggestions List -->
                            </div>
                        </div>
                        @if ($merkSuggestions)
                            <ul
                                class="absolute z-10 w-72 bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto shadow-lg">
                                @foreach ($merkSuggestions as $suggestion)
                                    <li wire:click="selectMerk({{ $suggestion->id }}, '{{ $suggestion->nama }}')"
                                        class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                        {{ $suggestion->nama }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td class="py-3 px-6">
                        <div class="flex items-center">
                            <input type="number" wire:model.live="newJumlah"
                                class="bg-gray-50 border border-gray-300 text-gray-900 {{ empty($newMerkId) ? 'cursor-not-allowed' : '' }} text-sm rounded-l-lg p-2.5"
                                min="1" placeholder="Jumlah" @disabled(empty($newMerkId))>

                            <span
                                class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                {{ $newMerkId ? optional(App\Models\MerkStok::find($newMerkId)->barangStok->satuanBesar)->nama : 'Satuan' }}
                            </span>
                        </div>
                    </td>

                    <td class="py-3 px-6">
                        <textarea wire:model.live="newKeterangan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full" placeholder="Keterangan"></textarea>
                    </td>
                    <td class="py-3 px-6">
                        <textarea wire:model.live="newLokasiPenerimaan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full"
                            placeholder="Lokasi Penerimaan"></textarea>
                    </td>
                    <td class="py-3 px-6">
                        <input type="file" wire:model="newBukti" class="hidden" id="upload-new-bukti">

                        @if ($newBukti)
                            <!-- Display uploaded proof preview with remove icon -->
                            <div class="relative inline-block">
                                <a href="{{ $newBukti->temporaryUrl() }}"
                                    download="{{ $newBukti->getClientOriginalName() }}">
                                    <img src="{{ $newBukti->temporaryUrl() }}" alt="Bukti"
                                        class="w-16 h-16 rounded-md">
                                </a>
                                <button wire:click="removeNewPhoto"
                                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 text-xs">
                                    &times;
                                </button>
                            </div>
                        @else
                            <!-- Show upload button if no file is selected -->
                            <button type="button" onclick="document.getElementById('upload-new-bukti').click()"
                                class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                Unggah Bukti
                            </button>
                        @endif

                        @error('newBukti')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="text-center py-3">
                        <button wire:click="addToList"
                            class="text-primary-900 border-primary-600 text-xl border  {{ $newMerkId && $newKeterangan && $newLokasiPenerimaan ? '' : 'hidden' }} bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200"
                            @disabled(empty($newMerkId))>
                            <i class="fa-solid fa-circle-check"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        @if (count($list) > 0)
            <button wire:click='saveKontrak'
                class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>
        @endif
        @if ($dokumenCount && $nomor_kontrak)
            <button wire:click='finishKontrak'
                class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Selesaikan
                Kontrak</button>
        @endif
    @endif
</div>
