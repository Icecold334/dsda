<div>
    <div>
        <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
            <thead>
                <tr class="text-white">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA BARANG</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH *</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DOKUMEN PENDUKUNG</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $index => $item)
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <!-- Empty Column -->
                        <td class="py-3 px-6"></td>

                        <!-- NAMA BARANG Column -->
                        <td class="py-3 px-6">
                            <select wire:change="updateList({{ $index }}, 'barang', $event.target.value)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="0">Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}"
                                        @if ($item['barang_id'] == $barang->id) selected @endif>
                                        {{ $barang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <!-- JUMLAH Column -->
                        <td class="py-3 px-6">
                            <input type="number" wire:model.live="list.{{ $index }}.jumlah" min="1"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </td>

                        <!-- DOKUMEN PENDUKUNG Column -->
                        <td class="py-3 px-6 text-center">
                            <div class="flex justify-center">
                                @if (isset($item['dokumen']))
                                    <div class="relative inline-block">
                                        <a href="{{ is_string($item['dokumen']) ? asset('storage/uploads/' . $item['dokumen']) : $item['dokumen']->temporaryUrl() }}"
                                            target="_blank">
                                            <i class="fa-solid fa-file text-primary-600"></i>
                                        </a>
                                        <button wire:click="removeDocument({{ $index }})"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs p-1 hover:bg-red-700">
                                            &times;
                                        </button>
                                    </div>
                                @else
                                    <input type="file" wire:model="list.{{ $index }}.dokumen" class="hidden"
                                        id="dokumen-upload-{{ $index }}">
                                    <button type="button"
                                        onclick="document.getElementById('dokumen-upload-{{ $index }}').click()"
                                        class="bg-gray-200 border border-gray-300 rounded-lg px-3 py-1.5 text-gray-700 hover:bg-blue-500 hover:text-white">
                                        Unggah Dokumen
                                    </button>
                                @endif
                            </div>
                        </td>

                        <!-- Remove Button Column -->
                        <td class="py-3 px-6 text-center">
                            <button wire:click="removeFromList({{ $index }})"
                                class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach

                <!-- New Item Row -->
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6"></td>

                    <td class="py-3 px-6">
                        <input type="text" wire:model="newBarang" wire:model.debounce.300ms="newBarang"
                            wire:focus="focusBarang" wire:blur="blurBarang" placeholder="Cari atau Tambah Barang"
                            class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                        @if ($barangSuggestions)
                            <ul
                                class="absolute z-10 w-96 bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto shadow-lg">
                                @foreach ($barangSuggestions as $suggestion)
                                    <li wire:click="selectBarang({{ $suggestion->id }}, '{{ $suggestion->nama }}')"
                                        class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                        {{ $suggestion->nama }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </td>

                    <td class="py-3 px-6">
                        <input type="number" wire:model="newJumlah" min="1"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </td>

                    <td class="py-3 px-6">
                        <input type="file" wire:model="newDokumen" class="hidden" id="new-dokumen-upload">
                        <button type="button" onclick="document.getElementById('new-dokumen-upload').click()"
                            class="bg-gray-200 border border-gray-300 rounded-lg px-3 py-1.5 text-gray-700 hover:bg-blue-500 hover:text-white">
                            Unggah Dokumen
                        </button>
                    </td>

                    <td class="py-3 px-6 text-center">
                        <button wire:click="addToList"
                            class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                            <i class="fa-solid fa-circle-check"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>




        <div class="flex justify-center">
            {{-- @if ($vendor_id != null && count($list) > 0)
                <button wire:click='savePengiriman'
                    class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Simpan
                </button>
            @endif --}}
        </div>
    </div>
</div>
