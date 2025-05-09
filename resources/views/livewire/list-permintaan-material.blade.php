<div>
    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white uppercase">
                <th
                    class="py-3 px-6 bg-primary-950 text-center font-semibold w-[15%] {{ $isSeribu && $withRab ?'rounded-l-lg':'hidden' }}">
                    RKB</th>
                <th
                    class="py-3 px-6 bg-primary-950 text-center font-semibold w-[15%] {{ $isSeribu && $withRab ?'':'rounded-l-lg' }}">
                    Nama barang</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[25%] ">Spesifikasi</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[20%]">Volume</th>
                <th
                    class="py-3 px-6 bg-primary-950 text-center font-semibold  {{ $isSeribu && $withRab ?'':'hidden' }}">
                    Keterangan</th>
                @if ($isShow)
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Foto barang diterima</th>
                @endif
                <th class="py-3 px-6 bg-primary-950 w-1/12 text-center font-semibold rounded-r-lg "></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $index => $item)
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="py-3 px-6 {{ $isSeribu && $withRab ?'':'hidden' }}">
                    <select wire:model.live="list.{{ $index }}.rab_id" disabled class="bg-gray-50 border cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-lg
                        focus:ring-primary-500
                        focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600
                        dark:text-white
                        dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="">Pilih Jenis Pekerjaan</option>
                        @foreach ($rabs as $rab)
                        <option value="{{ $rab->id }}">{{ $rab->jenis_pekerjaan }}</option>
                        @endforeach
                    </select>
                    @error('newMerkId')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
                <td class="py-3 px-6 ">
                    <select wire:model.live="list.{{ $index }}.merk" disabled
                        class="bg-gray-50 border border-gray-300 cursor-not-allowed  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="{{ $item['merk']->id }}">{{ $item['merk']->barangStok->nama }}
                            {{-- - {{
                            $item['merk']->nama ?? 'Tanpa merk' }} - {{
                            $item['merk']->tipe ?? 'Tanpa tipe' }} -
                            {{ $item['merk']->ukuran?? 'Tanpa ukuran' }} --}}
                        </option>
                    </select>
                    @error('newMerkId')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
                <td class="py-3 px-6 ">
                    <select wire:model.live="list.{{ $index }}.merk" disabled
                        class="bg-gray-50 border border-gray-300 cursor-not-allowed  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="{{ $item['merk']->id }}">
                            {{
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
                <td class="px-6 py-3 {{ $isSeribu && $withRab ?'':'hidden' }}">
                    <textarea id="jumlah" wire:model.live="list.{{ $index }}.keterangan" disabled rows="2"
                        class="w-full border  border-gray-300 {{ $isSeribu && $withRab ?'':'hidden' }} cursor-not-allowed rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Keterangan (opsional)"></textarea>
                </td>
                @if ($isShow)
                <td class="py-3 px-6 text-center align-top">
                    @if (
                    $item['editable'] &&
                    auth()->id() == $permintaan->user_id &&
                    $permintaan->persetujuan()->where('is_approved', 1)->get()->unique('user_id')->count() >= 3
                    )
                    {{-- Input file tersembunyi --}}
                    <input type="file" wire:model="list.{{ $index }}.img" accept="image/*" class="hidden"
                        id="upload-img-{{ $index }}">

                    {{-- Jika sudah ada gambar, tampilkan preview dan tombol hapus --}}
                    @if (isset($item['img']) && $item['img'])
                    <div class="relative inline-block ">
                        {{-- Gambar preview --}}
                        @if (is_string($item['img']))
                        <img src="{{ asset('storage/fotoPerBarang/' . $item['img']) }}"
                            class="object-cover w-16 h-16  rounded border" alt="Preview">
                        @else
                        <img src="{{ $item['img']->temporaryUrl() }}" class="object-cover w-16 h-16  rounded border"
                            alt="Preview">
                        <button type="button" wire:click="resetImage({{ $index }})"
                            class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 text-white rounded-full text-xs hover:bg-red-700">
                            &times;
                        </button>
                        @endif

                    </div>
                    @else
                    {{-- Tombol unggah --}}
                    <button type="button" onclick="document.getElementById('upload-img-{{ $index }}').click()"
                        class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                        Unggah
                    </button>
                    @endif

                    @elseif(isset($item['img']) && $item['img'])
                    {{-- Non-editable preview --}}
                    <a href="{{ asset('storage/fotoPerBarang/' . $item['img']) }}" target="_blank">
                        <img src="{{ asset('storage/fotoPerBarang/' . $item['img']) }}" alt="Foto"
                            class="w-16 h-16 object-cover rounded border inline-block" />
                    </a>
                    @else
                    <span class=" text-secondary-500 italic text-xs">Belum
                        ada unggahan</span>
                    @endif
                </td>

                @endif
                <td class="py-3 px-6">
                    @if ($showRule && !$isShow)
                    <button wire:click="removeFromList({{ $index }})"
                        class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                    @endif
                    @if ($isShow && !is_null($item['img']) && !is_string($item['img']))
                    <button wire:click="saveItemPic({{ $index }})"
                        class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                        <i class="fa-solid fa-circle-check"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
            @if (!$isShow)
            @if ($showRule)
            {{-- @if (1) --}}
            <tr
                class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl {{ $isShow ? 'hidden' :'' }}">
                <td class="py-3 px-6 {{ $isSeribu && $withRab ?'':'hidden' }}">
                    <select wire:model.live="newRabId" class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg
                        focus:ring-primary-500
                        focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600
                        dark:text-white
                        dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="">Pilih Jenis Pekerjaan</option>
                        @foreach ($rabs as $rab)
                        <option value="{{ $rab->id }}">{{ $rab->jenis_pekerjaan }}</option>
                        @endforeach
                    </select>
                    @error('newMerkId')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
                <td class="py-3 px-6 ">
                    <select wire:model.live="newBarangId" @disabled($isSeribu && !$newRabId && $withRab)
                        class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="">Pilih Barang
                        </option>
                        @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}">
                            {{ $barang->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('newBarangId')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
                <td class="py-3 px-6 ">
                    <select wire:model.live="newMerkId" @disabled(!$newBarangId)
                        class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg {{ !$newBarangId?'cursor-not-allowed':'' }} focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="">Pilih Barang
                        </option>
                        @php
                        $disabledIds = collect($list)->pluck('merk.id')->toArray();
                        @endphp
                        @foreach ($merks as $merk)
                        <option value="{{ $merk->id }}" @disabled(in_array($merk->id, $disabledIds))>
                            {{ $merk->nama ?? 'Tanpa merk' }} - {{
                            $merk->tipe ?? 'Tanpa tipe' }} -
                            {{ $merk->ukuran?? 'Tanpa ukuran' }}
                        </option>
                        @endforeach

                    </select>
                    @error('newMerkId')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
                <td class="py-3 px-6">
                    <div class="flex items-center">
                        <input type="number" wire:model.live="newJumlah" min="1" max="{{ $newMerkMax }}"
                            @disabled(!$newMerkId)
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ !$newMerkId?'cursor-not-allowed':'' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="{{ !$newMerkId ? 'Jumlah' : 'Stok Tersedia : '.$newMerkMax }}">
                        <span
                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                            {{ $newUnit }}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-3 {{ $isSeribu && $withRab ?'':'hidden' }}">
                    <textarea id="jumlah" wire:model.live="newKeterangan" rows="2"
                        class="w-full border  border-gray-300  rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Keterangan (opsional)"></textarea>
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
                <td colspan="{{ $isSeribu && $withRab ?'6':'4' }}" class="text-center text-xl px-3 py-6 font-bold">
                    Lengkapi Data
                    Kegiatan</td>
            </tr>
            @endif
            @endif
        </tbody>
    </table>
    <div class="flex justify-center">
        {{-- @role('penanggungjawab') --}}
        @if (count($list) > 0 && $showRule && !$isShow)
        <button wire:click="saveData" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Simpan
        </button>
        @endif
        {{-- @endrole --}}

    </div>
</div>