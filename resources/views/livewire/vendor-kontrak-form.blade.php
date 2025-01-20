<div>
    <table class="w-full border-separate border-spacing-y-4">
        <tr>
            <td class="w-1/3">
                <label for="vendor_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Nama Vendor *</label>
            </td>
            <td>
                <div class="">
                    <input type="text" wire:model.live="query" wire:focus="focus" @disabled($listCount)
                        placeholder="Cari Vendor" wire:blur="hideSuggestions"
                        class="bg-gray-50 border border-gray-300 {{ $listCount ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">

                    {{-- @if (!empty($suggestions)) --}}
                    @if ($showSuggestions)
                        <ul
                            class="absolute z-20 w-96 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                            @if ($show)
                                <li wire:click="toggleAddVendorForm"
                                    class="px-4 py-2 hover:bg-blue-500 transition duration-200 hover:text-white cursor-pointer group">
                                    <span class="text-primary-500 group-hover:text-white"><i
                                            class="fa-solid fa-circle-plus"></i></span> Tambah
                                    Vendor
                                </li>
                            @endif
                            @foreach ($suggestions as $suggestion)
                                <li wire:click="selectSuggestion({{ $suggestion['id'] }}, '{{ $suggestion['nama'] }}')"
                                    class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer transition duration-200">
                                    {{ $suggestion['nama'] }}
                                </li>
                            @endforeach

                        </ul>
                    @endif
                </div>
                @error('vendor_id')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        @if ($showNomor)
            <tr class={{ !$cekSemuaItem ? 'hidden' : '' }}>
                <td class="w-1/3">
                    <label for="nomor_kontrak"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor
                        Kontrak *</label>
                </td>
                <td>
                    <input type="text" wire:model.live="nomor_kontrak"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        @disabled(!$cekSemuaItem)>
                    @error('nomor_kontrak')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr class="{{ !$cekSemuaItem ? 'hidden' : '' }}">
                <td class="w-1/3">
                    <label for="tanggal_kontrak"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Kontrak *</label>
                </td>
                <td>
                    <input type="date" wire:model.live="tanggal_kontrak"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    @error('tanggal_kontrak')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        <tr>
            <td class="w-1/3">
                <label for="barang_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Jenis Barang *</label>
            </td>
            <td>
                <select wire:model.live="barang_id" @disabled($listCount) @disabled($vendor_id == null)
                    class="bg-gray-50 border border-gray-300 {{ $listCount || $vendor_id == null ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option value="">Pilih Jenis Barang</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}" @selected($barang->id == $barang_id)>{{ $barang->nama }}</option>
                    @endforeach

                </select>
                @error('barang_id')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        @if ($showMetode)

            <tr class="{{ !$cekSemuaItem ? 'hidden' : '' }}">
                <td class="w-1/3">
                    <label for="barang_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Metode Pengadaan *</label>
                </td>
                <td>
                    <select wire:model.live="metode_id" @disabled($vendor_id == null)
                        class="bg-gray-50 border border-gray-300 {{ $vendor_id == null ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="">Pilih Metode Pengadaan</option>
                        @foreach ($metodes as $metode)
                            <option value="{{ $metode->id }}" @selected($metode->id == $metode_id)>{{ $metode->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('metode_id')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr class="{{ !$type ? 'hidden' : '' }}">
                <td class="w-1/3">
                    <label for="vendor_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nominal Kontrak *</label>
                </td>
                <td>
                    <div class="">
                        <div class="flex">
                            <div
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block max-w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                Rp
                            </div>
                            <input @disabled($listCount)
                                class="bg-gray-50 {{ $listCount ? 'cursor-not-allowed' : '' }} border border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                type="text" placeholder="Nominal Kontrak" oninput="formatRupiahh(this)">
                            @push('scripts')
                                <script type="module">
                                    window.formatRupiahh = function(param) {
                                        console.log('sadsad');

                                        let angka = param.value
                                        const numberString = angka.replace(/[^,\d]/g, '').toString();
                                        const split = numberString.split(',');
                                        let sisa = split[0].length % 3;
                                        let rupiah = split[0].substr(0, sisa);
                                        const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                                        if (ribuan) {
                                            const separator = sisa ? '.' : '';
                                            rupiah += separator + ribuan.join('.');
                                        }

                                        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                                        @this.set('nominalKontrak', rupiah);
                                        return param.value = rupiah;
                                    }
                                </script>
                            @endpush
                        </div>
                    </div>
                </td>
            </tr>
        @endif

    </table>
    <!-- Button to toggle Add New Vendor form -->
    {{-- @if ($show && $showAddVendorForm)
        <div class="mt-3">
            <button wire:click="toggleAddVendorForm"
                class="text-sm text-white bg-primary-600 px-2 rounded-lg  hover:shadow-lg hover:bg-primary-400 transition duration-200">{{ $showAddVendorForm ? 'Batal' : '+ Tambah Vendor Baru' }}</button>
        </div>
    @endif --}}

    <!-- Add New Vendor Form -->
    @if ($showAddVendorForm)
        <div class="mt-4 border p-4 rounded bg-gray-100">
            <h3 class="text-md font-semibold mb-2">Tambah Vendor Baru</h3>
            <div class="mb-3">
                <label for="nama" class="block text-sm font-medium">Nama</label>
                <input type="text" wire:model.live="nama" class="border p-2 rounded w-full">
                @error('nama')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="alamat" class="block text-sm font-medium">Alamat</label>
                <input type="text" wire:model.live="alamat" class="border p-2 rounded w-full">
                @error('alamat')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="kontak" class="block text-sm font-medium">Kontak</label>
                <input type="text" wire:model.live="kontak" class="border p-2 rounded w-full">
                @error('kontak')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <button wire:click="addNewVendor"
                class="bg-primary-500 text-white text-sm px-2 py-1 rounded hover:bg-primary-600 transition duration-200">Simpan
                Vendor Baru</button>
            <button wire:click="toggleAddVendorForm"
                class="bg-primary-500 text-white text-sm px-2 py-1 rounded hover:bg-primary-600 transition duration-200">
                Batal</button>


        </div>
    @endif
</div>
