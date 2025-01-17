<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Kategori Aset</h1>
        <div>
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Kategori..."
                class="rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-600" />
            <a href="/kategori/utama"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + KATEGORI UTAMA
            </a>
            <a href="/kategori/sub"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + SUB KATEGORI
            </a>
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Kategori</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Sub-Kategori</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Keterangan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jumlah Aset</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kategoris as $kategori)
                <tr
                    class="bg-gray-300 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">{{ $kategori['nama'] }}</td>
                    <td class="px-6 py-3">-</td>
                    <td class="px-6 py-3">{{ $kategori['keterangan'] }}</td>
                    <td class="text-center px-6 py-3">
                        <a href="{{ route('aset.index', ['kategori_id' => $kategori['id']]) }}"
                            class="text-primary-950 hover:underline"
                            data-tooltip-target="tooltip-jumlah-kategori-{{ $kategori['id'] }}">
                            {{ $kategori['aset_count'] }}
                        </a>
                        <div id="tooltip-jumlah-kategori-{{ $kategori['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat aset untuk "{{ $kategori['nama'] }}"
                            <div class="tooltip-arrow" data-popper-arrow></div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="/kategori/utama/{{ $kategori['id'] }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-aset-kategori-{{ $kategori['id'] }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <div id="tooltip-aset-kategori-{{ $kategori['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat aset untuk "{{ $kategori['nama'] }}"
                            <div class="tooltip-arrow" data-popper-arrow></div>
                    </td>
                </tr>
                @foreach ($kategori['children'] as $child)
                    <tr
                        class="bg-gray-200 hover:bg-gray-100 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3">--</td>
                        <td class="px-6 py-3">{{ $child['nama'] }}</td>
                        <td class="px-6 py-3">{{ $child['keterangan'] }}</td>
                        <td class="text-center px-6 py-3">
                            <a href="{{ route('aset.index', ['kategori_id' => $child['id']]) }}"
                                class="text-primary-950 hover:underline"
                                data-tooltip-target="tooltip-jumlah-child-{{ $child['id'] }}">
                                {{ $child['aset_count'] ?? 0 }}
                            </a>
                            <div id="tooltip-jumlah-child-{{ $child['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Lihat aset untuk "{{ $child['nama'] }}"
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <a href="/kategori/sub/{{ $child['id'] }}"
                                class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-aset-child-{{ $child['id'] }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-aset-child-{{ $child['id'] }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Lihat aset untuk "{{ $child['nama'] }}"
                                <div class="tooltip-arrow" data-popper-arrow></div>
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="text-center py-3">Tidak ada data kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $kategoris->onEachSide(1)->links() }}
</div>
