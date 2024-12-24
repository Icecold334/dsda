<div>
    <div class="flex justify-between py-2 mb-3">
        <div class="flex flex-col justify-between">
            <h1 class="text-2xl font-bold text-primary-900">Daftar Lokasi Penyimpanan Stok</h1>
            <span class="text-2xl font-bold text-primary-900">
                {{ optional(auth()->user()->unitKerja)->parent
                    ? optional(auth()->user()->unitKerja->parent)->nama
                    : optional(auth()->user()->unitKerja)->nama }}
            </span>
        </div>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Search Input -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Lokasi Stok..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-600 me-2 mb-2" />
            </div>
            <a href="/lokasi-stok/lokasi"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Lokasi</a>
            <a href="/lokasi-stok/bagian"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Bagian</a>
            <a href="/lokasi-stok/posisi"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Posisi</a>
        </div>
    </div>

    <!-- Lokasi Table -->
    <div x-data="{ openSections: {} }">
        <table class="w-full border-3 border-separate border-spacing-y-4">
            <thead>
                <tr class="text-white">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">LOKASI</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BAGIAN</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">POSISI</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lokasiStok as $lokasi)
                    <tr
                        class="bg-gray-300 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3 text-center">{{ $lokasi['nama'] }}</td>
                        <td class="px-6 py-3 text-center">
                            <button
                                @click="openSections['lokasi-{{ $lokasi['id'] }}'] = !openSections['lokasi-{{ $lokasi['id'] }}']"
                                class="text-primary-900 font-semibold">
                                <i x-show="!openSections['lokasi-{{ $lokasi['id'] }}']"
                                    class="fa-solid fa-chevron-down"></i>
                                <i x-show="openSections['lokasi-{{ $lokasi['id'] }}']"
                                    class="fa-solid fa-chevron-up"></i>
                            </button>
                        </td>
                        <td class="px-6 py-3">
                        </td>
                        <td class="py-3 px-6 text-center">
                            <a href="/lokasi-stok/lokasi/{{ $lokasi['id'] }}"
                                class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                data-tooltip-target="tooltip-lokasi-{{ $lokasi['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-lokasi-{{ $lokasi['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Ubah Lokasi
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                    @forelse ($lokasi->bagianStok as $bagian)
                        <tr x-show="openSections['lokasi-{{ $lokasi['id'] }}']"
                            class="bg-gray-200 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3 text-center">
                                {{ $bagian['nama'] }}
                            </td>
                            <td class="px-6 py-3 text-center">
                                <button
                                    @click="openSections['bagian-{{ $bagian['id'] }}'] = !openSections['bagian-{{ $bagian['id'] }}']"
                                    class="text-primary-900 font-semibold">
                                    <i x-show="!openSections['bagian-{{ $bagian['id'] }}']"
                                        class="fa-solid fa-chevron-down"></i>
                                    <i x-show="openSections['bagian-{{ $bagian['id'] }}']"
                                        class="fa-solid fa-chevron-up"></i>
                                </button>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="/lokasi-stok/bagian/{{ $bagian['id'] }}"
                                    class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                    data-tooltip-target="tooltip-bagian-{{ $bagian['id'] }}">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <div id="tooltip-bagian-{{ $bagian['id'] }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Ubah Bagian
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                        <!-- Posisi Rows -->
                        @forelse ($bagian->posisiStok as $posisi)
                            <tr x-show="openSections['bagian-{{ $bagian['id'] }}']"
                                class="bg-gray-100 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                                <td class="px-6 py-3"></td>
                                <td class="px-6 py-3"></td>
                                <td class="px-6 py-3"></td>
                                <td class="px-6 py-3 text-center">{{ $posisi['nama'] }}</td>
                                <td class="py-3 px-6 text-center">
                                    <a href="/lokasi-stok/posisi/{{ $posisi['id'] }}"
                                        class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                        data-tooltip-target="tooltip-posisi-{{ $posisi['id'] }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <div id="tooltip-posisi-{{ $posisi['id'] }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Ubah Posisi
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-gray-50 font-semibold text-center transition duration-200 rounded-2xl">
                                <td class="px-6 py-3" colspan="5">Tidak Ada Posisi</td>
                            </tr>
                        @endforelse
                    @empty
                        <tr
                            class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg font-semibold text-center transition duration-200 rounded-2xl">
                            <td class="px-6 py-3" colspan="5">Tidak Ada Bagian</td>
                        </tr>
                    @endforelse
                @endforeach

            </tbody>
        </table>
    </div>

    <!-- Navigasi Paginasi -->
    <div class="mt-4">
        {{ $lokasiStok->links() }}
    </div>
</div>
