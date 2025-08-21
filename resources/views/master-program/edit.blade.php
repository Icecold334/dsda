<x-body>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">

            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Edit Unit Kerja Program</h1>
                        <p class="mt-1 text-sm text-gray-600">Ubah unit kerja untuk program: {{ $program->program }}</p>
                    </div>
                    <a href="{{ route('master-program.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('master-program.update', $program->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Program Info (Read Only) -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Program</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kode Program</label>
                                <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded">
                                    {{ $program->kode ?? 'Tidak ada kode' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unit Kerja Saat Ini</label>
                                <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded">
                                    {{ $program->parent ? $program->parent->nama : 'Belum Ditentukan' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Program</label>
                            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded">
                                {{ $program->program }}
                            </p>
                        </div>
                    </div>

                    <!-- Unit Kerja Selection -->
                    <div class="mb-6">
                        <label for="bidang_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Unit Kerja Baru <span class="text-red-500">*</span>
                        </label>
                        <select name="bidang_id" id="bidang_id" required
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('bidang_id') ? 'border-red-500' : 'border-gray-300' }}">
                            <option value="">Pilih Unit Kerja</option>
                            @foreach($unitKerjas as $unit)
                                <option value="{{ $unit->id }}" {{ old('bidang_id', $program->bidang_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('bidang_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('master-program.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Warning Box -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Perubahan unit kerja akan mempengaruhi semua kegiatan yang terkait dengan program ini.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-body>