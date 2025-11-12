<x-card title="Detail Aset" class="mb-3">
    {{-- <form wire:submit.prevent="submit"> --}}
        <table class="w-full border-separate border-spacing-y-4">
            <!-- Merk Field -->
            <tr>
                <td style="width: 40%"><label for="merk"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Merk *</label></td>
                <td>
                    <input type="text" id="merk" wire:model.live.debounce.500ms="merk"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan merk" required>
                    @error('merk')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <!-- Tipe Field -->
            <tr>
                <td><label for="tipe" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                </td>
                <td>
                    <input type="text" id="tipe" wire:model.live.debounce.500ms="tipe"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan tipe">
                    @error('tipe')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <!-- Produsen Field -->
            <tr>
                <td><label for="produsen"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produsen</label></td>
                <td>
                    <input type="text" id="produsen" wire:model.live.debounce.500ms="produsen"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan produsen">
                    @error('produsen')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <!-- No. Seri / Kode Produksi Field -->
            <tr>
                <td><label for="noseri" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No. Seri
                        / Kode Produksi</label></td>
                <td>
                    <input type="text" id="noseri" wire:model.live.debounce.500ms="noseri"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan nomor seri atau kode produksi">
                    @error('noseri')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <!-- Tahun Produksi Field -->
            <tr>
                <td><label for="tahunProduksi"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun
                        Produksi</label></td>
                <td>
                    <input type="text" id="tahunProduksi" wire:model.lazy="tahunProduksi" data-numeric-input
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan tahun produksi (YYYY)">
                    @error('tahunProduksi')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <!-- Deskripsi Field -->
            <tr>
                <td><label for="deskripsi"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label></td>
                <td>
                    <textarea id="deskripsi" wire:model.live.debounce.500ms="deskripsi"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan deskripsi" rows="3"></textarea>
                    @error('deskripsi')
                    <span class="text-sm text-red-500 font-semibold">{{ the_message }}</span>
                    @enderror
                </td>
            </tr>
        </table>
        {{--
    </form> --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                const numericInputs = document.querySelectorAll('input[data-numeric-input]');

                numericInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
                        if (this.value.length > 4) {
                            this.value = this.value.slice(0, 4); // Limit to 4 digits
                        }
                    });
                });
            });
    </script>
    @endpush
</x-card>