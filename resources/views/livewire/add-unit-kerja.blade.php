<div>
    <table class="w-full border-0 border-separate border-spacing-y-4">

        @if ($tipe == 'utama')
        <tr>
            <td>
                <label for="utama">Kode Utama</label>
            </td>
            <td>

                <input type="text" id="kode" wire:model.live.debounce.500ms="kode"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Kode Unit Kerja Utama" required />
                @error('kode')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="utama">Unit Kerja Utama</label>
            </td>
            <td>

                <input type="text" id="utama" wire:model.live.debounce.500ms="utama"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Nama Unit Kerja Utama" required />
                @error('utama')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="keterangan">Keterangan</label>
            </td>
            <td>

                <textarea id="keterangan" wire:model.live.debounce.500ms="keterangan"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Masukkan Keterangan" rows="3"></textarea>
                @error('keterangan')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        @endif
        @if ($tipe == 'sub')
        <tr>
            <td>
                <label for="parent_id">Unit Kerja Utama</label>
            </td>
            <td>
                <select id="parent_id" wire:model.live.debounce.500ms="parent_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Pilih Unit Kerja Utama</option>
                    @foreach ($unitkerjas as $unitkerja)
                    <option value="{{ $unitkerja->id }}">
                        {{ $unitkerja->nama }}
                    </option>
                    @endforeach
                </select>
                @error('parent_id')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="kode">Kode Sub</label>
            </td>
            <td>

                <input type="text" id="kode" wire:model.live.debounce.500ms="kode"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Kode Sub Unit Kerja" required />
                @error('kode')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="sub">Sub Unit Kerja</label>
            </td>
            <td>

                <input type="text" id="sub" wire:model.live.debounce.500ms="sub"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Nama Sub Unit Kerja" required />
                @error('sub')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="keterangan">Keterangan</label>
            </td>
            <td>

                <textarea id="keterangan" wire:model.live.debounce.500ms="keterangan"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Masukkan Keterangan" rows="3"></textarea>
                @error('keterangan')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        @endif
    </table>
    <div class="flex justify-end">
        @if ($id)
        <button type="button"
            onclick="confirmRemove('Apakah Anda yakin ingin menghapus Unit Kerja ini?', () => @this.call('removeUnitKerja'))"
            class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Hapus</button>
        @endif
        <button type="button" wire:click="saveUnitKerja"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>
</div>