<x-card title="Pembelian">
    <table class="w-full border-separate border-spacing-y-4">
        <!-- Tanggal Pembelian Field -->
        <tr wire:ignore>
            <td class="w-1/3"><label for="tanggalPembelian"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Pembelian *</label>
            </td>
            <td>
                <input type="date" id="tanggalPembelian" wire:model.live.debounce.500ms="tanggalPembelian"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                    required>
                @error('tanggalPembelian')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>

        <!-- Toko / Distributor Field -->
        <tr>
            <td><label for="toko" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Toko /
                    Distributor *</label></td>
            <td>
                <input type="text" id="toko" wire:model.live.debounce.500ms="toko"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                    required>
                @error('toko')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <!-- No. Invoice Field -->
        <tr>
            <td style="width: 40%"><label for="invoice"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No.
                    Invoice</label></td>
            <td>
                <input type="text" id="invoice" wire:model.live.debounce.500ms="invoice"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                @error('invoice')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <!-- Jumlah Field -->
        <tr>
            <td><label for="jumlah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah
                    *</label></td>
            <td>
                <div class="flex items-center ">
                    <input type="number" id="jumlah" wire:model.live.debounce.500ms="jumlah" value="1" min="1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                        required>
                    <label for="jumlah"
                        class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        Unit
                    </label>
                </div>
                @error('jumlah')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>

        </tr>
        <!-- Harga Satuan Field -->
        <tr>
            <td><label for="hargaSatuan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
                    Satuan (Rp) *</label></td>
            <td>
                <input type="text" id="hargaSatuan" wire:model.live.debounce.500ms="hargaSatuan"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                    required>
                @error('hargaSatuan')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <!-- Harga Total Field -->
        <tr>
            <td><label for="hargaTotal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
                    Total (Rp)</label></td>
            <td>
                <input type="text" id="hargaTotal" wire:model.live.debounce.500ms="hargaTotal"
                    class="bg-gray-200 border cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                    disabled>
            </td>
        </tr>
    </table>
    @push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                const jumlahInput = document.querySelector('#jumlah');
                const hargaSatuanInput = document.querySelector('#hargaSatuan');
                const hargaTotalInput = document.querySelector('#hargaTotal');

                // Initialize formatted values
                formatRupiahOnInput(hargaSatuanInput);

                // Update total when 'jumlah' changes
                jumlahInput.addEventListener('input', function() {
                    updateTotal();
                    this.dispatchEvent(new Event(
                    'input')); // Ensure Livewire or other frameworks can react to this change
                });

                // Update total when 'harga satuan' changes
                hargaSatuanInput.addEventListener('input', function() {
                    formatRupiahOnInput(this);
                    updateTotal();
                    this.dispatchEvent(new Event(
                    'input')); // Ensure Livewire or other frameworks can react to this change
                });

                function updateTotal() {
                    const jumlah = parseFloat(jumlahInput.value) || 0;
                    const hargaSatuan = parseFloat(hargaSatuanInput.value.replace(/[^0-9,-]+/g, '')) || 0;
                    const total = jumlah * hargaSatuan;
                    hargaTotalInput.value = formatRupiah(total);
                    hargaTotalInput.dispatchEvent(new Event('input')); // Dispatch event for total input as well
                }
            });

            function formatRupiah(value) {
                const formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                });
                return formatter.format(value);
            }

            function formatRupiahOnInput(input) {
                const numericValue = parseFloat(input.value.replace(/[^0-9,-]+/g, '')) || 0;
                input.value = formatRupiah(numericValue);
            }



            document.addEventListener('DOMContentLoaded', function() {
                const dateInput = document.querySelector("#tanggalPembelian");
                flatpickr("#tanggalPembelian", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    maxDate: "today",
                    defaultDate: "today",
                    onChange: function(selectedDates, dateStr, instance) {
                        // This event is triggered when the date is selected or changed
                        const event = new Event('input', {
                            bubbles: true
                        }); // Create a new 'input' event
                        dateInput.dispatchEvent(event); // Dispatch it on the original input element
                    }
                });

                // Optionally, trigger the input event immediately after initializing to set the initial state
                dateInput.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            });
    </script>
    @endpush
</x-card>