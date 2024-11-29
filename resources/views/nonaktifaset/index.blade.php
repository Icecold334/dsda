<x-body>
    <div class="flex justify-between py-2 mb-3"update>

        <h1 class="text-2xl font-bold text-primary-900 ">ASET NON AKTIF</h1>
        
    </div>



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
                            {{ date('j F Y', $aset->tanggalbeli) }}
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
        </tbody>
    </table>



</x-body>
