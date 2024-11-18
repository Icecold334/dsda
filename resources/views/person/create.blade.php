<x-body>
    <div class="flex justify-between py-2 mb-3">
        <!-- Judul Dinamis -->
        <h1 class="text-2xl font-bold text-primary-900">
            {{ $tipe === 'edit' ? 'Edit Penanggung Jawab' : 'Tambah Penanggung Jawab' }}
        </h1>
        <div>
            <a href="{{ route('person.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="border p-4 rounded-lg shadow-md">

                <!-- Nama -->
                <div class="mb-4">
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">Nama <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" required
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Jabatan -->
                <div class="mb-4">
                    <label for="jabatan" class="block mb-2 text-sm font-medium text-gray-700">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" 
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Alamat -->
                <div class="mb-4">
                    <label for="alamat" class="block mb-2 text-sm font-medium text-gray-700">Alamat</label>
                    <input type="text" id="alamat" name="alamat"
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Telepon -->
                <div class="mb-4">
                    <label for="telepon" class="block mb-2 text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" id="telepon" name="telepon" 
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" 
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                    <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="4"
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">{{ $person->keterangan ?? old('keterangan') }}</textarea>
                </div>

                <!-- Tombol -->
                <div class="flex justify-between items-center mt-4">
                    @if ($tipe === 'edit')
                        <!-- Tombol Hapus -->
                            <button type="submit"
                                class="text-red-500 border border-red-500 hover:bg-red-500 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                    @endif

                    <!-- Tombol Simpan -->
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 transition duration-200">
                        Simpan
                    </button>
                </div>
        </div>
    </div>
</x-body>
