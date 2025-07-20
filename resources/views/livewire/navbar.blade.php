<header class="">
    <nav class="bg-[#d9faff] border-gray-200 shadow-2xl dark:bg-gray-800 flex flex-nowrap justify-between items-center">
        <div class="flex items-center">
            <a href="/" class="flex">
                <img src="{{ asset('img/material.png') }}" alt="Logo"
                    class="h-[4.5rem] max-w-[25rem] object-contain bg-[#003569] p-1">
                <img src="{{ asset('img/header-bg.jpg') }}" alt="Logo"
                    class="h-[4.5rem] max-w-[25rem] object-contain bg-[#003569]">
            </a>
        </div>

        <div class="flex" id="menu">
            <ul
                class="flex flex-col md:flex-row md:space-x-0 {{ Request::is('scan/*') || Request::is('qr/*') ? 'hidden' : '' }}">
                <livewire:nav-item href="/dashboard" title="dashboard" />

                @can('kontrak.read')
                <livewire:nav-item href="/kontrak-vendor-stok" title="Daftar Kontrak" />
                @endcan
                @can('rab.read')
                <livewire:nav-item title="Daftar RAB" href='/rab' />
                @endcan

                @php
                $formBarangItems = [];
                if (auth()->user()->can('permintaan_barang.read')) {
                $formBarangItems[] = ['href' => '/permintaan/material', 'title' => 'Permintaan Barang'];
                }
                if (auth()->user()->can('penerimaan_barang.read')) {
                $formBarangItems[] = ['href' => '/pengiriman-stok', 'title' => 'Pengiriman Barang'];
                }
                @endphp

                @if (count($formBarangItems) > 0)
                <livewire:nav-item title="Form Barang" :child="$formBarangItems" />
                @endif

                @can('riwayat_transaksi.read')
                <livewire:nav-item href="/log-barang" title="Riwayat Barang" />
                @endcan

                @if (auth()->user()->unitKerja?->hak == 1)
                <livewire:nav-item href="/dashboard" title="home" />
                <livewire:nav-item title="Pelayanan Umum" :child="[
                        ['href' => '/permintaan/umum', 'title' => 'Form Pelayanan Umum'],
                        ['href' => '/permintaan-stok', 'title' => 'List Pelayanan Umum'],
                    ]" />
                @endif

                @if (auth()->user()->unitKerja->hak)
                <livewire:nav-item title="aset" :child="[
                        ['href' => '/aset', 'title' => 'aset aktif'],
                        ['href' => '/nonaktifaset', 'title' => 'aset non aktif'],
                    ]" />
                @endif

                @php
                $masterDataItems = [];
                // if (auth()->user()->can('gudang.read')) {
                $masterDataItems[] = ['href' => '/stok', 'title' => 'Stok'];
                $masterDataItems[] = ['href' => '/stok/sudin/sudin', 'title' => 'Stok Sudin'];
                $masterDataItems[] = ['href' => '/barang', 'title' => 'Barang'];
                // }
                if (auth()->user()->can('gudang.read')) {
                $masterDataItems[] = ['href' => '/kategori', 'title' => 'kategori'];
                }
                if (auth()->user()->can('gudang.read')) {
                $masterDataItems[] = ['href' => '/toko', 'title' => 'Toko / distributor'];
                }
                if (auth()->user()->can('gudang.read')) {
                $masterDataItems[] = ['href' => '/lokasi-stok', 'title' => 'lokasi gudang'];
                }
                if (auth()->user()->can('manajemen_user.read')) {
                $masterDataItems[] = ['href' => '/unit-kerja', 'title' => 'Unit Kerja'];
                }
                if (auth()->user()->can('input_driver_security.read')) {
                $masterDataItems[] = ['href' => '/driver', 'title' => 'Driver'];
                }
                if (auth()->user()->can('input_driver_security.read')) {
                $masterDataItems[] = ['href' => '/security', 'title' => 'Security'];
                }
                @endphp
                @if (count($masterDataItems) > 0)
                <livewire:nav-item title="master data" :child="$masterDataItems" />
                @endif

                @can('dashboard.read')
                <livewire:nav-item href="/qrprint" title='<button data-tooltip-target="tooltipQR" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-print"></i></button>
                        <div id="tooltipQR" role="tooltip" class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Cetak QR-Code
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>' />
                @endcan

                @can('manajemen_user.read')
                <livewire:nav-item href="/option" title='<button data-tooltip-target="tooltipPengaturan" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-gear"></i></button>
                        <div id="tooltipPengaturan" role="tooltip" class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Pengaturan
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>' />
                @endcan
                <livewire:notification />
                <livewire:nav-item href="/profil" title='<button data-tooltip-target="tooltipProfil" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-user"></i></button>
                    <div id="tooltipProfil" role="tooltip" class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Profil dan Langganan
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>' />
                <livewire:nav-item href="/logout" title='<button data-tooltip-target="tooltipLogout" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-power-off"></i></button>
                    <div id="tooltipLogout" role="tooltip" class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Keluar
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>' />
            </ul>
        </div>
    </nav>
</header>