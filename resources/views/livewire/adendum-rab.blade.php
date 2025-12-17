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

    <!-- Info Notice -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi Adendum</h3>
                <p class="text-sm text-blue-700 mt-1">
                    Anda dapat mengubah data material RAB berdasarkan kondisi lapangan. Data kegiatan RAB tidak dapat diubah dan hanya ditampilkan sebagai referensi.
                    Setelah adendum dibuat, perubahan perlu dikonfirmasi oleh pembuat RAB.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" wire:ignore.self>
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

        <!-- Data RAB (Read-Only) -->
        <div>
            <x-card title="Data RAB" class="mb-3">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
                    <p class="text-xs text-gray-600">
                        <i class="fas fa-lock mr-1"></i> Data kegiatan RAB tidak dapat diubah dalam adendum
                    </p>
                </div>
                <div class="space-y-4">
                    <!-- Program dan Kegiatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                        <select disabled readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Program</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ $program_id == $program->id ? 'selected' : '' }}>
                                    {{ $program->program }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kegiatan</label>
                        <select disabled readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Kegiatan</option>
                            @foreach($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->id }}" {{ $kegiatan_id == $kegiatan->id ? 'selected' : '' }}>
                                    {{ $kegiatan->kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sub Kegiatan</label>
                        <select disabled readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Sub Kegiatan</option>
                            @foreach($sub_kegiatans as $sub_kegiatan)
                                <option value="{{ $sub_kegiatan->id }}" {{ $sub_kegiatan_id == $sub_kegiatan->id ? 'selected' : '' }}>
                                    {{ $sub_kegiatan->sub_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Aktivitas Sub Kegiatan</label>
                        <select disabled readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Aktivitas</option>
                            @foreach($aktivitas_sub_kegiatans as $aktivitas)
                                <option value="{{ $aktivitas->id }}" {{ $aktivitas_sub_kegiatan_id == $aktivitas->id ? 'selected' : '' }}>
                                    {{ $aktivitas->aktivitas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uraian Rekening</label>
                        <select disabled readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Uraian Rekening</option>
                            @foreach($uraian_rekenings as $uraian)
                                <option value="{{ $uraian->id }}" {{ $uraian_rekening_id == $uraian->id ? 'selected' : '' }}>
                                    {{ $uraian->uraian }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                        <select disabled readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}" {{ $kecamatan_id == $kecamatan->id ? 'selected' : '' }}>
                                    {{ $kecamatan->kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan</label>
                        <select disabled readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Kelurahan</option>
                            @foreach($kelurahans as $kelurahan)
                                <option value="{{ $kelurahan->id }}" {{ $kelurahan_id == $kelurahan->id ? 'selected' : '' }}>
                                    {{ $kelurahan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Fields (Read-Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pekerjaan</label>
                        <input type="text" value="{{ $jenis_pekerjaan }}" disabled readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <textarea disabled readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">{{ $lokasi }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mulai</label>
                            <input type="date" value="{{ $mulai }}" disabled readonly
                                class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Selesai</label>
                            <input type="date" value="{{ $selesai }}" disabled readonly
                                class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Panjang (m)</label>
                            <input type="number" step="0.01" value="{{ $p }}" disabled readonly
                                class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lebar (m)</label>
                            <input type="number" step="0.01" value="{{ $l }}" disabled readonly
                                class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kedalaman (m)</label>
                            <input type="number" step="0.01" value="{{ $k }}" disabled readonly
                                class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed opacity-75">
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Data Barang RAB (Editable) -->
    <div class="mt-6">
        <x-card title="Ubah Material RAB" class="mb-3">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Perubahan</label>
                <textarea wire:model="keterangan" rows="3"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Jelaskan alasan perubahan material berdasarkan kondisi lapangan..."
                    id="keteranganPerubahan"></textarea>
                @error('keterangan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-between items-center mb-4">
                <h4 class="font-semibold text-gray-800">Daftar Material dalam RAB</h4>
            </div>

            <div class="mb-6">
                <h5 class="text-sm font-medium text-gray-700 mb-3">Tambah Material Baru</h5>
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                        <livewire:searchable-select 
                            wire:model.live="newBarangId" 
                            :options="$barangs" 
                            label="nama" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
                        @if($newBarangId)
                            <livewire:searchable-select 
                                wire:model.live="newMerkId" 
                                :options="$merks" 
                                label="nama" />
                        @else
                            <livewire:searchable-select 
                                wire:model.live="newMerkId" 
                                :options="[]" 
                                label="nama"
                                :disabled="true" />
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <div class="flex items-center gap-2">
                            <input type="number" wire:model.live="newJumlah" min="1"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                placeholder="0">
                            <span class="text-sm text-gray-600">{{ $newUnit }}</span>
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="addToList" 
                            class="w-full bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition duration-200 {{ !$ruleAdd ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !$ruleAdd ? 'disabled' : '' }}>
                            Tambah
                        </button>
                    </div>
                </div>
            </div>

            @if(count($list) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-3 border-separate border-spacing-y-4 h-5 min-w-[1200px]">
                        <thead>
                            <tr class="text-white uppercase">
                                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[15%] rounded-l-lg">Nama Barang</th>
                                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[25%]">Spesifikasi</th>
                                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[10%]">Satuan</th>
                                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[12%]">Jumlah Lama</th>
                                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[15%] min-w-[180px]">Jumlah Baru</th>
                                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[15%] min-w-[180px]">Telah Digunakan</th>
                                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[10%]">Status</th>
                                <th class="py-3 px-6 bg-primary-950 w-[8%] text-center font-semibold rounded-r-lg">Aksi</th>
                            </tr>
                        </thead>
                    <tbody>
                            @foreach($list as $index => $item)
                            <tr class="{{ $item['action'] === 'delete' ? 'bg-red-50 hover:bg-red-100' : ($item['action'] === 'add' ? 'bg-green-50 hover:bg-green-100' : ($item['action'] === 'edit' ? 'bg-yellow-50 hover:bg-yellow-100' : 'bg-gray-50 hover:bg-gray-200')) }} hover:shadow-lg transition duration-200 rounded-2xl {{ $item['action'] === 'delete' ? 'opacity-60' : '' }}">
                                <td class="py-3 px-6 font-semibold {{ $item['action'] === 'delete' ? 'line-through' : '' }}">
                                            {{ $item['merk']->barangStok->nama ?? '-' }}
                                        </td>
                                <td class="py-3 px-6 font-semibold {{ $item['action'] === 'delete' ? 'line-through' : '' }}">
                                            {{ $item['merk']->nama }} 
                                            @if($item['merk']->tipe) - {{ $item['merk']->tipe }} @endif
                                            @if($item['merk']->ukuran) - {{ $item['merk']->ukuran }} @endif
                                        </td>
                                <td class="py-3 px-6 font-semibold {{ $item['action'] === 'delete' ? 'line-through' : '' }}">
                                            {{ $item['merk']->barangStok->satuanBesar->nama ?? '-' }}
                                        </td>
                                <td class="py-3 px-6 font-semibold text-center {{ $item['action'] === 'delete' ? 'line-through' : '' }}">
                                            {{ $item['jumlah_lama'] > 0 ? number_format($item['jumlah_lama']) : '-' }}
                                        </td>
                                <td class="py-3 px-6 min-w-[180px]">
                                            @if($item['action'] === 'delete')
                                                <span class="text-red-600 font-semibold">Dihapus</span>
                                            @else
                                                @php
                                                    $telahDigunakan = (int)($item['telah_digunakan'] ?? 0);
                                                    $minJumlah = $item['list_rab_id'] ? max(0, $telahDigunakan) : 0;
                                                @endphp
                                        <div class="flex items-center justify-center">
                                                <input type="number" 
                                                    wire:change="updateJumlah({{ $index }}, $event.target.value)"
                                                    value="{{ $item['jumlah_baru'] }}" 
                                                    min="{{ $minJumlah }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-24 p-2.5 text-center"
                                            >
                                            <span
                                                class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm whitespace-nowrap">
                                                {{ $item['merk']->barangStok->satuanBesar->nama ?? 'Satuan' }}
                                            </span>
                                        </div>
                                                @if($telahDigunakan > 0 && $item['list_rab_id'])
                                            <p class="text-xs text-gray-500 mt-1 text-center">Min: {{ number_format($telahDigunakan) }}</p>
                                                @endif
                                            @endif
                                        </td>
                                <td class="py-3 px-6 min-w-[180px]">
                                            <div class="flex items-center justify-center">
                                                @php
                                                    $telahDigunakan = (int)($item['telah_digunakan'] ?? 0);
                                                    $satuan = $item['merk']->barangStok->satuanBesar->nama ?? 'Satuan';
                                                @endphp
                                                <input type="number"
                                                    value="{{ $telahDigunakan }}"
                                                    disabled
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg cursor-not-allowed focus:ring-blue-500 focus:border-blue-500 block w-24 p-2.5 text-center {{ $item['action'] === 'delete' ? 'line-through' : '' }}"
                                                >
                                                <span
                                            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm whitespace-nowrap {{ $item['action'] === 'delete' ? 'line-through' : '' }}">
                                                    {{ $satuan }}
                                                </span>
                                            </div>
                                        </td>
                                <td class="py-3 px-6 font-semibold text-center">
                                            @if($item['action'] === 'add')
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Baru</span>
                                            @elseif($item['action'] === 'edit')
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Diubah</span>
                                            @elseif($item['action'] === 'delete')
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Dihapus</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">Tidak Berubah</span>
                                            @endif
                                        </td>
                                <td class="py-3 px-6">
                                    @if($item['action'] === 'delete')
                                        {{-- Tombol recover untuk item yang sudah dihapus --}}
                                        <button wire:click="recoverFromList({{ $index }})"
                                            class="text-green-900 border-green-600 text-xl border bg-green-100 hover:bg-green-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200"
                                            title="Kembalikan item">
                                            <i class="fa-solid fa-circle-plus"></i>
                                        </button>
                                    @else
                                        {{-- Tombol hapus untuk item yang belum dihapus --}}
                                            @php
                                                $telahDigunakan = (int)($item['telah_digunakan'] ?? 0);
                                                $canDelete = !$item['list_rab_id'] || $telahDigunakan == 0;
                                            @endphp
                                            <button wire:click="removeFromList({{ $index }})"
                                            class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200 {{ !$canDelete ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                @if(!$canDelete) disabled title="Barang tidak dapat dihapus karena sudah digunakan ({{ number_format($telahDigunakan) }} {{ $item['merk']->barangStok->satuanBesar->nama ?? 'satuan' }})" @endif>
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            </button>
                                    @endif
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-4"></i>
                    <p>Belum ada data material dalam RAB ini</p>
                </div>
            @endif

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('rab.show', ['rab' => $rab->id]) }}"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button wire:click="saveAdendum" wire:loading.attr="disabled"
                    class="px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-200">
                    <span wire:loading.remove wire:target="saveAdendum">
                        <i class="fas fa-save mr-2"></i>Simpan Adendum
                    </span>
                    <span wire:loading wire:target="saveAdendum">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...
                    </span>
                </button>
            </div>
        </x-card>
    </div>

    @push('scripts')
    <script>
        const initAdendumToast = () => {
            // #region agent log
            fetch('http://127.0.0.1:7242/ingest/43b03c8c-2c2c-459e-8e69-b6f08dd9dc05', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    sessionId: 'debug-session',
                    runId: 'post-fix-frontend',
                    hypothesisId: 'H5',
                    location: 'adendum-rab.blade.php:init',
                    message: 'script initialized',
                    data: {},
                    timestamp: Date.now()
                })
            }).catch(() => {});
            // #endregion

            // SweetAlert2 Toast untuk error
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            const handler = (params) => {
                const msg = params && typeof params === 'object' && 'message' in params
                    ? params.message
                    : Array.isArray(params) && params.length > 0
                        ? (params[0]?.message ?? params[0])
                        : 'Terjadi kesalahan';

                // #region agent log
                fetch('http://127.0.0.1:7242/ingest/43b03c8c-2c2c-459e-8e69-b6f08dd9dc05', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        sessionId: 'debug-session',
                        runId: 'post-fix-frontend',
                        hypothesisId: 'H6',
                        location: 'adendum-rab.blade.php:swal-error',
                        message: 'received swal-error',
                        data: { params, resolvedMessage: msg },
                        timestamp: Date.now()
                    })
                }).catch(() => {});
                // #endregion

                Toast.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: typeof msg === 'string' ? msg : 'Jumlah baru tidak boleh kurang dari jumlah yang telah digunakan.',
                });
            };

            // Listen Livewire event
            if (window.Livewire) {
                window.Livewire.on('swal-error', handler);
            }
            // Fallback for browser event
            window.addEventListener('swal-error', (e) => handler(e.detail || e));

            // Flash messages
            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}',
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        };

        // Run once DOM ready and also when Livewire initializes to ensure bindings exist
        const runInit = () => {
            initAdendumToast();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', runInit, { once: true });
        } else {
            runInit();
        }

        document.addEventListener('livewire:initialized', runInit, { once: true });
    </script>
    @endpush
</div>
