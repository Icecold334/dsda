<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Unit Kerja</h1>
        <div>
            <a href="/unit-kerja/utama"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                UNIT KERJA UTAMA</a>
            <a href="/unit-kerja/sub"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                SUB UNIT KERJA</a>
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Kode</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Unit Kerja</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Kode Sub</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Sub-Unit</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Keterangan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unitKerja as $unitKerja)
                <tr
                    class="bg-gray-300 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl ">
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">{{ $unitKerja->kode }}</td>
                    <td class="px-6 py-3">{{ $unitKerja->nama }}</td>
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">{{ $unitKerja->keterangan }}</td>
                    <td class="py-3 px-6 text-center">
                        <a href="/unit-kerja/utama/{{ $unitKerja->id }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                            data-tooltip-target="tooltip-aset-{{ $unitKerja->id }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <div id="tooltip-aset-{{ $unitKerja->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Ubah Unit Kerja Utama
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
                @foreach ($unitKerja->children as $child)
                    <tr
                        class="bg-gray-200 hover:bg-gray-100 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3">{{ $child->kode }}</td>
                        <td class="px-6 py-3">{{ $child->nama }}</td>
                        <td class="px-6 py-3">{{ $child->keterangan }}</td>
                        <td class="py-3 px-6 text-center">
                            <a href="/unit-kerja/sub/{{ $child->id }}"
                                class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                data-tooltip-target="tooltip-aset-{{ $child->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <div id="tooltip-aset-{{ $child->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Ubah Sub Unit Kerja
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</x-body>
