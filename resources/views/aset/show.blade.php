<x-body>

    <div class="flex justify-between">
        <div class="text-5xl font-regular mb-6 text-primary-600 ">{{ $aset->nama }}</div>
        <div>
            <a href="{{ route('aset.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
            <a data-tooltip-target="tooltip-Edit" href="{{ route('aset.edit', ['aset' => $aset->id]) }}"
                class="text-primary-900 bg-white border-2 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"><i
                    class="fa-solid fa-pen"></i></a>
            <div id="tooltip-Edit" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Edit Aset
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
                            @if ($aset->kategori)
                                @if ($aset->kategori->parent == null)
                                    {{ $aset->kategori->nama }}
                                @else
                                    {{ $aset->kategori->parent->nama }} - {{ $aset->kategori->nama }}
                                @endif
                            @else
                                Tidak Berkategori
                            @endif
                    </tr>
                </table>
            </x-card>
            <x-card title="Foto & QR Code" class="mb-5">
                <div class="flex justify-around">
                    <div
                        class="w-80 h-80 overflow-hidden relative flex justify-center  p-1 hover:shadow-lg transition duration-200 hover:opacity-80 border-2 rounded-lg bg-white">
                        <img src="{{ asset($aset->foto ? 'storage/asetImg/' . $aset->foto : 'img/default-pic.png') }}"
                            alt="" class="w-full h-full object-cover object-center rounded-sm">
                    </div>
                    <div
                        class="w-80 h-80 overflow-hidden relative flex justify-center  p-4 hover:shadow-lg transition duration-200  border-2 rounded-lg bg-white">
                        <img src="{{ asset($aset->systemcode ? 'storage/qr/' . $aset->systemcode . '.png' : 'img/default-pic.png') }}"
                            data-tooltip-target="tooltip-QR" alt=""
                            class="w-full h-full object-cover object-center rounded-sm">
                    </div>
                    <div id="tooltip-QR" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Klik Untuk Mengunduh QR-Code Ini
                        <div class="tooltip-arrow" data-popper-arrow></div>
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
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-primary-100 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800"
                    id="defaultTab" data-tabs-toggle="#defaultTabContent" role="tablist">
                    <li class="me-2">
                        <button id="history-tab" data-tabs-target="#history" type="button" role="tab"
                            aria-controls="history" aria-selected="true"
                            class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Riwayat</button>
                    </li>
                    <li class="me-2">
                        <button id="agenda-tab" data-tabs-target="#agenda" type="button" role="tab"
                            aria-controls="agenda" aria-selected="false"
                            class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Agenda</button>
                    </li>
                    <li class="me-2">
                        <button id="keuangan-tab" data-tabs-target="#keuangan" type="button" role="tab"
                            aria-controls="keuangan" aria-selected="false"
                            class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Keuangan</button>
                    </li>
                    <li class="me-2">
                        <button id="jurnal-tab" data-tabs-target="#jurnal" type="button" role="tab"
                            aria-controls="jurnal" aria-selected="false"
                            class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Jurnal</button>
                    </li>
                </ul>
                {{-- <div id="defaultTabContent">
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="history"
                        role="tabpanel" aria-labelledby="about-tab">
                        <livewire:history :histories="$aset->histories" />
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="agenda"
                        role="tabpanel" aria-labelledby="services-tab">
                        <livewire:agenda :agendas="$aset->agendas" />
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="keuangan"
                        role="tabpanel" aria-labelledby="statistics-tab">
                        <livewire:keuangan :keuangans="$aset->keuangans" />
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="jurnal"
                        role="tabpanel" aria-labelledby="statistics-tab">
                        <livewire:jurnal :jurnals="$aset->jurnals" />
                    </div>
                </div> --}}
                <div id="defaultTabContent">
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="history" role="tabpanel">
                        <livewire:asset-details type="history" :aset="$aset" />
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="agenda" role="tabpanel">
                        <livewire:asset-details type="agenda" :aset="$aset" />
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="keuangan" role="tabpanel">
                        <livewire:asset-details type="keuangan" :aset="$aset" />
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="jurnal" role="tabpanel">
                        <livewire:asset-details type="jurnal" :aset="$aset" />
                    </div>
                </div>
                
            </div>

        </div>
    </div>


</x-body>
