<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Detail Kecamatan</h1>
        <div>
            <a href="{{ route('kecamatan.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="border p-6 rounded-lg shadow-md bg-white">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kecamatan</h3>

            <div class="space-y-4">
                <div class="border-b pb-3">
                    <dt class="text-sm font-medium text-gray-500">Nama Kecamatan</dt>
                    <dd class="text-lg font-semibold text-gray-900">{{ $kecamatan->kecamatan }}</dd>
                </div>

                <div class="border-b pb-3">
                    <dt class="text-sm font-medium text-gray-500">Jumlah Kelurahan</dt>
                    <dd class="text-lg font-semibold text-gray-900">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $kecamatan->kelurahans->count() }} Kelurahan
                        </span>
                    </dd>
                </div>

                <div class="border-b pb-3">
                    <dt class="text-sm font-medium text-gray-500">Unit Kerja</dt>
                    <dd class="text-lg font-semibold text-gray-900">
                        @if($kecamatan->unitKerja)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ $kecamatan->unitKerja->nama }}
                            </span>
                        @else
                            <span class="text-gray-400">Tidak ada unit kerja</span>
                        @endif
                    </dd>
                </div>

                <div class="border-b pb-3">
                    <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                    <dd class="text-base text-gray-900">{{ $kecamatan->created_at->format('d/m/Y H:i') }}</dd>
                </div>

                <div class="pb-3">
                    <dt class="text-sm font-medium text-gray-500">Diperbarui Pada</dt>
                    <dd class="text-base text-gray-900">{{ $kecamatan->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </div>

            <div class="mt-6 flex space-x-3">
                @can('kecamatan.update')
                    <a href="{{ route('kecamatan.edit', $kecamatan->id) }}"
                        class="text-amber-600 hover:text-amber-900 font-medium text-sm bg-amber-50 hover:bg-amber-100 px-4 py-2 rounded-lg transition duration-200">
                        Edit Kecamatan
                    </a>
                @endcan
                @can('kecamatan.delete')
                    <form action="{{ route('kecamatan.destroy', $kecamatan->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-600 hover:text-red-900 font-medium text-sm bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition duration-200"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus kecamatan ini?')">
                            Hapus Kecamatan
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        @if($kecamatan->kelurahans->count() > 0)
            <div class="border p-6 rounded-lg shadow-md bg-white">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Kelurahan</h3>

                <div class="space-y-2">
                    @foreach($kecamatan->kelurahans as $kelurahan)
                        <div
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                            <span class="font-medium text-gray-900">{{ $kelurahan->nama }}</span>
                            <span class="text-sm text-gray-500">
                                {{ $kelurahan->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-body>