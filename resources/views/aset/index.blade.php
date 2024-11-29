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
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-2">
                <!-- Filter Tampilan -->
                <fieldset class="border p-4 rounded-lg">
                    <legend class="text-lg font-semibold text-gray-800">Filter Tampilan</legend>

                    <div class="grid grid-cols-3 gap-2">
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

                        <!-- Filter Merk -->
                        <div>
                            <label for="merk_id" class="block text-sm font-medium text-gray-700">Merk</label>
                            <select name="merk_id" id="merk_id" class="border border-gray-300 rounded-lg p-2 mt-1">
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
                            <select name="toko_id" id="toko_id" class="border border-gray-300 rounded-lg p-2 mt-1">
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
                                class="border border-gray-300 rounded-lg p-2 mt-1">
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
                            <select name="lokasi_id" id="lokasi_id" class="border border-gray-300 rounded-lg p-2 mt-1">
                                <option value="">Semua Lokasi</option>
                                @foreach ($lokasis as $lokasi)
                                    <option value="{{ $lokasi->id }}"
                                        {{ request('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                                        {{ $lokasi->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-blue-500 text-white rounded-lg px-4 py-2 mt-4 w-20">GO!</button>
                    </div>
                </fieldset>

                <!-- Filter Urutan -->
                <fieldset class="border p-4 rounded-lg">
                    <legend class="text-lg font-semibold text-gray-800">Filter Urutan</legend>

                    <div class="grid grid-cols-3 gap-4">
                        <!-- Dropdown untuk Kolom Urutan -->
                        <div>
                            <label for="order_by" class="block text-sm font-medium text-gray-700">Urutkan
                                Berdasarkan</label>
                            <select name="order_by" id="order_by" class="border border-gray-300 rounded-lg p-2 mt-1">
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
                                class="border border-gray-300 rounded-lg p-2 mt-1">
                                <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>
                                    Menaik</option>
                                <option value="desc" {{ request('order_direction') == 'desc' ? 'selected' : '' }}>
                                    Menurun</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <!-- Submit Button -->
                            <button type="submit"
                                class="bg-blue-500 text-white rounded-lg px-4 py-2 mt-4 w-20">GO!</button>
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
            @foreach ($asets as $aset)
                <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl ">
                    <td class="py-3 px-6 w-[15rem]">
                        <div class="grid grid-cols-2 gap-3">
                            <div
                                class="w-20 h-20 overflow-hidden relative flex justify-center p-1  border-2 rounded-lg bg-white">
                                <img class="w-full h-full object-cover object-center rounded-sm"
                                    src="{{ asset($aset->systemcode ? 'storage/qr/' . $aset->systemcode . '.png' : 'img/default-pic-thumb.png') }}"
                                    alt="">
                            </div>
                            <div
                                class="w-20 h-20 overflow-hidden relative flex justify-center p-1  border-2 rounded-lg bg-white">
                                <img class="w-full h-full object-cover object-center rounded-sm"
                                    src="{{ asset($aset->foto ? 'storage/asetImg/' . $aset->foto : 'img/default-pic-thumb.png') }}"
                                    alt="">
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $aset->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $aset->kategori->nama ?? 'Tidak Berkategori' }}</p>
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
        </tbody>
    </table>



</x-body>
