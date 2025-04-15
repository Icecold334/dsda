<div>
    <div>
        <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
            <thead>
                <tr class="text-white uppercase">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                    @if ($requestIs == 'spare-part' || optional($permintaan?->jenisStok)->nama == 'Spare Part')
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">JENIS KDO</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DESKRIPSI KERUSAKAN</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">CATATAN</th>
                    @endif
                    @if ($requestIs == 'material' || optional($permintaan?->jenisStok)->nama == 'Material')
                        {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">LOKASI PENGGUNAAN</th> --}}
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KEPERLUAN</th>
                    @endif
                    @if ($kategori_id == 6)
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">PILIH KDO *</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DRIVER / PENANGGUNG JAWAB *
                        </th>
                    @endif
                    @if ($kategori_id != 6)
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA
                            {{ $kategori_id == 4 ? 'Konsumsi' : ($kategori_id == 5 ? 'Tipe Service' : 'Barang') }}
                        </th>
                    @endif
                    @if ($kategori_id != 5 && $kategori_id != 6)
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH *</th>
                    @endif
                    @if ($kategori_id == 5)
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KETERANGAN</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KONDISI AWAL*</th>
                    @endif
                    @if ($kategori_id == 6)
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">FOTO KENDARAAN*</th>
                    @endif
                    @if (!$showAdd && !in_array($kategori_id, [4, 5, 6]))
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH disetujui</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">FOTO STOCK</th>
                    @endif
                    @if (!$showAdd && $kategori_id == 5)
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">KONDISI AKHIR</th>
                    @endif
                    @if (!$showAdd && $kategori_id == 6)
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">FOTO PROSES</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">NAMA VOUCHER</th>
                    @endif
                    @if (
                        $requestIs == 'spare-part' ||
                            optional($permintaan?->jenisStok)->nama == 'Spare Part' ||
                            $requestIs == 'material' ||
                            optional($permintaan?->jenisStok)->nama == 'Material')
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[10%]"></th>
                    @endif
                    {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DOKUMEN PENDUKUNG</th> --}}
                    <th class="py-3 px-6 bg-primary-950 w-1/12 text-center font-semibold rounded-r-lg ">
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($ruleShow)
                    {{-- @if (true) --}}
                    @foreach ($list as $index => $item)
                        <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                            <!-- Empty Column -->
                            <td class="py-3 px-6"></td>
                            @if ($requestIs == 'spare-part' || optional($permintaan?->jenisStok)->nama == 'Spare Part')
                                <!-- Jenis KDO -->
                                <td class="py-3 px-6">
                                    <input type="text" disabled wire:model.live="list.{{ $index }}.aset_name"
                                        wire:focus="focusAset" wire:blur="blurAset" placeholder="Cari atau Tambah KDO"
                                        class="block cursor-not-allowed w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                </td>

                                <!-- Deskripsi Kerusakan -->
                                <td class="px-6 py-3">
                                    <textarea id="deskripsiKerusakan" disabled wire:model.live="list.{{ $index }}.deskripsi" rows="2"
                                        class="w-full border cursor-not-allowed border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Deskripsikan kerusakan secara detail"></textarea>
                                </td>

                                <!-- Catatan -->
                                <td class="px-6 py-3">
                                    <textarea id="catatan" disabled wire:model.live="list.{{ $index }}.catatan" rows="2"
                                        class="w-full border cursor-not-allowed border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                </td>
                            @endif
                            @if ($requestIs == 'material' || optional($permintaan?->jenisStok)->nama == 'Material')
                                <td class="px-6 py-3">
                                    <textarea id="deskripsiKerusakan" disabled wire:model.live="list.{{ $index }}.deskripsi" rows="2"
                                        class="w-full border cursor-not-allowed border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Deskripsikan keperluan"></textarea>
                                </td>
                            @endif

                            @if ($kategori_id == 6)
                                <td class="py-3 px-6">
                                    @if (!is_null($list[$index]['aset_id']))
                                        <select wire:model.live="list.{{ $index }}.aset_id" disabled
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="">Pilih KDO</option>
                                            @foreach ($asets as $aset)
                                                <option value="{{ $aset->id }}">
                                                    {{ $aset->merk->nama . ' ' . $aset->nama . ' - ' . $aset->noseri . ' | ' . $aset->tipe }}
                                                </option>
                                            @endforeach
                                            <option value="0">KDO Lain</option>
                                        </select>
                                        @error("list.$index.aset_id")
                                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                        @enderror
                                    @endif

                                    @if (is_null($list[$index]['aset_id']))
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="list.{{ $index }}.noseri"
                                                disabled
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                placeholder="No. Plat">

                                            <input type="text" wire:model.live="list.{{ $index }}.jenis_kdo"
                                                disabled
                                                class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                placeholder="Jenis Mobil">

                                            <input type="text" wire:model.live="list.{{ $index }}.nama_kdo"
                                                disabled
                                                class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                placeholder="Merk / Nama Mobil">
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <textarea id="namadriver" disabled wire:model.live="list.{{ $index }}.driver_name" rows="1"
                                        class="w-full border cursor-not-allowed border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Masukan Nama Driver"></textarea>
                                </td>
                                {{-- <td class="py-3 px-6">
                                    <select wire:model.live="list.{{ $index }}.driver_id" disabled
                                        class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Pilih Driver
                                        </option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">
                                                {{ $driver->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error("list.$index.driver_id")
                                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                    @enderror
                                </td> --}}
                            @endif

                            <!-- NAMA BARANG Column -->
                            @if ($kategori_id != 6)
                                <td class="py-3 px-6">
                                    <select wire:model.live="list.{{ $index }}.barang_id" disabled
                                        class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                        <option value="">Pilih
                                            {{ $kategori_id == 4 ? 'Konsumsi' : ($kategori_id == 5 ? 'Tipe Service' : 'Barang') }}
                                        </option>
                                        @foreach ($availBarangs as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error("list.$index.barang_id")
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </td>
                            @endif

                            @if ($kategori_id != 5 && $kategori_id != 6)
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
                            @endif

                            @if ($kategori_id == 5)
                                <!-- Deskripsi Kerusakan -->
                                <td class="px-6 py-3">
                                    <textarea id="deskripsiKerusakan" disabled wire:model.live="list.{{ $index }}.deskripsi" rows="2"
                                        class="w-full border cursor-not-allowed border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Deskripsikan kerusakan secara detail"></textarea>
                                </td>
                            @endif

                            @if ($kategori_id == 5 || $kategori_id == 6)
                                <td class="px-6 py-3 text-center">
                                    <div class="relative inline-block">
                                        @if (is_string($item['dokumen']))
                                            <!-- Jika newBukti adalah string (path file) -->
                                            <a href="{{ asset('storage/buktikdo/' . $item['dokumen']) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/buktikdo/' . $item['dokumen']) }}"
                                                    alt="Preview Bukti" class="w-16 h-16 rounded-md">
                                            </a>
                                        @elseif (is_object($item['dokumen']) && method_exists($item['dokumen'], 'temporaryUrl'))
                                            <!-- Jika newBukti adalah file Livewire upload -->
                                            <a href="{{ $item['dokumen']->temporaryUrl() }}" target="_blank">
                                                <img src="{{ $item['dokumen']->temporaryUrl() }}" alt="Preview Bukti"
                                                    class="w-16 h-16 rounded-md">
                                            </a>
                                        @else
                                            <span class="text-gray-500">Bukti tidak valid</span>
                                        @endif
                                    </div>
                            @endif
                            </td>
                            @if (!$showAdd && !in_array($kategori_id, [4, 5, 6]))
                                <td class="py-3 px-6">
                                    <div class="flex items-center">
                                        <input type="number"
                                            wire:model.live="list.{{ $index }}.jumlah_approve" min="1"
                                            disabled
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg {{ true ? 'cursor-not-allowed' : '' }} focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            placeholder="Jumlah Disetujui">
                                        <span
                                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                            {{ $item['satuan'] }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-3 text-center">
                                    <div class="relative inline-block">
                                        @if (is_string($item['img_done']))
                                            <!-- Jika img_done adalah string (path file) -->
                                            <div class="relative inline-block">
                                                <a href="{{ asset('storage/kondisiKdo/' . $item['img_done']) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/kondisiKdo/' . $item['img_done']) }}"
                                                        alt="Preview Bukti" class="w-16 h-16 rounded-md">
                                                </a>
                                            </div>
                                        @elseif (is_object($item['img_done']) && method_exists($item['img_done'], 'temporaryUrl'))
                                            <!-- Jika img_done adalah file upload Livewire -->
                                            <div class="relative inline-block">
                                                <a href="{{ $item['img_done']->temporaryUrl() }}" target="_blank">
                                                    <img src="{{ $item['img_done']->temporaryUrl() }}"
                                                        alt="Preview Bukti" class="w-16 h-16 rounded-md">
                                                </a>
                                                <button wire:click="removeBukti({{ $index }})"
                                                    class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 text-white rounded-full text-xs hover:bg-red-700">
                                                    &times;
                                                </button>
                                            </div>
                                        @else
                                            <!-- Jika img_done kosong, tampilkan tombol unggah -->
                                            <input type="file" wire:model.live="list.{{ $index }}.img_done"
                                                class="hidden" id="upload-list.{{ $index }}.img_done">
                                            @php
                                                $isPenjagaGudang = auth()->user()?->hasRole('Penjaga Gudang');
                                            @endphp

                                            <button type="button"
                                                onclick="document.getElementById('upload-list.{{ $index }}.img_done').click()"
                                                class="text-primary-700 bg-gray-200 border text-sm border-primary-500 rounded-lg px-3 py-1.5 transition
                                            {{ !$isPenjagaGudang ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'hover:bg-primary-600 hover:text-white' }}"
                                                {{ !$isPenjagaGudang ? 'disabled' : '' }}>
                                                Unggah Foto
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            @endif
                            @if (!$showAdd && in_array($kategori_id, [5, 6]))
                                <td class="px-6 py-3 text-center">
                                    <div class="relative inline-block">
                                        @if (is_string($item['img_done']))
                                            <!-- Jika img_done adalah string (path file) -->
                                            <div class="relative inline-block">
                                                <a href="{{ asset('storage/kondisiKdo/' . $item['img_done']) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/kondisiKdo/' . $item['img_done']) }}"
                                                        alt="Preview Bukti" class="w-16 h-16 rounded-md">
                                                </a>
                                            </div>
                                        @elseif (is_object($item['img_done']) && method_exists($item['img_done'], 'temporaryUrl'))
                                            <!-- Jika img_done adalah file upload Livewire -->
                                            <div class="relative inline-block">
                                                <a href="{{ $item['img_done']->temporaryUrl() }}" target="_blank">
                                                    <img src="{{ $item['img_done']->temporaryUrl() }}"
                                                        alt="Preview Bukti" class="w-16 h-16 rounded-md">
                                                </a>
                                                <button wire:click="removeBukti({{ $index }})"
                                                    class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 text-white rounded-full text-xs hover:bg-red-700">
                                                    &times;
                                                </button>
                                            </div>
                                        @else
                                            <!-- Jika img_done kosong, tampilkan tombol unggah -->
                                            <input type="file" wire:model.live="list.{{ $index }}.img_done"
                                                class="hidden" id="upload-list.{{ $index }}.img_done">
                                            <button type="button"
                                                @if (auth()->id() == $item['user_id'] && $item['detail_permintaan_status']) onclick="document.getElementById('upload-list.{{ $index }}.img_done').click()" @endif
                                                class="text-primary-700 bg-gray-200 border text-sm border-primary-500 rounded-lg px-3 py-1.5 transition
                                            @if (auth()->id() != $item['user_id'] || !$item['detail_permintaan_status']) opacity-50 cursor-not-allowed pointer-events-none 
                                            @else 
                                                hover:bg-primary-600 hover:text-white @endif"
                                                @if (auth()->id() != $item['user_id'] || !$item['detail_permintaan_status']) disabled @endif>
                                                Unggah Foto
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                @if ($kategori_id == 6)
                                    <td class="px-6 py-3 text-center">
                                        <div class="relative inline-block">
                                            @can('permintaan_persetujuan_jumlah_barang')
                                                <input type="text"
                                                    wire:model.live="list.{{ $index }}.voucher_name"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-blue-500 focus:border-blue-500
                                                {{ auth()->id() === $item['user_id'] ? 'cursor-not-allowed bg-gray-200' : '' }}"
                                                    placeholder="Nama Voucher"
                                                    @if (auth()->id() === $item['user_id']) disabled @endif>
                                            @endcan
                                        </div>
                                    </td>
                                @endif
                            @endif
                            @if (
                                $requestIs == 'spare-part' ||
                                    optional($permintaan?->jenisStok)->nama == 'Spare Part' ||
                                    $requestIs == 'material' ||
                                    optional($permintaan?->jenisStok)->nama == 'Material')
                                <td class="px-6 py-3 text-center">
                                    <div class="relative inline-block">
                                        @if (is_string($item['img']))
                                            <!-- Jika newBukti adalah string (path file) -->
                                            <a href="{{ asset('storage/kondisiKdo/' . $item['img']) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/kondisiKdo/' . $item['img']) }}"
                                                    alt="Preview Bukti" class="w-16 h-16 rounded-md">
                                            </a>
                                        @elseif (is_object($item['img']) && method_exists($item['img'], 'temporaryUrl'))
                                            <!-- Jika newBukti adalah file Livewire upload -->
                                            <a href="{{ $item['img']->temporaryUrl() }}" target="_blank">
                                                <img src="{{ $item['img']->temporaryUrl() }}" alt="Preview Bukti"
                                                    class="w-16 h-16 rounded-md">
                                            </a>
                                        @else
                                            <span class="text-gray-500 text-sm">Tidak ada unggahan</span>
                                        @endif
                                        {{-- <button wire:click="removePhoto"
                                                class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 text-white rounded-full text-xs hover:bg-red-700">
                                                &times;
                                            </button> --}}
                                    </div>

                                </td>
                            @endif

                            <td class="py-3 px-6 text-center {{ $kategori_id != 4 ? '' : 'hidden' }}">
                                @if (!$item['id'])
                                    <button wire:click="removeFromList({{ $index }})"
                                        class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </button>
                                @else
                                    @if (!in_array($kategori_id, [5, 6]))
                                        @if (isset($item['jumlah_approve']) && $item['jumlah_approve'] > 0)
                                            <!-- Tombol untuk melihat catatan -->
                                            <button wire:click="openNoteModal({{ $item['id'] }})"
                                                class="text-primary-700 border-primary-500 text-sm border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                                Lihat Catatan
                                            </button>
                                        @else
                                            @can('permintaan_persetujuan_jumlah_barang')
                                                {{-- @if (in_array($item['detail_permintaan_id'], $approvals)) --}}
                                                <!-- Tombol untuk menyetujui -->
                                                <button wire:click="openApprovalModal({{ $item['id'] }})"
                                                    class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                                    Detail
                                                </button>
                                                {{-- @else --}}
                                                {{-- <div class="text-sm">Menunggu {{ $approve_after }}</div> --}}
                                                {{-- @endif --}}
                                            @endcan
                                        @endif
                                    @endif
                                    @if (is_object($item['img_done']) && method_exists($item['img_done'], 'temporaryUrl'))
                                        <!-- Jika file baru diunggah (Livewire) -->
                                        <button onclick="confirmItem({{ $index }})"
                                            class="text-success-900 border-success-600 text-xl border bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    @endif

                                    @if ($kategori_id == 6 && auth()->id() !== $item['user_id'])
                                        <button onclick="confirmVoucherName({{ $index }})"
                                            class="text-success-900 border-success-600 text-xl border bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    @endif
                                @endif
                            </td>
                            @push('scripts')
                                <script>
                                    function confirmItem(index) {
                                        Swal.fire({
                                            title: 'Keterangan',
                                            input: 'textarea',
                                            inputPlaceholder: 'Masukkan keterangan (Opsional)',
                                            inputAttributes: {
                                                'aria-label': 'Masukkan alasan Anda'
                                            },
                                            showCancelButton: true,
                                            confirmButtonText: 'Kirim',
                                            cancelButtonText: 'Batal',
                                            preConfirm: (inputValue) => {
                                                return inputValue; // Allows submission
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                @this.call('doneItem', index, result.value);
                                            }
                                        });
                                    }

                                    function confirmVoucherName(index) {
                                        Swal.fire({
                                            title: 'Konfirmasi',
                                            text: 'Apakah Anda yakin ingin mengonfirmasi Nama Voucher ini?',
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonText: 'Ya',
                                            cancelButtonText: 'Tidak'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                @this.call('VoucherNameAction', index); // Ganti dengan nama method Livewire kamu
                                            }
                                        });
                                    }
                                </script>
                            @endpush

                        </tr>
                    @endforeach
                    @if (
                        $showAdd &&
                            (($kategori_id === 6 && count($list) < 1) ||
                                ($kategori_id === 5 && count($list) < 2) ||
                                ($kategori_id !== 6 && $kategori_id !== 5)))
                        <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                            <td class="py-3 px-6"></td>
                            @if ($requestIs == 'spare-part')
                                <!-- Jenis KDO -->
                                <td class="py-3 px-6 relative">
                                    <input type="text" wire:model.live="newAset" wire:focus="focusAset"
                                        wire:blur="blurAset" placeholder="Cari atau Tambah KDO"
                                        class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                    <ul
                                        class="absolute z-10 w-96 bg-white border border-gray-300 rounded-lg mt-2 max-h-60 overflow-auto shadow-lg">
                                        @foreach ($asetSuggestions as $aset)
                                            {{-- <li class="px-4 py-2 font-bold text-gray-900 bg-gray-100 cursor-default"> --}}
                                            <li wire:click="selectAset({{ $aset->id }})"
                                                class="px-6 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                                {{ $aset->nama }}</li>
                                        @endforeach
                                    </ul>
                                </td>

                                <!-- Deskripsi Kerusakan -->
                                <td class="px-6 py-3">
                                    <textarea id="deskripsiKerusakan" wire:model.live="newDeskripsi" rows="2"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Deskripsikan kerusakan secara detail"></textarea>
                                </td>
                                <!-- Catatan -->
                                <td class="px-6 py-3">
                                    <textarea id="catatan" wire:model.live="newCatatan" rows="2"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                </td>
                            @endif
                            @if ($requestIs == 'material')
                                <!-- Jenis KDO -->
                                {{-- <td class="py-3 px-6 relative">
                                    <input type="text" wire:model.live="newLokasi" wire:focus="focusLokasi"
                                        wire:blur="blurLokasi" placeholder="Cari Lokasi"
                                        class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                    <ul
                                        class="absolute z-10 w-96 bg-white border border-gray-300 rounded-lg mt-2 max-h-60 overflow-auto shadow-lg">
                                        @foreach ($lokasiSuggestions as $lokasi)
                                            <li wire:click="selectLokasi({{ $lokasi->id }})"
                                                class="px-6 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                                {{ $lokasi->nama }}</li>
                                        @endforeach
                                    </ul>
                                </td> --}}

                                <!-- Deskripsi Kerusakan -->
                                <td class="px-6 py-3">
                                    <textarea id="deskripsiKerusakan" wire:model.live="newDeskripsi" rows="2"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Deskripsikan keperluan"></textarea>
                                </td>
                            @endif
                            @if ($kategori_id == 6)
                                <td class="py-3 px-6">
                                    <select wire:model.live="newAsetId"
                                        class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Pilih KDO
                                        </option>
                                        @foreach ($asets as $aset)
                                            <option value="{{ $aset->id }}">
                                                {{ $aset->merk->nama . ' ' . $aset->nama . ' - ' . $aset->noseri . ' | ' . $aset->tipe }}
                                            </option>
                                        @endforeach
                                        <option value="0">KDO Lain</option> <!-- Opsi Tambahan -->
                                    </select>
                                    @error('newAsetId')
                                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                    @enderror

                                    @if ($newAsetId === '0')
                                        <div class="mt-2">
                                            <input type="text" wire:model.live="NoSeri"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                placeholder="No. Plat">

                                            <input type="text" wire:model.live="JenisKDO"
                                                class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                placeholder="Jenis Mobil">

                                            <input type="text" wire:model.live="NamaKDO"
                                                class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                                placeholder="Merk / Nama Mobil">
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <textarea id="namadriver" wire:model.live="newDriverId" rows="1"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Masukan Nama Driver"></textarea>
                                </td>
                                {{-- <td class="py-3 px-6">
                                    <select wire:model.live="newDriverId"
                                        class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Pilih Driver
                                        </option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">
                                                {{ $driver->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('newAsetId')
                                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                    @enderror
                                </td> --}}
                            @endif
                            @if ($kategori_id == 5)
                                @php
                                    $rowCount = count($list);
                                    $allowedRow1 = ['berat', 'ringan', 'berkala'];
                                    $allowedRow2 = ['body repair berat', 'body repair ringan'];
                                @endphp

                                <td class="py-3 px-6">
                                    <select wire:model.live="newBarangId" wire:change="selectMerk"
                                        class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                        <option value="" selected>Pilih Tipe Service</option>
                                        @foreach ($availBarangs as $barang)
                                            @if (
                                                ($rowCount == 0 && in_array(strtolower(trim($barang->nama)), $allowedRow1)) ||
                                                    ($rowCount == 1 && in_array(strtolower(trim($barang->nama)), $allowedRow2)))
                                                <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                            @endif
                            @if (!in_array($kategori_id, [5, 6]))
                                <td class="py-3 px-6">
                                    <select wire:model.live="newBarangId" wire:change="selectMerk"
                                        class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                                        <option value="" selected>Pilih
                                            {{ $kategori_id == 4 ? 'Konsumsi' : ($kategori_id == 5 ? 'Tipe Service' : 'Barang') }}
                                        </option>
                                        @foreach ($availBarangs as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('newBarangId')
                                        <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </td>
                            @endif

                            @if ($kategori_id != 5 && $kategori_id != 6)
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
                            @endif
                            @if ($kategori_id == 5)
                                <td class="px-6 py-3">
                                    <textarea id="deskripsiKerusakan" wire:model.live="newDeskripsi" rows="2"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                                        placeholder="Deskripsikan kerusakan secara detail"></textarea>
                                </td>
                            @endif
                            @if ($kategori_id == 5 || $kategori_id == 6)
                                <td class="px-6 py-3 text-center">
                                    @if ($newDokumen)
                                        <div class="relative inline-block">
                                            <a href="{{ $newDokumen->temporaryUrl() }}" target="_blank">
                                                <img src="{{ $newDokumen->temporaryUrl() }}" alt="Preview Bukti"
                                                    class="w-16 h-16 rounded-md">
                                            </a>
                                            <button wire:click="removePhoto"
                                                class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 text-white rounded-full text-xs hover:bg-red-700">
                                                &times;
                                            </button>
                                        </div>
                                    @else
                                        <input type="file" wire:model.live="newDokumen" class="hidden"
                                            id="upload-newDokumen">
                                        <button type="button"
                                            onclick="document.getElementById('upload-newDokumen').click()"
                                            class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                            Unggah Foto
                                        </button>
                                    @endif
                                </td>
                            @endif
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

                            @if ($requestIs == 'spare-part' || $requestIs == 'material')
                                <td class="px-6 py-3 text-center">
                                    @if ($newBukti)
                                        <div class="relative inline-block">
                                            <a href="{{ $newBukti->temporaryUrl() }}" target="_blank">
                                                <img src="{{ $newBukti->temporaryUrl() }}" alt="Preview Bukti"
                                                    class="w-16 h-16 rounded-md">
                                            </a>
                                            <button wire:click="removePhoto"
                                                class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 text-white rounded-full text-xs hover:bg-red-700">
                                                &times;
                                            </button>
                                        </div>
                                    @else
                                        <input type="file" wire:model.live="newBukti" class="hidden"
                                            id="upload-newBukti">
                                        <button type="button"
                                            onclick="document.getElementById('upload-newBukti').click()"
                                            class="text-primary-700 bg-gray-200 border text-sm border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                            Unggah Foto
                                        </button>
                                    @endif

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
                                @if ($ruleAdd)
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


        <div wire:loading wire:target='saveData'>
            <livewire:loading />
        </div>
        <div class="flex justify-center">
            {{-- @role('penanggungjawab') --}}
            @if (count($list) > 0 && $showAdd)
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
                {{-- @dd($approvalData) --}}
                <!-- Modal Body -->
                <div class="p-4 space-y-4">
                    <p><strong>Nama Barang:</strong> {{ $approvalData['barang']->nama ?? '-' }}</p>
                    <p><strong>Jumlah Permintaan:</strong> {{ $approvalData['jumlah_permintaan'] }}</p>

                    <!-- Daftar Lokasi, Jumlah, dan Catatan -->
                    <div class="max-h-72 overflow-y-auto">

                        <table class="w-full border-collapse border border-gray-300 ">
                            <thead>
                                <tr class="bg-gray-200 text-left">
                                    <th class="border px-4 py-2">Spesifikasi</th>
                                    <th class="border px-4 py-2">Lokasi</th>
                                    <th class="border px-4 py-2">Bagian</th>
                                    <th class="border px-4 py-2">Posisi</th>
                                    <th class="border px-4 py-2">Jumlah Tersedia</th>
                                    <th class="border px-4 py-2">Jumlah Disetujui</th>
                                    <th class="border px-4 py-2">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approvalData['stok'] as $index => $stok)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            <div>Merk : {{ $stok['nama'] ?? '-' }}</div>
                                            <div>Tipe : {{ $stok['tipe'] ?? '-' }}</div>
                                            <div>Ukuran : {{ $stok['ukuran'] ?? '-' }}</div>
                                        </td>
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
                                @empty
                                    <tr>
                                        <td class="font-semibold py-4 text-center" colspan="7">Barang Tidak
                                            Tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                                <th class="border px-4 py-2">Spesifikasi</th>

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
                                    <td class="border px-4 py-2">
                                        <div>Merk : {{ $note['merk']->nama ?? '-' }}</div>
                                        <div>Tipe : {{ $note['merk']->ukuran ?? '-' }}</div>
                                        <div>Ukuran : {{ $note['merk']->tipe ?? '-' }}</div>
                                    </td>
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
