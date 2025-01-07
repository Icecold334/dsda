<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">Daftar Kategori Stok</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Kategori..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-600" />
            </div>

            <!-- Tombol Tambah KategoriStok -->
            <a href="{{ route('kategori-stok.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Tambah Kategori Stok
            </a>
        </div>
    </div>
    <!-- Table -->
    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white uppercase">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Nama Kategori Stok</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Barang (nama/satuan besar/satuan kecil)
                </th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kategoris as $kategori)
                <tr
                    class="bg-gray-100 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">
                        <div>{{ $kategori->nama }}</div>
                    </td>
                    <td class="px-6 py-3">
                        <table class="w-full">
                            @forelse ($kategori->BarangStok as $barang)
                                <tr class="border-b-4 border-gray-400">
                                    <td class="w-1/3 text-center">{{ $barang->nama ?? '-' }}</td>
                                    <td class="border-x-2 border-primary-600 w-1/3 text-center">
                                        {{ $barang->satuanBesar->nama ?? '-' }}</td>
                                    <td class="w-1/3 text-center">
                                        {{ $barang->satuanKecil->nama ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">Tidak ada data Barang.</td>
                                </tr>
                            @endforelse
                        </table>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="/kategori-stok/edit/{{ $kategori['id'] }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-kategori-{{ $kategori->id }}">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        <div id="tooltip-kategori-{{ $kategori->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Ubah Kategori Stok
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-3">Tidak ada data Kategori Stok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
