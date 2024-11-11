<div>
    @if ($vendor_id)
        <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
            <thead>
                <tr class="text-white">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold  rounded-l-lg">BARANG</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">MERK</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">JUMLAH</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">KETERANGAN</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">LOKASI PENERIMAAN</th>
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
                        <select wire:model.live="newBarangId"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="0">Pilih Barang</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama }} -
                                    {{ $barang->jenisStok->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-3 px-6">
                        <select wire:model.live="newMerkId"
                            class="bg-gray-50 border border-gray-300 {{ $newBarangId == null ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled($newBarangId == null)>
                            <option value="0">Pilih Merk</option>
                            @forelse ($merks as $merk)
                                <option value="{{ $merk->id }}">{{ $merk->nama }}</option>
                            @empty
                            @endforelse
                        </select>
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
                    <td class="text-center py-3">
                        <button wire:click="addToList"
                            class="text-primary-900 border-primary-600 text-xl border  {{ $newMerkId && $newKeterangan && $newLokasiPenerimaan ? '' : 'hidden' }} bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200"
                            @disabled(empty($newMerkId))>
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        @if (count($list) > 0)
            <button wire:click='saveKontrak'
                class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>
        @endif
        @if ($dokumenCount)
            <button wire:click='finishKontrak'
                class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Selesaikan
                Kontrak</button>
        @endif
    @endif
</div>
