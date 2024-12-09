
<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Lokasi Aset</h1>
        <div>
         
            <a href="{{ route('lokasi.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Lokasi</a>
        </div>
    </div>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Okay'
            });
        });
    </script>
@endif

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Lokasi</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Keterangan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jumlah Aset</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lokasis as $lokasi)
                <tr
                    class="bg-gray-300 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl ">
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">{{ $lokasi->nama }}</td>
                    <td class="px-6 py-3">{{ $lokasi->keterangan }}</td>
                    {{-- <td class="text-center px-6 py-3">{{ $lokasi->aset->count() }}</td> --}}
                    <td class="text-center px-6 py-3">
                        <!-- Link to aset.index with tooltip -->
                        <a href="{{ route('aset.index', ['lokasi_id' => $lokasi->id]) }}"
                           class="text-primary-950 hover:underline"
                           data-tooltip-target="tooltip-jumlah-lokasi-{{ $lokasi->id }}">
                            {{ $lokasi->aset->count() }}
                        </a>
                        <div id="tooltip-jumlah-lokasi-{{ $lokasi->id }}" role="tooltip"
                             class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat aset untuk "{{ $lokasi->nama }}"
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="/lokasi/edit/{{ $lokasi->id }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                            data-tooltip-target="tooltip-aset-{{ $lokasi->id }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <div id="tooltip-aset-{{ $lokasi->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Ubah Lokasi
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-body>
