    <header class="">
        <nav
            class="bg-[#d9faff] border-gray-200 shadow-2xl dark:bg-gray-800 flex flex-wrap justify-between items-center overflow-y-hidden">
            <div class="flex justify-start  items-center"
                style="background:#003569 url({{ asset('img/header-bg.jpg') }}) no-repeat right center;float:left;width:420px;height:64px">
                <a href="https://flowbite.com" class="flex mx-8">
                    <span class="self-center  text-white font-semibold whitespace-nowrap dark:text-white"><img
                            src="{{ asset('img/inventa-logo.png') }}" class=" w-[250px] h-auto" alt=""></span>
                </a>
            </div>
            <div class="hidden xl:flex items-center mx-8 lg:order-2 text-[#003569]">
                <ul class="grid grid-flow-col gap-0 -my-4 ">
                    <livewire:nav-item href="/dashboard" title="home" />
                    <livewire:nav-item title="aset" :child="[
                        ['href' => '/aset', 'title' => 'aset aktif'],
                        ['href' => '/aset-non-aktif', 'title' => 'aset non aktif'],
                    ]" />
                    <livewire:nav-item title="inventaris" :child="[
                        ['href' => '/pengiriman-stok', 'title' => 'inventaris'],
                        ['href' => '/stok', 'title' => 'stok'],
                    ]" />
                    <livewire:nav-item title="Rekam Kontrak" :child="[
                        ['href' => '/kontrak-vendor-stok', 'title' => 'Daftar Kontrak'],
                        ['href' => '/transaksi-darurat-stok', 'title' => 'Barang Belum Berkontrak'],
                    ]" />

                    <livewire:nav-item title="Form" :child="[
                        ['href' => '/aset-aktif', 'title' => 'Form peminjaman Umum'],
                        ['href' => '/aset-non-aktif', 'title' => 'Form permintaan spare part'],
                        ['href' => '/aset-non-aktif', 'title' => 'Form permintaan material'],
                        ['href' => '/aset-non-aktif', 'title' => 'Form barang datang'],
                    ]" />
                    <livewire:nav-item title="data" :child="[
                        ['href' => '/aset-aktif', 'title' => 'kategori'],
                        ['href' => '/aset-non-aktif', 'title' => 'Merk'],
                        ['href' => '/aset-non-aktif', 'title' => 'Toko / distributor'],
                        ['href' => '/aset-non-aktif', 'title' => 'Penanggung jawab'],
                        ['href' => '/aset-non-aktif', 'title' => 'lokasi'],
                    ]" />
                    <livewire:nav-item href="/home"
                        title='                <button data-tooltip-target="tooltipKalender" data-tooltip-placement="bottom" type="button"><i
                        class="fa-solid fa-calendar-days"></i></button>

                <div id="tooltipKalender" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Kalender Aset
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                    <livewire:nav-item href="/home"
                        title='                <button data-tooltip-target="tooltipQR" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-print"></i></button>

                <div id="tooltipQR" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Cetak QR-Code
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />
                    <livewire:nav-item href="/home"
                        title='<button data-tooltip-target="tooltipPengaturan" data-tooltip-placement="bottom" type="button"><i class="fa-solid fa-gear"></i></button>

                <div id="tooltipPengaturan" role="tooltip"
                    class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Pengaturan
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>' />

                    <livewire:nav-item href="/home"
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
    </header>
