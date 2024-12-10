<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">ASET AKTIF</h1>
        <div>
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

            <a href="{{ route('aset.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Aset</a>
        </div>
    </div>
    <div class="mb-4">
        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('aset.index') }}" id="searchForm" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                <!-- Filter Tampilan -->
                <fieldset class="border p-4 rounded-lg">
                    <legend class="text-lg font-semibold text-gray-800">Filter Tampilan</legend>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Filter Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Cari Nama Aset</label>
                            <input type="text" name="nama" id="nama" placeholder="Cari Nama Aset"
                                class="border border-gray-300 rounded-lg p-2 mt-1 w-full"
                                value="{{ request('nama') }}" />
                        </div>

                        <!-- Filter Kategori -->
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="kategori_id" id="kategori_id"
                                class="border border-gray-300 rounded-lg p-2 mt-1 w-full">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kategoriItem)
                                    @if ($kategoriItem->parent_id == null)
                                        <!-- Parent Kategori -->
                                        <option value="{{ $kategoriItem->id }}"
                                            {{ request('kategori_id') == $kategoriItem->id ? 'selected' : '' }}>
                                            {{ $kategoriItem->nama }}
                                        </option>

                                        <!-- Child Kategori -->
                                        @foreach ($kategoris->where('parent_id', $kategoriItem->id) as $childkategoriItem)
                                            <option value="{{ $childkategoriItem->id }}"
                                                {{ request('kategori_id') == $childkategoriItem->id ? 'selected' : '' }}>
                                                --- {{ $childkategoriItem->nama }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Merk -->
                        <div>
                            <label for="merk_id" class="block text-sm font-medium text-gray-700">Merk</label>
                            <select name="merk_id" id="merk_id"
                                class="border border-gray-300 rounded-lg p-2 mt-1 w-full">
                                <option value="">Semua Merk</option>
                                @foreach ($merks as $merk)
                                    <option value="{{ $merk->id }}"
                                        {{ request('merk_id') == $merk->id ? 'selected' : '' }}>
                                        {{ $merk->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Toko -->
                        <div>
                            <label for="toko_id" class="block text-sm font-medium text-gray-700">Toko</label>
                            <select name="toko_id" id="toko_id"
                                class="border border-gray-300 rounded-lg p-2 mt-1 w-full">
                                <option value="">Semua Toko</option>
                                @foreach ($tokos as $toko)
                                    <option value="{{ $toko->id }}"
                                        {{ request('toko_id') == $toko->id ? 'selected' : '' }}>
                                        {{ $toko->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Penanggung Jawab -->
                        <div>
                            <label for="penanggung_jawab_id" class="block text-sm font-medium text-gray-700">Penanggung
                                Jawab</label>
                            <select name="penanggung_jawab_id" id="penanggung_jawab_id"
                                class="border border-gray-300 rounded-lg p-2 mt-1 w-full">
                                <option value="">Semua Penanggung</option>
                                @foreach ($penanggungJawabs as $penanggung)
                                    <option value="{{ $penanggung->id }}"
                                        {{ request('penanggung_jawab_id') == $penanggung->id ? 'selected' : '' }}>
                                        {{ $penanggung->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Lokasi -->
                        <div>
                            <label for="lokasi_id" class="block text-sm font-medium text-gray-700">Lokasi</label>
                            <select name="lokasi_id" id="lokasi_id"
                                class="border border-gray-300 rounded-lg p-2 mt-1 w-full">
                                <option value="">Semua Lokasi</option>
                                @foreach ($lokasis as $lokasi)
                                    <option value="{{ $lokasi->id }}"
                                        {{ request('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                                        {{ $lokasi->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit and Reset Buttons in a Flex Container -->
                        <div class="flex items-center justify-start mt-4 space-x-4">
                            <!-- Submit Button -->
                            <button type="submit"
                                class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors">GO!</button>

                            <!-- Show Reset Filter Button if query parameters exist -->
                            @if (request()->hasAny([
                                    'nama',
                                    'kategori_id',
                                    'merk_id',
                                    'toko_id',
                                    'penanggung_jawab_id',
                                    'lokasi_id',
                                    'order_by',
                                    'order_direction',
                                ]))
                                <a href="{{ route('aset.index') }}"
                                    class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors">
                                    <i class="fa fa-sync-alt"></i>
                                </a>
                            @endif
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
                            <select name="order_by" id="order_by"
                                class="border border-gray-300 rounded-lg p-2 mt-1 w-full">
                                <option value="nama" {{ request('order_by') == 'nama' ? 'selected' : '' }}>Nama Aset
                                </option>
                                <option value="tanggalbeli"
                                    {{ request('order_by') == 'tanggalbeli' ? 'selected' : '' }}>Tanggal Pembelian
                                </option>
                                <option value="hargasatuan"
                                    {{ request('order_by') == 'hargasatuan' ? 'selected' : '' }}>Harga Pembelian
                                </option>
                                <option value="riwayat" {{ request('order_by') == 'riwayat' ? 'selected' : '' }}>
                                    Riwayat</option>
                            </select>
                        </div>

                        <!-- Dropdown untuk Arah Urutan -->
                        <div>
                            <label for="order_direction" class="block text-sm font-medium text-gray-700">Arah
                                Urutan</label>
                            <select name="order_direction" id="order_direction"
                                class="border border-gray-300 rounded-lg p-2 mt-1 w-full">
                                <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>
                                    Menaik</option>
                                <option value="desc" {{ request('order_direction') == 'desc' ? 'selected' : '' }}>
                                    Menurun</option>
                            </select>
                        </div>

                        <div class="flex items-end justify-end">
                            <!-- Submit Button -->
                            <button type="submit"
                                class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors">GO!</button>
                        </div>
                    </div>
                </fieldset>
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
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">MERK & TIPE</th>
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold">PENYUSUTAN</th>
                @can('history_view')
                    <th class="py-3 px-6 bg-primary-950 text-left font-semibold">RIWAYAT TERAKHIR</th>
                @endcan
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
                                            class="absolute -top-4 -right-4 text-gray-600 hover:text-gray-900 w-7 h-7 bg-danger-500 rounded-full z-50 text-white">
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
                                                        class="w-72 h-72 object-cover -p-1 object-center z-20 mt-5">

                                                    <!-- Baris Keterangan 1 -->
                                                    <div id="qr-description1"
                                                        class="text-sm text-black font-bold mt-2 text-center z-20">
                                                        <!-- Baris 1 akan diisi melalui JavaScript -->
                                                    </div>

                                                    <!-- Baris Keterangan 2 -->
                                                    <div id="qr-description2"
                                                        class="text-sm text-black font-semibold text-center z-20">
                                                        <!-- Baris 2 akan diisi melalui JavaScript -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- @push('scripts')
                                    <script>
                                        function openQrModal(imageSrc, qrData) {
                                            const modal = document.getElementById('qr-modal');
                                            const modalImg = document.getElementById('qr-modal-img');
                                            const modalTitle = document.getElementById('qr-title');
                                            const modalDescription1 = document.getElementById('qr-description1');
                                            const modalDescription2 = document.getElementById('qr-description2');
                                            const modalContent = modal.querySelector('.transform');

                                            // Set gambar QR Code
                                            modalImg.src = imageSrc;

                                            // Set data dinamis ke modal
                                            modalTitle.innerText = qrData.judul || 'Judul Tidak Ditemukan';
                                            modalDescription1.innerText = qrData.baris1 || 'Baris 1 Tidak Tersedia';
                                            modalDescription2.innerText = qrData.baris2 || 'Baris 2 Tidak Tersedia';

                                            // Tampilkan modal
                                            modal.classList.remove('hidden');
                                            setTimeout(() => {
                                                modalContent.classList.remove('scale-90');
                                                modalContent.classList.add('scale-100');
                                            }, 10);
                                        }

                                        function closeQrModal() {
                                            const modal = document.getElementById('qr-modal');
                                            const modalContent = modal.querySelector('.transform');

                                            // Animasi menutup modal
                                            modalContent.classList.remove('scale-100');
                                            modalContent.classList.add('scale-90');
                                            setTimeout(() => {
                                                modal.classList.add('hidden');
                                            }, 300);
                                        }
                                    </script>
                                @endpush --}}
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
                            <p class="font-normal text-gray-500 text-sm">Tanggal Pembelian :
                                {{ date('j F Y', $aset->tanggalbeli) }}
                            </p>
                        </td>
                        <td class="py-3 px-6 ">
                            <p class="font-semibold text-gray-800">{{ $aset->merk->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $aset->tipe ?? '---' }}</p>

                        </td>
                        <td class="py-3 px-6 ">
                            <div class="flex items-center text-gray-800">
                                {{ $aset->hargatotal }}
                            </div>
                            <div class="flex items-center text-gray-800">
                                {{ $aset->totalpenyusutan }}
                            </div>
                            <div class="flex items-center text-gray-800">
                                {{ $aset->nilaiSekarang }}
                            </div>
                        </td>
                        @can('history_view')
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
                        @endcan
                        <td class="py-3 px-6">
                            <a href="{{ route('aset.show', ['aset' => $aset->id]) }}"
                                class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                data-tooltip-target="tooltip-aset-{{ $aset->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <div id="tooltip-aset-{{ $aset->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Lihat Detail Aset
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <p class="text-gray-600">Tidak ada Aset Aktif.</p>
            @endif
        </tbody>
    </table>
</x-body>
