<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Daftar Kecamatan</h1>
        <div>
            @can('kecamatan.create')
                <a href="{{ route('kecamatan.create') }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    + Tambah Kecamatan
                </a>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <div class="mb-6">
        <form method="GET" action="{{ route('kecamatan.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kecamatan..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-6 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-200">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('kecamatan.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition duration-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Search Results Info -->
    @if(request('search'))
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-blue-800 text-sm">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                ({{ $kecamatans->total() }} hasil ditemukan)
            </p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-3 border-separate border-spacing-y-4">
            <thead>
                <tr class="bg-primary-950 text-white">
                    <th class="py-3 px-6 text-left font-semibold rounded-l-lg">No</th>
                    <th class="py-3 px-6 text-left font-semibold">Kecamatan</th>
                    <th class="py-3 px-6 text-left font-semibold">Unit Kerja</th>
                    <th class="py-3 px-6 text-center font-semibold">Jumlah Kelurahan</th>
                    <th class="py-3 px-6 text-center font-semibold rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kecamatans as $index => $kecamatan)
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="py-3 px-6 bg-gray-50 rounded-l-lg">{{ $index + 1 }}</td>
                        <td class="py-3 px-6 bg-gray-50 font-medium">{{ $kecamatan->kecamatan }}</td>
                        <td class="py-3 px-6 bg-gray-50">
                            @if($kecamatan->unitKerja)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $kecamatan->unitKerja->nama }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">Tidak ada</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 bg-gray-50 text-center">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $kecamatan->kelurahans->count() }}
                            </span>
                        </td>
                        <td class="py-3 px-6 bg-gray-50 text-center rounded-r-lg">
                            <div class="flex justify-center space-x-2">
                                @can('kecamatan.read')
                                    <a href="{{ route('kecamatan.show', $kecamatan->id) }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium text-sm bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded transition duration-200">
                                        Detail
                                    </a>
                                @endcan
                                @can('kecamatan.update')
                                    <a href="{{ route('kecamatan.edit', $kecamatan->id) }}"
                                        class="text-amber-600 hover:text-amber-900 font-medium text-sm bg-amber-50 hover:bg-amber-100 px-3 py-1 rounded transition duration-200">
                                        Edit
                                    </a>
                                @endcan
                                @can('kecamatan.delete')
                                    <form action="{{ route('kecamatan.destroy', $kecamatan->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 font-medium text-sm bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition duration-200"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kecamatan ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center">
                                @if(request('search'))
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada hasil pencarian</p>
                                    <p class="text-sm">Coba gunakan kata kunci yang berbeda atau
                                        <a href="{{ route('kecamatan.index') }}"
                                            class="text-primary-600 hover:text-primary-800 underline">reset pencarian</a>
                                    </p>
                                @else
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data kecamatan</p>
                                    <p class="text-sm">Silakan tambah kecamatan baru</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($kecamatans->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $kecamatans->links() }}
        </div>
    @endif
</x-body>