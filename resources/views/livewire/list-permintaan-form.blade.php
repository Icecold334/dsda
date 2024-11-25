<div>
    <div>
        <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
            <thead>
                <tr class="text-white uppercase">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA BARANG</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH *</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH disetujui</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DOKUMEN PENDUKUNG</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold "></th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
                </tr>
            </thead>
            <tbody>
                @if ($tanggal_permintaan && $keterangan && $unit_id)
                    @foreach ($list as $index => $item)
                        <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                            <!-- Empty Column -->
                            <td class="py-3 px-6"></td>

                            <!-- NAMA BARANG Column -->
                            <td class="py-3 px-6">
                                <input type="text" wire:model.live="list.{{ $index }}.barang_name" disabled
                                    wire:focus="focusBarang" wire:blur="blurBarang"
                                    placeholder="Cari atau Tambah Barang"
                                    class="block w-full px-4 py-2 cursor-not-allowed text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </td>

                            <!-- JUMLAH Column -->
                            <td class="py-3 px-6">
                                <div class="flex items-center">
                                    <input type="number" wire:model.live="list.{{ $index }}.jumlah"
                                        min="1" disabled
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg cursor-not-allowed focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="Jumlah">
                                    <span
                                        class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                        {{ $item['satuan'] }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex items-center">
                                    <input type="number" wire:model.live="list.{{ $index }}.jumlah_approve"
                                        min="1" @disabled(!auth()->user()->hasRole('penanggungjawab') || $item['status'])
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ !auth()->user()->hasRole('penanggungjawab') || $item['status'] ? 'cursor-not-allowed' : '' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="Jumlah Disetujui">
                                    <span
                                        class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                        {{ $item['satuan'] }}
                                    </span>
                                </div>
                            </td>

                            <!-- DOKUMEN PENDUKUNG Column -->
                            <td class="py-3 px-6 text-center">
                                @if ($permintaan && is_null($permintaan->status))
                                    @role('penanggungjawab')
                                        @if ($item['dokumen'])
                                            <!-- Tampilkan gambar dan tombol hapus jika dokumen sudah diunggah -->
                                            <div class="relative inline-block">
                                                <a href="{{ is_string($item['dokumen']) ? Storage::url($item['dokumen']) : $item['dokumen']->temporaryUrl() }}"
                                                    target="_blank">
                                                    <img src="{{ is_string($item['dokumen']) ? Storage::url($item['dokumen']) : $item['dokumen']->temporaryUrl() }}"
                                                        alt="Uploaded Document" class="w-16 h-16 rounded-md">
                                                </a>
                                                <button wire:click="removeDocument({{ $index }})"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-700 text-xs">
                                                    &times;
                                                </button>
                                            </div>
                                        @else
                                            <!-- Tombol untuk mengunggah dokumen jika belum ada dokumen yang diunggah -->
                                            <input type="file" wire:model.live="list.{{ $index }}.dokumen"
                                                class="hidden" id="upload-dokumen-new">
                                            <button type="button"
                                                onclick="document.getElementById('upload-dokumen-new').click()"
                                                class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                                Unggah Foto
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-gray-500">Belum ada unggahan</span>
                                    @endrole
                                @else
                                    <!-- Tidak ada permintaan, hanya penanggung jawab yang bisa upload/edit -->
                                    @role('penanggungjawab')
                                        @if ($item['dokumen'])
                                            <div class="relative inline-block">
                                                <a href="{{ Storage::url($item['dokumen']) }}" target="_blank">
                                                    <img src="{{ Storage::url($item['dokumen']) }}" alt="Uploaded Document"
                                                        class="w-16 h-16 rounded-md">
                                                </a>
                                                <button wire:click="removeNewDokumen"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-700 text-xs">
                                                    &times;
                                                </button>
                                            </div>
                                        @else
                                            <input type="file" wire:model.live="newDokumen" class="hidden"
                                                id="upload-dokumen-new">
                                            <button type="button"
                                                onclick="document.getElementById('upload-dokumen-new').click()"
                                                class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                                Unggah Foto
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-gray-500">Belum ada unggahan</span>
                                    @endrole
                                @endif
                            </td>
                            <td class="py-3 px-6 text-center">
                                @if (!$item['id'])
                                    <button wire:click="removeFromList({{ $index }})"
                                        class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </button>
                                @endif
                            </td>

                            <!-- Remove Button Column -->
                            <td class="text-center py-3 px-6">
                                @if (!empty($item['jumlah_approve']) && auth()->user()->hasRole('penanggungjawab') && !$item['status'] && $item['id'])
                                    <button wire:click="approveItem({{ $index }})"
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Simpan
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="py-3 px-6"></td>

                        <td class="py-3 px-6 relative">
                            <input type="text" wire:model.live.debounce.300ms="newBarang" wire:focus="focusBarang"
                                wire:blur="blurBarang" placeholder="Cari atau Tambah Barang"
                                class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            @foreach ($barangSuggestions as $barang)
                                <li class="px-4 py-2 font-bold text-gray-900 bg-gray-100 cursor-default">
                                    {{ $barang->nama }}
                                    @foreach ($barang->merkStok as $merk)
                                        @php
                                            $totalStock = $merk->stok->sum('total_jumlah'); // Calculate total stock for this merk
                                        @endphp
                                <li wire:click="selectMerk({{ $merk->id }})"
                                    class="px-6 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                    Merk: {{ $merk->nama }} | Tipe: {{ $merk->tipe }} | Ukuran:
                                    {{ $merk->ukuran }} | Stock: {{ $totalStock }}
                                </li>
                            @endforeach
                            </ul>
                @endforeach

                </td>


                <td class="py-3 px-6">
                    <div class="flex items-center">
                        <input type="number" wire:model.live="newJumlah" min="1"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Jumlah">
                        <span
                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                            {{ $newUnit }}
                        </span>
                    </div>
                </td>
                <td class="py-3 px-6">
                    <div class="flex items-center">
                        <input type="number" min="1" @disabled(true)
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ true ? 'cursor-not-allowed' : '' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Diisi oleh penanggung jawab">
                        <span
                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                            {{ $newUnit }}
                        </span>
                    </div>
                </td>


                <td class="text-center py-3 px-6">
                    @if ($permintaan && is_null($permintaan->status))
                        @role('penanggungjawab')
                            @if ($newDokumen)
                                <!-- Tampilkan gambar dan tombol hapus jika dokumen sudah diunggah -->
                                <div class="relative inline-block">
                                    <a href="{{ $newDokumen->temporaryUrl() }}" target="_blank">
                                        <img src="{{ $newDokumen->temporaryUrl() }}" alt="Uploaded Document"
                                            class="w-16 h-16 rounded-md">
                                    </a>
                                    <button wire:click="removeNewDokumen"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-700 text-xs">
                                        &times;
                                    </button>
                                </div>
                            @else
                                <!-- Tombol untuk mengunggah dokumen jika belum ada dokumen yang diunggah -->
                                <input type="file" wire:model.live="newDokumen" class="hidden"
                                    id="upload-dokumen-new">
                                <button type="button" onclick="document.getElementById('upload-dokumen-new').click()"
                                    class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                    Unggah Foto
                                </button>
                            @endif
                        @else
                            <span class="text-gray-500">Belum ada unggahan</span>
                        @endrole
                    @else
                        <!-- Tidak ada permintaan, hanya penanggung jawab yang bisa upload/edit -->
                        @role('penanggungjawab')
                            @if ($newDokumen)
                                <div class="relative inline-block">
                                    <a href="{{ $newDokumen->temporaryUrl() }}" target="_blank">
                                        <img src="{{ Storage::url($newDokumen) }}" alt="Uploaded Document"
                                            class="w-16 h-16 rounded-md">
                                    </a>
                                    <button wire:click="removeNewDokumen"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-700 text-xs">
                                        &times;
                                    </button>
                                </div>
                            @else
                                <input type="file" wire:model.live="newDokumen" class="hidden"
                                    id="upload-dokumen-new">
                                <button type="button" onclick="document.getElementById('upload-dokumen-new').click()"
                                    class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                    Unggah Foto
                                </button>
                            @endif
                        @else
                            <span class="text-gray-500">Belum ada unggahan</span>
                        @endrole
                    @endif


                </td>


                <td class="py-3 px-6 text-center">
                    @if ($newBarang && $newJumlah)
                        <button wire:click="addToList"
                            class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                            <i class="fa-solid fa-circle-check"></i>
                        </button>
                    @endif
                </td>
                </tr>
            @else
                <tr>
                    <td colspan="8" class="text-center">Lengkapi data diatas terlebih dahulu</td>
                </tr>

                @endif
            </tbody>
        </table>



        <div class="flex justify-center">
            {{-- @role('penanggungjawab') --}}
            @if (count($this->list) > 0)
                <button wire:click="saveData"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
            @endif
            {{-- @endrole --}}

        </div>
    </div>
</div>
