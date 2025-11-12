<div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Form Pembuatan {{ $RKB }}
        </h1>
        <div>
            <a href="/rab" class=" text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm
            px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>

    {{-- Notifikasi khusus untuk Pengurus Barang --}}
    @if(auth()->user()->hasRole('Pengurus Barang'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">
                    Informasi untuk Pengurus Barang
                </h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>RAB yang Anda buat akan <strong>langsung disetujui</strong> karena merupakan RAB yang sudah ada
                        sebelum sistem ini dikembangkan.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-2 gap-6">
        <div>
            <x-card title="Data Kegiatan" maxH="true">
                <table class="w-full border-separate border-spacing-y-4">
                    <!-- Program -->
                    <tr>
                        <td class="font-semibold w-1/3">
                            <label for="program" class="block mb-2">Program *</label>
                        </td>
                        <td>
                            <select id="program" wire:model.live.debounce.500ms="program" @disabled($listCount) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                               focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white
                               dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">-- Pilih Program --</option>
                                @foreach($programs as $item)
                                <option value="{{ $item->id }}">{{ $item->kode }} {{ $item->program }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <!-- Kegiatan -->
                    <tr>
                        <td class="font-semibold"><label for="nama" class="block mb-2">Kegiatan *</label></td>
                        <td>
                            <select id="nama" wire:model.live.debounce.500ms="nama" @disabled(!$program || $listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                               focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white
                               dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">-- Pilih Kegiatan --</option>
                                @foreach($namas as $item)
                                <option value="{{ $item->id }}">{{ $item->kode }} {{ $item->kegiatan }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <!-- Sub Kegiatan -->
                    <tr>
                        <td class="font-semibold"><label for="sub_kegiatan" class="block mb-2">Sub Kegiatan *</label>
                        </td>
                        <td>
                            <select id="sub_kegiatan" wire:model.live.debounce.500ms="sub_kegiatan" @disabled(!$nama ||
                                $listCount) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                               focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white
                               dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">-- Pilih Sub Kegiatan --</option>
                                @foreach($sub_kegiatans as $item)
                                <option value="{{ $item->id }}">{{ $item->kode }} {{ $item->sub_kegiatan }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <!-- Rincian Sub Kegiatan -->
                    <tr>
                        <td class="font-semibold"><label for="aktivitas_sub_kegiatan" class="block mb-2">Aktivitas Sub
                                Kegiatan *</label></td>
                        <td>
                            <select id="aktivitas_sub_kegiatan" wire:model.live.debounce.500ms="aktivitas_sub_kegiatan"
                                @disabled(!$sub_kegiatan || $listCount) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                               focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white
                               dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">-- Pilih Aktivitas --</option>
                                @foreach($aktivitas_sub_kegiatans as $item)
                                <option value="{{ $item->id }}">{{ $item->kode }} {{ $item->aktivitas }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <!-- Kode Rekening -->
                    <tr>
                        <td class="font-semibold"><label for="kode_rekening" class="block mb-2">Kode Rekening *</label>
                        </td>
                        <td>
                            <select id="kode_rekening" wire:model.live.debounce.500ms="kode_rekening"
                                @disabled(!$aktivitas_sub_kegiatan || $listCount) class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                               focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white
                               dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">-- Pilih Kode Rekening --</option>
                                @foreach($kode_rekenings as $item)
                                <option value="{{ $item->id }}">{{ $item->kode }} {{ $item->uraian }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <!-- Tahun Anggaran -->
                    <tr>
                        <td class="">
                            <label class="block mb-2 cursor-not-allowed font-semibold text-gray-900 dark:text-white">
                                Tahun Anggaran *</label>
                        </td>
                        <td>
                            <input type="text" disabled
                                class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Tahun Anggaran" value="{{ Carbon\Carbon::now()->format('Y') }}">
                        </td>
                    </tr>
                    <!-- Jenis Pekerjaan -->
                    <tr>
                        <td class="">
                            <label for="jenis" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Jenis Pekerjaan *</label>
                        </td>
                        <td>
                            <input type="text" wire:model.live.debounce.500ms="jenis"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Jenis Pekerjaan">
                        </td>
                    </tr>

                    <!-- Tanggal Mulai -->
                    <tr>
                        <td class="font-semibold">
                            <label for="tanggal_mulai" class="block mb-2">Tanggal Mulai *</label>
                        </td>
                        <td>
                            <input type="date" id="tanggal_mulai" wire:model.live.debounce.500ms="mulai"
                                @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 {{ $listCount ? 'cursor-not-allowed opacity-50' : '' }}">
                            @error('tanggal_mulai')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>

                    <!-- Tanggal Selesai -->
                    <tr>
                        <td class="font-semibold">
                            <label for="tanggal_selesai" class="block mb-2">Tanggal Selesai *</label>
                        </td>
                        <td>
                            <input type="date" id="tanggal_selesai" wire:model.live.debounce.500ms="selesai"
                                @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 {{ $listCount ? 'cursor-not-allowed opacity-50' : '' }}">
                            @error('tanggal_selesai')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>

                    <!-- Lokasi -->
                    <tr>
                        <td class="font-semibold">
                            <label for="lokasi" class="block mb-2">Lokasi *</label>
                        </td>
                        <td>
                            <div class="flex flex-col gap-2">
                                <selectwire:model.live.debounce.500ms="kecamatan_id" @disabled($listCount)
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach($kecamatans as $item)
                                    <option value="{{ $item->id }}">{{ $item->kecamatan }}</option>
                                    @endforeach
                                    </select>
                                    {{-- <selectwire:model.live.debounce.500ms="kecamatan_id" @disabled($listCount)
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @foreach($saluran as $item)
                                        <option value="{{ $item['nama'] }}">{{ $item['nama'] }}
                                        </option>
                                        @endforeach
                                        </select> --}}

                                        <selectwire:model.live.debounce.500ms="kelurahan_id" @disabled(!$kecamatan_id ||
                                            $listCount)
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                            <option value="">-- Pilih Kelurahan --</option>
                                            @foreach($kelurahans as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                            </select>

                                            <textarea id="lokasi" wire:model.live.debounce.500ms="lokasi"
                                                @disabled($listCount)
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                rows="2"
                                                placeholder="Detail lokasi tambahan (misal: Jl. ABC No. 123, samping lapangan)">
                                                    </textarea>
                            </div>
                        </td>
                    </tr>
                    <tr class="">
                        <td>
                            <label for="jenis" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Volume Pekerjaan</label>
                        </td>
                        <td>
                            <div class="flex gap-x-2">
                                <input type="text" wire:model.live.debounce.500ms="vol.p"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                                    placeholder="Panjang">
                                <input type="text" wire:model.live.debounce.500ms="vol.l"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                                    placeholder="Lebar">
                                <input type="text" wire:model.live.debounce.500ms="vol.k"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                                    placeholder="Kedalaman">
                            </div>
                        </td>
                    </tr>
                </table>
            </x-card>
        </div>
        <div>
            <x-card title="Lampiran {{ $Rkb }}">
                <livewire:upload-surat-kontrak>
            </x-card>
        </div>
    </div>
    <div>
        <livewire:list-rab />
    </div>
</div>