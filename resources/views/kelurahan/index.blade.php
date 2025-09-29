<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Daftar Kelurahan</h1>
        <div>
            @can('kelurahan.create')
                <a href="{{ route('kelurahan.create') }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    + Tambah Kelurahan
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
        <form method="GET" action="{{ route('kelurahan.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama kelurahan atau kecamatan..."
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
                    <a href="{{ route('kelurahan.index') }}"
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
                ({{ $kelurahans->total() }} hasil ditemukan)
            </p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-3 border-separate border-spacing-y-4">
            <thead>
                <tr class="bg-primary-950 text-white">
                    <th class="py-3 px-6 text-left font-semibold rounded-l-lg">No</th>
                    <th class="py-3 px-6 text-left font-semibold">Nama Kelurahan</th>
                    <th class="py-3 px-6 text-left font-semibold">Kecamatan</th>
                    <th class="py-3 px-6 text-center font-semibold rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kelurahans as $index => $kelurahan)
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="py-3 px-6 bg-gray-50 rounded-l-lg">{{ $index + 1 }}</td>
                        <td class="py-3 px-6 bg-gray-50 font-medium">{{ $kelurahan->nama }}</td>
                        <td class="py-3 px-6 bg-gray-50">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $kelurahan->kecamatan->kecamatan }}
                            </span>
                        </td>
                        <td class="py-3 px-6 bg-gray-50 text-center rounded-r-lg">
                            <div class="flex justify-center space-x-2">
                                @can('kelurahan.read')
                                    <a href="{{ route('kelurahan.show', $kelurahan->id) }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium text-sm bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded transition duration-200">
                                        Detail
                                    </a>
                                @endcan
                                @can('kelurahan.update')
                                    <a href="{{ route('kelurahan.edit', $kelurahan->id) }}"
                                        class="text-amber-600 hover:text-amber-900 font-medium text-sm bg-amber-50 hover:bg-amber-100 px-3 py-1 rounded transition duration-200">
                                        Edit
                                    </a>
                                @endcan
                                @can('kelurahan.delete')
                                    <form action="{{ route('kelurahan.destroy', $kelurahan->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 font-medium text-sm bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition duration-200"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kelurahan ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center">
                                @if(request('search'))
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada hasil pencarian</p>
                                    <p class="text-sm">Coba gunakan kata kunci yang berbeda atau
                                        <a href="{{ route('kelurahan.index') }}"
                                            class="text-primary-600 hover:text-primary-800 underline">reset pencarian</a>
                                    </p>
                                @else
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data kelurahan</p>
                                    <p class="text-sm">Silakan tambah kelurahan baru</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($kelurahans->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $kelurahans->links() }}
        </div>
    @endif
</x-body>