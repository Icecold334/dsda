<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Daftar Lokasi Penyimpanan Stok</h1>
        <div>
            {{-- <a href="{{ route('lokasi-stok.create', ['tipe' => 0]) }}" --}}
            <a href="/lokasi-stok/lokasi"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Lokasi</a>
            {{-- <a href="{{ route('lokasi-stok.create', ['tipe' => 1]) }}" --}}
            <a href="/lokasi-stok/bagian"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Bagian</a>
            {{-- <a href="{{ route('lokasi-stok.create', ['tipe' => 2]) }}" --}}
            <a href="/lokasi-stok/posisi"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Posisi</a>
        </div>
    </div>

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
                    <td class="px-6 py-3">{{ $lokasi->nama }}</td>
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3"></td>
                </tr>
                @forelse ($lokasi->bagianStok as $bagian)
                    <tr
                        class="bg-gray-200 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3">{{ $bagian->nama }}</td>
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3"></td>

                    </tr>
                    @forelse ($bagian->posisiStok as $posisi)
                        <tr
                            class="bg-gray-100 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3">{{ $posisi->nama }}</td>
                            <td class="px-6 py-3"></td>
                        </tr>

                    @empty
                        {{-- <tr
                            class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg font-semibold text-center transition duration-200 rounded-2xl">
                            <td class="px-6 py-3" colspan="5">Tidak Ada Posisi</td>
                        </tr> --}}
                    @endforelse
                @empty
                    {{-- <tr
                        class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg font-semibold text-center transition duration-200 rounded-2xl">
                        <td class="px-6 py-3" colspan="5">Tidak Ada Bagian</td>
                    </tr> --}}
                @endforelse
            @endforeach
        </tbody>
    </table>

</x-body>