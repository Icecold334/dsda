<x-body>
    <h1 class="text-2xl font-bold text-primary-900 mb-4">ASET AKTIF</h1>


    <table class="w-full shadow-md border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-left font-semibold rounded-l-lg">NAMA ASET</th>
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
                <tr class="bg-gray-50 border-8 hover:bg-gray-100 transition duration-200 rounded-2xl ">
                    <td class="py-1 px-6">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $aset->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $aset->kategori->nama }}</p>
                        </div>
                    </td>
                    <td class="py-1 px-6">
                        <p class="font-semibold text-gray-800">{{ $aset->systemcode }}</p>
                    </td>
                    <td class="py-1 px-6 font-semibold text-gray-800">
                        {{ $aset->merk->nama }}
                    </td>
                    <td class="py-1 px-6 ">
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
                        <td class="py-1 px-6 text-gray-500">
                            {{ $aset->history ?? '---' }}
                        </td>
                    @endcan
                    <td class="py-1 px-6">
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
