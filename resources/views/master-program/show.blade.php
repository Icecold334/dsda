<x-body>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">

            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detail Program</h1>
                        <p class="mt-1 text-sm text-gray-600">Informasi lengkap program dan unit kerja terkait</p>
                    </div>
                    <a href="{{ route('master-program.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Program Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Program</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kode Program</label>
                        <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded">
                            {{ $program->kode ?? 'Tidak ada kode' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Program</label>
                        <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded">
                            {{ $program->program }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Kerja Saat Ini</label>
                        <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded">
                            {{ $program->parent ? $program->parent->nama : 'Belum Ditentukan' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dibuat Pada</label>
                        <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded">
                            {{ $program->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Terakhir Diperbarui</label>
                        <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded">
                            {{ $program->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex space-x-3">
                    <a href="{{ route('master-program.edit', $program->id) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Edit Unit Kerja
                    </a>
                </div>
            </div>

            <!-- Related Kegiatan (if any) -->
            @if($program->children && $program->children->count() > 0)
                <div class="bg-white shadow rounded-lg p-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Kegiatan Terkait</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode Kegiatan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Kegiatan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($program->children as $kegiatan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $kegiatan->kode ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $kegiatan->kegiatan }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-body>