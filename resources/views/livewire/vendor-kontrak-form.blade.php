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
                            @if ($show && !$showAddVendorForm)
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
            {{-- <td>
                <select wire:model.live="vendor_id" @disabled($listCount)
                    class="bg-gray-50 border border-gray-300 {{ $listCount ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option value="">Pilih Vendor</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}" @selected($vendor->id == $vendor_id)>{{ $vendor->nama }}</option>
                    @endforeach
                </select>
                @error('vendor_id')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td> --}}
        </tr>
        @if ($showNomor)
            <tr>
                <td class="w-1/3">
                    <label for="nomor_kontrak"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor
                        Kontrak *</label>
                </td>
                <td>
                    <input type="text" wire:model.live="nomor_kontrak"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    @error('nomor_kontrak')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
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
            <tr>
                <td class="w-1/3">
                    <label for="barang_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Metode Pengadaan *</label>
                </td>
                <td>
                    <select wire:model.live="metode_id" @disabled($listCount) @disabled($vendor_id == null)
                        class="bg-gray-50 border border-gray-300 {{ $listCount || $vendor_id == null ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
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
