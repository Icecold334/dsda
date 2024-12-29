<x-body>
    {{-- <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Toko/Distributor Aset</h1>
        <div>

            <a href="{{ route('toko.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Toko</a>
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Toko</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Alamat</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Telepon</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Email</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Petugas</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Keterangan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jumlah Aset</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tokos as $toko)
                <tr
                    class="bg-gray-300 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl ">
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">{{ $toko->nama }}</td>
                    <td class="px-6 py-3">{{ $toko->alamat }}</td>
                    <td class="px-6 py-3">{{ $toko->telepon }}</td>
                    <td class="px-6 py-3">{{ $toko->email }}</td>
                    <td class="px-6 py-3">{{ $toko->petugas }}</td>
                    <td class="px-6 py-3">{{ $toko->keterangan }}</td>
                    {{-- <td class="text-center px-6 py-3">{{ $toko->aset->count() }}</td> --}}
    {{-- <td class="text-center px-6 py-3">
                        <!-- Link to aset.index with tooltip -->
                        <a href="{{ route('aset.index', ['toko_id' => $toko->id]) }}"
                            class="text-primary-950 hover:underline"
                            data-tooltip-target="tooltip-jumlah-toko-{{ $toko->id }}">
                            {{ $toko->aset->count() }}
                        </a>
                        <div id="tooltip-jumlah-toko-{{ $toko->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat aset untuk "{{ $toko->nama }}"
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="/toko/edit/{{ $toko->id }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                            data-tooltip-target="tooltip-aset-{{ $toko->id }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <div id="tooltip-aset-{{ $toko->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Ubah Toko/Distributor
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
    <div>
        <livewire:data-toko />
    </div>

</x-body>
