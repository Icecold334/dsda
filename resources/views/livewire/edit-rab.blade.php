<div>
    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
    @endif

    <div class="flex justify-between">
        <h1 class="text-2xl font-bold text-primary-900">EDIT RAB</h1>
        <div>
            <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                href="{{ route('rab.show', $rab->id) }}">Kembali</a>
            @if($canDelete)
            <button wire:click="confirmDelete"
                class="cursor-pointer text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                <i class="fas fa-trash mr-1"></i>Hapus RAB
            </button>
            @endif
        </div>
    </div>

    <!-- Authorization Notice -->
    @if($isSuperAdmin)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-crown text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Mode Super Admin</h3>
                <p class="text-sm text-yellow-700 mt-1">
                    Anda dapat mengedit dan menghapus RAB dari semua suku dinas tanpa batasan status.
                    Setiap perubahan akan dicatat dengan keterangan yang Anda berikan.
                </p>
            </div>
        </div>
    </div>
    @elseif(!$canEdit)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lock text-red-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">RAB Tidak Dapat Diedit</h3>
                <p class="text-sm text-red-700 mt-1">
                    RAB ini sudah disetujui atau dalam proses persetujuan sehingga tidak dapat diedit.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Cascade Message Alert -->
    @if($cascadeMessage)
    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">{{ $cascadeMessage }}</p>
            </div>
            <div class="ml-auto">
                <button wire:click="clearCascadeMessage" class="text-blue-400 hover:text-blue-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informasi RAB -->
        <div>
            <x-card title="Informasi RAB" class="mb-3">
                <table class="table-auto font-medium text-sm text-gray-900 ">
                    <tbody class="text-sm">
                        <tr class="font-semibold">
                            <td class="w-[40%]">Jenis Pekerjaan</td>
                            <td>{{ $rab->jenis_pekerjaan }}</td>
                        </tr>
                        <tr class="font-semibold">
                            <td>Lokasi</td>
                            <td>
                                @if ($rab->kelurahan)
                                Kelurahan {{ $rab->kelurahan->nama }},
                                Kecamatan {{ $rab->kelurahan->kecamatan->kecamatan }} –
                                @endif
                                {{ $rab->lokasi }}
                            </td>
                        </tr>
                        <tr class="font-semibold">
                            <td>Periode</td>
                            <td>{{ $rab->mulai?->format('d/m/Y') }} - {{ $rab->selesai?->format('d/m/Y') }}</td>
                        </tr>
                        <tr class="font-semibold">
                            <td>Dimensi</td>
                            <td>P: {{ $rab->p }}m, L: {{ $rab->l }}m, K: {{ $rab->k }}m</td>
                        </tr>
                        <tr class="font-semibold">
                            <td>Pembuat</td>
                            <td>{{ $rab->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr class="font-semibold">
                            <td>Dibuat</td>
                            <td>{{ $rab->created_at->format('d F Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </x-card>
        </div>

        <!-- Form Edit -->
        <div>
            <x-card title="Edit Data RAB" class="mb-3">
                <form wire:submit.prevent="save">
                    <!-- Program dan Kegiatan -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                            <selectwire:model.live.debounce.500ms="program_id" wire:loading.attr="disabled"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                {{ !$canEdit ? 'disabled' : '' }}>
                                <option value="">Pilih Program</option>
                                @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->program }}</option>
                                @endforeach
                                </select>
                                <div wire:loading wire:target="program_id" class="text-sm text-blue-600 mt-1">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat data kegiatan...
                                </div>
                                @error('program_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kegiatan</label>
                            <selectwire:model.live.debounce.500ms="kegiatan_id" wire:loading.attr="disabled"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                {{ !$canEdit ? 'disabled' : '' }}>
                                <option value="">Pilih Kegiatan</option>
                                @foreach($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->id }}">{{ $kegiatan->kegiatan }}</option>
                                @endforeach
                                </select>
                                <div wire:loading wire:target="kegiatan_id" class="text-sm text-blue-600 mt-1">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat data sub kegiatan...
                                </div>
                                @error('kegiatan_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sub Kegiatan</label>
                            <selectwire:model.live.debounce.500ms="sub_kegiatan_id" wire:loading.attr="disabled"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                {{ !$canEdit ? 'disabled' : '' }}>
                                <option value="">Pilih Sub Kegiatan</option>
                                @foreach($sub_kegiatans as $sub_kegiatan)
                                <option value="{{ $sub_kegiatan->id }}">{{ $sub_kegiatan->sub_kegiatan }}</option>
                                @endforeach
                                </select>
                                <div wire:loading wire:target="sub_kegiatan_id" class="text-sm text-blue-600 mt-1">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat data aktivitas...
                                </div>
                                @error('sub_kegiatan_id') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Aktivitas Sub Kegiatan</label>
                            <selectwire:model.live.debounce.500ms="aktivitas_sub_kegiatan_id"
                                wire:loading.attr="disabled"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                {{ !$canEdit ? 'disabled' : '' }}>
                                <option value="">Pilih Aktivitas</option>
                                @foreach($aktivitas_sub_kegiatans as $aktivitas)
                                <option value="{{ $aktivitas->id }}">{{ $aktivitas->aktivitas }}</option>
                                @endforeach
                                </select>
                                <div wire:loading wire:target="aktivitas_sub_kegiatan_id"
                                    class="text-sm text-blue-600 mt-1">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat data uraian rekening...
                                </div>
                                @error('aktivitas_sub_kegiatan_id') <span class="text-red-500 text-sm">{{ $message
                                    }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Uraian Rekening</label>
                            <selectwire:model.live.debounce.500ms="uraian_rekening_id" wire:loading.attr="disabled"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                {{ !$canEdit ? 'disabled' : '' }}>
                                <option value="">Pilih Uraian Rekening</option>
                                @foreach($uraian_rekenings as $uraian)
                                <option value="{{ $uraian->id }}">{{ $uraian->uraian }}</option>
                                @endforeach
                                </select>
                                @error('uraian_rekening_id') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                        </div>

                        <!-- Unit Context for Super Admin (Read Only) -->
                        @if($isSuperAdmin)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja (Readonly)</label>
                            <select disabled readonly
                                class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                                @if($selected_unit_id)
                                @php
                                $selectedUnit = $all_units->where('id', $selected_unit_id)->first();
                                @endphp
                                @if($selectedUnit)
                                <option value="{{ $selectedUnit->id }}" selected>{{ $selectedUnit->nama }}</option>
                                @endif
                                @else
                                <option value="">Unit Kerja RAB Asli</option>
                                @endif
                            </select>
                            <div class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-info-circle"></i> Unit kerja tidak dapat diubah saat mengedit RAB yang
                                sudah ada
                            </div>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                            <selectwire:model.live.debounce.500ms="kecamatan_id" wire:loading.attr="disabled"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                {{ !$canEdit ? 'disabled' : '' }}>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}">{{ $kecamatan->kecamatan }}</option>
                                @endforeach
                                </select>
                                <div wire:loading wire:target="kecamatan_id" class="text-sm text-blue-600 mt-1">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat data kelurahan...
                                </div>
                                @error('kecamatan_id') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan</label>
                            <select wire:model="kelurahan_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                {{ !$canEdit ? 'disabled' : '' }}>
                                <option value="">Pilih Kelurahan</option>
                                @foreach($kelurahans as $kelurahan)
                                <option value="{{ $kelurahan->id }}">{{ $kelurahan->nama }}</option>
                                @endforeach
                            </select>
                            @error('kelurahan_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Input Fields -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pekerjaan</label>
                            <input type="text" wire:model="jenis_pekerjaan" {{ !$canEdit ? 'disabled' : '' }}
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            @error('jenis_pekerjaan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                            <textarea wire:model="lokasi" {{ !$canEdit ? 'disabled' : '' }}
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                            @error('lokasi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mulai</label>
                                <input type="date" wire:model="mulai" {{ !$canEdit ? 'disabled' : '' }}
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                @error('mulai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Selesai</label>
                                <input type="date" wire:model="selesai" {{ !$canEdit ? 'disabled' : '' }}
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                @error('selesai') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Panjang (m)</label>
                                <input type="number" step="0.01" wire:model="p" {{ !$canEdit ? 'disabled' : '' }}
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                @error('p') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lebar (m)</label>
                                <input type="number" step="0.01" wire:model="l" {{ !$canEdit ? 'disabled' : '' }}
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                @error('l') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kedalaman (m)</label>
                                <input type="number" step="0.01" wire:model="k" {{ !$canEdit ? 'disabled' : '' }}
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                @error('k') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Keterangan Perubahan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Perubahan</label>
                            <textarea wire:model="keterangan_perubahan" {{ !$canEdit ? 'disabled' : '' }}
                                placeholder="Jelaskan alasan perubahan data RAB..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                            @error('keterangan_perubahan') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($canEdit)
                        <div class="pt-4">
                            <button type="submit" wire:loading.attr="disabled"
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md transition duration-200">
                                <span wire:loading.remove wire:target="save">
                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                </span>
                                <span wire:loading wire:target="save">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...
                                </span>
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </x-card>
        </div>
    </div>

    <!-- Data Barang RAB -->
    <div class="mt-6">
        <x-card title="Data Barang RAB" class="mb-3">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-semibold text-gray-800">Daftar Barang dalam RAB</h4>
                @if($canEdit)
                <button wire:click="addDetailRab"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-4 py-2 transition duration-200">
                    <i class="fas fa-plus mr-1"></i>Tambah Barang
                </button>
                @endif
            </div>

            @if(count($detailRabs) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Spesifikasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Satuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kuantitas</th>
                            @if($canEdit)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detailRabs as $detail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail['nama_barang'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail['spesifikasi'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail['satuan'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail['kuantitas'] }}
                            </td>
                            @if($canEdit)
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="editDetailRab({{ $detail['id'] }})"
                                    class="text-primary-600 hover:text-primary-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button wire:click="deleteDetailRab({{ $detail['id'] }})"
                                    class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-box-open text-4xl mb-4"></i>
                <p>Belum ada data barang dalam RAB ini</p>
            </div>
            @endif
        </x-card>
    </div>


    <!-- Modal untuk Edit Detail Barang -->
    @if($showDetailModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ $editingDetailId ? 'Edit' : 'Tambah' }} Detail Barang
                </h3>

                <form wire:submit.prevent="saveDetailRab" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Barang</label>
                        <selectwire:model.live.debounce.500ms="selectedBarangId"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangStoks as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                            @endforeach
                            </select>
                            @error('selectedBarangId') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                    </div>

                    @if(count($merkStoks) > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Merk/Spesifikasi</label>
                        <selectwire:model.live.debounce.500ms="detailForm.merk_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">-- Pilih Merk --</option>
                            @foreach($merkStoks as $merk)
                            <option value="{{ $merk->id }}">
                                {{ $merk->nama }}
                                @if($merk->spesifikasi) - {{ $merk->spesifikasi }} @endif
                                @if($merk->ukuran) - {{ $merk->ukuran }} @endif
                            </option>
                            @endforeach
                            </select>
                            @error('detailForm.merk_id') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                    </div>
                    @endif

                    <!-- Option untuk input manual jika tidak memilih dari dropdown -->
                    @if(!$detailForm['merk_id'])
                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600 mb-3">Atau isi manual jika barang tidak ada dalam daftar:</p>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                            <input type="text" wire:model="detailForm.nama_barang" placeholder="Masukkan nama barang..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            @error('detailForm.nama_barang') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi</label>
                            <textarea wire:model="detailForm.spesifikasi" placeholder="Masukkan spesifikasi barang..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                rows="3"></textarea>
                            @error('detailForm.spesifikasi') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                            <input type="text" wire:model="detailForm.satuan"
                                placeholder="Masukkan satuan (contoh: pcs, kg, meter)..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            @error('detailForm.satuan') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @else
                    <!-- Display selected item info -->
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-sm text-gray-700"><strong>Nama:</strong> {{ $detailForm['nama_barang'] }}</p>
                        <p class="text-sm text-gray-700"><strong>Spesifikasi:</strong> {{ $detailForm['spesifikasi'] }}
                        </p>
                        <p class="text-sm text-gray-700"><strong>Satuan:</strong> {{ $detailForm['satuan'] }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kuantitas</label>
                        <input type="number" wire:model="detailForm.kuantitas" step="0.01"
                            placeholder="Masukkan jumlah/kuantitas..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @error('detailForm.kuantitas') <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea wire:model="detailForm.keterangan"
                            placeholder="Masukkan keterangan tambahan (opsional)..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                            rows="3"></textarea>
                        @error('detailForm.keterangan') <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="cancelDetailModal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                            {{ $editingDetailId ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-trash text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus RAB</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Apakah Anda yakin ingin menghapus RAB ini? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penghapusan</label>
                    <textarea wire:model="reason_delete" placeholder="Jelaskan alasan penghapusan RAB..."
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                    @error('reason_delete') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-center space-x-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button wire:click="deleteRab" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>