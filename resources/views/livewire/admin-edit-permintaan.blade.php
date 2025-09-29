<div>
    <!-- Main Edit Form -->
    <x-card title="Form Edit Permintaan (Admin)">
        <form wire:submit.prevent="confirmSave">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Permintaan -->
                <div>
                    <label for="nodin" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Permintaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('nodin') ? 'border-red-500' : 'border-gray-300' }}"
                        id="nodin" wire:model="nodin" placeholder="Masukkan kode permintaan">
                    @error('nodin')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tanggal Permintaan -->
                <div>
                    <label for="tanggal_permintaan" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Permintaan <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('tanggal_permintaan') ? 'border-red-500' : 'border-gray-300' }}"
                        id="tanggal_permintaan" wire:model="tanggal_permintaan">
                    @error('tanggal_permintaan')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Unit Kerja (Readonly) -->
                <div>
                    <label for="unit_nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Kerja
                    </label>
                    <input type="text"
                        class="w-full border rounded-md px-3 py-2 bg-gray-100 text-gray-500 cursor-not-allowed"
                        id="unit_nama" value="{{ $unit_nama }}" readonly>
                    <small class="text-gray-500">Unit kerja tidak dapat diubah oleh admin</small>
                </div>

                <!-- Menggunakan RAB -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Menggunakan RAB
                    </label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" wire:model="withRab" value="1" class="mr-2">
                            Ya
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model="withRab" value="0" class="mr-2">
                            Tidak
                        </label>
                    </div>
                </div>

                <!-- RAB Selection (shown when withRab = 1) -->
                @if($withRab)
                    <div class="md:col-span-2">
                        <label for="rab_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih RAB
                        </label>
                        <select
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('rab_id') ? 'border-red-500' : 'border-gray-300' }}"
                            id="rab_id" wire:model="rab_id">
                            <option value="">Pilih RAB</option>
                            @foreach($rabOptions as $rab)
                                <option value="{{ $rab->id }}">{{ $rab->nodin }} - {{ $rab->nama }}</option>
                            @endforeach
                        </select>
                        @error('rab_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <!-- Gudang -->
                <div>
                    <label for="gudang_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Gudang <span class="text-red-500">*</span>
                    </label>
                    <select
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('gudang_id') ? 'border-red-500' : 'border-gray-300' }}"
                        id="gudang_id" wire:model="gudang_id">
                        <option value="">Pilih Gudang</option>
                        @foreach($gudangOptions as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                        @endforeach
                    </select>
                    @error('gudang_id')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="kecamatan_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kecamatan
                    </label>
                    <select
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        id="kecamatan_id" wire:model="kecamatan_id">
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatanOptions as $kecamatan)
                            <option value="{{ $kecamatan->id }}">{{ $kecamatan->kecamatan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kelurahan -->
                <div>
                    <label for="kelurahan_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelurahan
                    </label>
                    <select
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        id="kelurahan_id" wire:model="kelurahan_id">
                        <option value="">Pilih Kelurahan</option>
                        @foreach($kelurahanOptions as $kelurahan)
                            <option value="{{ $kelurahan->id }}">{{ $kelurahan->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi Spesifik
                    </label>
                    <input type="text"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('lokasi') ? 'border-red-500' : 'border-gray-300' }}"
                        id="lokasi" wire:model="lokasi" placeholder="Masukkan lokasi spesifik">
                    @error('lokasi')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Lokasi Material -->
                <div>
                    <label for="lokasiMaterial" class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi Material/Detail
                    </label>
                    <textarea
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('lokasiMaterial') ? 'border-red-500' : 'border-gray-300' }}"
                        id="lokasiMaterial" wire:model="lokasiMaterial" rows="2"
                        placeholder="Detail lokasi tambahan (misal: Jl. ABC No. 123, dekat Lapangan)"></textarea>
                    @error('lokasiMaterial')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Kontak Person -->
                <div>
                    <label for="KontakPerson" class="block text-sm font-medium text-gray-700 mb-2">
                        Kontak Person
                    </label>
                    <input type="text"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('KontakPerson') ? 'border-red-500' : 'border-gray-300' }}"
                        id="KontakPerson" wire:model="KontakPerson" placeholder="Masukkan kontak person">
                    @error('KontakPerson')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Volume P -->
                <div>
                    <label for="p" class="block text-sm font-medium text-gray-700 mb-2">
                        Panjang (meter)
                    </label>
                    <input type="number" step="0.01"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('p') ? 'border-red-500' : 'border-gray-300' }}"
                        id="p" wire:model="p" placeholder="0.00">
                    @error('p')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Volume L -->
                <div>
                    <label for="l" class="block text-sm font-medium text-gray-700 mb-2">
                        Lebar (meter)
                    </label>
                    <input type="number" step="0.01"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('l') ? 'border-red-500' : 'border-gray-300' }}"
                        id="l" wire:model="l" placeholder="0.00">
                    @error('l')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Volume K -->
                <div>
                    <label for="k" class="block text-sm font-medium text-gray-700 mb-2">
                        Kedalaman (meter)
                    </label>
                    <input type="number" step="0.01"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('k') ? 'border-red-500' : 'border-gray-300' }}"
                        id="k" wire:model="k" placeholder="0.00">
                    @error('k')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('keterangan') ? 'border-red-500' : 'border-gray-300' }}"
                        id="keterangan" wire:model="keterangan" rows="3"
                        placeholder="Masukkan keterangan permintaan"></textarea>
                    @error('keterangan')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Transport & Document Section -->
            <div class="mt-8">
                <h4 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Data Transport & Dokumen</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Driver -->
                    <div>
                        <label for="driver" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Driver
                        </label>
                        <select
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('driver') ? 'border-red-500' : 'border-gray-300' }}"
                            id="driver" wire:model="driver">
                            <option value="">Pilih Driver</option>
                            @foreach($driverOptions as $driverOption)
                                <option value="{{ $driverOption->nama }}">{{ $driverOption->nama }}</option>
                            @endforeach
                        </select>
                        @error('driver')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor Polisi -->
                    <div>
                        <label for="nopol" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Polisi
                        </label>
                        <input type="text"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('nopol') ? 'border-red-500' : 'border-gray-300' }}"
                            id="nopol" wire:model="nopol" placeholder="Masukkan nomor polisi kendaraan">
                        @error('nopol')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Security -->
                    <div>
                        <label for="security" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Security
                        </label>
                        <input type="text"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('security') ? 'border-red-500' : 'border-gray-300' }}"
                            id="security" wire:model="security" placeholder="Masukkan nama security">
                        @error('security')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- TTD Driver -->
                    <div>
                        <label for="ttd_driver" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanda Tangan Driver
                        </label>
                        <input type="text"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('ttd_driver') ? 'border-red-500' : 'border-gray-300' }}"
                            id="ttd_driver" wire:model="ttd_driver" placeholder="Status tanda tangan driver">
                        @error('ttd_driver')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- TTD Security -->
                    <div>
                        <label for="ttd_security" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanda Tangan Security
                        </label>
                        <input type="text"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('ttd_security') ? 'border-red-500' : 'border-gray-300' }}"
                            id="ttd_security" wire:model="ttd_security" placeholder="Status tanda tangan security">
                        @error('ttd_security')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor Surat Jalan -->
                    <div>
                        <label for="suratJalan" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Surat Jalan
                        </label>
                        <input type="text"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('suratJalan') ? 'border-red-500' : 'border-gray-300' }}"
                            id="suratJalan" wire:model="suratJalan" placeholder="Masukkan nomor surat jalan">
                        @error('suratJalan')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor SPPB -->
                    <div>
                        <label for="sppb" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor SPPB
                        </label>
                        <input type="text"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('sppb') ? 'border-red-500' : 'border-gray-300' }}"
                            id="sppb" wire:model="sppb" placeholder="Masukkan nomor SPPB">
                        @error('sppb')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor SPB -->
                    <div>
                        <label for="spb_path" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor SPB
                        </label>
                        <input type="text"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('spb_path') ? 'border-red-500' : 'border-gray-300' }}"
                            id="spb_path" wire:model="spb_path" placeholder="Masukkan nomor SPB">
                        @error('spb_path')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- List Data Permintaan Material -->
            <div class="mt-8">
                <h4 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Data Permintaan Material</h4>
                @if($permintaan->permintaanMaterial && $permintaan->permintaanMaterial->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Merk</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Spesifikasi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Satuan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($permintaan->permintaanMaterial as $index => $material)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $material->merkStok->barangStok->nama ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $material->merkStok->nama ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $material->spesifikasi ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($material->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $material->merkStok->barangStok->satuan ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $material->keterangan ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p>Belum ada data permintaan material</p>
                    </div>
                @endif
            </div>

            <div class="flex justify-end space-x-2 mt-6">
                <a href="/permintaan/material" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Batal
                </a>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md">
                    Update Permintaan
                </button>
            </div>
        </form>
    </x-card>

    <!-- Admin Confirmation Modal -->
    @if($showSaveModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Update Admin</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Anda akan mengupdate permintaan ini sebagai admin. Mohon berikan alasan perubahan:
                </p>

                <div class="mb-4">
                    <label for="adminReason" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Perubahan <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('adminReason') ? 'border-red-500' : 'border-gray-300' }}"
                        id="adminReason" wire:model="adminReason" rows="3"
                        placeholder="Jelaskan alasan admin mengubah permintaan ini"></textarea>
                    @error('adminReason')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="cancelSave"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Batal
                    </button>
                    <button type="button" wire:click="save"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Konfirmasi Update
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>