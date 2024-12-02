<x-card title="Penyusutan" class="mb-3">
    <table class="w-full border-separate border-spacing-y-4">
        <tr>
            <td style="width: 40%">
                <label for="umur" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Umur Ekonomi *</label>
            </td>
            <td class="">
                <input type="number" id="umur" wire:model.live="umur"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Umur" required />
                @error('umur')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="penyusutan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Penyusutan (Rp)</label>
            </td>
            <td>
                <input type="text" id="penyusutan" wire:model.live="penyusutan" disabled
                    class="bg-gray-50 border border-gray-300 text-gray-900 cursor-not-allowed text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Penyusutan Aset" required />
                @error('penyusutan')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
    </table>
</x-card>
