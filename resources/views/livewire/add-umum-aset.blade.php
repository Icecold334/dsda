<x-card title="umum" class="mb-3">
    {{-- <form action=""> --}}
        <table class="w-full border-separate border-spacing-y-4">
            <tr>
                <td style="width: 40%">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nama Aset *</label>
                </td>
                <td>
                    <input type="text" id="nama" wire:model.live.debounce.500ms="nama"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Nama" required />
                    @error('nama')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>
                    <label for="kode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Kode Aset *</label>
                </td>
                <td>
                    <input type="text" id="kode" wire:model.live.debounce.500ms="kode"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Kode Aset" required />
                    @error('kode')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>
                    <label for="kategori" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Kategori *</label>
                </td>
                <td>
                    <select id="kategori" wire:model.live.debounce.500ms="kategori"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="0">Tidak Berkategori</option>
                        @foreach ($kategoris as $kategoriItem)
                        <option value="{{ $kategoriItem->id }}">
                            {{ $kategoriItem->parent != null ? '--- ' . $kategoriItem->nama : $kategoriItem->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('kategori')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        </table>
        {{--
    </form> --}}
</x-card>