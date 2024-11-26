<div>
    <!-- Template Kode Aset -->
    <x-card title="Kode Aset" class="mb-3">
        <p class="text-sm text-gray-600 mb-4">
            Kode Aset, atau biasa yang juga biasa disebut "Nomor Inventaris", adalah sistem penomoran yang
            berfungsi sebagai kode ringkas untuk mengidentifikasi aset. Nomor ini harus unik dan berbeda
            untuk setiap aset.
        </p>
        <p class="text-sm text-gray-600 mb-4">
            Di sini Anda bisa membuat template-penomoran yang akan otomatis muncul pada kolom "Kode Aset"
            saat Aset Baru ditambahkan. Perubahan template ini hanya berlaku saat ada aset baru yang akan
            diinput, tidak berpengaruh pada data aset yang sudah tersimpan.
        </p>
        <p class="text-sm text-gray-600 mb-4">
            Anda bisa menggunakan tag-tag berikut ini:
        </p>
        <p class="text-sm text-gray-600">
            <span class="font-bold">[nomor]</span> Nomor urut aset berdasarkan jumlah aset yang pernah
            diinput.
        </p>
        <p class="text-sm text-gray-600">
            <span class="font-bold">[tanggal]</span> Tanggal hari ini (01-31).
        </p>
        <p class="text-sm text-gray-600">
            <span class="font-bold">[bulan-angka]</span> Bulan sekarang, dalam format angka (01-12).
        </p>
        <p class="text-sm text-gray-600">
            <span class="font-bold">[bulan-romawi]</span> Bulan sekarang, dalam format huruf romawi (I -
            XII).
        </p>
        <p class="text-sm text-gray-600 mb-4">
            <span class="font-bold">[tahun]</span> Tahun sekarang.
        </p>

        <div class="flex items-center space-x-4">
            <label for="kode_aset" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                Template Kode Aset
            </label>
            <input type="text" id="kode_aset" wire:model.live="kode_aset"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                placeholder="[nomor]/INV/[bulan-angka]/[tahun]" />
            @error('kode_aset')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end mt-4">
            <button type="button" wire:click="save"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                Simpan
            </button>
        </div>
    </x-card>

    <!-- QR Code Settings -->
    <x-card title="Keterangan QR-Code" class="mb-3">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- QR Code Image and Description -->
            <div>
                <div class="border rounded-md p-4 flex justify-center items-center">
                    <img src="/img/qrcode-sample.jpg" alt="QR Code Preview">
                </div>
                <p class="text-sm text-gray-700 mt-4">
                    Isi keterangan QR-Code akan otomatis dipotong jika lebih dari 25 karakter.
                </p>
            </div>

            <!-- Form Configuration -->
            <div class="space-y-4">
                <!-- Judul -->
                <div>
                    <div class="mb-4">
                        <label for="qr_judul" class="block text-sm font-medium text-gray-700">Judul QR</label>
                        <select id="qr_judul" wire:model.live="qr_judul"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="perusahaan">Nama Perusahaan/Institusi</option>
                            <option value="nama">Nama Aset</option>
                            <option value="kategori">Kategori</option>
                            <option value="kode">Kode Aset</option>
                            <option value="systemcode">Kode Sistem</option>
                            <option value="tanggalbeli">Tanggal Pembelian</option>
                            <option value="hargapembelian">Harga Pembelian</option>
                            <option value="person">Nama Penanggung Jawab Terakhir</option>
                            <option value="lokasi">Lokasi Terakhir</option>
                            <option value="null">[Kosong]</option>
                            <option value="other">Lainnya...</option>
                        </select>
                        @if ($qr_judul === 'other')
                            <input type="text" wire:model.live="qr_judul_other"
                                class="mt-2 block w-full border-gray-300 rounded-md">
                        @endif
                        @error('qr_judul')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Baris Pertama -->
                <div class="mb-4">
                    <label for="qr_baris1" class="block text-sm font-medium text-gray-700">Baris Pertama</label>
                    <select id="qr_baris1" wire:model.live="qr_baris1"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="perusahaan">Nama Perusahaan/Institusi</option>
                        <option value="nama">Nama Aset</option>
                        <option value="kategori">Kategori</option>
                        <option value="kode">Kode Aset</option>
                        <option value="systemcode">Kode Sistem</option>
                        <option value="tanggalbeli">Tanggal Pembelian</option>
                        <option value="hargapembelian">Harga Pembelian</option>
                        <option value="person">Nama Penanggung Jawab Terakhir</option>
                        <option value="lokasi">Lokasi Terakhir</option>
                        <option value="null">[Kosong]</option>
                        <option value="other">Lainnya...</option>
                    </select>
                    @if ($qr_baris1 === 'other')
                        <input type="text" wire:model.live="qr_baris1_other" maxlength="25"
                            placeholder="Ketik di sini"
                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @endif
                </div>

                <!-- Baris Kedua -->
                <div class="mb-4">
                    <label for="qr_baris2" class="block text-sm font-medium text-gray-700">Baris Kedua</label>
                    <select id="qr_baris2" wire:model.live="qr_baris2"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="perusahaan">Nama Perusahaan/Institusi</option>
                        <option value="nama">Nama Aset</option>
                        <option value="kategori">Kategori</option>
                        <option value="kode">Kode Aset</option>
                        <option value="systemcode">Kode Sistem</option>
                        <option value="tanggalbeli">Tanggal Pembelian</option>
                        <option value="hargapembelian">Harga Pembelian</option>
                        <option value="person">Nama Penanggung Jawab Terakhir</option>
                        <option value="lokasi">Lokasi Terakhir</option>
                        <option value="null">[Kosong]</option>
                        <option value="other">Lainnya...</option>
                    </select>
                    @if ($qr_baris2 === 'other')
                        <input type="text" wire:model.live="qr_baris2_other" maxlength="25"
                            placeholder="Ketik di sini"
                            class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @endif
                </div>

                <!-- Save Button -->
                <div class="flex justify-end mt-4">
                    <button type="button" wire:click="save"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </x-card>
</div>
