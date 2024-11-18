<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900">
            {{ $tipe === 'edit' ? 'Edit Lokasi' : 'Tambah Lokasi' }}
        </h1>

        <div>
            <a href="{{ route('lokasi.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="border p-4 rounded-lg shadow-md">
            <form action="/tambah-lokasi" method="POST">
                @csrf
                <!-- Nama Merk -->
                <div class="mb-4">
                    <label for="nama_lokasi" class="block mb-2 text-sm font-medium text-gray-700">Nama Lokasi <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="nama_lokasi" name="nama_lokasi" required
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                    <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="4"
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"></textarea>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end">
                    <button type="button" wire:click="#"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>
                </div>
        </div>
        </form>
    </div>
    </div>


</x-body>
