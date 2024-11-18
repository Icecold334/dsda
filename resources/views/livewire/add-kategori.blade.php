<div>
    <table class="w-full border-0 border-separate border-spacing-y-4">

        @if ($tipe == 'utama')
            <tr>
                <td>

                    <label for="utama">Utama</label>
                </td>
                <td>

                    <input type="text" id="utama" wire:model.live="utama"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Nama utama" required />
                    @error('utama')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="alamat">Alamat</label>
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
        @if ($tipe == 'sub')
            <tr>
                <td>
                    <label for="parent_id">Kategori Utama</label>
                </td>
                <td>
                    {{-- @dump($parent_id); --}}
                    <select id="parent_id" wire:model.live="parent_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Pilih Kategori Utama</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">
                                {{ $kategori->nama }}
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

                    <label for="sub">Sub Kategori</label>
                </td>
                <td>

                    <input type="text" id="sub" wire:model.live="sub"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Nama Sub Kategori" required />
                    @error('sub')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
    </table>
    <div class="flex justify-end">
        @if ($id)
            <button type="button" wire:click="removeKategori"
                class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Hapus</button>
        @endif
        <button type="button" wire:click="saveKategori"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>
</div>
