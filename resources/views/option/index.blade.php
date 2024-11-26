<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">Pengaturan</h1>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <div>
            <livewire:setting-options />
        </div>
        <div>
            <div>
                <x-card title="Scan QR-Code" class="mb-3">
                    <form class="space-y-6">
                        <!-- Judul -->
                        <p class="text-sm text-gray-700">
                            Apa saja data yang muncul saat QR-Code discan menggunakan smartphone?
                        </p>

                        <!-- Informasi Umum -->
                        <fieldset class="border border-gray-300 p-4 rounded-lg">
                            <legend class="text-sm font-semibold text-gray-700">Informasi Umum</legend>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                <div class="flex items-center">
                                    <input id="nama_aset" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="nama_aset" class="ml-2 text-sm text-gray-700">Nama Aset</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="kategori" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="kategori" class="ml-2 text-sm text-gray-700">Kategori</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="kode_aset" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="kode_aset" class="ml-2 text-sm text-gray-700">Kode Aset</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="kode_sistem" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="kode_sistem" class="ml-2 text-sm text-gray-700">Kode Sistem</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="keterangan_tambahan" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="keterangan_tambahan" class="ml-2 text-sm text-gray-700">Keterangan
                                        Tambahan</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="foto" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="foto" class="ml-2 text-sm text-gray-700">Foto</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="lampiran" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="lampiran" class="ml-2 text-sm text-gray-700">Lampiran</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="status_aset" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="status_aset" class="ml-2 text-sm text-gray-700">Status Aset
                                        (Aktif/Non-Aktif)</label>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Jika Status Aset adalah Non-Aktif -->
                        <fieldset class="border border-gray-300 p-4 rounded-lg">
                            <legend class="text-sm font-semibold text-gray-700">Jika Status Aset adalah Non-Aktif,
                                apa
                                saja item yang muncul?</legend>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                <div class="flex items-center">
                                    <input id="tanggal_nonaktif" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="tanggal_nonaktif" class="ml-2 text-sm text-gray-700">Tanggal
                                        Non-Aktif</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="sebab" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="sebab" class="ml-2 text-sm text-gray-700">Sebab</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="keterangan" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="keterangan" class="ml-2 text-sm text-gray-700">Keterangan</label>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Detail Aset-->
                        <fieldset class="border border-gray-300 p-4 rounded-lg">
                            <legend class="text-sm font-semibold text-gray-700">Detail Aset</legend>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                <div class="flex items-center">
                                    <input id="merk" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="merk" class="ml-2 text-sm text-gray-700">Merk</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="tipe" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="tipe" class="ml-2 text-sm text-gray-700">Tipe</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="no_seri" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="no_seri" class="ml-2 text-sm text-gray-700">No Seri / Kode
                                        Produksi</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="tahun_produksi" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="tahun_produksi" class="ml-2 text-sm text-gray-700">Tahun
                                        Produksi</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="deskripsi" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="deskripsi" class="ml-2 text-sm text-gray-700">Deskripsi</label>
                                </div>

                            </div>
                        </fieldset>

                        <!-- Pembelian-->
                        <fieldset class="border border-gray-300 p-4 rounded-lg">
                            <legend class="text-sm font-semibold text-gray-700">Pembelian</legend>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                <div class="flex items-center">
                                    <input id="tanggal_pembelian" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="tanggal_pembelian" class="ml-2 text-sm text-gray-700">Tanggal
                                        Pembelian</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="toko" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="toko" class="ml-2 text-sm text-gray-700">Toko /
                                        Distributor</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="no_invoice" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="no_invoice" class="ml-2 text-sm text-gray-700">No.Invoice</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="jumlah_unit" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="jumlah_unit" class="ml-2 text-sm text-gray-700">Jumlah
                                        Unit</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="harga_satuan" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="harga_satuan" class="ml-2 text-sm text-gray-700">Harga
                                        Satuan</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="harga_total" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="harga_total" class="ml-2 text-sm text-gray-700">Harga
                                        Total</label>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Umur & Penyusutan-->
                        <fieldset class="border border-gray-300 p-4 rounded-lg">
                            <legend class="text-sm font-semibold text-gray-700">Umur & Penyusutan</legend>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                <div class="flex items-center">
                                    <input id="umur_ekonomi" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="umur_ekonomi" class="ml-2 text-sm text-gray-700">Umur
                                        Ekonomi</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="nilai_penyusutan" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="nilai_penyusutan" class="ml-2 text-sm text-gray-700">Nilai
                                        Penyusutan
                                        per Bulan (Rp)</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="usia_aset" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="usia_aset" class="ml-2 text-sm text-gray-700">Usia Aset</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="nilai_sekarang" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="nilai_sekarang" class="ml-2 text-sm text-gray-700"> Nilai Sekarang
                                        (Rp)</label>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Keuangan, Agenda, dan Jurnal -->
                        <fieldset class="border border-gray-300 p-4 rounded-lg">
                            <legend class="text-sm font-semibold text-gray-700">Keuangan, Agenda, dan Jurnal
                            </legend>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                <div class="flex items-center">
                                    <input id="keuangan" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="keuangan" class="ml-2 text-sm text-gray-700">Transaksi Pengeluaran
                                        /
                                        Pemasukan Aset</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="agenda" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="agenda" class="ml-2 text-sm text-gray-700">Agenda Aset</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="jurnal" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="jurnal" class="ml-2 text-sm text-gray-700">Jurnal Aset</label>
                                </div>
                            </div>
                        </fieldset>


                        <!-- Riwayat -->
                        <fieldset class="border border-gray-300 p-4 rounded-lg">
                            <legend class="text-sm font-semibold text-gray-700">Riwayat</legend>
                            <div class="flex space-x-6 mt-2"> <!-- Flex Row dengan jarak horizontal -->
                                <!-- Riwayat Terakhir -->
                                <div class="flex items-center">
                                    <input id="riwayat_terakhir" name="riwayat" type="radio" value="terakhir"
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="riwayat_terakhir" class="ml-2 text-sm text-gray-700">Riwayat
                                        Terakhir</label>
                                </div>

                                <!-- Semua Riwayat -->
                                <div class="flex items-center">
                                    <input id="semua_riwayat" name="riwayat" type="radio" value="semua"
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="semua_riwayat" class="ml-2 text-sm text-gray-700">Semua
                                        Riwayat</label>
                                </div>

                                <!-- Tidak Ditampilkan -->
                                <div class="flex items-center">
                                    <input id="tidak_tampil" name="riwayat" type="radio" value="tidak"
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="tidak_tampil" class="ml-2 text-sm text-gray-700">Tidak
                                        Ditampilkan</label>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Jika Riwayat Aset ditampilkan, apa saja item yang muncul?-->
                        <fieldset class="border border-gray-300 p-4 rounded-lg">
                            <legend class="text-sm font-semibold text-gray-700">Jika Riwayat Aset ditampilkan, apa
                                saja
                                item yang muncul?</legend>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                <div class="flex items-center">
                                    <input id="riwayat_tanggal" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="riwayat_tanggal" class="ml-2 text-sm text-gray-700">Tanggal</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="penanggungjawab" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="penanggungjawab" class="ml-2 text-sm text-gray-700">Penanggung
                                        Jawab</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="lokasi" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="lokasi" class="ml-2 text-sm text-gray-700">Lokasi</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="jumlah" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="jumlah" class="ml-2 text-sm text-gray-700">Jumlah</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="kondisi" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="kondisi" class="ml-2 text-sm text-gray-700">Kondisi</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="kelengkapan" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="kelengkapan" class="ml-2 text-sm text-gray-700">Kelengkapan</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="keterangan" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="keterangan" class="ml-2 text-sm text-gray-700">Keterangan</label>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Simpan -->
                        <div class="flex justify-end">
                            <button type="button" wire:click="#"
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                Simpan
                            </button>
                        </div>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
    </div>
    {{-- @push('scripts')
        <script>
            function buka_other(id) {
                const selectElement = document.getElementById(id);
                const otherInput = document.getElementById(`${id}_other`);

                // Check if "Lainnya..." is selected
                if (selectElement.value === "other") {
                    otherInput.classList.remove("hidden");
                } else {
                    otherInput.classList.add("hidden");
                }
            }
        </script>
    @endpush --}}
</x-body>
