<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Detail Kelurahan</h1>
        <div>
            <a href="{{ route('kelurahan.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali
            </a>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="border p-6 rounded-lg shadow-md bg-white">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kelurahan</h3>

            <div class="space-y-4">
                <div class="border-b pb-3">
                    <dt class="text-sm font-medium text-gray-500">Nama Kelurahan</dt>
                    <dd class="text-lg font-semibold text-gray-900">{{ $kelurahan->nama }}</dd>
                </div>

                <div class="border-b pb-3">
                    <dt class="text-sm font-medium text-gray-500">Kecamatan</dt>
                    <dd class="text-lg font-semibold text-gray-900">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            {{ $kelurahan->kecamatan->kecamatan }}
                        </span>
                    </dd>
                </div>

                <div class="border-b pb-3">
                    <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                    <dd class="text-base text-gray-900">{{ $kelurahan->created_at->format('d/m/Y H:i') }}</dd>
                </div>

                <div class="pb-3">
                    <dt class="text-sm font-medium text-gray-500">Diperbarui Pada</dt>
                    <dd class="text-base text-gray-900">{{ $kelurahan->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </div>

            <div class="mt-6 flex space-x-3">
                @can('kelurahan.update')
                    <a href="{{ route('kelurahan.edit', $kelurahan->id) }}"
                        class="text-amber-600 hover:text-amber-900 font-medium text-sm bg-amber-50 hover:bg-amber-100 px-4 py-2 rounded-lg transition duration-200">
                        Edit Kelurahan
                    </a>
                @endcan
                @can('kelurahan.delete')
                    <form action="{{ route('kelurahan.destroy', $kelurahan->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-600 hover:text-red-900 font-medium text-sm bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition duration-200"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus kelurahan ini?')">
                            Hapus Kelurahan
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</x-body>