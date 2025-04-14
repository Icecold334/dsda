<div>
    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white uppercase">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[60%] rounded-l-lg">Nama barang</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">estimasi penggunaan</th>
                @if ($rab_id)
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">telah digunakan</th>
                @endif
                <th class="py-3 px-6 bg-primary-950 w-1/12 text-center font-semibold rounded-r-lg "></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $index => $item)
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="py-3 px-6 ">
                    <select wire:model.live="list.{{ $index }}.merk" disabled
                        class="bg-gray-50 border border-gray-300 cursor-not-allowed  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="{{ $item['merk']->id }}">{{ $item['merk']->barangStok->nama }} - {{
                            $item['merk']->nama ?? 'Tanpa merk' }} - {{
                            $item['merk']->tipe ?? 'Tanpa tipe' }} -
                            {{ $item['merk']->ukuran?? 'Tanpa ukuran' }}
                        </option>
                    </select>
                    @error('newMerkId')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
                <td class="py-3 px-6">
                    <div class="flex items-center">
                        <input type="number" wire:model.live="list.{{ $index }}.jumlah" min="1" disabled
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ !$newMerkId?'cursor-not-allowed':'' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Jumlah">
                        <span
                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                            {{ $item['merk']->barangStok->satuanBesar->nama }}
                        </span>
                    </div>
                </td>
                @if ($rab_id)
                <td class="py-3 px-6">
                    <div class="flex items-center">
                        <input type="number" wire:model.live="list.{{ $index }}.jumlah" min="1" disabled
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ !$newMerkId?'cursor-not-allowed':'' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Jumlah">
                        <span
                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                            {{ $item['merk']->barangStok->satuanBesar->nama }}
                        </span>
                    </div>
                </td>
                @endif
                <td class="py-3 px-6">
                    @if ($showRule)
                    <button wire:click="removeFromList({{ $index }})"
                        class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
            @if (!$rab_id)
            @if ($showRule)
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="py-3 px-6 ">
                    <select wire:model.live="newMerkId"
                        class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="">Pilih Barang
                        </option>
                        @foreach ($barangs as $barang)
                        <optgroup label="{{ $barang->nama }}">
                            @foreach ($barang->merkStok as $merk)
                            <option value="{{ $merk->id }}">
                                {{ $merk->nama ?? 'Tanpa merk' }} - {{
                                $merk->tipe ?? 'Tanpa tipe' }} -
                                {{ $merk->ukuran?? 'Tanpa ukuran' }}
                            </option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                    @error('newMerkId')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
                <td class="py-3 px-6">
                    <div class="flex items-center">
                        <input type="number" wire:model.live="newJumlah" min="1" @disabled(!$newMerkId)
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ !$newMerkId?'cursor-not-allowed':'' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Jumlah">
                        <span
                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                            {{ $newUnit }}
                        </span>
                    </div>
                </td>
                <td class="py-3 px-6">
                    @if ($ruleAdd)
                    <button wire:click="addToList"
                        class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                        <i class="fa-solid fa-circle-check"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @else
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td colspan="3" class="text-center text-xl px-3 py-6 font-bold"> Lengkapi Data Kegiatan</td>
            </tr>
            @endif
            @endif
        </tbody>
    </table>
    <div class="flex justify-center">
        {{-- @role('penanggungjawab') --}}
        @if (count($list) > 0 && $showRule)
        <button wire:click="saveData" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Simpan
        </button>
        @endif
        {{-- @endrole --}}

    </div>
</div>