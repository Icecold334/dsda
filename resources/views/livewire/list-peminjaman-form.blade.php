<div>
    <div>
        {{-- @if ($tanggal_peminjaman && $keterangan && $unit_id) --}}
        @if (true)

            <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
                <thead>
                    <tr class="text-white uppercase">
                        <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold rounded-l-lg">NAMA
                            {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }}</th>
                        @if (!$showNew)
                            <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold ">NAMA
                                {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }} DISETUjui</th>
                        @endif
                        @if ($tipe == 'Peralatan Kantor')
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">peminjaman</th>
                            @if (!$showNew)
                                <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold ">peminjaman
                                    DISETUjui</th>
                            @endif
                        @endif
                        <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold">waktu penggunaan</th>
                        @if (!$showNew)
                            <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold ">waktu penggunaan
                                DISETUjui</th>
                        @endif
                        @if ($tipe == 'KDO' || $tipe == 'Ruangan')
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jumlah Orang</th>
                        @endif
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">keterangan</th>
                        @if ($tipe == 'Ruangan')
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">Undangan</th>
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
                                    <option value="{{ $item['aset_id'] }}" selected>{{ $item['aset_name'] }}</option>
                                </select>
                            </td>
                            @if (!$showNew)
                                <td class="py-3 px-6">
                                    <select wire:model.live="list.{{ $index }}.approved_aset_id"
                                        @disabled(auth()->user()->cannot('peminjaman_persetujuan_peminjaman_aset') ||
                                                $item['fix'] ||
                                                !in_array($item['detail_peminjaman_id'], $approvals))
                                        class="bg-gray-50 border border-gray-300   text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Pilih {{ $tipe ? Str::ucfirst($tipe) : 'Layanan' }}
                                        </option>
                                        @foreach ($asets as $asets)
                                            <option value="{{ $asets->id }}">{{ $asets->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('newAsetId')
                                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                    @enderror
                                </td>
                            @endif
                            @if ($tipe == 'Peralatan Kantor')
                                <td class="py-3 px-6">
                                    <input type="number" value="{{ $item['jumlah'] }}" min="1" disabled
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="Jumlah Permintaan">
                                </td>
                                @if (!$showNew)
                                    <td class="py-3 px-6">
                                        <input type="number"
                                            wire:model.live='list.{{ $index }}.approved_jumlah' min="1"
                                            @disabled(auth()->user()->cannot('peminjaman_persetujuan_peminjaman_aset') ||
                                                    $item['fix'] ||
                                                    !in_array($item['detail_peminjaman_id'], $approvals))
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            placeholder="Jumlah Disetujui">
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
                            @if (!$showNew)
                                <td class="py-3 px-6">
                                    <select wire:model.live="list.{{ $index }}.approved_waktu_id"
                                        @disabled(auth()->user()->cannot('peminjaman_persetujuan_peminjaman_aset') ||
                                                $item['fix'] ||
                                                !in_array($item['detail_peminjaman_id'], $approvals))
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
                            @if ($tipe == 'KDO' || $tipe == 'Ruangan')
                                <td class="py-3 px-6">
                                    <input type="number" value="{{ $item['jumlah_peserta'] }}" disabled
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="Jumlah Orang">
                                </td>
                            @endif
                            <td class="py-3 px-6">
                                <input type="text" value="{{ $item['keterangan'] }}" disabled
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="Keterangan">
                            </td>
                            @if ($tipe == 'Ruangan')
                                <td class="px-6 py-3 text-center">
                                    <div class="relative inline-block">
                                        @if (is_string($item['img']))
                                            <!-- Jika newBukti adalah string (path file) -->
                                            <a href="{{ asset('storage/undanganRapat/' . $item['img']) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/undanganRapat/' . $item['img']) }}"
                                                    alt="Preview Bukti" class="w-16 h-16 rounded-md">
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
                                    @if (!$item['fix'] && ($item['approved_waktu_id'] && $item['approved_aset_id']))
                                        <button onclick="confirmItem({{ $index }})"
                                            class="text-success-900 border-success-600 text-xl border bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if ($showNew)
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
                            @if ($tipe == 'Peralatan Kantor')
                                <td class="py-3 px-6">
                                    <input type="number" wire:model.live="newJumlah" min="1"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="Jumlah Permintaan">
                                </td>
                            @endif
                            <td class="py-3 px-6">
                                <select wire:model.live="newWaktu"
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
                                    <input type="number" wire:model.live="newPeserta"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="Jumlah Orang">
                                </td>
                            @endif
                            <td class="py-3 px-6">
                                <input type="text" wire:model.live="newKeterangan"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="Keterangan">
                            </td>
                            @if ($tipe == 'Ruangan')
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
                            <td class="text-center py-3 px-6">
                                @php
                                    $show = null;
                                    if ($tipe == 'Ruangan') {
                                        $show = $newAsetId && $newWaktu && $newPeserta && $newKeterangan && $newDokumen;
                                    } elseif ($tipe == 'KDO') {
                                        $show = $newAsetId && $newWaktu && $newPeserta && $newKeterangan;
                                    } else {
                                        $show = $newAsetId && $newWaktu && $newJumlah && $newKeterangan;
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
        <div class="flex justify-center mt-4">
            @if (count($list) > 0 && $showNew)
                <button wire:click="saveData"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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
        </script>
    @endpush
</div>
