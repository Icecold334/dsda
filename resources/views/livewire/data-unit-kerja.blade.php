<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Unit Kerja</h1>
        <div>
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Unit Kerja..."
                class="rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-600" />
            <a href="/unit-kerja/utama"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + UNIT KERJA UTAMA
            </a>
            <a href="/unit-kerja/sub"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + SUB UNIT KERJA
            </a>
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Unit Kerja</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Sub-Unit</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Keterangan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($units as $unit)
                <tr
                    class="bg-gray-300 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">{{ $unit['nama'] }}</td>
                    <td class="px-6 py-3">-</td>
                    <td class="px-6 py-3">{{ $unit['keterangan'] }}</td>
                    <td class="py-3 px-6 text-center">
                        <a href="/unit-kerja/utama/{{ $unit['id'] }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                </tr>
                @foreach ($unit['children'] as $child)
                    <tr
                        class="bg-gray-200 hover:bg-gray-100 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3">--</td>
                        <td class="px-6 py-3">{{ $child['nama'] }}</td>
                        <td class="px-6 py-3">{{ $child['keterangan'] }}</td>
                        <td class="py-3 px-6 text-center">
                            <a href="/unit-kerja/sub/{{ $child['id'] }}"
                                class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="text-center py-3">Tidak ada data unit kerja.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
