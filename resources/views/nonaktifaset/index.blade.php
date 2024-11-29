<x-body>
    <div class="flex justify-between py-2 mb-3"update>

        <h1 class="text-2xl font-bold text-primary-900 ">ASET NON AKTIF</h1>
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
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-2">
                <!-- Filter Tampilan -->
                <fieldset class="border p-4 rounded-lg">
                    <legend class="text-lg font-semibold text-gray-800">Filter Tampilan</legend>

                    <div class="grid grid-cols-5 gap-2">
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


                        {{-- <div class="flex justify-end"> --}}
                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-blue-500 text-white rounded-lg px-4 py-2 mt-4 w-20">GO!</button>
                        <div>
                            <a href="{{ route('nonaktifaset.index') }}"
                                class="bg-gray-500 text-white rounded-lg px-4 py-2 mt-4 w-20 text-center inline-block">
                                Reset Filter
                            </a>
                        </div>
                        <!-- Reset Button -->

                        {{-- </div> --}}
                    </div>
                </fieldset>

                <fieldset class="border p-4 rounded-lg">
                    <legend class="text-lg font-semibold text-gray-800">Filter Urutan</legend>

                    <div class="grid grid-cols-6 gap-4">
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

                        <!-- Tombol Submit -->
                        {{-- <div class="flex justify-end col-span-6"> --}}
                        <button type="submit"
                            class="bg-blue-500 text-white rounded-lg px-4 py-2 mt-4 w-20">GO!</button>
                        {{-- </div> --}}

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
