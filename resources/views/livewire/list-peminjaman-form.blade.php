<div>
    <div>
        <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
            <thead>
                <tr class="text-white uppercase">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg">NAMA
                        {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }}</th>
                    @if ($tipe == 'KDO')
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12">peminjaman</th>
                    @endif
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">waktu penggunaan</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">keterangan</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
                </tr>
            </thead>
            <tbody>
                {{-- @if ($tanggal_permintaan && $keterangan && $unit_id) --}}
                @if (true)
                    @foreach ($list as $index => $item)
                        <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                            <td class="py-3 px-6"></td>
                            <td class="py-3 px-6">{{ $item['aset_name'] }}</td>
                            <td class="py-3 px-6">{{ $item['permintaan'] }}</td>
                            <td class="py-3 px-6">{{ $item['waktu_penggunaan'] }}</td>
                            <td class="py-3 px-6">{{ $item['keterangan'] }}</td>
                            <td class="text-center py-3 px-6">
                                <button wire:click="removeFromList({{ $index }})"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="py-3 px-6">
                            <select wire:model.live="newAsetId"
                                class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Pilih {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }}</option>
                                @foreach ($asets as $asets)
                                    <option value="{{ $asets->id }}">{{ $asets->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('newAsetId')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror

                        </td>
                        {{-- <td class="py-3 px-6 relative">
                            <input type="text" wire:model.live="newAset"
                                wire:input="fetchSuggestions('aset', $event.target.value)"
                                wire:focus="fetchSuggestions('aset')" wire:blur="blurSpecification('aset')"
                                placeholder="Cari Aset"
                                class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            @if ($suggestions['aset'])
                                <ul
                                    class="absolute z-10 w-80 bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto shadow-lg">
                                    @foreach ($suggestions['aset'] as $suggestion)
                                        <li wire:click="selectSuggestion('aset', '{{ $suggestion }}')"
                                            class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                            {{ $suggestion }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td> --}}
                        @if ($tipe == 'KDO')
                            <td class="py-3 px-6">
                                <input type="number" wire:model.live="newPeminjaman" min="1"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="Jumlah Permintaan">
                            </td>
                        @endif
                        <td class="py-3 px-6">
                            <input type="date" wire:model.live="newWaktuPenggunaan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </td>
                        <td class="py-3 px-6">
                            <input type="text" wire:model.live="newKeterangan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Keterangan">
                        </td>
                        <td class="text-center py-3 px-6">
                            <button wire:click="addToList"
                                class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                <i class="fa-solid fa-circle-plus"></i>
                            </button>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="9" class="text-center">Lengkapi data diatas terlebih dahulu</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="flex justify-center mt-4">
            @if (count($list) > 0)
                <button wire:click="saveData"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
            @endif
        </div>
    </div>
</div>
