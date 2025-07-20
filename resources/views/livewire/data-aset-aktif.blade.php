<div>
    <!-- Header -->
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">
            Aset Aktif
            @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <div>
            <button wire:click="$toggle('showFilters')"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                <i class="fa fa-search"></i> Pencarian & Pengurutan
            </button>

            @can('gudang.create')
                <a href="{{ route('aset.create') }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    + Tambah Aset
                </a>
            @endcan
        </div>
    </div>

    <div x-data="{ showFilters: @entangle('showFilters') }">
        <div x-show="showFilters" class="mt-4 p-4 border rounded-lg">
            <fieldset class="border p-4 rounded-lg">
                <legend class="text-lg font-semibold text-gray-800">Filter Tampilan</legend>
                <form wire:submit.prevent="fetchAsets">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Filter Nama -->
                        <div>
                            <label for="nama" class="text-sm font-medium">Cari Nama Aset</label>
                            <input type="text" wire:model.live="nama" class="border p-2 rounded w-full">
                        </div>

                        <!-- Filter Kategori -->
                        <div>
                            <label class="text-sm font-medium">Kategori</label>
                            <select wire:model.live="kategori_id" class="border p-2 rounded w-full">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Merk -->
                        <div>
                            <label class="text-sm font-medium">Merk</label>
                            <select wire:model.live="merk_id" class="border p-2 rounded w-full">
                                <option value="">Semua Merk</option>
                                @foreach ($merks as $merk)
                                    <option value="{{ $merk->id }}">{{ $merk->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Filter Toko -->
                        <div>
                            <label class="text-sm font-medium">Toko</label>
                            <select wire:model.live="toko_id" class="border p-2 rounded w-full">
                                <option value="">Semua Toko</option>
                                @foreach ($tokos as $toko)
                                    <option value="{{ $toko->id }}">{{ $toko->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Filter Penanggung Jawab -->
                        <div>
                            <label class="text-sm font-medium">Penanggung Jawab</label>
                            <select wire:model.live="penanggung_jawab_id" class="border p-2 rounded w-full">
                                <option value="">Semua Penanggung Jawab</option>
                                @foreach ($penanggungJawabs as $penanggung)
                                    <option value="{{ $penanggung->id }}">{{ $penanggung->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Filter Lokasi -->
                        <div>
                            <label class="text-sm font-medium">Lokasi</label>
                            <select wire:model.live="lokasi_id" class="border p-2 rounded w-full">
                                <option value="">Semua Lokasi</option>
                                @foreach ($lokasis as $lokasi)
                                    <option value="{{ $lokasi->id }}">{{ $lokasi->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4">
                        <button type="button" wire:click="resetFilters"
                            class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-400 transition">
                            <i class="fa fa-sync-alt"></i>
                        </button>

                        @can('gudang.read')
                            <div x-data="{ tooltip: false }" class="relative inline-block">
                                <!-- Tombol Download Excel -->
                                <button wire:click="exportExcel" @mouseover="tooltip = true" @mouseleave="tooltip = false"
                                    class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors"><i
                                        class="fa-solid fa-file-excel"></i></a>
                                </button>

                                <!-- Tooltip -->
                                <div x-show="tooltip" x-transition
                                    class="absolute left-1/2 transform -translate-x-1/2 mt-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-lg">
                                    Download dalam format MS Excel
                                </div>
                            </div>
                        @endcan
                    </div>
                </form>
            </fieldset>

            <!-- Filter Urutan -->
            <fieldset class="border p-4 rounded-lg">
                <legend class="text-lg font-semibold text-gray-800">Filter Urutan</legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Dropdown untuk Kolom Urutan -->
                    <div>
                        <label for="order_by" class="block text-sm font-medium text-gray-700">Urutkan
                            Berdasarkan</label>
                        <select wire:model.live="orderBy" id="order_by"
                            class="border border-gray-300 rounded-lg p-2 mt-1 w-full">
                            <option value="nama">Nama Aset</option>
                            <option value="tanggalbeli">Tanggal Pembelian</option>
                            <option value="hargasatuan">Harga Pembelian</option>
                            <option value="riwayat">Riwayat</option>
                        </select>
                    </div>

                    <!-- Dropdown untuk Arah Urutan -->
                    <div>
                        <label for="order_direction" class="block text-sm font-medium text-gray-700">Arah Urutan</label>
                        <select wire:model.live="orderDirection" id="order_direction"
                            class="border border-gray-300 rounded-lg p-2 mt-1 w-full">
                            <option value="asc">Menaik</option>
                            <option value="desc">Menurun</option>
                        </select>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>


    <table class="w-full  border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold ">NAMA ASET</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">KODE</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">MERK & TIPE</th>
                @can('gudang.read')
                    <th class="py-3 px-6 bg-primary-950 text-left font-semibold">PENYUSUTAN</th>
                @endcan
                @can('riwayat_transaksi.read')
                    <th class="py-3 px-6 bg-primary-950 text-left font-semibold">RIWAYAT TERAKHIR</th>
                @endcan
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @if (collect($asets)->isNotEmpty())
                @foreach ($asets as $aset)
                    <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl ">
                        <td class="py-3 px-6 w-[15rem]">
                            <div class="grid grid-cols-2 gap-3">
                                <div x-data="{ isOpen: false, qrImage: '', judul: '', baris1: '', baris2: '' }">
                                    @if (isset($aset['qr_code']))
                                        <!-- Thumbnail QR Code -->
                                        <div class="w-20 h-20 overflow-hidden relative flex justify-center p-1 border-2 rounded-lg bg-white cursor-pointer"
                                            @click="isOpen = true; qrImage = '{{ $aset['qr_code']['qr_image'] }}'; judul = '{{ $aset['qr_code']['judul'] }}'; baris1 = '{{ $aset['qr_code']['baris1'] }}'; baris2 = '{{ $aset['qr_code']['baris2'] }}'">
                                            <img class="w-full h-full object-cover object-center rounded-sm"
                                                src="{{ $aset['qr_code']['qr_image'] }}" alt="QR Code">
                                        </div>
                                    @else
                                        <div class="w-20 h-20 flex justify-center p-1 border-2 rounded-lg bg-white">
                                            <p class="text-gray-500 text-sm">QR Not Found</p>
                                        </div>
                                    @endif

                                    <!-- Modal QR Code -->
                                    <div x-show="isOpen"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                        x-cloak x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform scale-90"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-end="opacity-0 transform scale-90">

                                        <div class="bg-white p-4 rounded-lg shadow-lg relative max-w-sm text-center">
                                            <!-- Tombol Tutup -->
                                            <button @click="isOpen = false"
                                                class="absolute -top-4 -right-4 text-gray-600 hover:text-gray-900 w-7 h-7 bg-red-500 rounded-full z-50 text-white">
                                                X
                                            </button>

                                            <!-- QR Code & Bingkai -->
                                            <div class="relative w-full h-full">
                                                <!-- Bingkai -->
                                                <img src="{{ asset('img/qrbase.png') }}"
                                                    class="absolute top-0 left-0 w-full h-full object-cover z-10">

                                                <!-- Konten Modal -->
                                                <div
                                                    class="relative w-[300px] h-[400px] flex flex-col items-center justify-center">

                                                    <!-- Judul QR -->
                                                    <div class="absolute top-2 w-full flex justify-center">
                                                        <div x-text="judul" class="text-lg font-bold text-black z-20">
                                                        </div>
                                                    </div>

                                                    <!-- QR Code -->
                                                    <div
                                                        class="absolute inset-0 flex flex-col items-center justify-center z-20">
                                                        <img :src="qrImage"
                                                            class="w-72 h-72 object-cover p-1 z-20 mt-5">

                                                        <!-- Baris Keterangan 1 -->
                                                        <div x-text="baris1"
                                                            class="text-sm text-black font-bold mt-2 text-center z-20">
                                                        </div>

                                                        <!-- Baris Keterangan 2 -->
                                                        <div x-text="baris2"
                                                            class="text-sm text-black font-semibold text-center z-20">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div x-data="{ open: false, imgSrc: '' }">
                                    <!-- Gambar Thumbnail -->
                                    <div class="w-20 h-20 overflow-hidden relative flex justify-center p-1 border-2 rounded-lg bg-white cursor-pointer"
                                        @click="open = true; imgSrc = '{{ asset($aset->foto ? 'storage/asetImg/' . $aset->foto : 'img/default-pic-thumb.png') }}'">
                                        <img class="w-full h-full object-cover object-center rounded-sm"
                                            src="{{ asset($aset->foto ? 'storage/asetImg/' . $aset->foto : 'img/default-pic-thumb.png') }}"
                                            alt="">
                                    </div>

                                    <!-- Modal -->
                                    <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-300"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                                        @click="open = false" @keydown.escape.window="open = false">
                                        <img :src="imgSrc" class="w-60 h-60 object-cover object-center">
                                    </div>
                                </div>

                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $aset->nama }}</p>
                                <p class="text-sm text-gray-500">{{ $aset->kategori->nama ?? 'Tidak Berkategori' }}
                                </p>
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800">{{ $aset->kode }}</p>
                            <p class="font-normal text-gray-500 text-sm">Kode Sistem : {{ $aset->systemcode }}</p>
                            <p class="font-normal text-gray-500 text-sm">Tanggal Pembelian :
                                {{ $aset->tanggalbeli ? date('j F Y', $aset->tanggalbeli) : '---' }}
                            </p>
                        </td>
                        <td class="py-3 px-6 ">
                            <p class="font-semibold text-gray-800">{{ $aset->merk->nama ?? '--' }}</p>
                            <p class="text-sm text-gray-500">{{ $aset->tipe ?? '---' }}</p>

                        </td>
                        @can('gudang.read')
                            <td class="py-3 px-6 ">
                                <div class="flex items-center text-gray-800">
                                    {{ $aset->hargatotal_formatted }}
                                </div>
                                <div class="flex items-center text-gray-800">
                                    {{ $aset->totalpenyusutan }}
                                </div>
                                <div class="flex items-center text-gray-800">
                                    {{ $aset->nilaiSekarang }}
                                </div>
                            </td>
                        @endcan
                        @can('riwayat_transaksi.read')
                            <td class="py-3 px-6 ">
                                @if ($aset->histories_mapped && $aset->histories_mapped->isNotEmpty())
                                    @php
                                        $lastHistory = $aset->histories_mapped->last();
                                    @endphp

                                    @if ($lastHistory)
                                        <p class="font-semibold text-gray-800">
                                            {{ \Carbon\Carbon::parse($lastHistory->tanggal)->format('j F Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $lastHistory->person }}</p>
                                        <p class="text-sm text-gray-500">{{ $lastHistory->lokasi }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">Riwayat tidak ditemukan</p>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-500">--</p>
                                @endif
                            </td>
                        @endcan
                        <td class="py-3 px-6">
                            <a href="{{ route('aset.show', ['aset' => $aset->id]) }}"
                                class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                data-tooltip-target="tooltip-aset-{{ $aset->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <div id="tooltip-aset-{{ $aset->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Lihat Detail Aset
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-600">Tidak ada Aset Aktif.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-6">
        {{ $asets->onEachSide(1)->links() }}
    </div>
</div>
