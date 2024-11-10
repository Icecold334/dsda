<table class="w-full border-separate border-spacing-y-4">
    <tr>
        <td class="w-1/3">
            <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Nama Vendor *</label>
        </td>
        <td>
            <select wire:model.live="vendor_id" @disabled($listCount)
                class="bg-gray-50 border border-gray-300 {{ $listCount ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Pilih Vendor</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->nama }}</option>
                @endforeach
            </select>
            @error('vendor_id')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
            @enderror
        </td>
    </tr>

</table>
