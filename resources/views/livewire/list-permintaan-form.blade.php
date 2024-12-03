<div>
    <div>
        <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
            <thead>
                <tr class="text-white uppercase">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA BARANG</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH *</th>
                    @if (!$showAdd)
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH disetujui</th>
                    @endif
                    {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DOKUMEN PENDUKUNG</th> --}}
                    <th class="py-3 px-6 bg-primary-950 w-1/6 text-center font-semibold rounded-r-lg"></th>
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
                            @if (!$showAdd)
                                <td class="py-3 px-6">
                                    <div class="flex items-center">
                                        <input type="number" wire:model.live="list.{{ $index }}.jumlah_approve"
                                            min="1" disabled
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ true ? 'cursor-not-allowed' : '' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            placeholder="Jumlah Disetujui">
                                        <span
                                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                            {{ $item['satuan'] }}
                                        </span>
                                    </div>
                                </td>
                            @endif


                            <td class="py-3 px-6 text-center">
                                @if (!$item['id'])
                                    <button wire:click="removeFromList({{ $index }})"
                                        class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </button>
                                @else
                                    @if (isset($item['jumlah_approve']) && $item['jumlah_approve'] > 0)
                                        <!-- Tombol untuk melihat catatan -->
                                        <button wire:click="openNoteModal({{ $item['id'] }})"
                                            class="text-primary-700 border-primary-500 text-sm border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                            Lihat Catatan
                                        </button>
                                    @else
                                        @can('permintaan_persetujuan_jumlah_barang')
                                            @if (in_array($item['detail_permintaan_id'], $approvals))
                                                <!-- Tombol untuk menyetujui -->
                                                <button wire:click="openApprovalModal({{ $item['id'] }})"
                                                    class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                                    Setujui
                                                </button>
                                            @else
                                                <div>Menunggu Persetujuan Kepala Seksi</div>
                                            @endif
                                        @endcan
                                    @endif
                                @endif
                            </td>


                        </tr>
                    @endforeach
                    @if ($showAdd)
                        <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                            <td class="py-3 px-6"></td>

                            <td class="py-3 px-6 relative">
                                <input type="text" wire:model.live="newBarang" wire:focus="focusBarang"
                                    wire:blur="blurBarang" placeholder="Cari atau Tambah Barang"
                                    class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                <ul
                                    class="absolute z-10 w-96 bg-white border border-gray-300 rounded-lg mt-2 max-h-60 overflow-auto shadow-lg">
                                    @foreach ($barangSuggestions as $barang)
                                        <li class="px-4 py-2 font-bold text-gray-900 bg-gray-100 cursor-default">
                                            {{ $barang->nama }}</li>
                                        @foreach ($barang->merkStok as $merk)
                                            <li wire:click="selectMerk({{ $merk->merk_id }})"
                                                class="px-6 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                                Merk: {{ $merk->nama ?? '-' }} | Tipe: {{ $merk->tipe ?? '-' }} |
                                                Ukuran:
                                                {{ $merk->ukuran ?? '-' }}
                                            </li>
                                        @endforeach
                                    @endforeach
                                </ul>


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
                            @if (!$showAdd)
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
                            @endif


                            {{-- <td class="text-center py-3 px-6">
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


                            </td> --}}


                            <td class="py-3 px-6 text-center">
                                @if ($newBarang && $newJumlah)
                                    <button wire:click="addToList"
                                        class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="8" class="text-center">Lengkapi data diatas terlebih dahulu</td>
                    </tr>

                @endif
            </tbody>
        </table>



        <div class="flex justify-center">
            {{-- @role('penanggungjawab') --}}
            @if (count($this->list) > 0 && $showAdd)
                <button wire:click="saveData"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
            @endif
            {{-- @endrole --}}

        </div>
    </div>
    @if ($showApprovalModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-2/3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold">Setujui Permintaan Barang</h3>
                    <button wire:click="$set('showApprovalModal', false)" class="text-gray-500 hover:text-gray-800">
                        &times;
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4 space-y-4">
                    <p><strong>Nama Barang:</strong> {{ $approvalData['merk']->barangStok->nama ?? '-' }}</p>
                    <p><strong>Merk:</strong> {{ $approvalData['merk']->nama ?? '-' }}</p>
                    <p><strong>tipe:</strong> {{ $approvalData['merk']->tipe ?? '-' }}</p>
                    <p><strong>Ukuran:</strong> {{ $approvalData['merk']->ukuran ?? '-' }}</p>
                    <p><strong>Jumlah Permintaan:</strong> {{ $approvalData['jumlah_permintaan'] }}</p>

                    <!-- Daftar Lokasi, Jumlah, dan Catatan -->
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="border px-4 py-2">Lokasi</th>
                                <th class="border px-4 py-2">Bagian</th>
                                <th class="border px-4 py-2">Posisi</th>
                                <th class="border px-4 py-2">Jumlah Tersedia</th>
                                <th class="border px-4 py-2">Jumlah Disetujui</th>
                                <th class="border px-4 py-2">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($approvalData['stok'] as $index => $stok)
                                <tr>
                                    <td class="border px-4 py-2">{{ $stok['lokasi'] }}</td>
                                    <td class="border px-4 py-2">{{ $stok['bagian'] ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ $stok['posisi'] ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ $stok['jumlah_tersedia'] }}</td>
                                    <td class="border px-4 py-2">
                                        <input type="number"
                                            wire:model="approvalData.stok.{{ $index }}.jumlah_disetujui"
                                            class="w-full border rounded px-2 py-1" min="0"
                                            max="{{ $stok['jumlah_tersedia'] }}">
                                    </td>
                                    <td class="border px-4 py-2">
                                        <textarea wire:model="approvalData.stok.{{ $index }}.catatan" class="w-full border rounded px-2 py-1"
                                            rows="1" placeholder="Tambahkan catatan"></textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end p-4 border-t">
                    <button wire:click="approveItems"
                        class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                        Setujui
                    </button>
                    <button wire:click="$set('showApprovalModal', false)"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    @endif


    @if ($noteModalVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-1/2">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold">Catatan Persetujuan</h3>
                    <button wire:click="$set('noteModalVisible', false)" class="text-gray-500 hover:text-gray-800">
                        &times;
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4 space-y-4">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="border px-4 py-2">Lokasi</th>
                                <th class="border px-4 py-2">Bagian</th>
                                <th class="border px-4 py-2">Posisi</th>
                                <th class="border px-4 py-2">Jumlah Disetujui</th>
                                <th class="border px-4 py-2">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedItemNotes as $note)
                                <tr>
                                    <td class="border px-4 py-2">{{ $note['lokasi'] }}</td>
                                    <td class="border px-4 py-2">{{ $note['bagian'] }}</td>
                                    <td class="border px-4 py-2">{{ $note['posisi'] }}</td>
                                    <td class="border px-4 py-2">{{ $note['jumlah_disetujui'] }}</td>
                                    <td class="border px-4 py-2">{{ $note['catatan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end p-4 border-t">
                    <button wire:click="$set('noteModalVisible', false)"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
