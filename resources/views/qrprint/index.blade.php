<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">CETAK QR-CODE</h1>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-3">
        <!-- Kolom Kiri -->
        <div>
            <x-card title="Pilih Aset" class="mb-3">
                <!-- Filter dan Pencarian -->
                <div class="flex items-center space-x-3 mb-4">
                    <select class="w-1/2 p-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <option value="">Semua Kategori</option>
                        <!-- Tambahkan kategori lain di sini -->
                    </select>
                    <input type="text" placeholder="Cari Nama Aset"
                        class="w-full p-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm">
                    <button
                        class="px-4 py-2 text-white bg-primary-600 hover:bg-primary-700 font-medium rounded-lg text-sm transition">
                        GO!
                    </button>
                    <button
                        class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm transition">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                </div>

                <!-- Daftar Aset -->
                <div class="overflow-y-auto max-h-[400px]">
                    @foreach ($assets as $asset)
                        <div class="flex items-center justify-between border-b py-2">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $asset->qrCode }}" alt="QR Code" class="w-12 h-12">
                                <div>
                                    <div class="text-sm font-medium text-gray-700">{{ $asset->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $asset->category }}</div>
                                </div>
                            </div>
                            <button
                                class="px-2 py-1 text-primary-900 bg-gray-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm transition">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>

        <!-- Kolom Kanan -->
        <div>
            <x-card title="Daftar Cetak QR-Code" class="mb-3">
                <!-- Instruksi -->
                <div class="text-sm text-gray-600 mb-4">
                    Siapkan kertas / stiker berukuran A4 (210 x 297 mm) untuk mencetak file PDF yang akan dihasilkan.
                    QRCode ini dapat ditempel di aset Anda. Saat di-scan dengan menggunakan smartphone, QRCode ini akan
                    menampilkan data aset, riwayat, atau informasi lain yang bisa Anda tentukan di <a
                        href="{{ route('option.index') }}" class="text-primary-600 hover:underline">halaman Pengaturan</a>
                </div>
                <!-- Kondisi jika tidak ada aset yang dipilih -->
                <div class="text-sm text-gray-500 italic mb-4">
                    Belum ada aset yang dipilih.
                </div>

                <!-- Pilihan Ukuran -->
                <div class="flex items-center space-x-3 mb-4">
                    <select
                        class="w-full p-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <option value="small">Ukuran Kecil (23 x 30 mm)</option>
                        <option value="medium">Ukuran Sedang (33 x 50 mm)</option>
                        <option value="large">Ukuran Besar (50 x 70 mm)</option>
                    </select>
                    <button
                        class="px-4 py-2 text-white bg-primary-600 hover:bg-primary-700 font-medium rounded-lg text-sm transition">
                        CETAK PDF
                    </button>
                </div>
            </x-card>
        </div>
    </div>
</x-body>
