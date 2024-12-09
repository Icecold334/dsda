<x-body>
    <div class="flex justify-between py-2 mb-3"update>

        <h1 class="text-2xl font-bold text-primary-900 ">Aset Non Aktif @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <!-- Toggle Button -->
        <button type="button" id="toggleButton"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm p-2 transition duration-200 relative group">
            <!-- Icon Search -->
            <i class="fa fa-search"></i>
            <!-- Tooltip -->
            <span
                class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 w-max px-2 py-1 text-sm text-white bg-gray-900 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                Pencarian dan Pengurutan
            </span>
        </button>

    </div>

    <div class="mb-4">
        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('nonaktifaset.index') }}" id="searchForm" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Filter Tampilan -->
                <fieldset class="border p-4 rounded-lg">
                    <legend class="text-lg font-semibold text-gray-800">Filter Tampilan</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Filter Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Cari Nama Aset</label>
                            <input type="text" name="nama" id="nama" placeholder="Cari Nama Aset"
                                class="border border-gray-300 rounded-lg p-2 mt-1" value="{{ request('nama') }}" />
                        </div>

                        <!-- Filter Kategori -->
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="kategori_id" id="kategori_id"
                                class="border border-gray-300 rounded-lg p-2 mt-1">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Sebab -->
                        <div>
                            <label for="sebab" class="block text-sm font-medium text-gray-700">Sebab</label>
                            <select name="sebab" id="sebab" class="border border-gray-300 rounded-lg p-2 mt-1">
                                <option value="">Semua Sebab</option>
                                <option value="Dijual" {{ request('sebab') == 'Dijual' ? 'selected' : '' }}>Dijual
                                </option>
                                <option value="Dihibahkan" {{ request('sebab') == 'Dihibahkan' ? 'selected' : '' }}>
                                    Dihibahkan</option>
                                <option value="Dibuang" {{ request('sebab') == 'Dibuang' ? 'selected' : '' }}>Dibuang
                                </option>
                                <option value="Hilang" {{ request('sebab') == 'Hilang' ? 'selected' : '' }}>Hilang
                                </option>
                                <option value="Rusak Total" {{ request('sebab') == 'Rusak Total' ? 'selected' : '' }}>
                                    Rusak Total</option>
                                <option value="Lainnya" {{ request('sebab') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <!-- Filter Urutan -->
                <fieldset class="border p-4 rounded-lg">
                    <legend class="text-lg font-semibold text-gray-800">Filter Urutan</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Dropdown untuk Kolom Urutan -->
                        <div>
                            <label for="order_by" class="block text-sm font-medium text-gray-700">Urutkan
                                Berdasarkan</label>
                            <select name="order_by" id="order_by" class="border border-gray-300 rounded-lg p-2 mt-1">
                                <option value="nama" {{ request('order_by') == 'nama' ? 'selected' : '' }}>Nama Aset
                                </option>
                                <option value="tglnonaktif"
                                    {{ request('order_by') == 'tglnonaktif' ? 'selected' : '' }}>
                                    Tanggal Non-Aktif
                                </option>
                                <option value="alasannonaktif"
                                    {{ request('order_by') == 'alasannonaktif' ? 'selected' : '' }}>
                                    Sebab Non-Aktif
                                </option>
                            </select>
                        </div>

                        <!-- Dropdown untuk Arah Urutan -->
                        <div>
                            <label for="order_direction" class="block text-sm font-medium text-gray-700">Arah
                                Urutan</label>
                            <select name="order_direction" id="order_direction"
                                class="border border-gray-300 rounded-lg p-2 mt-1">
                                <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>
                                    Menaik</option>
                                <option value="desc" {{ request('order_direction') == 'desc' ? 'selected' : '' }}>
                                    Menurun</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <!-- Submit and Reset Buttons -->
                <div class="flex justify-start items-center space-x-4">
                    <!-- GO Button -->
                    <button
                        type="submit"class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors"
                        w>GO!</button>

                    <!-- Reset Button (only shown if filters are applied) -->
                    @if (request()->hasAny(['nama', 'kategori_id', 'sebab', 'order_by', 'order_direction']))
                        <a href="{{ route('nonaktifaset.index') }}"
                            class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors">
                            <i class="fa fa-sync-alt"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Ambil elemen tombol dan form pencarian
            const toggleButton = document.getElementById('toggleButton');
            const searchForm = document.getElementById('searchForm');

            // Set event listener untuk toggle
            toggleButton.addEventListener('click', () => {
                // Toggle kelas hidden pada form pencarian
                searchForm.classList.toggle('hidden');
            });
        </script>
    @endpush



    <table class="w-full  border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold ">NAMA ASET</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">KODE</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">NON AKTIF</th>
                {{-- @can('history_view')
                    <th class="py-3 px-6 bg-primary-950 text-left font-semibold">RIWAYAT TERAKHIR</th>
                @endcan --}}
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @if ($asets->isNotEmpty())
                @foreach ($asets as $aset)
                    <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl ">
                        <td class="py-3 px-6 w-[15rem]">
                            <div class="grid grid-cols-2 gap-3">
                                @if (isset($asetqr[$aset->id]))
                                    @php
                                        $qr = $asetqr[$aset->id]; // Ambil data QR untuk aset ini
                                    @endphp
                                    <div class="w-20 h-20 overflow-hidden relative flex justify-center p-1 border-2 rounded-lg bg-white cursor-pointer"
                                        onclick="openQrModal('{{ asset($qr['qr_image']) }}', {
                                        judul: '{{ $qr['judul'] }}',
                                        baris1: '{{ $qr['baris1'] }}',
                                        baris2: '{{ $qr['baris2'] }}',
                                    })">
                                        <img class="w-full h-full object-cover object-center rounded-sm"
                                            src="{{ asset($qr['qr_image']) }}" alt="QR Code">
                                    </div>
                                @else
                                    <div
                                        class="w-20 h-20 overflow-hidden relative flex justify-center p-1 border-2 rounded-lg bg-white">
                                        <p class="text-gray-500 text-sm">QR Not Found</p>
                                    </div>
                                @endif


                                <!-- Modal untuk Menampilkan QR Code dengan Bingkai dan Data -->
                                <div id="qr-modal"
                                    class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                                    <div
                                        class="bg-white p-4 rounded-lg shadow-lg relative max-w-sm text-center transform scale-90 transition-transform duration-300 ease-out">
                                        <!-- Tombol Tutup -->
                                        <button onclick="closeQrModal()"
                                            class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">
                                            <i class="fa-solid fa-times"></i>
                                        </button>

                                        <!-- Konten Modal -->
                                        <div
                                            class="relative w-[300px] h-[400px] flex flex-col items-center justify-center">
                                            <!-- Bingkai dan QR Code -->
                                            <div class="relative w-full h-full">
                                                <!-- Bingkai -->
                                                <img src="{{ asset('img/qrbase.png') }}" id="qr-frame"
                                                    class="absolute top-0 left-0 w-full h-full object-cover z-10">
                                                <!-- Konten di dalam bingkai (judul, QR Code, dan deskripsi) -->
                                                <!-- Title di atas Bingkai -->
                                                <div class="absolute top-2 w-full flex justify-center">
                                                    <div id="qr-title" class="text-lg font-bold text-white z-20">
                                                        <!-- Judul akan diisi melalui JavaScript -->
                                                    </div>
                                                </div>

                                                <!-- Konten di dalam bingkai (QR Code dan deskripsi) -->
                                                <div
                                                    class="absolute inset-0 flex flex-col items-center justify-center z-20">
                                                    <!-- QR Code -->
                                                    <img id="qr-modal-img" src="" alt="QR Code"
                                                        class="w-[250px] h-[250px] object-cover object-center z-20">

                                                    <!-- Baris Keterangan 1 -->
                                                    <div id="qr-description1"
                                                        class="text-sm text-black mt-2 text-center z-20">
                                                        <!-- Baris 1 akan diisi melalui JavaScript -->
                                                    </div>

                                                    <!-- Baris Keterangan 2 -->
                                                    <div id="qr-description2"
                                                        class="text-sm text-black text-center z-20">
                                                        <!-- Baris 2 akan diisi melalui JavaScript -->
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
                            {{-- <p class="font-normal text-gray-500 text-sm">Tanggal Pembelian :
                            {{ date('j F Y', $aset->tanggalnonaktif) }}
                        </p> --}}
                        </td>
                        <td class="py-3 px-6 ">
                            <p class="font-semibold text-gray-800">{{ date('j F Y', $aset->tglnonaktif) }}</p>
                            <p class="text-sm text-gray-500">{{ $aset->alasannonaktif }}</p>

                        </td>
                        {{-- @can('history_view')
                        <td class="py-3 px-6 ">
                            @if ($aset->histories->last())
                                <p class="font-semibold text-gray-800">
                                    {{ date('j F Y', $aset->histories->last()->tanggal) }}</p>
                                <p class="text-sm text-gray-500">{{ $aset->histories->last()->person->nama }}</p>
                                <p class="text-sm text-gray-500">{{ $aset->histories->last()->lokasi->nama }}</p>
                            @else
                                ---
                            @endif
                        </td>
                    @endcan --}}
                        <td class="py-3 px-6">
                            <a href="{{ route('nonaktifaset.show', ['nonaktifaset' => $aset->id]) }}"
                                class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                data-tooltip-target="tooltip-nonaktif-aset-{{ $aset->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <div id="tooltip-nonaktif-aset-{{ $aset->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Lihat Detail Aset
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <p class="text-gray-600">Tidak ada Aset Non Aktif.</p>
            @endif
        </tbody>
    </table>



</x-body>
