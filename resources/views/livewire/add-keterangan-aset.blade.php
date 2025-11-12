<x-card title="Keterangan" class="mb-3">
    <div class="flex mb-3">
        <textarea id="keterangan" wire:model.live.debounce.500ms="keterangan"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
            placeholder="Masukkan Keterangan" rows="4"></textarea>
    </div>
</x-card>