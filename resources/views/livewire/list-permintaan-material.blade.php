<div>
     {{-- <pre>{{ json_encode($merks) }}</pre> --}}
    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white uppercase">
                <th
                    class="py-3 px-6 bg-primary-950 text-center font-semibold w-[15%] {{ $isSeribu && $withRab ? 'rounded-l-lg' : 'hidden' }}">
                    RKB</th>
                <th
                    class="py-3 px-6 bg-primary-950 text-center font-semibold w-[15%] {{ $isSeribu && $withRab ? '' : 'rounded-l-lg' }}">
                    Nama barang</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[25%] ">Spesifikasi</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[20%]">Volume</th>
                <th
                    class="py-3 px-6 bg-primary-950 text-center font-semibold  {{ $isSeribu && $withRab ? '' : 'hidden' }}">
                    Keterangan</th>
                @if ($isShow)
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[10%] ">Foto barang diterima</th>
                @endif
                <th class="py-3 px-6 bg-primary-950 w-1/12 text-center font-semibold rounded-r-lg "></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $index => $item)
                   <tr class="{{ (isset($item['is_replacing']) && $item['is_replacing']) ? 'line-through bg-gray-200 opacity-60' : 'bg-gray-50 hover:bg-gray-200 hover:shadow-lg' }} transition duration-200 rounded-2xl">
                        <td class="py-3 px-6 {{ $isSeribu && $withRab ? '' : 'hidden' }}">
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
                        
                        {{-- permision pengurus barang nama barang --}} 
                        <td class="py-3 px-6 ">
                            <select wire:model.live="list.{{ $index }}.merk" disabled
                                class="bg-gray-50 border border-gray-300 cursor-not-allowed  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                 @disabled(isset($item['is_replacing']) && $item['is_replacing'])>
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

                        {{-- permision pengurus barang spesifikasi --}}
                        <td class="py-3 px-6">
                           @if(auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 1)
                            {{-- @if(auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 1) --}}
                                <select 
                                    wire:model.live="list.{{ $index }}.merk_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    {{-- Dropdown akan di-disable jika 'Nama Barang' belum dipilih --}}
                                    @disabled(empty($item['barang_id']) || (isset($item['is_replacing']) && $item['is_replacing']))>
                                    {{-- <option value="">Pilih Spesifikasi</option> --}}
                                    
                                    {{-- Loop ke 'available_merks' yang sudah difilter secara otomatis di komponen --}}
                                    @if(!empty($item['available_merks']))
                                        @foreach ($item['available_merks'] as $merk)
                                            <option value="{{ $merk->id }}">
                                                {{ $merk->nama ?? '' }} - {{ $merk->tipe ?? '' }} - {{ $merk->ukuran ?? '' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            @else
                                {{-- Untuk tampilan disabled, lebih baik gunakan input text biasa agar lebih bersih --}}
                                <input 
                                    type="text"
                                    value="{{ $item['merk']->nama ?? '' }} - {{ $item['merk']->tipe ?? '' }} - {{ $item['merk']->ukuran ?? '' }}"
                                    class="bg-gray-50 border border-gray-300 cursor-not-allowed text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                    disabled
                                >
                            @endif

                            {{-- Pastikan error key-nya benar --}}
                            @error("list.{$index}.merk_id")
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                        
                        {{-- permision pengurus barang valume --}}
                        <td class="py-3 px-6">
                            <div class="flex items-center">
                                @if(auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 1)
                              {{-- @if(auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 1) --}}
                                    @php
                                        $stokTersedia = $item['stok_gudang'] ?? 0;
                                    @endphp
                                    <input type="number" 
                                        wire:model.live="list.{{ $index }}.jumlah" 
                                        min="1" 
                                        max="{{ $stokTersedia }}"
                                        placeholder="Maksimal: {{ $stokTersedia }} (berdasarkan stok gudang)"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        @disabled(!isset($item['merk']) || (isset($item['is_replacing']) && $item['is_replacing']))>
                                @else
                                    <input type="number" 
                                        value="{{ $item['jumlah'] }}" 
                                        placeholder="Maksimal: {{ $stokTersedia ?? 0 }} (berdasarkan stok gudang)"
                                        disabled
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg cursor-not-allowed block w-full p-2.5">
                                @endif
                                <span class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                    {{ isset($item['merk']) && isset($item['merk']->barangStok) ? $item['merk']->barangStok->satuanBesar->nama : '-' }}
                                </span>
                            </div>
                            
                            @php
                                $stokTersedia = $item['stok_gudang'] ?? 0;
                                $jumlahDiminta = $item['jumlah'] ?? 0;
                            @endphp
                            @if($jumlahDiminta > $stokTersedia && $stokTersedia > 0)
                                <span class="text-sm text-red-500 font-semibold">
                                    Melebihi stok gudang. Tersedia: {{ $stokTersedia }}, diminta: {{ $jumlahDiminta }}
                                </span>
                            @endif
                            
                            @error("list.{$index}.jumlah")
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>

                        {{-- <td class="py-3 px-6">
                            @if ($ruleAdd)
                                <button wire:click="addToList"
                                    class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                    <i class="fa-solid fa-circle-check"></i>
                                </button>
                            @endif
                        </td> --}}

                        <td class="px-6 py-3 {{ $isSeribu && $withRab ? '' : 'hidden' }}">
                            <textarea id="jumlah" wire:model.live="list.{{ $index }}.keterangan" disabled rows="2"
                                class="w-full border  border-gray-300 {{ $isSeribu && $withRab ? '' : 'hidden' }} cursor-not-allowed rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Keterangan (opsional)"></textarea>
                        </td>
                        @if ($isShow)
                            <td class="py-3 px-6 text-center align-top">
                                @if (
                                        $item['editable'] &&
                                        auth()->id() == $permintaan->user_id &&
                                        $permintaan->persetujuan()->where('is_approved', 1)->get()->unique('user_id')->count() >= 2
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
                            @if(auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 1 && !is_null($permintaan->rab_id) && isset($item['id']))
                                
                                @if(isset($item['is_replacing']) && $item['is_replacing'])
                                    {{-- Tombol Batal Ganti (X) --}}
                                    <button type="button" wire:click="undoReplacement({{ $index }})" class="text-danger-900 border-danger-600 border bg-danger-100 hover:bg-danger-600 hover:text-white rounded-lg px-3 py-1">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                @else
                                    {{-- Tombol Ganti Barang (Pensil) --}}
                                    <button type="button" wire:click="markForReplacement({{ $index }})" class="text-blue-900 border-blue-600 border bg-blue-100 hover:bg-blue-600 hover:text-white rounded-lg px-3 py-1">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                @endif

                            @elseif($showRule && !$isShow)
                                {{-- Tombol Hapus untuk Draft Baru (X Merah Biasa) --}}
                                <button wire:click="removeFromList({{ $index }})" class="text-danger-900 border-danger-600 border bg-danger-100 hover:bg-danger-600 hover:text-white rounded-lg px-3 py-1">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                            @endif

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
                            @if ($isShow)
                                @if ($permintaan->persetujuan()->where('is_approved', 1)->get()->unique('user_id')->count() >= 2)
                                    @php
                                        $isTersebar = $this->isMerkTersebar($item['merk']->id);
                                        $isAlocated = $item['merk']->permintaanMaterial()
                                            ->where('detail_permintaan_id', $permintaan->id)
                                            ->where('alocated', 1)
                                            ->exists();
                                    @endphp
                                    @if (auth()->user()->can('permintaan_persetujuan_jumlah_barang'))
                                        @if ($isAlocated)
                                            {{-- Detail Alokasi (readonly) --}}
                                            <button wire:click="openReadonlyAlokasiModal({{ $item['merk']->id }},{{ $index }})"
                                                class="text-green-800 border-green-600 text-sm border bg-green-100 hover:bg-green-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                                <i class="fa-solid fa-circle-info"></i> Lihat Alokasi </button>
                                        @elseif ($isTersebar && auth()->user()->can('permintaan_persetujuan_jumlah_barang'))
                                            <button wire:click="openDistribusiModal({{ $index }})"
                                                class="text-blue-900 border-blue-600 text-sm border bg-blue-100 hover:bg-blue-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                                <i class="fa-solid fa-boxes-stacked"></i> Alokasi
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-500 italic">Tidak Tersebar</span>
                                        @endif
                                    @endif

                                @endif
                            @endif
                        </td>
                    </tr>
            @endforeach
            @if (!$isShow)
                @if ($showRule)
                    {{-- @if (1) --}}
                    <tr
                        class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl {{ $isShow ? 'hidden' : '' }}">
                        <td class="py-3 px-6 {{ $isSeribu && $withRab ? '' : 'hidden' }}">
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

                        {{-- view form list add permintaan --}}
                        <td class="py-3 px-6 ">
                            @if ($rab_id)
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
                            @else
                                <livewire:searchable-select wire:model.live="newBarangId" :options="$barangs" />
                            @endif
                        </td>
                        <td class="py-3 px-6 ">
                            <select wire:model.live="newMerkId" @disabled(!$newBarangId)
                                class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg {{ !$newBarangId ? 'cursor-not-allowed' : '' }} focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Pilih Barang
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
                                <input type="number" wire:model.live="newJumlah" min="1" max="{{ $newMerkMax }}"
                                    @disabled(!$newMerkId)
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ !$newMerkId ? 'cursor-not-allowed' : '' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="{{ !$newMerkId ? 'Jumlah' : 'Maksimal: ' . $newMerkMax . ' (berdasarkan ' . ($withRab && $newRabId ? 'RAB & stok gudang' : 'stok gudang') . ')' }}">
                                <span
                                    class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                    {{ $newUnit }}
                                </span>
                            </div>
                            @error('newJumlah')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                        <td class="px-6 py-3 {{ $isSeribu && $withRab ? '' : 'hidden' }}">
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
                    </tr>
                @else
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td colspan="{{ $isSeribu && $withRab ? '6' : '4' }}" class="text-center text-xl px-3 py-6 font-bold">
                            Lengkapi Data
                            Kegiatan</td>
                    </tr>
                @endif
            @endif
        </tbody>
        @if ($showRule || $showAddForm)
            <tfoot>
                <tr class="bg-gray-50 rounded-2xl">
                    
                    {{-- 1. RKB (Tambahkan logic hidden ini agar sejajar dengan thead) --}}
                    <td class="py-3 px-6 {{ $isSeribu && $withRab ? '' : 'hidden' }}">
                        <select wire:model.live="newRabId" class="bg-gray-50 border border-gray-300 rounded-lg block w-full p-2.5">
                            <option value="">Pilih RKB</option>
                            @foreach ($rabs as $rab)
                                <option value="{{ $rab->id }}">{{ $rab->jenis_pekerjaan }}</option>
                            @endforeach
                        </select>
                    </td>
                    
                    {{-- 2. Nama Barang --}}
                    <td class="py-3 px-6">
                        <select wire:model.live="newBarangId" class="bg-gray-50 border border-gray-300 rounded-lg block w-full p-2.5">
                            <option value="">Pilih Barang</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    
                    {{-- 3. Spesifikasi --}}
                    <td class="py-3 px-6">
                        <select wire:model.live="newMerkId" @disabled(!$newBarangId) class="bg-gray-50 border border-gray-300 rounded-lg block w-full p-2.5">
                            <option value="">Pilih Spesifikasi</option>
                            @foreach ($merks as $merk)
                                <option value="{{ $merk->id }}">{{ $merk->nama }} - {{ $merk->tipe }}</option>
                            @endforeach
                        </select>
                    </td>

                    {{-- 4. Volume --}}
                    <td class="py-3 px-6">
                        <div class="flex items-center">
                            <input type="number" wire:model.live="newJumlah" placeholder="Max: {{ $newMerkMax }}" class="bg-gray-50 border border-gray-300 rounded-l-lg block w-full p-2.5">
                            <span class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5">{{ $newUnit }}</span>
                        </div>
                    </td>

                    {{-- 5. Keterangan (Tambahkan logic hidden juga di sini agar sejajar) --}}
                    <td class="px-6 py-3 {{ $isSeribu && $withRab ? '' : 'hidden' }}">
                        <textarea wire:model.live="newKeterangan" rows="1" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Ket."></textarea>
                    </td>

                    {{-- 6. Foto (Hanya muncul jika isShow true) --}}
                    @if($isShow) 
                        <td></td> 
                    @endif 

                    {{-- 7. Tombol Aksi --}}
                    <td class="py-3 px-6 text-center">
                        <button wire:click="addToList" class="text-primary-900 border-primary-600 border bg-primary-100 hover:bg-primary-600 hover:text-white rounded-lg px-3 py-1">
                            <i class="fa-solid fa-circle-check"></i>
                        </button>
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- simpan perubahan pada Daftar Permintaan Material user Pengurus Barang --}}
    <div class="flex justify-center">
    {{-- Tombol ini HANYA akan muncul jika $isDataChanged bernilai true --}}    
    @if ($isDataChanged)
        <button 
            wire:click="updateData" {{-- Panggil method baru untuk update --}}
            wire:loading.attr="disabled"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
            <span wire:loading.remove wire:target="updateData">
                Simpan Perubahan
            </span>
            <span wire:loading wire:target="updateData">
                Menyimpan...
            </span>
        </button>
    @endif
    </div>

    <div class="flex justify-center">
        {{-- @role('penanggungjawab') --}}
        @if (count($list) > 0 && $showRule && !$isShow)
            <div class="flex gap-3">
                <button wire:click="saveDraft" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Simpan Draft
                </button>
                <button wire:click="saveData" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Submit Permintaan
                </button>
            </div>
        @endif
        {{-- @endrole --}}
    </div>
    @if (!is_null($distribusiModalIndex))
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl relative">
                <button wire:click="$set('distribusiModalIndex', null)"
                    class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
                <h2 class="text-lg font-bold mb-4">Alokasikan Stok</h2>
                <p class="mb-2 text-sm text-gray-700">Sisa yang harus dialokasikan: <strong>{{ $alokasiSisa }}</strong></p>
                <table class="w-full border text-sm mb-4">
                    <thead>
                        <tr class="bg-gray-200">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="px-4 py-2">Lokasi</th>
                                    <th class="px-4 py-2">Bagian</th>
                                    <th class="px-4 py-2">Posisi</th>
                                    <th class="px-4 py-2 text-center">Stok Tersedia</th>
                                    <th class="px-4 py-2 text-center">Jumlah</th>
                                </tr>
                            </thead>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stokDistribusiList as $lokasiKey => $jumlahTersedia)
                            @php
                                [$type, $id] = explode(':', $lokasiKey);
                                $lokasi = '-';
                                $bagian = '-';
                                $posisi = '-';

                                if ($type === 'lokasi') {
                                    $lokasiModel = \App\Models\LokasiStok::find($id);
                                    $lokasi = $lokasiModel->nama ?? '-';
                                } elseif ($type === 'bagian') {
                                    $bagianModel = \App\Models\BagianStok::find($id);
                                    $lokasi = $bagianModel->lokasiStok->nama ?? '-';
                                    $bagian = $bagianModel->nama ?? '-';
                                } elseif ($type === 'posisi') {
                                    $posisiModel = \App\Models\PosisiStok::find($id);
                                    $lokasi = $posisiModel->bagianStok->lokasiStok->nama ?? '-';
                                    $bagian = $posisiModel->bagianStok->nama ?? '-';
                                    $posisi = $posisiModel->nama ?? '-';
                                }
                            @endphp

                            <tr>
                                <td class="px-4 py-2">{{ $lokasi }}</td>
                                <td class="px-4 py-2">{{ $bagian }}</td>
                                <td class="px-4 py-2">{{ $posisi }}</td>
                                <td class="px-4 py-2 text-center">{{ $jumlahTersedia }}</td>
                                <td class="px-4 py-2 text-center">
                                    <input type="number" min="0" max="{{ $jumlahTersedia }}"
                                        wire:model.live="alokasiInput.{{ $lokasiKey }}"
                                        class="w-20 border rounded px-2 py-1 text-center" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex justify-end">
                    <button wire:click="submitDistribusi"
                        class=" {{ $alokasiSisa != 0 ? 'bg-primary-400 cursor-not-allowed' : 'bg-primary-600 hover:bg-primary-700' }} transition duration-100 text-white font-semibold px-4 py-2 rounded "
                        @disabled($alokasiSisa != 0)>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif
    @if ($readonlyAlokasiMerkId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl relative">
                <button wire:click="$set('readonlyAlokasiMerkId', null)"
                    class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
                <h2 class="text-lg font-bold mb-4">Detail Alokasi Stok</h2>
                <table class="w-full text-sm border rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Lokasi</th>
                            <th class="px-4 py-2">Bagian</th>
                            <th class="px-4 py-2">Posisi</th>
                            <th class="px-4 py-2 text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->getAlokasiByMerk($readonlyAlokasiMerkId, $readonlyAlokasiIndex) as $alok)
                            <tr>
                                <td class="px-4 py-2">{{ $alok->lokasiStok->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $alok->bagianStok->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $alok->posisiStok->nama ?? '-' }}</td>
                                <td class="px-4 py-2 text-center">{{ $alok->jumlah_disetujui }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>