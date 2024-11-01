<x-body>

    <div class="flex justify-between">
        <div class="text-5xl font-regular mb-6 text-primary-600 ">{{ $aset->nama }}</div>
        <div>
            <a href="{{ route('aset.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>


    <div class="grid grid-cols-2 gap-6">
        <div>
            <x-card title="Data Aset" class="mb-5">
                <table class="text-gray-600 w-full">
                    <tr>
                        <td class="font-bold" style="width: 30%">Nama Aset</td>
                        <td class="font-bold">{{ $aset->nama }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Kode Aset</td>
                        <td class="">{{ $aset->kode ?? '---' }}</td>
                    </tr>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Kode Sistem</td>
                        <td class="">{{ $aset->systemcode ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Kategori</td>
                        <td class="">
                            @if ($aset->kategori->parent == null)
                                {{ $aset->kategori->nama }}
                            @else
                                {{ $aset->kategori->parent->nama }} - {{ $aset->kategori->nama }}
                            @endif
                    </tr>
                </table>
            </x-card>
            <x-card title="Foto & QR Code" class="mb-5">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <img src="{{ asset('img/default-pic.png') }}" alt="">
                    </div>
                    <div>
                        <img src="{{ asset('img/default-pic.png') }}" data-tooltip-target="tooltip-QR" alt="">
                        <div id="tooltip-QR" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Klik Untuk Mengunduh QR-Code Ini
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                </div>
            </x-card>
            <x-card title="Detail Aset" class="mb-3">
                <table class="text-gray-600 w-full">
                    <tr>
                        <td class="" style="width: 30%">Merk</td>
                        <td class="">{{ $aset->merk->nama ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Tipe</td>
                        <td class="">{{ $aset->tipe ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Produsen</td>
                        <td class="">{{ $aset->produsen ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">No. Seri / Kode Produksi</td>
                        <td class="">{{ $aset->noseri ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Tahun Produksi</td>
                        <td class="">{{ $aset->thproduksi ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Deskripsi</td>
                        <td class="">{{ $aset->deskripsi ?? '---' }}</td>
                    </tr>
                </table>
            </x-card>
            <x-card title="Pembelian" class="mb-3">
                <table class="text-gray-600 w-full">
                    <tr>
                        <td class="" style="width: 30%">Tanggal Pembelian</td>
                        <td class="">{{ date('d M Y', strtotime($aset->tanggalbeli)) }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Toko / Distributor</td>
                        <td class="">{{ $aset->toko->nama ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">No. Invoice</td>
                        <td class="">{{ $aset->invoice ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Jumlah</td>
                        <td class="">{{ $aset->jumlah ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Harga Satuan</td>
                        <td class="">{{ $aset->hargasatuan ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Harga Total</td>
                        <td class="">{{ $aset->hargatotal ?? '---' }}</td>
                    </tr>
                </table>
            </x-card>
            <x-card title="Keterangan" class="mb-3">
                {{ $aset->keterangan ?? '---' }}
            </x-card>
            <x-card title="Lampiran" class="mb-3">
                ---
            </x-card>
        </div>
        <div>


            <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800"
                    id="defaultTab" data-tabs-toggle="#defaultTabContent" role="tablist">
                    <li class="me-2">
                        <button id="about-tab" data-tabs-target="#about" type="button" role="tab"
                            aria-controls="about" aria-selected="true"
                            class="inline-block p-4 ho text-blue-600 rounded-ss-lg hover:bg-primary-300 transition duration-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-blue-500">Riwayat</button>
                    </li>
                    <li class="me-2">
                        <button id="services-tab" data-tabs-target="#services" type="button" role="tab"
                            aria-controls="services" aria-selected="false"
                            class="inline-block p-4 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300">Services</button>
                    </li>
                    <li class="me-2">
                        <button id="statistics-tab" data-tabs-target="#statistics" type="button" role="tab"
                            aria-controls="statistics" aria-selected="false"
                            class="inline-block p-4 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300">Facts</button>
                    </li>
                </ul>
                <div id="defaultTabContent">
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="about"
                        role="tabpanel" aria-labelledby="about-tab">
                        <h2 class="mb-3 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Powering
                            innovation & trust at 200,000+ companies worldwide</h2>
                        <p class="mb-3 text-gray-500 dark:text-gray-400">Empower Developers, IT Ops, and business teams
                            to collaborate at high velocity. Respond to changes and deliver great customer and employee
                            service experiences fast.</p>
                        <a href="#"
                            class="inline-flex items-center font-medium text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-700">
                            Learn more
                            <svg class=" w-2.5 h-2.5 ms-2 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                        </a>
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="services"
                        role="tabpanel" aria-labelledby="services-tab">
                        <h2 class="mb-5 text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">We invest
                            in the world’s potential</h2>
                        <!-- List -->
                        <ul role="list" class="space-y-4 text-gray-500 dark:text-gray-400">
                            <li class="flex space-x-2 rtl:space-x-reverse items-center">
                                <svg class="flex-shrink-0 w-3.5 h-3.5 text-blue-600 dark:text-blue-500"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                </svg>
                                <span class="leading-tight">Dynamic reports and dashboards</span>
                            </li>
                            <li class="flex space-x-2 rtl:space-x-reverse items-center">
                                <svg class="flex-shrink-0 w-3.5 h-3.5 text-blue-600 dark:text-blue-500"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                </svg>
                                <span class="leading-tight">Templates for everyone</span>
                            </li>
                            <li class="flex space-x-2 rtl:space-x-reverse items-center">
                                <svg class="flex-shrink-0 w-3.5 h-3.5 text-blue-600 dark:text-blue-500"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                </svg>
                                <span class="leading-tight">Development workflow</span>
                            </li>
                            <li class="flex space-x-2 rtl:space-x-reverse items-center">
                                <svg class="flex-shrink-0 w-3.5 h-3.5 text-blue-600 dark:text-blue-500"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                </svg>
                                <span class="leading-tight">Limitless business automation</span>
                            </li>
                        </ul>
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="statistics"
                        role="tabpanel" aria-labelledby="statistics-tab">
                        <dl
                            class="grid max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-6 dark:text-white sm:p-8">
                            <div class="flex flex-col">
                                <dt class="mb-2 text-3xl font-extrabold">73M+</dt>
                                <dd class="text-gray-500 dark:text-gray-400">Developers</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="mb-2 text-3xl font-extrabold">100M+</dt>
                                <dd class="text-gray-500 dark:text-gray-400">Public repositories</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="mb-2 text-3xl font-extrabold">1000s</dt>
                                <dd class="text-gray-500 dark:text-gray-400">Open source projects</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

        </div>
    </div>


</x-body>
