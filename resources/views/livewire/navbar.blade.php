    <header class="">
        <nav
            class="bg-[#d9faff] border-gray-200 shadow-2xl dark:bg-gray-800 flex flex-wrap justify-between items-center overflow-y-hidden">
            <div class="flex justify-start items-center "
                style="background:#003569 url({{ asset('img/header-bg.jpg') }}) no-repeat right center;float:left;width:420px;height:64px">
                <a href="https://flowbite.com" class="flex mx-8">
                    <span class="self-center  text-white font-semibold whitespace-nowrap dark:text-white"><img
                            src="{{ asset('img/inventa-logo.png') }}" class=" w-[250px] h-auto" alt=""></span>
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

            <div class="flex justify-between items-center overflow-y-hidden" id="menu">
                <ul class="flex flex-col md:flex-row md:space-x-4 {{ Request::is('scan/*') ? 'hidden' : '' }}">
                    {{-- <ul class="grid grid-flow-col gap-0 -my-4 "> --}}
                    <livewire:nav-item href="/dashboard" title="home" />
                    <livewire:nav-item title="aset" :child="[
                        ['href' => '/aset', 'title' => 'aset aktif'],
                        ['href' => '/nonaktifaset', 'title' => 'aset non aktif'],
                    ]" />
                    <livewire:nav-item title="inventaris" :child="[
                        ['href' => '/pengiriman-stok', 'title' => 'inventaris'],
                        ['href' => '/stok', 'title' => 'stok'],
                    ]" />
                    <livewire:nav-item title="Rekam Kontrak" :child="[
                        ['href' => '/kontrak-vendor-stok', 'title' => 'Daftar Kontrak'],
                        ['href' => '/transaksi-darurat-stok', 'title' => 'Transaksi Belum Berkontrak'],
                    ]" />

                    <livewire:nav-item title="Form" :child="[
                        ['href' => route('permintaan-stok.index'), 'title' => 'Form pelayanan Umum'],
                        // ['href' => '/#', 'title' => 'Form permintaan spare part'],
                        ['href' => '/#', 'title' => 'Form permintaan material'],
                        ['href' => route('pengiriman-stok.create'), 'title' => 'Form barang datang'],
                    ]" />
                    <livewire:nav-item title="data" :child="[
                        ['href' => '/kategori', 'title' => 'kategori'],
                        ['href' => '/merk', 'title' => 'Merk'],
                        ['href' => '/toko', 'title' => 'Toko / distributor'],
                        ['href' => '/person', 'title' => 'Penanggung jawab'],
                        ['href' => '/lokasi', 'title' => 'lokasi'],
                        ['href' => '/lokasi-stok', 'title' => 'lokasi stok'],
                    ]" />
                    <livewire:nav-item href="/kalender"
                        title='                <button data-tooltip-target="tooltipKalender" data-tooltip-placement="bottom" type="button"><i
                        class="fa-solid fa-calendar-days"></i></button>

                <div id="tooltipKalender" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Kalender Aset
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                    <livewire:nav-item href="/qrprint"
                        title='                <button data-tooltip-target="tooltipQR" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-print"></i></button>

                <div id="tooltipQR" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Cetak QR-Code
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                    <livewire:nav-item href="/option"
                        title='<button data-tooltip-target="tooltipPengaturan" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-gear"></i></button>

                <div id="tooltipPengaturan" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Pengaturan
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />

                    <livewire:nav-item href="/profil"
                        title='<button data-tooltip-target="tooltipProfil" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-user"></i></button>

                <div id="tooltipProfil" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Profil dan Langganan
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                    <livewire:nav-item href="/logout"
                        title='<button data-tooltip-target="tooltipLogout" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-power-off"></i></button>

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
