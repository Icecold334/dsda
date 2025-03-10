<div>
    <table class="w-full border-0 border-separate border-spacing-y-4">
        <tr>
            <td>
                <label for="ruang">Nama Ruang Rapat</label>
            </td>
            <td>

                <input type="text" id="ruang" wire:model.live="ruang"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Nama ruang" required />
                @error('ruang')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="ruang">Penanggung Jawab</label>
            </td>
            <td>
                <input type="text" wire:model.live="user" wire:input="fetchSuggestions('user', $event.target.value)"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Masukkan Penanggung Jawab Ruangan" wire:blur="hideSuggestions('user')" required>
                @if ($suggestions['user'])
                    <ul class="absolute z-20 w-96 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                        @foreach ($suggestions['user'] as $suggestion)
                            <li wire:click="selectSuggestion('user', '{{ $suggestion }}')"
                                class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer transition duration-200">
                                {{ $suggestion }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                @error('user')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </td>
        </tr>
    </table>
    <div class="flex justify-end">
        @if ($id)
            <button type="button"
                onclick="confirmRemove('Apakah Anda yakin ingin menghapus Ruang ini?', () => @this.call('removeRuang'))"
                class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Hapus</button>
        @endif
        <button type="button" wire:click="saveRuang"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>
</div>
