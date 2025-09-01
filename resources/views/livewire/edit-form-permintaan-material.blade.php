<div>
    {{-- Alert Messages --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">
                        {{ session('warning') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="Data Umum">
                <form wire:submit.prevent="{{ $permintaan->status === 4 ? 'updateData' : '' }}">
                    <table class="w-full border-separate border-spacing-y-4">
                {{-- RAB --}}
                <tr>
                    <td class="w-1/3">
                        <label for="withRab" class="block mb-2 font-semibold text-gray-900">
                            Menggunakan RAB
                        </label>
                    </td>
                    <td>
                        <select wire:model.live="withRab" @disabled($permintaan->status !== 4) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 
                                @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                        @error('withRab')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>

                {{-- Pilih RAB --}}
                @if($withRab && !$isSeribu)
                    <tr>
                        <td class="w-1/3">
                            <label for="rab_id" class="block mb-2 font-semibold text-gray-900">
                                Pilih RAB
                            </label>
                        </td>
                        <td>
                            <select wire:model.live="rab_id" @disabled($permintaan->status !== 4)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                                        @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif">
                                <option value="">Pilih RAB</option>
                                @foreach($rabs as $rab)
                                    <option value="{{ $rab->id }}">{{ $rab->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                            @error('rab_id')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                @endif

                {{-- No Din --}}
                <tr>
                    <td class="w-1/3">
                        <label for="nodin" class="block mb-2 font-semibold text-gray-900">
                            Nomor SPB
                        </label>
                    </td>
                    <td>
                        <input type="text" wire:model.live="nodin" @disabled($permintaan->status !== 4) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                               @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif"
                            placeholder="Masukkan nomor nota dinas">
                        @error('nodin')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>

                {{-- Gudang --}}
                <tr>
                    <td class="w-1/3">
                        <label for="gudang_id" class="block mb-2 font-semibold text-gray-900">
                            Gudang <span class="text-red-500">*</span>
                        </label>
                    </td>
                    <td>
                        <select wire:model.live="gudang_id" @disabled($permintaan->status !== 4) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif">
                            <option value="">Pilih Gudang</option>
                            @foreach($gudangs as $gudang)
                                <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                            @endforeach
                        </select>
                        @error('gudang_id')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>

                {{-- Nama Kegiatan (jika tidak pakai RAB) --}}
                @if(!$withRab)
                    <tr>
                        <td class="w-1/3">
                            <label for="namaKegiatan" class="block mb-2 font-semibold text-gray-900">
                                Nama Kegiatan
                            </label>
                        </td>
                        <td>
                            <input type="text" wire:model.live="namaKegiatan" @disabled($permintaan->status !== 4) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                                       @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif"
                                placeholder="Masukkan nama kegiatan">
                            @error('namaKegiatan')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                @endif

                {{-- Info RAB jika dipilih --}}
                @if($rab && !$isSeribu)
                    <tr>
                        <td class="w-1/3">
                            <label class="block mb-2 font-semibold text-gray-900">
                                Nama Kegiatan
                            </label>
                        </td>
                        <td>
                            <input type="text" disabled value="{{ $rab->nama_kegiatan }}"
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                        </td>
                    </tr>
                    <tr>
                        <td class="w-1/3">
                            <label class="block mb-2 font-semibold text-gray-900">
                                Nilai RAB
                            </label>
                        </td>
                        <td>
                            <input type="text" disabled value="Rp {{ number_format($rab->jumlah_biaya ?? 0, 0, ',', '.') }}"
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                        </td>
                    </tr>
                @endif

                {{-- Lokasi Kegiatan --}}
                <tr>
                    <td class="font-semibold">
                        <label for="kecamatan_id">Lokasi Kegiatan</label>
                    </td>
                    <td>
                        <select wire:model.live="kecamatan_id" @disabled($permintaan->status !== 4) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}">{{ $kecamatan->kecamatan }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <select wire:model.live="kelurahan_id" @disabled($permintaan->status !== 4) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif">
                            <option value="">Pilih Kelurahan</option>
                            @foreach($kelurahans as $kelurahan)
                                <option value="{{ $kelurahan->id }}">{{ $kelurahan->nama }}</option>
                            @endforeach
                        </select>
                        @error('kelurahan_id')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="text" wire:model.live="lokasiMaterial" @disabled($permintaan->status !== 4) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                               @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif"
                            placeholder="Detail lokasi kegiatan">
                        @error('lokasiMaterial')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>

                {{-- Volume Pekerjaan --}}
                <tr>
                    <td class="font-semibold">
                        <label for="volume_pekerjaan">Volume Pekerjaan</label>
                    </td>
                    <td>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Panjang</label>
                                <input type="number" step="0.01" wire:model.live="p" @disabled($permintaan->status !== 4)
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                    @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif"
                                    placeholder="0.00">
                                @error('p')
                                    <span class="text-xs text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Lebar</label>
                                <input type="number" step="0.01" wire:model.live="l" @disabled($permintaan->status !== 4)
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                    @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif"
                                    placeholder="0.00">
                                @error('l')
                                    <span class="text-xs text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kedalaman</label>
                                <input type="number" step="0.01" wire:model.live="k" @disabled($permintaan->status !== 4)
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                    @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif"
                                    placeholder="0.00">
                                @error('k')
                                    <span class="text-xs text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- Tanggal Permintaan --}}
                <tr>
                    <td class="font-semibold">
                        <label for="tanggal_permintaan">Tanggal Permintaan <span class="text-red-500">*</span></label>
                    </td>
                    <td>
                        <input type="date" wire:model.live="tanggal_permintaan" @disabled($permintaan->status !== 4)
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                               @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif">
                        @error('tanggal_permintaan')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>

                {{-- Keterangan --}}
                <tr>
                    <td class="font-semibold">
                        <label for="keterangan">Keterangan</label>
                    </td>
                    <td>
                        <textarea wire:model.live="keterangan" @disabled($permintaan->status !== 4) rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                  @if($permintaan->status !== 4) cursor-not-allowed opacity-50 @endif"
                            placeholder="Masukkan keterangan permintaan"></textarea>
                        @error('keterangan')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
                    </table>
                </form>
            </x-card>
        </div>
        
        <div>
            <x-card title="Tambah Lampiran">
                {{-- Display existing attachments --}}
                @if($permintaan->lampiranDokumen->count() > 0)
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-900 mb-2">Lampiran yang Ada:</h5>
                        <div class="space-y-2">
                            @foreach($permintaan->lampiranDokumen as $attachment)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-2">
                                        <span>
                                            @php
                                                $fileType = pathinfo($attachment->path, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($fileType, ['jpg', 'jpeg', 'png', 'gif']))
                                                <i class="fa-solid fa-image text-green-500"></i>
                                            @elseif($fileType === 'pdf')
                                                <i class="fa-solid fa-file-pdf text-red-500"></i>
                                            @elseif(in_array($fileType, ['doc', 'docx']))
                                                <i class="fa-solid fa-file-word text-blue-500"></i>
                                            @else
                                                <i class="fa-solid fa-file text-gray-500"></i>
                                            @endif
                                        </span>
                                        <span>
                                            <a href="{{ asset('storage/lampiranMaterial/' . $attachment->path) }}" target="_blank"
                                                class="text-gray-800 hover:underline">
                                                {{ basename($attachment->path) }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                {{-- Upload new attachments only if status is draft --}}
                @if($permintaan->status === 4)
                    <livewire:upload-surat-kontrak>
                @else
                    @if($permintaan->lampiranDokumen->count() === 0)
                        <div class="text-center text-gray-500 italic">
                            Tidak ada lampiran
                        </div>
                    @endif
                @endif
            </x-card>
        </div>
    </div>

    {{-- List Items --}}
    <div class="mt-6">
        <x-card title="Daftar Item Permintaan">
            @if($permintaan->status === 4)
                {{-- Form Add New Item --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold mb-3">Tambah Item Baru</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                            <select wire:model.live="newBarangId"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="">Pilih Barang</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Merk/Spesifikasi</label>
                            <select wire:model.live="newMerkId"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="">Pilih Merk</option>
                                @foreach($merks as $merk)
                                    <option value="{{ $merk->id }}">{{ $merk->nama }} - {{ $merk->tipe }} - {{ $merk->ukuran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                            <div class="flex">
                                <input type="number" wire:model.live="newJumlah"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    placeholder="0" min="1">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-l-0 border-gray-300 rounded-r-lg">
                                    {{ $newUnit }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-end">
                            <button type="button" wire:click="addToList" @disabled(!$newMerkId || !$newJumlah)
                                class="w-full bg-primary-600 hover:bg-primary-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                Tambah
                            </button>
                        </div>
                    </div>
                    @if($newMerkId && $isSeribu && $withRab)
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">RAB Item</label>
                            <select wire:model.live="newRabId"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="">Pilih RAB</option>
                                @foreach($rabs as $rab)
                                    <option value="{{ $rab->id }}">{{ $rab->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    {{-- @if($newMerkId)
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea wire:model.live="newKeterangan" rows="2"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                            placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>
                    @endif --}}
                </div>
            @endif

            {{-- List Table --}}
            <div class="overflow-x-auto">
                <table class="w-full border-3 border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-white uppercase">
                            @if($isSeribu && $withRab)
                                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[15%] rounded-l-lg">RKB</th>
                            @endif
                            <th
                                class="py-3 px-6 bg-primary-950 text-center font-semibold {{ $isSeribu && $withRab ? 'w-[15%]' : 'w-[20%] rounded-l-lg' }}">
                                Nama Barang</th>
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[25%]">Spesifikasi</th>
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[20%]">Volume</th>
                            @if($isSeribu && $withRab)
                                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Keterangan</th>
                            @endif
                            @if($permintaan->status === 4)
                                <th class="py-3 px-6 bg-primary-950 w-1/12 text-center font-semibold rounded-r-lg">Aksi</th>
                            @else
                                <th class="py-3 px-6 bg-primary-950 w-1/12 text-center font-semibold rounded-r-lg"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $index => $item)
                            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                                @if($isSeribu && $withRab)
                                    <td class="py-3 px-6">
                                        @if($item['rab_id'])
                                            {{ $rabs->firstWhere('id', $item['rab_id'])->nama_kegiatan ?? 'RAB tidak ditemukan' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif
                                <td class="py-3 px-6">
                                    {{ $item['merk']->barangStok->nama ?? 'Barang tidak ditemukan' }}
                                </td>
                                <td class="py-3 px-6">
                                    {{ $item['merk']->nama ?? 'Tanpa merk' }} -
                                    {{ $item['merk']->tipe ?? 'Tanpa tipe' }} -
                                    {{ $item['merk']->ukuran ?? 'Tanpa ukuran' }}
                                </td>
                                <td class="py-3 px-6">
                                    {{ number_format($item['jumlah'], 0, ',', '.') }} {{ $item['unit'] }}
                                </td>
                                @if($isSeribu && $withRab)
                                    <td class="py-3 px-6">
                                        {{ $item['keterangan'] ?? '-' }}
                                    </td>
                                @endif
                                <td class="py-3 px-6 text-center">
                                    @if($permintaan->status === 4)
                                        <button type="button" wire:click="removeFromList({{ $index }})"
                                            class="text-red-600 hover:text-red-900 font-medium">
                                            Hapus
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isSeribu && $withRab ? '6' : '4' }}"
                                    class="text-center text-xl px-3 py-6 font-bold">
                                    Belum ada item permintaan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Action Buttons --}}
            @if($permintaan->status === 4 && count($list) > 0)
                <div class="flex justify-center mt-6 gap-3">
                    <button type="button" wire:click="updateData"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                        Simpan Draft
                    </button>
                    <button type="button" wire:click="submitData"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                        Submit Permintaan
                    </button>
                </div>
            @endif
        </x-card>
    </div>
</div>