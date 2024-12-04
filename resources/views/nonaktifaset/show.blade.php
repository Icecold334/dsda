<x-body>
    <div class="flex justify-between">
        <div class="text-5xl font-regular mb-6 text-primary-600 ">{{ $nonaktifaset->nama }}</div>
        <div>
            <div class="flex space-x-2">
                <a href="{{ route('nonaktifaset.index') }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
                <!-- Tombol Hapus -->
                <div>
                    <livewire:delete-asset :model="$nonaktifaset" />
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="Data Aset" class="mb-5">
                <table class="text-gray-600 w-full">
                    <tr>
                        <td class="font-bold" style="width: 30%">Nama Aset</td>
                        <td class="font-bold">{{ $nonaktifaset->nama }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Kode Aset</td>
                        <td class="">{{ $nonaktifaset->kode ?? '---' }}</td>
                    </tr>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Kode Sistem</td>
                        <td class="">{{ $nonaktifaset->systemcode ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Kategori</td>
                        <td class="">
                            @if ($nonaktifaset->kategori)
                                @if ($nonaktifaset->kategori->parent == null)
                                    {{ $nonaktifaset->kategori->nama }}
                                @else
                                    {{ $nonaktifaset->kategori->parent->nama }} - {{ $nonaktifaset->kategori->nama }}
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
                            @click="open = true; imgSrc = '{{ asset($nonaktifaset->foto ? 'storage/asetImg/' . $nonaktifaset->foto : 'img/default-pic.png') }}'">
                            <img src="{{ asset($nonaktifaset->foto ? 'storage/asetImg/' . $nonaktifaset->foto : 'img/default-pic.png') }}"
                                alt="" class="w-full h-full object-cover object-center rounded-sm">
                        </div>

                        <!-- Modal -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                            @click="open = false" @keydown.escape.window="open = false">
                            <!-- Kontainer Gambar Modal -->
                            <div class="relative">
                                <img :src="imgSrc" alt="Preview Image"
                                    class="max-w-full max-h-full object-cover object-center">
                            </div>
                        </div>
                    </div>
                    {{-- <div
                        class="w-80 h-80 overflow-hidden relative flex justify-center  p-1 hover:shadow-lg transition duration-200 hover:opacity-80 border-2 rounded-lg bg-white">
                        <img src="{{ asset($nonaktifaset->foto ? 'storage/asetImg/' . $nonaktifaset->foto : 'img/default-pic.png') }}"
                            alt="" class="w-full h-full object-cover object-center rounded-sm">
                    </div> --}}
                    <div
                    class="w-80 h-80 overflow-hidden relative flex justify-center  p-4 hover:shadow-lg transition duration-200  border-2 rounded-lg bg-white">
                    <a href="{{ route('aset.downloadQrImage', $nonaktifaset->id) }}" class="w-full h-full">
                        <img src="{{ asset($nonaktifaset->systemcode ? 'storage/qr/' . $nonaktifaset->systemcode . '.png' : 'img/default-pic.png') }}"
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
                        <td class="">{{ $nonaktifaset->merk->nama ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Tipe</td>
                        <td class="">{{ $nonaktifaset->tipe ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Produsen</td>
                        <td class="">{{ $nonaktifaset->produsen ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">No. Seri / Kode Produksi</td>
                        <td class="">{{ $nonaktifaset->noseri ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Tahun Produksi</td>
                        <td class="">{{ $nonaktifaset->thproduksi ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Deskripsi</td>
                        <td class="">{{ $nonaktifaset->deskripsi ?? '---' }}</td>
                    </tr>
                </table>
            </x-card>
            <x-card title="Pembelian" class="mb-3">
                <table class="text-gray-600 w-full">
                    <tr>
                        <td class="" style="width: 30%">tgl Pembelian</td>
                        <td class="">{{ date('d M Y', strtotime($nonaktifaset->tanggalbeli)) }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Toko / Distributor</td>
                        <td class="">{{ $nonaktifaset->toko->nama ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">No. Invoice</td>
                        <td class="">{{ $nonaktifaset->invoice ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Jumlah</td>
                        <td class="">{{ $nonaktifaset->jumlah ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Harga Satuan</td>
                        <td class="">{{ $nonaktifaset->hargasatuan ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="" style="width: 30%">Harga Total</td>
                        <td class="">{{ $nonaktifaset->hargatotal ?? '---' }}</td>
                    </tr>
                </table>
            </x-card>
            <x-card title="Keterangan" class="mb-3">
                {{ $nonaktifaset->keterangan ?? '---' }}
            </x-card>
            <x-card title="Lampiran" class="mb-3">
                ---
            </x-card>
        </div>
        <div>
            
            <livewire:nonaktif-aset :nonaktifaset="$nonaktifaset" />


            <livewire:aset-details :aset="$nonaktifaset" />
        </div>
    </div>


</x-body>
