<div>
    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-2/5 rounded-l-lg">BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">MERK</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($list as $index => $item)
                <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6">
                        <select wire:change="updateList({{ $index }}, 'barang', $event.target.value)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Pilih Barang</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}" @if ($item['barang_id'] == $barang->id) selected @endif>
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
                                <option value="{{ $merk->id }}" @if ($item['merk_id'] == $merk->id) selected @endif>
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
                    <select wire:model.live="barang_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Pilih Barang</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama }} - {{ $barang->jenisStok->nama }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="py-3 px-6">
                    <select wire:model.live="merk_id" @disabled($barang_id == null) wire:loading.attr="disabled"
                        wire:target="barang_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm {{ $barang_id == null ? 'cursor-not-allowed' : '' }} rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Pilih Merk</option>
                        @if ($barang_id !== null)
                            @foreach ($merks as $merk)
                                <option value="{{ $merk->id }}">{{ $merk->nama }}</option>
                            @endforeach
                        @endif
                    </select>
                </td>
                <td class="py-3 px-6">
                    <div class="flex items-center">
                        <input type="number" wire:model.live="jumlah" @disabled($merk_id == null)
                            wire:loading.attr="disabled" wire:target="merk_id"
                            class="bg-gray-50 border border-gray-300 {{ $merk_id == null ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                            min="1" placeholder="Jumlah">
                        <span
                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            {{ $merk_item ? $merk_item->barangStok->satuanBesar->nama : 'Satuan' }}
                        </span>
                    </div>
                </td>
                <td class="text-center py-3 ">
                    <button wire:click="addToList" wire:loading.attr="disabled" wire:target="merk_id, addToList"
                        class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 me-2 mb-2 transition duration-200"
                        {{ $merk_id ? '' : 'hidden' }}>
                        <i class="fa-solid fa-circle-plus"></i>
                    </button>
                </td>
            </tr>

        </tbody>

    </table>
    @if ($vendor_id != null && count($list) > 0)
        <button wire:click='saveKontrak'
            class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>
    @endif
</div>
