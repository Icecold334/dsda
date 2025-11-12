<div>
    <div class="overflow-x-auto w-full">
        @if ($tanggal_peminjaman && $keterangan && $unit_id && $sub_unit_id)
        {{-- @if (true) --}}
        <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
            <thead>
                <tr class="text-white uppercase">
                    <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold rounded-l-lg">NAMA
                        {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }}</th>
                    @if (!$showNew && $tipe != 'Peralatan Kantor')
                    <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold ">NAMA
                        {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }} Disetujui</th>
                    @endif
                    @if ($tipe == 'Peralatan Kantor')
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12">Foto</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">peminjaman</th>
                    @if (!$showNew)
                    <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold ">peminjaman
                        Disetujui</th>
                    @endif
                    @endif
                    <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold">waktu penggunaan</th>
                    @if (!$showNew && $tipe == 'Ruangan')
                    <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold ">waktu penggunaan
                        Disetujui</th>
                    @endif
                    @if (!$showNew && $tipe == 'Peralatan Kantor')
                    <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold ">Pengembalian</th>
                    @endif
                    @if ($tipe == 'KDO' || $tipe == 'Ruangan')
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jumlah Orang</th>
                    @endif
                    @if ($tipe == 'Ruangan')
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">Undangan</th>
                    @endif
                    @if ($tipe == 'KDO')
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">Surat Permohonan</th>
                    @endif
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12 rounded-r-lg"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $index => $item)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6">
                        <select
                            class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            disabled>
                            <option value="{{ $item['aset_id'] }}" selected>
                                {{ $tipe == 'KDO'
                                ? $item['aset_merk'] . ' ' . $item['aset_name'] . ' - ' . $item['aset_noseri']
                                : ($tipe == 'Ruangan'
                                ? $item['aset_name']
                                : $item['aset_name']) }}
                            </option>
                        </select>
                    </td>
                    @if (!$showNew && $tipe == 'KDO')
                    <td class="py-3 px-6">
                        <selectwire:model.live.debounce.500ms="list.{{ $index }}.approved_aset_id" @disabled(auth()->
                            user()->cannot('peminjaman_persetujuan_peminjaman_aset') ||
                            $item['fix'] ||
                            auth()->id() === $item['user_id'] ||
                            in_array($item['detail_peminjaman_id'], $approvals))
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700
                            dark:border-gray-600 dark:text-white dark:focus:ring-primary-500
                            dark:focus:border-primary-500">
                            <option value="">Pilih {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }}
                            </option>
                            @foreach ($asets as $aset)
                            <option value="{{ $aset->id }}">
                                {{ $aset->merk->nama . ' ' . $aset->nama . ' - ' . $aset->noseri }}
                            </option>
                            @endforeach
                            </select>
                            @error('newAsetId')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                    </td>
                    @endif
                    @if (!$showNew && $tipe == 'Ruangan')
                    <td class="py-3 px-6">
                        @php
                        $isPembuat = auth()->id() === $item['user_id'];
                        $canApprove = auth()->user()->can('peminjaman_persetujuan_peminjaman_aset');
                        @endphp
                        <selectwire:model.live.debounce.500ms="list.{{ $index }}.approved_aset_id" {{ !$canApprove ||
                            $isPembuat ? 'disabled' : '' }}
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Pilih {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }}
                            </option>
                            @foreach ($asetsAvail as $aset)
                            <option value="{{ $aset->id }}">
                                {{ $aset->nama }}
                            </option>
                            @endforeach
                            </select>
                            @error('newAsetId')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                    </td>
                    @endif
                    @if ($tipe == 'Peralatan Kantor')
                    <td class="py-2 px-4 items-center">
                        <div x-data="{ open: false, imgSrc: '{{ $item['foto'] ?? asset('img/default-pic-thumb.png') }}' }"
                            class="flex justify-center items-center">

                            <!-- Thumbnail Gambar -->
                            <div class="w-20 h-20 overflow-hidden flex justify-center items-center p-1 border-2 rounded-lg bg-white cursor-pointer"
                                @click="open = true; imgSrc = '{{ $item['foto'] ?? asset('img/default-pic-thumb.png') }}'">
                                <img class="w-full h-full object-cover object-center rounded-sm"
                                    src="{{ $item['foto'] ?? asset('img/default-pic-thumb.png') }}" alt="Preview Aset"
                                    onerror="this.src='{{ asset('img/default-pic-thumb.png') }}'">
                            </div>

                            <!-- Modal -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[9999]"
                                @click="open = false" @keydown.escape.window="open = false">

                                <!-- Container Gambar Modal -->
                                <div
                                    class="p-2 bg-white rounded-lg shadow-lg max-w-[90vw] max-h-[90vh] overflow-hidden">
                                    <img :src="imgSrc" class="max-w-full max-h-[80vh] object-contain rounded-lg"
                                        alt="Preview Gambar">
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <input type="number" value="{{ $item['jumlah'] }}" min="1" disabled
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Jumlah Permintaan">
                        @if (empty($newAsetId) && $availableJumlah)
                        <span class="text-sm text-gray-500">Stok tersedia:
                            {{ $availableJumlah }}</span>
                        @endif
                    </td>
                    @if (!$showNew)
                    <td class="py-3 px-6">
                        <input type="number" wire:model.live.debounce.500ms='list.{{ $index }}.approved_jumlah' min="1"
                            @disabled(auth()->user()->cannot('peminjaman_persetujuan_peminjaman_aset') ||
                        $item['fix'] ||
                        in_array($item['detail_peminjaman_id'], $approvals))
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                        focus:border-blue-500 block w-full p-2.5"
                        placeholder="Jumlah Disetujui">
                        @if ($item['aset_id'] && $item['avilable_jumlah'] && empty($item['approved_jumlah']))
                        <span class="text-sm text-gray-500">Stok tersedia:
                            {{ $item['avilable_jumlah'] }}</span>
                        @endif
                    </td>
                    @endif
                    @endif
                    <td class="py-3 px-6">
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            disabled>
                            <option value="{{ $item['waktu_id'] }}" selected>
                                {{ $item['waktu']->waktu }}
                                {{ \Carbon\Carbon::parse($item['waktu']->mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($item['waktu']->selesai)->format('H:i') }}
                            </option>
                        </select>
                    </td>
                    @if (!$showNew && $tipe == 'Ruangan')
                    <td class="py-3 px-6">
                        @php
                        $isPembuat = auth()->id() === $item['user_id'];
                        $canApprove = auth()->user()->can('peminjaman_persetujuan_peminjaman_aset');
                        @endphp
                        <selectwire:model.live.debounce.500ms="list.{{ $index }}.approved_waktu_id" {{ !$canApprove ||
                            $isPembuat ? 'disabled' : '' }}
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Pilih Waktu Peminjaman</option>
                            @foreach ($waktus as $waktu)
                            <option value="{{ $waktu->id }}">
                                {{ $waktu->waktu }}
                                {{ \Carbon\Carbon::parse($waktu->mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($waktu->selesai)->format('H:i') }}
                            </option>
                            @endforeach
                            </select>
                    </td>
                    @endif
                    @if (!$showNew && $tipe == 'Peralatan Kantor')
                    <td class="py-3 px-6 text-center">
                        @if ($item['img_pengembalian'])
                        <div class="flex flex-col items-center gap-2">
                            <button
                                onclick="previewPengembalian('{{ asset('storage/pengembalianUmum/' . $item['img_pengembalian']) }}', `{{ $item['keterangan_pengembalian'] ?? '-' }}`)"
                                class="text-blue-600 hover:text-blue-800">
                                <i class="fa-solid fa-eye text-xl"></i>
                            </button>
                        </div>
                        @else
                        <button
                            onclick="{{ auth()->id() == $item['user_id'] && $item['detail_peminjaman_status'] && $item['detail_peminjaman_cancel'] === 0 ? "
                            openBackItemModal($index)" : '' }}" class="text-primary-700 bg-gray-200 border text-sm border-primary-500 rounded-lg px-3 py-1.5 transition
                                        {{ auth()->id() == $item['user_id'] &&
                                        $item['detail_peminjaman_status'] &&
                                        $item['detail_peminjaman_cancel'] === 0
                                            ? 'hover:bg-primary-600 hover:text-white'
                                            : 'opacity-50 cursor-not-allowed pointer-events-none' }}" {{ auth()->id()
                            != $item['user_id'] || !$item['detail_peminjaman_status'] ||
                            $item['detail_peminjaman_cancel'] !== 0 ? 'disabled' : '' }}>
                            Kembalikan
                        </button>
                        <input type="file" id="uploadFoto-{{ $index }}" class="hidden" accept="image/*"
                            wire:model.live.debounce.500ms="fotoPengembalian">
                        @endif

                    </td>
                    @endif
                    @push('scripts')
                    <script>
                        function openBackItemModal(index) {
                                        Swal.fire({
                                            title: 'Kembalikan Item',
                                            html: `
                                            <button id="open-file" class="swal2-confirm swal2-styled" style="margin-bottom:10px;">Pilih Foto</button>
                                            <span id="file-name" style="display: block; font-size: 0.9rem; margin-bottom: 10px; color: #555;"></span>
                                            <textarea id="inputKeterangan"
                                            class="w-full min-w-full max-w-full h-28 p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Masukkan keterangan..."></textarea>
                                            <div id="swal-error" style="color: red; font-size: 0.8rem; margin-top: 0.5rem;"></div>
                                        `,
                                            showCancelButton: true,
                                            confirmButtonText: 'Kirim',
                                            cancelButtonText: 'Batal',
                                            focusConfirm: false,
                                            didOpen: () => {
                                                const hiddenInput = document.getElementById(`uploadFoto-${index}`);
                                                const openFileBtn = document.getElementById('open-file');
                                                const fileNameSpan = document.getElementById('file-name');

                                                openFileBtn.addEventListener('click', () => {
                                                    hiddenInput.click();
                                                });

                                                hiddenInput.addEventListener('change', (e) => {
                                                    const file = e.target.files[0];
                                                    if (file) {
                                                        fileNameSpan.textContent = "📎 " + file.name;
                                                        document.getElementById('swal-error').textContent = '';
                                                    }
                                                });
                                            },
                                            preConfirm: () => {
                                                const file = document.getElementById(`uploadFoto-${index}`).files[0];
                                                if (!file) {
                                                    document.getElementById('swal-error').textContent =
                                                        'Silakan unggah foto terlebih dahulu.';
                                                    return false;
                                                }
                                                return true;
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                const keterangan = document.getElementById('inputKeterangan').value;
                                                @this.set('keteranganPengembalian', keterangan);
                                                @this.call('backItem', index);
                                            }
                                        });
                                    }

                                    function previewPengembalian(imageUrl, keterangan) {
                                        Swal.fire({
                                            title: 'Bukti Pengembalian',
                                            html: `
                                                <img src="${imageUrl}" alt="Preview Pengembalian" class="w-full max-h-96 object-contain rounded mb-3">
                                                <p class="text-left text-sm text-gray-700"><strong>Keterangan:</strong><br>${keterangan}</p>
                                            `,
                                            showCloseButton: true,
                                            showConfirmButton: false,
                                            width: '600px',
                                        });
                                    }
                    </script>
                    @endpush
                    @if ($tipe == 'KDO' || $tipe == 'Ruangan')
                    <td class="py-3 px-6">
                        <input type="number" value="{{ $item['jumlah_peserta'] }}" disabled
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed"
                            placeholder="Jumlah Orang">
                    </td>
                    @endif
                    @if (!$tipe == 'KDO' || !$tipe == 'Ruangan')
                    <td class="py-3 px-6">
                        <input type="text" value="{{ $item['keterangan'] }}" disabled
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed"
                            placeholder="Keterangan">
                    </td>
                    @endif
                    @if ($tipe == 'KDO' || $tipe == 'Ruangan')
                    <td class="px-6 py-3 text-center">
                        <div class="relative inline-block">
                            @if (is_string($item['img']))
                            <!-- Jika newBukti adalah string (path file) -->
                            <a href="{{ asset('storage/suratPeminjaman/' . $item['img']) }}" target="_blank">
                                <img src="{{ asset('storage/suratPeminjaman/' . $item['img']) }}" alt="Preview Bukti"
                                    class="w-16 h-16 rounded-md">
                            </a>
                            @elseif (is_object($item['img']) && method_exists($item['img'], 'temporaryUrl'))
                            <!-- Jika newBukti adalah file Livewire upload -->
                            <a href="{{ $item['img']->temporaryUrl() }}" target="_blank">
                                <img src="{{ $item['img']->temporaryUrl() }}" alt="Preview Bukti"
                                    class="w-16 h-16 rounded-md">
                            </a>
                            @else
                            <span class="text-gray-500">Bukti tidak valid</span>
                            @endif
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
                        @if (!$item['fix'] && ($item['approved_aset_id'] || $item['approved_jumlah']))
                        <button onclick="confirmItem({{ $index }})"
                            class="text-success-900 border-success-600 text-xl border bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                            <i class="fa-solid fa-circle-check"></i>
                        </button>
                        @elseif(
                        (auth()->id() !== $item['user_id'] && ($item['fix'] && $item['approved_aset_id'] !=
                        $item['aset_id'])) ||
                        ($item['approved_waktu_id'] != $item['waktu_id'] && $item['approved_aset_id'] &&
                        $item['approved_waktu_id']))
                        <button onclick="confirmItem({{ $index }})"
                            class="text-success-900 border-success-600 text-xl border bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                            <i class="fa-solid fa-circle-check"></i>
                        </button>
                        @endif
                        @endif
                    </td>
                </tr>
                @endforeach
                @if ($showNew && (($tipe != 'KDO' && $tipe != 'Ruangan') || count($list) < 1)) <tr
                    class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6">
                        <selectwire:model.live.debounce.500ms="newAsetId"
                            class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Pilih {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }}
                            </option>
                            @foreach ($asets as $aset)
                            <option value="{{ $aset->id }}">
                                {{ $tipe == 'Peralatan Kantor' ? $aset->nama : ($aset->getTable() == 'ruangs' ?
                                $aset->nama : $aset->merk->nama . ' ' . $aset->nama . ' - ' . $aset->noseri) }}
                            </option>
                            @endforeach
                            </select>
                            @error('newAsetId')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                    </td>
                    @if ($tipe == 'Peralatan Kantor')
                    <td class="py-2 px-4">
                        <div x-data="{ open: false, imgSrc: '' }" class="flex justify-center items-center">
                            <!-- Gambar Thumbnail -->
                            <div class="w-20 h-20 overflow-hidden relative flex justify-center p-1 border-2 rounded-lg bg-white cursor-pointer"
                                @click="open = true; imgSrc = '{{ $newFoto ?? asset('img/default-pic-thumb.png') }}'">
                                <img class="w-full h-full object-cover object-center rounded-sm"
                                    src="{{ $newFoto ?? asset('img/default-pic-thumb.png') }}" alt="">
                            </div>

                            <!-- Modal -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                                @click="open = false" @keydown.escape.window="open = false">
                                <img :src="imgSrc" class="w-60 h-60 object-cover object-center">
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <input type="number" wire:model.live.debounce.500ms="newJumlah" min="1"
                            max="{{ $availableJumlah }}" class="bg-gray-50
                                        border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                                        focus:border-blue-500 block w-full p-2.5" placeholder="Jumlah Peminjaman">
                        @if ($availableJumlah && $newAsetId)
                        <span class="text-sm text-gray-500">Stok tersedia:
                            {{ $availableJumlah }}</span>
                        @endif

                    </td>
                    @endif
                    <td class="py-3 px-6">
                        <selectwire:model.live.debounce.500ms="newWaktu"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Pilih Waktu Peminjaman</option>
                            @foreach ($waktus as $waktu)
                            <option value="{{ $waktu->id }}">
                                {{ $waktu->waktu }}
                                {{ \Carbon\Carbon::parse($waktu->mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($waktu->selesai)->format('H:i') }}
                            </option>
                            @endforeach
                            </select>
                            @error('newWaktu')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                    </td>

                    @if ($tipe == 'KDO' || $tipe == 'Ruangan')
                    <td class="py-3 px-6">
                        <input type="number" wire:model.live.debounce.500ms="newPeserta"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Jumlah Orang">
                    </td>
                    @endif
                    @if (!$tipe == 'KDO' || !$tipe == 'Ruangan')
                    <td class="py-3 px-6">
                        <input type="text" wire:model.live.debounce.500ms="newKeterangan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Keterangan">
                    </td>
                    @endif
                    @if ($tipe == 'KDO' || $tipe == 'Ruangan')
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
                        <input type="file" wire:model.live.debounce.500ms="newDokumen" class="hidden"
                            id="upload-newDokumen">
                        <button type="button" onclick="document.getElementById('upload-newDokumen').click()"
                            class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                            Unggah Foto
                        </button>
                        @endif

                    </td>
                    @endif
                    <td class="text-center py-3 px-6">
                        @php
                        $show = null;
                        if ($tipe == 'Ruangan') {
                        $show = $newAsetId && $newWaktu && $newPeserta && $newDokumen;
                        } elseif ($tipe == 'KDO') {
                        $show = $newAsetId && $newWaktu && $newPeserta;
                        } else {
                        $show = $newAsetId && $newWaktu && $newJumlah;
                        }
                        @endphp
                        @if ($show)
                        <button wire:click="addToList"
                            class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                        @endif
                    </td>
                    </tr>
                    @endif
            </tbody>

        </table>
        @else
        <div class="text-xl font-semibold mt-8 flex w-full justify-center">Lengkapi data diatas terlebih dahulu
        </div>
        @endif
        {{-- @php
        $showSaveButton = false;
        if ($tipe == 'Ruangan') {
        $showSaveButton = $newAsetId && $newWaktu && $newPeserta && $newKeterangan && $newDokumen;
        } elseif ($tipe == 'KDO') {
        $showSaveButton = $newAsetId && $newWaktu && $newPeserta && $newKeterangan;
        } else {
        $showSaveButton = count($list) > 0 && $showNew;
        }
        @endphp --}}

        <div class="flex justify-center mt-4">
            @if (count($list) > 0 && $showNew)
            <button wire:click="saveData" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Simpan
            </button>
            @endif
        </div>

    </div>
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
                        // if (!inputValue || inputValue.trim() === '') {
                        //     Swal.showValidationMessage('Keterangan tidak boleh kosong!');
                        //     return false; // Prevent submission
                        // }
                        return inputValue; // Allows submission
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('approveItem', index, result.value);
                    }
                });
            }
    </script>
    @endpush
    @push('scripts')
    <script type="module">
        document.addEventListener('success', function(e) {
                feedback('Berhasil!', e.detail[0],
                    'success')

            })
            document.addEventListener('error', function(e) {
                feedback('Gagal!', e.detail[0],
                    'error')

            })
    </script>
    @endpush
</div>