<div>
    <table class="w-full border-0 border-separate border-spacing-y-4">

        @if ($tipe == 'lokasi')
            <tr>
                <td>

                    <label for="lokasi">Lokasi</label>
                </td>
                <td>

                    <input type="text" id="lokasi" wire:model.live="lokasi"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Nama Lokasi" required />
                    @error('lokasi')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="lokasi">Alamat</label>
                </td>
                <td>

                    <textarea id="alamat" wire:model.live="alamat"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan alamat" rows="3"></textarea>
                    @error('alamat')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        @if ($tipe == 'bagian')
            <tr>
                <td>
                    <label for="lokasi_id">Lokasi</label>
                </td>
                <td>
                    <select id="lokasi_id" wire:model.live="lokasi_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Pilih Lokasi</option>
                        @foreach ($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}">
                                {{ $lokasi->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_id')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="bagian">Bagian</label>
                </td>
                <td>

                    <input type="text" id="bagian" wire:model.live="bagian"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Nama Bagian" required />
                    @error('bagian')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        @if ($tipe == 'posisi')
            <tr>
                <td>
                    <label for="bagian_id">Bagian</label>
                </td>
                <td>
                    <select id="bagian_id" wire:model.live="bagian_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Pilih Bagian</option>
                        @foreach ($lokasis as $lokasi)
                            <optgroup label="{{ $lokasi->nama }}">
                                @foreach ($lokasi->bagianStok as $bagian)
                                    <option value="{{ $bagian->id }}">{{ $bagian->nama }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('bagian_id')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="posisi">Posisi</label>
                </td>
                <td>

                    <input type="text" id="posisi" wire:model.live="posisi"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Nama Posisi" required />
                    @error('posisi')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
    </table>
    <div class="flex justify-end">
        @if ($id)
            <button type="button" onclick="confirmRemove('Apakah Anda yakin ingin menghapus Lokasi/Bagian/Posisi ini?', () => @this.call('removeLokasi'))"
                class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Hapus</button>
        @endif
        <button type="button" wire:click="saveLokasi"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>
</div>
