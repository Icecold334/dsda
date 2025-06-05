<header class="">
    <nav
        class="bg-[#d9faff] border-gray-200 shadow-2xl dark:bg-gray-800 flex flex-nowrap justify-between items-center ">
        <div class="flex items-center">
            <a href="/" class="flex">
                <img src="{{ asset('img/material.png') }}" alt="Logo" class="h-[4.5rem] max-w-[25rem] object-contain bg-[#003569]
 p-1">
                <img src="{{ asset('img/header-bg.jpg') }}" alt="Logo" class="h-[4.5rem] max-w-[25rem] object-contain bg-[#003569]
 ">
            </a>
        </div>

        <!-- Hamburger Icon -->
        {{-- <button class="p-4 lg:hidden" id="menuButton">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button> --}}

        <div class="flex" id="menu">
            <ul
                class="flex flex-col md:flex-row md:space-x-0 {{ Request::is('scan/*') || Request::is('qr/*') ? 'hidden' : '' }}">
                {{-- <ul class="grid grid-flow-col gap-0 -my-4 "> --}}
                    <livewire:nav-item href="/dashboard" title="home" />
                    @if (auth()->user()->hasRole(['Admin Sudin','Kepala Suku Dinas','P3K','Kepala Seksi', 'Kepala Suku
                    Dinas','Perencanaan']))
                    <livewire:nav-item href="/kontrak-vendor-stok" title="Daftar Kontrak" />
                    <livewire:nav-item title="Daftar RAB" href='/rab' />
                    @endif
                    @if (auth()->user()->hasRole(['Admin Sudin','Penjaga Gudang','Kepala Suku Dinas','Pengurus Barang',
                    'Pejabat Pelaksana Teknis Kegiatan', 'Pejabat Pembuat Komitmen','P3K','Kepala Satuan Pelaksana']))
                    <livewire:nav-item title="Form Barang" :child="[
                        // ['href' => route('pengiriman-stok.create'), 'title' => 'Form barang datang'],
                        ['href' => '/permintaan/material', 'title' => 'Permintaan Barang'],
                        ['href' => '/pengiriman-stok', 'title' => 'Pengiriman Barang'],
                        ]" />
                    <livewire:nav-item href="/log-barang" title="Riwayat Barang" />
                    @endif
                    @if (auth()->user()->unitKerja?->hak == 1)
                    <livewire:nav-item href="/dashboard" title="home" />
                    <livewire:nav-item title="Pelayanan Umum" :child="[
                        ['href' => '/permintaan/umum', 'title' => 'Form Pelayanan Umum'],
                        ['href' => '/permintaan-stok', 'title' => 'List Pelayanan Umum'],
                    ]" />
                    @else
                    @if (auth()->user()->hasRole(['Admin Sudin','Kepala Seksi', 'Kepala Suku Dinas','Perencanaan']))
                    {{--
                    <livewire:nav-item title="Daftar RAB" href='/rab' /> --}}
                    @endif
                    {{--
                    <livewire:nav-item title="RAB"
                        :child="[['href' => '/rab', 'title' => 'Daftar RAB'], ['href' => '#', 'title' => 'NODIN']]" />
                    --}}
                    {{--
                    <livewire:nav-item href="/permintaan-stok" title="Pelayanan Umum" /> --}}
                    @if (auth()->user()->hasRole(['Admin Sudin','Kepala Seksi', 'Kepala Subbagian',
                    'Pengurus Barang',
                    'Kepala Suku Dinas', 'Kepala Satuan Pelaksana' ]))
                    {{--
                    <livewire:nav-item href="/permintaan/material" title="Permintaan Barang" /> --}}
                    {{--
                    <livewire:nav-item title="Form" :child="[
                        // ['href' => /route('permintaan-stok.index'), 'title' => 'Form pelayanan Umum'],
                        // ['href' => '/#', 'title' => 'Form permintaan spare part'],
                        // ['href' => '/permintaan/umum', 'title' => 'Form pelayanan Umum'],
                        ['href' => '/permintaan/spare-part', 'title' => 'Form permintaan spare part'],
                        ['href' => '/permintaan/material', 'title' => 'Form permintaan material'],
                        // ['href' => route('pengiriman-stok.create'), 'title' => 'Form barang datang'],
                    ]" /> --}}
                    @endif
                    @endif
                    @if (auth()->user()->unitKerja->hak)
                    <livewire:nav-item title="aset" :child="[
                                        ['href' => '/aset', 'title' => 'aset aktif'],
                                        ['href' => '/nonaktifaset', 'title' => 'aset non aktif'],
                                    ]" />
                    @endif

                    <livewire:nav-item title="data" :child="[
                    ['href' => '/stok', 'title' => 'Stok'],
                    ['href' => '/kategori', 'title' => 'kategori'],
                    ['href' => '/barang', 'title' => 'Barang'],
                    // ['href' => '/merk', 'title' => 'Merk'],
                    ['href' => '/toko', 'title' => 'Toko / distributor'],
                    // ['href' => '/person', 'title' => 'Penanggung jawab'],
                    // ['href' => '/lokasi', 'title' => 'lokasi'],
                    ['href' => '/lokasi-stok', 'title' => 'lokasi gudang'],
                    ['href' => '/unit-kerja', 'title' => 'Unit Kerja'],
                    // ['href' => '/kategori-stok', 'title' => 'kategori stok'],
                    // ['href' => '/ruang', 'title' => 'ruang rapat'],
                ]" />
                    <livewire:notification />
                    @if (auth()->user()->unitKerja->hak)
                    <livewire:nav-item href="/kalender-aset" title='                <button data-tooltip-target="tooltipKalenderAset" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-book"></i></button>

                <div id="tooltipKalenderAset" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Kalender
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                    <livewire:nav-item href="/qrprint" title='<button data-tooltip-target="tooltipQR" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-print"></i></button>
                
                                <div id="tooltipQR" role="tooltip"
                                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Cetak QR-Code
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>' />
                    @endif
                    {{--
                    <livewire:nav-item href="/kalender" title='                <button data-tooltip-target="tooltipKalender" data-tooltip-placement="bottom" type="button"><i
                        class="fa-solid fa-calendar-days"></i></button>

                <div id="tooltipKalender" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Kalender Aset
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' /> --}}

                    <livewire:nav-item href="/option" title='<button data-tooltip-target="tooltipPengaturan" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-gear"></i></button>

                <div id="tooltipPengaturan" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Pengaturan
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                    {{-- @if (Auth::user()->unit_id)
                    <livewire:nav-item href="/option-approval" title='<button data-tooltip-target="tooltipPengaturanApproval" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-list-check"></i></button>

                <div id="tooltipPengaturanApproval" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Pengaturan Persetujuan
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                    @endif --}}

                    <livewire:nav-item href="/profil" title='<button data-tooltip-target="tooltipProfil" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-user"></i></button>

                <div id="tooltipProfil" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Profil dan Langganan
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                    <livewire:nav-item href="/logout" title='<button data-tooltip-target="tooltipLogout" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-power-off"></i></button>

                <div id="tooltipLogout" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Keluar
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                </ul>

        </div>
    </nav>
    {{-- @push('scripts')
    <script>
        const menuButton = document.getElementById('menuButton');
                const menu = document.getElementById('menu');

                menuButton.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });
    </script>
    @endpush --}}
</header>