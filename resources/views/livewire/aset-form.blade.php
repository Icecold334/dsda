<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <div>
            {{-- Umum --}}
            <div>
                <x-card title="umum" class="mb-3">
                    {{-- <form action=""> --}}
                    <table class="w-full border-separate border-spacing-y-4">
                        <tr>
                            <td style="width: 40%">
                                <label for="nama"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Nama Aset *</label>
                            </td>
                            <td>
                                <input type="text" id="nama" wire:model.live="nama"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Nama aset" required />
                                @error('nama')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="kode"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Kode Aset *</label>
                            </td>
                            <td>
                                <input type="text" id="kode" wire:model.live="kode"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Kode Aset" required />
                                @error('kode')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="kategori"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Kategori *</label>
                            </td>
                            <td>
                                <select id="kategori" wire:model.live="kategori"
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
                    {{-- </form> --}}
                </x-card>

            </div>
            {{--  --}}
            {{-- Detail --}}
            <div>
                <x-card title="Detail Aset" class="mb-3">
                    {{-- <form wire:submit.prevent="submit"> --}}
                    <table class="w-full border-separate border-spacing-y-4">
                        <!-- Merk Field -->
                        <tr>
                            <td style="width: 40%"><label for="merk"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Merk *</label>
                            </td>
                            <td>
                                <input type="text" id="merk" wire:model.live="merk" wire:focus="focusMerk"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Masukkan merk" required>
                                @if ($showSuggestionsMerk)
                                    <ul
                                        class="absolute z-20 w-96 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                                        @foreach ($suggestionsMerk as $suggestionMerk)
                                            <li wire:click="selectSuggestionMerk({{ $suggestionMerk['id'] }}, '{{ $suggestionMerk['nama'] }}')"
                                                class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer transition duration-200">
                                                {{ $suggestionMerk['nama'] }}
                                            </li>
                                        @endforeach

                                    </ul>
                                @endif
                                @error('merk')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <!-- Tipe Field -->
                        <tr>
                            <td><label for="tipe"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                            </td>
                            <td>
                                <input type="text" id="tipe" wire:model.live="tipe"
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
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produsen</label>
                            </td>
                            <td>
                                <input type="text" id="produsen" wire:model.live="produsen"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Masukkan produsen">
                                @error('produsen')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <!-- No. Seri / Kode Produksi Field -->
                        <tr>
                            <td><label for="noseri"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No. Seri
                                    / Kode Produksi</label></td>
                            <td>
                                <input type="text" id="noseri" wire:model.live="noseri"
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
                                <input type="text" id="tahunProduksi" wire:model.lazy="tahunProduksi"
                                    data-numeric-input
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
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                            </td>
                            <td>
                                <textarea id="deskripsi" wire:model.live="deskripsi"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Masukkan deskripsi" rows="3"></textarea>
                                @error('deskripsi')
                                    <span class="text-sm text-red-500 font-semibold">{{ the_message }}</span>
                                @enderror
                            </td>
                        </tr>
                    </table>
                    {{-- </form> --}}
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

            </div>
            {{--  --}}
            {{-- Pembelian --}}
            <div>
                <x-card title="Pembelian">
                    <table class="w-full border-separate border-spacing-y-4">
                        <tr wire:ignore>
                            <td class="w-1/3">
                                <label for="tanggalPembelian"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                    Pembelian *</label>
                            </td>
                            <td>
                                <input type="date" id="tanggalPembelian" wire:model.live="tanggalPembelian"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    required placeholder="Pilih tanggal">
                                @error('tanggalPembelian')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>

                        <!-- Toko / Distributor Field -->
                        <tr>
                            <td style="width: 40%"><label for="toko"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Toko /
                                    Distributor *</label>
                            </td>
                            <td>
                                <input type="text" id="toko" wire:model.live="toko" wire:focus="focusToko"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Masukkan toko" required>
                                @if ($showSuggestionsToko)
                                    <ul
                                        class="absolute z-20 w-96 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                                        @foreach ($suggestionsToko as $suggestionToko)
                                            <li wire:click="selectSuggestionToko({{ $suggestionToko['id'] }}, '{{ $suggestionToko['nama'] }}')"
                                                class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer transition duration-200">
                                                {{ $suggestionToko['nama'] }}
                                            </li>
                                        @endforeach

                                    </ul>
                                @endif
                                @error('toko')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <!-- No. Invoice Field -->
                        <tr>
                            <td style="width: 40%">
                                <label for="invoice"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No.
                                    Invoice</label>
                            </td>
                            <td>
                                <input type="text" id="invoice" wire:model.live="invoice"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    placeholder="Masukkan nomor invoice">
                                @error('invoice')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>

                        <!-- Jumlah Field -->
                        <tr>
                            <td>
                                <label for="jumlah"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah
                                    *</label>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <input type="number" id="jumlah" wire:model.live="jumlah" value="1"
                                        min="1"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                        required placeholder="Masukkan jumlah">
                                    <label for="jumlah"
                                        class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">Unit</label>
                                </div>
                                @error('jumlah')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>

                        <!-- Harga Satuan Field -->
                        <tr>
                            <td>
                                <label for="hargaSatuan"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Satuan
                                    (Rp) *</label>
                            </td>
                            <td>
                                <input type="text" id="hargaSatuan" wire:model.live="hargaSatuan"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    required placeholder="Masukkan harga satuan">
                                @error('hargaSatuan')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>

                        <!-- Harga Total Field -->
                        <tr>
                            <td>
                                <label for="hargaTotal"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Total
                                    (Rp)</label>
                            </td>
                            <td>
                                <input type="text" id="hargaTotal" wire:model.live="hargaTotal"
                                    class="bg-gray-200 border cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    disabled placeholder="Harga total akan dihitung otomatis">
                            </td>
                        </tr>

                        <!-- Lama Garansi Field -->
                        <tr>
                            <td>
                                <label for="lamagaransi"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lama Garansi
                                </label>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <input type="number" id="lamagaransi" wire:model.live="lamagaransi"
                                        value="" min="1"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                        required placeholder="Masukkan Lama Garansi">
                                    <label for="lamagaransi"
                                        class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">Tahun</label>
                                </div>
                                @error('lamagaransi')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <!--Kartu Garansi Field -->
                        <tr>
                            <td style="width: 40%"><label for="kartu_garansi"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kartu
                                    Garansi</label></td>
                            <td>
                                <input type="file" wire:model="newGaransiAttachments" multiple class="hidden"
                                    id="fileGaransi">
                                <label for="fileGaransi"
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
                                    + Unggah File
                                </label>
                                @error('newGaransiAttachments.*')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                                <div class="mt-3">
                                    @foreach ($garansiattachments as $index => $attachment)
                                        <div class="flex  items-center justify-between border-b-4 p-2 rounded my-1">
                                            <span><span class="text-primary-600 me-3"> @php
                                                $fileType = $attachment->getClientOriginalExtension();
                                            @endphp
                                                    @if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif']))
                                                        <i class="fa-solid fa-image text-green-500"></i>
                                                    @elseif($fileType == 'pdf')
                                                        <i class="fa-solid fa-file-pdf text-red-500"></i>
                                                    @elseif($fileType == 'doc' || $fileType == 'docx')
                                                        <i class="fa-solid fa-file-word text-blue-500"></i>
                                                    @else
                                                        <i class="fa-solid fa-file text-gray-500"></i>
                                                    @endif
                                                </span><span>{{ $attachment->getClientOriginalName() }}</span></span>
                                            <button wire:click="removeGaransiAttachment({{ $index }})"
                                                class="text-red-500 hover:text-red-700">&times;</button>
                                        </div>
                                    @endforeach
                                </div>
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
                                const umurInput = document.querySelector('#umur');
                                const penyusutanInput = document.querySelector('#penyusutan');

                                function calculateTotalAndDepreciation() {
                                    const jumlah = parseFloat(jumlahInput.value) || 0;
                                    const rawHargaSatuan = hargaSatuanInput.value.replace(/[^0-9,-]+/g, '');
                                    const hargaSatuan = parseFloat(rawHargaSatuan) || 0;
                                    const umur = parseInt(umurInput.value) || 0;
                                    const total = jumlah * hargaSatuan;

                                    // Set the total and dispatch an input event
                                    hargaTotalInput.value = formatRupiah(total);
                                    hargaTotalInput.dispatchEvent(new Event('input')); // Inform Livewire of change

                                    if (umur > 0) {
                                        const bulan = umur * 12;
                                        const penyusutan = total / bulan;

                                        // Set depreciation and dispatch an input event
                                        penyusutanInput.value = formatRupiah(penyusutan);
                                        penyusutanInput.dispatchEvent(new Event('input')); // Inform Livewire of change
                                    } else {
                                        penyusutanInput.value = formatRupiah(0);
                                        penyusutanInput.dispatchEvent(new Event('input')); // Inform Livewire of change
                                    }
                                }

                                // Format on input for Harga Satuan
                                hargaSatuanInput.addEventListener('input', function() {
                                    const numericValue = parseFloat(this.value.replace(/[^0-9,-]+/g, '')) || 0;
                                    this.value = formatRupiah(numericValue);
                                    calculateTotalAndDepreciation();
                                });

                                jumlahInput.addEventListener('input', calculateTotalAndDepreciation);
                                umurInput.addEventListener('input', calculateTotalAndDepreciation);

                                function formatRupiah(value) {
                                    const formatter = new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0
                                    });
                                    return formatter.format(value);
                                }
                            });




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
            </div>
            {{--  --}}
        </div>
        <div>
            {{-- Foto --}}
            <div>
                <x-card title="Foto" class="mb-3">
                    <div class="flex">
                        <div x-data="{ open: false }" class="relative w-2/5 px-5">
                            <!-- Trigger to open modal -->
                            <div class="w-60 h-60 overflow-hidden relative flex justify-center rounded-lg cursor-pointer"
                                @click="open = true; document.body.classList.add('overflow-hidden')">
                                @if ($img)
                                    <button
                                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 transition duration-200 text-white rounded-full p-1 text-lg leading-none h-8 w-8 flex items-center justify-center shadow"
                                        wire:click="removeImg"
                                        @click.stop="document.body.classList.remove('overflow-hidden'); open = false;">
                                        &times;
                                    </button>
                                @endif
                                <img src="{{ is_string($img) ? asset('storage/asetImg/' . $img) : ($img ? $img->temporaryUrl() : asset('img/default-pic.png')) }}"
                                    alt="Preview Image" class="w-full h-full object-cover object-center">
                            </div>

                            <!-- Modal for full image preview -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class=" fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                                @click="open = false; document.body.classList.remove('overflow-hidden')"
                                @keydown.escape.window="open = false; document.body.classList.remove('overflow-hidden')">
                                <img src="{{ is_string($img) ? asset('storage/asetImg/' . $img) : ($img ? $img->temporaryUrl() : asset('img/default-pic.png')) }}"
                                    class="max-w-full max-h-full " @click.stop="">
                            </div>
                        </div>  



                        <div class="w-3/5 px-5">
                            <div class="mb-3 text-sm">
                                Anda bisa mengunggah satu foto utama aset di sini.
                            </div>
                            <input type="file" wire:model.live="img" accept="image/*" class="hidden"
                                id="imgUpload">
                            <label for="imgUpload"
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
                                + Unggah Foto
                            </label>
                        </div>
                    </div>
                    @push('scripts')
                        <script type="module">
                            document.addEventListener('DOMContentLoaded', function() {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    }
                                });

                                window.addEventListener('swal:error', event => {

                                    Toast.fire({
                                        icon: 'error',
                                        text: event.detail[0].text,
                                    });
                                });
                            });
                        </script>
                    @endpush
                </x-card>

            </div>
            {{--  --}}
            {{-- Lampiran --}}
            <div>
                <div>
                    <x-card title="Lampiran" class="mb-3">
                        <div class="flex mb-3">
                            <div class="w-full px-5">
                                <div class="mb-3 text-sm">
                                    Anda bisa mengunggah dokumen, invoice, sertifikat, atau foto tambahan di sini.
                                </div>
                                <input type="file" wire:model="newAttachments" multiple class="hidden"
                                    id="fileUpload">
                                <label for="fileUpload"
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
                                    + Unggah Lampiran
                                </label>
                                @error('newAttachments.*')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                                <div class="mt-3">
                                    @foreach ($attachments as $index => $attachment)
                                        <div class="flex  items-center justify-between border-b-4 p-2 rounded my-1">
                                            <span><span class="text-primary-600 me-3"> @php
                                                $fileType = $attachment->getClientOriginalExtension();
                                            @endphp
                                                    @if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif']))
                                                        <i class="fa-solid fa-image text-green-500"></i>
                                                    @elseif($fileType == 'pdf')
                                                        <i class="fa-solid fa-file-pdf text-red-500"></i>
                                                    @elseif($fileType == 'doc' || $fileType == 'docx')
                                                        <i class="fa-solid fa-file-word text-blue-500"></i>
                                                    @else
                                                        <i class="fa-solid fa-file text-gray-500"></i>
                                                    @endif
                                                </span><span>{{ $attachment->getClientOriginalName() }}</span></span>
                                            <button wire:click="removeAttachment({{ $index }})"
                                                class="text-red-500 hover:text-red-700">&times;</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

            </div>
            {{--  --}}
            {{-- Keterangan --}}
            <div>
                <x-card title="Keterangan" class="mb-3">
                    <div class="flex mb-3">
                        <textarea id="keterangan" wire:model.live="keterangan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Masukkan Keterangan" rows="4"></textarea>
                    </div>
                </x-card>

            </div>
            {{--  --}}
            {{-- Penyusutan --}}
            <x-card title="Penyusutan" class="mb-3">
                <table class="w-full border-separate border-spacing-y-4">
                    <tr>
                        <td style="width: 40%">
                            <label for="umur"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
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
                            <label for="penyusutan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
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
            {{--  --}}
        </div>
    </div>
    <div class="flex justify-end">
        <button type="button" wire:click="saveAset"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>

</div>
