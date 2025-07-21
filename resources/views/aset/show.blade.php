<x-body>

    <div class="flex justify-between">
        <div class="text-5xl font-regular mb-6 text-primary-600 ">{{ $aset->nama }}</div>
        <div>
            <a href="{{ route('aset.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
            @can('aset_edit')
                <a data-tooltip-target="tooltip-Edit" href="{{ route('aset.edit', ['aset' => $aset->id]) }}"
                    class="text-primary-900 bg-white border-2 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"><i
                        class="fa-solid fa-pen"></i></a>
                <div id="tooltip-Edit" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Edit Aset
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            @endcan
            @can('aset_noaktif')
                <a data-tooltip-target="tooltip-Nonaktif"
                    class="text-primary-900 bg-white border-2 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                    data-modal-target="nonaktifModal" data-modal-toggle="nonaktifModal">
                    <i class="fa-solid fa-boxes-packing"></i>
                </a>
                <div id="tooltip-Nonaktif" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Nonaktifkan Aset ini, yaitu saat aset ini dijual atau tidak dimiliki lagi.
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            @endcan
            @can('aset_pdf')
                <a data-tooltip-target="tooltip-PDF" href="{{ route('aset.export-pdf', ['id' => $aset->id]) }}"
                    target="_blank"
                    class="text-primary-900 bg-white border-2 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"><i
                        class="fa-solid fa-file-pdf"></i></a>
                <div id="tooltip-PDF" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Cetak / Download Kartu Aset dalam format PDF
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            @endcan
        </div>
    </div>

    <div id="nonaktifModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal Header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Non-Aktifkan Aset</h5>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="nonaktifModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal Body -->
                <form action="{{ route('show.nonaktif', $aset->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <!-- Tanggal Non-Aktif -->
                        <div>
                            <label for="tanggalNonaktif"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Non-Aktif
                                *</label>
                            <input type="date" name="tanggal_nonaktif" id="tanggalNonaktif"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                required>
                        </div>
                        <!-- Sebab Non-Aktif -->
                        <div>
                            <label for="sebabNonaktif"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sebab Non-Aktif
                                *</label>
                            <select name="sebab_nonaktif" id="sebabNonaktif"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                required>
                                <option value="">Pilih Sebab</option>
                                <option value="Dijual">Dijual</option>
                                <option value="Dihibahkan">Dihibahkan</option>
                                <option value="Dibuang">Dibuang</option>
                                <option value="Hilang">Hilang</option>
                                <option value="Rusak Total">Rusak Total</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                            <textarea name="keterangan" id="keterangan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                rows="3"></textarea>
                        </div>
                        <div class="text-gray-500 text-sm">
                            Catatan: Aset yang sudah dinonaktifkan tidak dapat diedit dan ditambah riwayatnya.
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div
                        class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Non
                            Aktifkan</button>
                        <button type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                            data-modal-hide="nonaktifModal">Batal</button>
                    </div>
                </form>
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
                    <div x-data="{ open: false, imgSrc: '' }">
                        <!-- Gambar Besar -->
                        <div class="w-80 h-80 overflow-hidden relative flex justify-center p-1 hover:shadow-lg transition duration-200 hover:opacity-80 border-2 rounded-lg bg-white cursor-pointer"
                            @click="open = true; imgSrc = '{{ asset($aset->foto ? 'storage/asetImg/' . $aset->foto : 'img/default-pic.png') }}'">
                            <img src="{{ asset($aset->foto ? 'storage/asetImg/' . $aset->foto : 'img/default-pic.png') }}"
                                alt="" class="w-full h-full object-cover object-center rounded-sm">
                        </div>

                        <!-- Modal -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                            @click="open = false" @keydown.escape.window="open = false">
                            <!-- Kontainer Gambar Modal -->
                            <div class="relative">
                                <img :src="imgSrc" alt="Preview Image"
                                    class="max-w-full max-h-full object-cover object-center">
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-80 h-80 overflow-hidden relative flex justify-center  p-4 hover:shadow-lg transition duration-200  border-2 rounded-lg bg-white">
                        <a href="{{ route('aset.downloadQrImage', $aset->id) }}" class="w-full h-full">
                            <img src="{{ asset($aset->systemcode ? 'storage/qr/' . $aset->systemcode . '.png' : 'img/default-pic.png') }}"
                                data-tooltip-target="tooltip-QR" alt="QR Code"
                                class="w-full h-full object-cover object-center rounded-sm">
                        </a>
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
                        <td class="">{{ $aset->tanggalbeli ? date('j F Y', $aset->tanggalbeli) : '---' }}</td>
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
                    <tr>
                        <td class="" style="width: 30%">Lama Garansi</td>
                        <td class="">{{ $aset->lama_garansi ?? '---' }} Tahun</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Kartu Garansi</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            @if ($aset->garansis->isNotEmpty())
                                @foreach ($aset->garansis as $attachment)
                                    <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
                                        <span class="flex items-center space-x-3">
                                            @php
                                                $fileType = pathinfo($attachment->file, PATHINFO_EXTENSION);
                                            @endphp
                                            <span class="text-primary-600">
                                                @if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif']))
                                                    <i class="fa-solid fa-image text-green-500"></i>
                                                @elseif($fileType == 'pdf')
                                                    <i class="fa-solid fa-file-pdf text-red-500"></i>
                                                @elseif(in_array($fileType, ['doc', 'docx']))
                                                    <i class="fa-solid fa-file-word text-blue-500"></i>
                                                @else
                                                    <i class="fa-solid fa-file text-gray-500"></i>
                                                @endif
                                            </span>

                                            <!-- File name with underline on hover and a link to the saved file -->
                                            <span>
                                                <a href="{{ asset('storage/LampiranAset/' . $attachment->file) }}"
                                                    target="_blank" class="text-gray-800 hover:underline">
                                                    {{ basename($attachment->file) }}
                                                </a>
                                            </span>
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500">Tidak ada Kartu Garansi yang diunggah</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </x-card>
            <x-card title="Keterangan" class="mb-3">
                <p>
                    {{ $aset->keterangan ?? '---' }}
                </p>
                <p>
                    {{ $aset->peminjaman === 1 ? 'Aset Bisa Dipinjam' : ($aset->peminjaman === 0 ? 'Aset Tidak Bisa Dipinjam' : '---') }}
                </p>
            </x-card>
            <x-card title="Lampiran" class="mb-3">
                @if ($aset->lampirans->isNotEmpty())
                    @foreach ($aset->lampirans as $attachment)
                        <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
                            <span class="flex items-center space-x-3">
                                @php
                                    $fileType = pathinfo($attachment->file, PATHINFO_EXTENSION);
                                @endphp
                                <span class="text-primary-600">
                                    @if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif']))
                                        <i class="fa-solid fa-image text-green-500"></i>
                                    @elseif($fileType == 'pdf')
                                        <i class="fa-solid fa-file-pdf text-red-500"></i>
                                    @elseif(in_array($fileType, ['doc', 'docx']))
                                        <i class="fa-solid fa-file-word text-blue-500"></i>
                                    @else
                                        <i class="fa-solid fa-file text-gray-500"></i>
                                    @endif
                                </span>

                                <!-- File name with underline on hover and a link to the saved file -->
                                <span>
                                    <a href="{{ asset('storage/LampiranAset/' . $attachment->file) }}"
                                        target="_blank" class="text-gray-800 hover:underline">
                                        {{ basename($attachment->file) }}
                                    </a>
                                </span>
                            </span>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">Tidak ada lampiran</p>
                @endif

            </x-card>
        </div>
        <div>
            <div
                class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-primary-100 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800"
                    id="defaultTab" data-tabs-toggle="#defaultTabContent" role="tablist">
                    @can('history_view')
                        <li class="me-2">
                            <button id="history-tab" data-tabs-target="#history" type="button" role="tab"
                                aria-controls="history" aria-selected="true"
                                class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Riwayat</button>
                        </li>
                    @endcan
                    <li class="me-2">
                        <button id="agenda-tab" data-tabs-target="#agenda" type="button" role="tab"
                            aria-controls="agenda" aria-selected="false"
                            class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Agenda</button>
                    </li>
                    @can('trans_view')
                        <li class="me-2">
                            <button id="keuangan-tab" data-tabs-target="#keuangan" type="button" role="tab"
                                aria-controls="keuangan"
                                aria-selected="{{ request('tab') === 'keuangan' ? 'true' : 'false' }}"
                                class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Keuangan</button>
                        </li>
                    @endcan
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

                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="history"
                        role="tabpanel">
                        <livewire:asset-details type="history" :aset="$aset" />
                    </div>

                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="agenda"
                        role="tabpanel">
                        <livewire:asset-details type="agenda" :aset="$aset" />
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="keuangan"
                        role="tabpanel">
                        <livewire:asset-details type="keuangan" :aset="$aset" />
                    </div>
                    <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="jurnal"
                        role="tabpanel">
                        <livewire:asset-details type="jurnal" :aset="$aset" />
                    </div>
                </div>

            </div>

        </div>
    </div>


</x-body>
