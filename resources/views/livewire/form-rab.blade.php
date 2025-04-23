<div>
    <div class="grid grid-cols-2 gap-6">
        <div>
            <x-card title="Data Kegiatan">
                <table class="w-full border-separate border-spacing-y-4">
                    <!-- Program -->
                    <tr>
                        <td class="font-semibold w-1/3">
                            <label for="program" class="block mb-2">Program *</label>
                        </td>
                        <td>
                            <input type="text" id="program" wire:model.live="program" @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 {{ $listCount ? 'cursor-not-allowed opacity-50' : '' }}">
                            @error('program')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>

                    <!-- Nama Kegiatan -->
                    <tr>
                        <td class="font-semibold">
                            <label for="nama_kegiatan" class="block mb-2">Nama Kegiatan *</label>
                        </td>
                        <td>
                            <input type="text" id="nama_kegiatan" wire:model.live="nama" @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 {{ $listCount ? 'cursor-not-allowed opacity-50' : '' }}">
                            @error('nama_kegiatan')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>

                    <!-- Sub Kegiatan -->
                    <tr>
                        <td class="font-semibold">
                            <label for="sub_kegiatan" class="block mb-2">Sub Kegiatan *</label>
                        </td>
                        <td>
                            <input type="text" id="sub_kegiatan" wire:model.live="sub_kegiatan" @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 {{ $listCount ? 'cursor-not-allowed opacity-50' : '' }}">
                            @error('sub_kegiatan')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>

                    <!-- Rincian Sub Kegiatan -->
                    <tr>
                        <td class="font-semibold">
                            <label for="rincian_sub_kegiatan" class="block mb-2">Rincian Sub Kegiatan *</label>
                        </td>
                        <td>
                            <input type="text" id="rincian_sub_kegiatan" wire:model.live="rincian_sub_kegiatan"
                                @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 {{ $listCount ? 'cursor-not-allowed opacity-50' : '' }}">
                            @error('rincian_sub_kegiatan')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>

                    <!-- Kode Rekening -->
                    <tr>
                        <td class="font-semibold">
                            <label for="kode_rekening" class="block mb-2">Kode Rekening *</label>
                        </td>
                        <td>
                            <input type="text" id="kode_rekening" wire:model.live="kode_rekening" @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 {{ $listCount ? 'cursor-not-allowed opacity-50' : '' }}">
                            @error('kode_rekening')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>

                    <!-- Tanggal Mulai -->
                    <tr>
                        <td class="font-semibold">
                            <label for="tanggal_mulai" class="block mb-2">Tanggal Mulai *</label>
                        </td>
                        <td>
                            <input type="date" id="tanggal_mulai" wire:model.live="mulai" @disabled($listCount)
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
                            <input type="date" id="tanggal_selesai" wire:model.live="selesai" @disabled($listCount)
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
                            <textarea id="lokasi" wire:model.live="lokasi" @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 {{ $listCount ? 'cursor-not-allowed opacity-50' : '' }}"
                                rows="3"></textarea>
                            @error('lokasi')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                </table>
            </x-card>
        </div>
        <div>
            <x-card title="Lampiran RAB">
                <livewire:upload-surat-kontrak>
            </x-card>
        </div>
    </div>
    <div>
        <livewire:list-rab />
    </div>
</div>