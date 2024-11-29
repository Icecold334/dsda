<div>
    <!-- Instruksi -->
    <div class="text-sm text-gray-600 mb-4">
        Siapkan kertas / stiker berukuran A4 (210 x 297 mm) untuk mencetak file PDF yang akan dihasilkan.
        QRCode ini dapat ditempel di aset Anda. Saat di-scan dengan menggunakan smartphone, QRCode ini akan
        menampilkan data aset, riwayat, atau informasi lain yang bisa Anda tentukan di
        <a href="{{ route('option.index') }}" class="text-primary-600 hover:underline">halaman Pengaturan</a>.
    </div>

    @if (!empty($selectedAssets))
        <!-- Daftar Aset Terpilih -->
        <div class="space-y-2 mb-4 overflow-y-auto max-h-96">
            @foreach ($selectedAssets as $key => $asset)
                <div class="flex items-center justify-between border rounded-lg p-3 bg-gray-50 shadow-sm">
                    <div class="flex items-center space-x-3">
                        {{-- {{ $asset['qrCode'] }} --}}
                        <img src="/storage/qr/{{ $asset['systemcode'] }}.png" alt="QR Code" class="w-12 h-12">
                        <div>
                            <div class="text-sm font-medium text-gray-700">{{ $asset['nama'] }}</div>
                            <div class="text-xs text-gray-500">{{ $asset['kategori'] }}</div>
                        </div>
                    </div>
                    <button wire:click="removeAsset({{ $key }})"
                        class="text-red-600 bg-gray-100 hover:bg-red-600 hover:text-white rounded-full p-2 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <!-- Kondisi jika tidak ada aset yang dipilih -->
        <div class="text-sm text-gray-500 italic mb-4">
            Belum ada aset yang dipilih.
        </div>
    @endif

    <!-- Pilihan Ukuran dan Tombol Cetak -->
    <div class="flex items-center space-x-3">
        <select wire:model.live="selectedSize"
            class="w-56 p-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm">
            <option value="none">Pilih ukuran...</option>
            <option value="small">Ukuran Kecil (23 x 30 mm)</option>
            <option value="medium">Ukuran Sedang (39 x 51 mm)</option>
            <option value="large">Ukuran Besar (61 x 80 mm)</option>
        </select>
        @if (!empty($selectedAssets) && $selectedSize != 'none')
            <button wire:click="generatePDF"
                class="px-4 py-2 text-white bg-primary-600 hover:bg-primary-700 font-medium rounded-lg text-sm transition">
                UNDUH PDF
            </button>
        @endif
    </div>
</div>
