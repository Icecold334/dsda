<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">Daftar Barang</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Barang..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-600" />
            </div>

            {{-- <div>
            {{-- <a href="{{ route('lokasi-stok.create', ['tipe' => 0]) }}" --}}
            {{-- <a href="/barang/barang"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Barang</a> --}}
            {{-- <a href="{{ route('barang.create', ['tipe' => 1]) }}" --}}
            {{-- <a href="/barang/merk"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Sub-Barang</a>
        </div>  --}}
        </div>
    </div>
    <!-- Table -->
    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white uppercase">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Nama barang</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Spesifikasi (merk/tipe/ukuran)</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $barang)
                <tr
                    class="bg-gray-100 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">
                        <div>{{ $barang->nama }}</div>
                        <div class="font-normal text-sm">{{ $barang->jenisStok->nama }}</div>
                    </td>
                    <td class="px-6 py-3">
                        <table class="w-full">
                            @foreach ($barang->merkStok as $merk)
                                <tr class="border-b-4 border-gray-400">
                                    <td class="w-1/3 text-center">{{ $merk->nama ?? '-' }}</td>
                                    <td class="border-x-2 border-primary-600 w-1/3 text-center">
                                        {{ $merk->tipe ?? '-' }}</td>
                                    <td class="w-1/3 text-center">
                                        {{ $merk->ukuran ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('barang.show', ['barang' => $barang->id]) }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-barang-{{ $barang->id }}">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <div id="tooltip-barang-{{ $barang->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat Barang
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-3">Tidak ada data barang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $barangs->onEachSide(1)->links() }}
</div>
