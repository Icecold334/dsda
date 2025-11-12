<div>

    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white uppercase">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[20%] rounded-l-lg">Nama barang
                    {{-- @if (isset($dataKegiatan['vol']))
                    @dump($dataKegiatan['vol'])
                    @endif --}}
                </th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[30%] ">Spesifikasi</th>
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
                    <selectwire:model.live.debounce.500ms="list.{{ $index }}.merk" disabled
                        class="bg-gray-50 border border-gray-300 cursor-not-allowed  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="{{ $item['merk']->id }}">{{ $item['merk']->barangStok->nama }}
                        </option>
                        </select>
                        @error('newMerkId')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                </td>
                <td class="py-3 px-6 ">
                    <selectwire:model.live.debounce.500ms="list.{{ $index }}.merk" disabled
                        class="bg-gray-50 border border-gray-300 cursor-not-allowed  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="{{ $item['merk']->id }}">{{
                            $item['merk']->nama ?? 'Tanpa merk' }} - {{
                            $item['merk']->tipe ?? 'Tanpa tipe' }} -
                            {{ $item['merk']->ukuran ?? 'Tanpa ukuran' }}
                        </option>
                        </select>
                        @error('newMerkId')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                </td>
                <td class="py-3 px-6">
                    <div class="flex items-center">
                        <input type="number" wire:model.live.debounce.500ms="list.{{ $index }}.jumlah" min="1" disabled
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ !$newMerkId ? 'cursor-not-allowed' : '' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
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
                        <input type="number" value="{{ $item['telah_digunakan'] ?? 0 }}" disabled
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg cursor-not-allowed focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="0">
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
            {{-- @if (1) --}}
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="py-3 px-6">
                    <div x-data="{ open: false, search: '' }" class="relative w-full">
                        <div @click.outside="open = false">
                            <!-- Trigger -->
                            <button type="button"
                                class="w-full border border-gray-300 rounded-md p-2 flex justify-between items-center bg-white"
                                @click="open = !open" :class="{ 'bg-gray-100': open }">
                                <span>
                                    {{ $newBarangId ? $barangs->firstWhere('id', $newBarangId)->nama : 'Pilih Barang' }}
                                </span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" x-transition
                                class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow">
                                <!-- Search -->
                                <input type="text" x-model="search" placeholder="Cari barang..."
                                    class="w-full px-3 py-2 border-b border-gray-200 focus:outline-none text-sm" />

                                <!-- List -->
                                <ul class="max-h-48 overflow-y-auto text-sm">
                                    @foreach ($barangs as $barang)
                                    <template
                                        x-if="{{ json_encode(Str::lower($barang->nama)) }}.includes(search.toLowerCase())">
                                        <li class="px-3 py-2 hover:bg-primary-100 cursor-pointer"
                                            @click="$wire.set('newBarangId', {{ $barang->id }}); open = false;">
                                            {{ $barang->nama }}
                                        </li>
                                    </template>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        @error('newBarangId')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </div>
                </td>
                <td class="py-3 px-6 ">
                    <selectwire:model.live.debounce.500ms="newMerkId" @disabled(!$newBarangId)
                        class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg {{ !$newBarangId ? 'cursor-not-allowed' : '' }} focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="">Pilih Spesifikasi
                        </option>
                        @php
                        $disabledIds = collect($list)->pluck('merk.id')->toArray();
                        @endphp
                        @foreach ($merks as $merk)
                        <option value="{{ $merk->id }}" @disabled(in_array($merk->id, $disabledIds))>
                            {{ $merk->nama ?? 'Tanpa merk' }} - {{
                            $merk->tipe ?? 'Tanpa tipe' }} -
                            {{ $merk->ukuran ?? 'Tanpa ukuran' }}
                        </option>
                        @endforeach
                        </select>
                        @error('newMerkId')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                </td>
                <td class="py-3 px-6">
                    <div class="flex items-center">
                        <input type="number" wire:model.live.debounce.500ms="newJumlah" min="1" @disabled(!$newMerkId)
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ !$newMerkId ? 'cursor-not-allowed' : '' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
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
                <td colspan="4" class="text-center text-xl px-3 py-6 font-bold"> Lengkapi Data Kegiatan</td>
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