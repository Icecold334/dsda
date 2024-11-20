<div>
    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-2/5 rounded-l-lg">BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">SPESIFIKASI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        {{-- @if ($vendor_id && $jenis_id && $metode_id) --}}
        @if (true)
            <tbody class="">
                @foreach ($list as $index => $item)
                    <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="py-3 px-6">
                            <select wire:change="updateList({{ $index }}, 'barang', $event.target.value)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                            <select wire:change="updateList({{ $index }}, 'merk', $event.target.value)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">Pilih Merk</option>
                                @forelse ($item['merks'] ?? [] as $merk)
                                    <option value="{{ $merk->id }}"
                                        @if ($item['merk_id'] == $merk->id) selected @endif>
                                        {{ $merk->nama }}
                                    </option>
                                @empty
                                    <option disabled>Tidak ada merk tersedia</option>
                                @endforelse
                            </select>
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex items-center">
                                <input type="number" wire:model.live="list.{{ $index }}.jumlah"
                                    wire:loading.attr="disabled" wire:target="list.{{ $index }}.merk_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    min="1" placeholder="Jumlah">

                                <span
                                    class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    {{ $list[$index]['merk_id'] ? App\Models\MerkStok::find($list[$index]['merk_id'])->barangStok->SatuanBesar->nama : 'Satuan' }}
                                </span>
                            </div>
                        </td>

                        <td class="text-center py-3 ">
                            <button wire:click="removeFromList({{ $index }})"
                                class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 me-2 mb-2 transition duration-200">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6">
                        <div>
                            <!-- Barang and Merk Input with Suggestions -->
                            <div class="">
                                <div class="flex">
                                    <input type="text" wire:model.live="newBarang" wire:blur="blurBarang"
                                        placeholder="Cari atau Tambah Barang"
                                        class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-l-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    @if ($barang_id == null && $newBarang)
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
                            <div class="flex space-x-2">
                                @foreach (['merek' => 'Merek', 'tipe' => 'Tipe', 'ukuran' => 'Ukuran'] as $key => $label)
                                    <input type="text" wire:model.live="specifications.{{ $key }}"
                                        wire:input="updateSpecification('{{ $key }}', $event.target.value)"
                                        wire:blur="blurSpecification('{{ $key }}')"
                                        placeholder="Masukkan {{ $label }}"
                                        class="w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                    @if (!$merk_id && isset($suggestions[$key]))
                                        <ul
                                            class="absolute z-10 w-96 bg-white border border-gray-300 rounded-lg mt-12 max-h-60 overflow-auto shadow-lg">
                                            @foreach ($suggestions[$key] as $suggestion)
                                                <li wire:click="selectSpecification('{{ $key }}', '{{ $suggestion }}')"
                                                    class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                                    {{ $suggestion }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach

                                @if (is_null($merk_id))
                                    <button wire:click="createNewMerk"
                                        class="px-4 py-1 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Tambah
                                    </button>
                                @endif


                                {{-- <!-- Merk Suggestions -->
                                @if ($merkSuggestions)
                                    <ul
                                        class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto">
                                        @foreach ($merkSuggestions as $suggestion)
                                            <li wire:click="selectMerk({{ $suggestion->id }}, '{{ $suggestion->nama }}')"
                                                class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                                {{ $suggestion->nama }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif --}}
                            </div>
                    </td>


                    <td class="py-3 px-6">
                        <div class="flex items-center">
                            <input type="number" wire:model.live="jumlah" @disabled($merk_id == null)
                                wire:loading.attr="disabled" wire:target="merk_id"
                                class="bg-gray-50 border border-gray-300 {{ $merk_id == null ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                min="1" placeholder="Jumlah">
                            <span
                                class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                {{ $barang_id ? $barang_item->satuanBesar->nama : 'Satuan' }}
                            </span>
                        </div>
                    </td>
                    <td class="text-center py-3 ">
                        <button wire:click="addToList" wire:loading.attr="disabled" wire:target="merk_id, addToList"
                            class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 me-2 mb-2 transition duration-200"
                            {{ $merk_id ? '' : 'hidden' }}>
                            <i class="fa-solid fa-circle-check"></i>
                        </button>
                    </td>
                </tr>

            </tbody>
        @endif

    </table>
    @if ($vendor_id && $jenis_id)
        <div class="flex w-full justify-evenly border-t-4 py-6 ">
            <!-- Penulis -->
            <div class="">
                <label for="penulis" class="block text-sm font-medium text-center mb-2 text-gray-900">Penulis</label>
                <div class="flex"><input type="text" id="penulis" wire:model.live="penulis" readonly
                        class="border-gray-300 rounded-lg p-2.5 focus:ring-primary-500  focus:border-primary-500 w-full" />
                    {{-- <button type="button"
                        class="bg-primary-200 rounded-r-lg hover:bg-primary-500 group transition duration-200 px-3">
                        <i class="fa-solid fa-check text-primary-600 group-hover:text-primary-100"></i>
                    </button> --}}
                </div>
            </div>
            {{-- @role('penanggungjawab')
                <!-- PJ1 -->
                <div class="">
                    <label for="pj1" class="block text-sm font-medium text-center mb-2 text-gray-900">Penanggung
                        Jawab</label>
                    <div class="flex"><input type="text" id="pj1" wire:model.live="pj1" readonly
                            class="border-gray-300 rounded-l-lg p-2.5 focus:ring-primary-500  focus:border-primary-500 w-full" />
                        <button type="button"
                            class="bg-primary-200 rounded-r-lg hover:bg-primary-500 group transition duration-200 px-3">
                            <i class="fa-solid fa-check text-primary-600 group-hover:text-primary-100"></i>
                        </button>
                    </div>
                </div>
            @endrole --}}

        </div>
    @endif
    @if ($vendor_id != null && count($list) > 0 && $dokumenCount > 0 && $nomor_kontrak && $tanggal_kontrak)
        <div class="flex justify-center"><button wire:click='saveKontrak'
                class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>
        </div>
    @endif
</div>
