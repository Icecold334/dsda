<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">Pengaturan Persetujuan
            @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
            @endif
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <x-card title="Permintaan" class="">

            <div class="flex flex-col  gap-3">
                <a href="/option-approval/permintaan/umum"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Permintaan Umum
                            </div>
                            <div
                                class="icon bg-primary-700 text-white w-8 h-8 text-sm rounded-full flex justify-center items-center">
                                <i class="fa-solid fa-boxes-packing"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="/option-approval/permintaan/spare-part"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Permintaan Spare Part
                            </div>
                            <div
                                class="icon bg-primary-700 text-white w-8 h-8 text-sm rounded-full flex justify-center items-center">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="/option-approval/permintaan/material"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Permintaan Material
                            </div>
                            <div
                                class="icon bg-primary-700 text-white w-8 h-8 text-sm rounded-full flex justify-center items-center">
                                <i class="fa-solid fa-hammer"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


        </x-card>
        <x-card title="Peminjaman" class="">

            <div class="flex flex-col  gap-3">
                <a href="/option-approval/peminjaman/kdo"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Peminjaman KDO
                            </div>
                            <div
                                class="icon bg-primary-700 text-white w-8 h-8 text-sm rounded-full flex justify-center items-center">
                                <i class="fa-solid fa-car-side"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="/option-approval/peminjaman/ruangan"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Peminjaman Ruangan
                            </div>
                            <div
                                class="icon bg-primary-700 text-white w-8 h-8 text-sm rounded-full flex justify-center items-center">
                                <i class="fa-solid fa-door-closed"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="/option-approval/peminjaman/peralatan"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Peminjaman Peralatan Kantor
                            </div>
                            <div
                                class="icon bg-primary-700 text-white w-8 h-8 text-sm rounded-full flex justify-center items-center">
                                <i class="fa-solid fa-fax"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


        </x-card>
        <x-card title="Transaksi" class="">

            <div class="flex flex-col gap-3">
                {{-- <a href="/option-approval/transaksi/kontrak"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Rekam Kontrak
                            </div>
                            <div
                                class="icon bg-primary-700 text-white w-8 h-8 text-sm rounded-full flex justify-center items-center">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>
                        </div>
                    </div>
                </a> --}}
                <a href="/option-approval/transaksi/langsung"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Transaksi Belum Berkontrak
                            </div>
                            <div
                                class="icon bg-primary-700 text-white w-8 h-8 text-sm rounded-full flex justify-center items-center">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="/option-approval/transaksi/pengiriman"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Pengiriman Barang
                            </div>
                            <div
                                class="icon bg-primary-700 text-white w-8 h-8 text-sm rounded-full flex justify-center items-center">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


        </x-card>
    </div>
</x-body>
