<div>
    <div wire:loading wire:target="cariKontrakApi">
        <livewire:loading>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <x-card title="Sumber Data Kontrak">
            <div class="flex flex-col gap-4">
                @if ($isAdendum)
                <div>
                    <label class="block text-sm font-medium">Nomor Kontrak Lama</label>
                    <input type="text" value="{{ $nomor_kontrak }}" disabled
                        class="w-full p-2 border border-gray-300 rounded-md text-sm bg-gray-100" />
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium">Tahun</label>
                    <select wire:model.live="tahun_api" {{ $mode_manual ? 'disabled' : '' }}
                        class="w-full p-2 border border-gray-300 rounded-md text-sm {{ $mode_manual ? 'bg-gray-100' : '' }}">
                        <option value="">-- Pilih Tahun --</option>
                        @for ($i = now()->year; $i >= now()->year - 5; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Nomor SPK {{ $isAdendum ? 'Baru' : '' }}</label>
                    <input type="text" wire:model.live="nomor_spk_api" {{ $mode_manual ? 'disabled' : '' }}
                        class="w-full p-2 border border-gray-300 rounded-md text-sm {{ $mode_manual ? 'bg-gray-100' : '' }}"
                        placeholder="Contoh: 123/SPK/2025">
                </div>

                @if (!$mode_manual)
                <button wire:click="cariKontrakApi"
                    class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 transition text-sm">
                    <i class="fa fa-search mr-1"></i> Cari Kontrak
                </button>
                @endif

                @if ($mode_manual)
                <div class="flex gap-2">
                    <div class="flex-1 p-2 bg-yellow-50 border border-yellow-200 rounded-md">
                        <small class="text-yellow-800 font-medium">Mode Pengisian Manual Aktif</small>
                    </div>
                    <button wire:click="batalkanPencarian"
                        class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition text-sm">
                        Reset
                    </button>
                </div>
                @endif
            </div>
        </x-card>

        <x-card title="Informasi Kontrak">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ([
                'nomor_spk_api' => 'Nomor SPK',
                'nama_paket' => 'Nama Paket',
                'jenis_pengadaan' => 'Jenis Pengadaan',
                'nama_penyedia' => 'Nama Penyedia',
                'tahun_anggaran' => 'Tahun Anggaran',
                'dinas_sudin' => 'Dinas/Sudin',
                'nama_bidang_seksi' => 'Bidang/Seksi',
                'program' => 'Program',
                'kegiatan' => 'Kegiatan',
                'sub_kegiatan' => 'Sub Kegiatan',
                'aktivitas_sub_kegiatan' => 'Aktivitas Sub Kegiatan',
                'rekening' => 'Kode Rekening',
                ] as $field => $label)
                <div>
                    <label class="block text-sm font-medium">{{ $label }}</label>
                    <input type="text" wire:model.live="{{ $field }}" {{ $readonly_fields && !$mode_manual ? 'disabled'
                        : '' }} class="w-full p-2 border border-gray-300 rounded-md text-sm 
                            {{ $readonly_fields && !$mode_manual ? 'bg-gray-100' : '' }}"
                        placeholder="{{ $mode_manual ? 'Isi ' . strtolower($label) : '' }}">
                </div>
                @endforeach

                <div>
                    <label class="block text-sm font-medium">Tanggal Kontrak</label>
                    <input type="date" wire:model.live="tanggal_kontrak" {{ $readonly_fields && !$mode_manual
                        ? 'disabled' : '' }} class="w-full p-2 border border-gray-300 rounded-md text-sm 
                        {{ $readonly_fields && !$mode_manual ? 'bg-gray-100' : '' }}">
                </div>

                <div>
                    <label class="block text-sm font-medium">Tanggal Akhir Kontrak</label>
                    <input type="date" wire:model.live="tanggal_akhir_kontrak" {{ $readonly_fields && !$mode_manual
                        ? 'disabled' : '' }} class="w-full p-2 border border-gray-300 rounded-md text-sm 
                        {{ $readonly_fields && !$mode_manual ? 'bg-gray-100' : '' }}">
                </div>

                @if ($durasi_kontrak)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Durasi Kontrak</label>
                    <div class="p-2 border border-gray-300 bg-gray-100 rounded-md text-sm">
                        {{ $durasi_kontrak }}
                    </div>
                </div>
                @endif

                @if ($mode_manual)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Metode Pengadaan</label>
                    <select wire:model.live="metode_id" class="w-full p-2 border border-gray-300 rounded-md text-sm">
                        <option value="">-- Pilih Metode --</option>
                        @foreach ($metodes as $metode)
                        <option value="{{ $metode->id }}">{{ $metode->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <x-card title="Upload Dokumen">
            <livewire:upload-surat-kontrak />
        </x-card>

        <x-card title="Tambah Barang">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium">Nama Barang</label>
                    <livewire:searchable-select wire:model.live="barang_id" :options="$barangs"
                        placeholder="Ketik atau pilih nama barang..." />
                </div>

                <div>
                    <label class="block text-sm font-medium">Jumlah</label>
                    <input type="number" wire:model.live="jumlah"
                        class="w-full p-2 border border-gray-300 rounded-md text-sm" placeholder="Jumlah" min="1">
                </div>

                <div <label class="block text-sm font-medium">Satuan</label>
                    <livewire:searchable-select wire:model.live="newSatuan" :options="$satuanOptions"
                        placeholder="Ketik atau pilih satuan..." />
                </div>

                <div x-data="{
                formatted: '',
                get raw() {
                    let val = @entangle('newHarga').live;
                    return val ? String(val) : '';
                },
                set raw(value) {
                    const number = String(value ?? '').replace(/[^0-9]/g, '');
                    this.formatted = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                    $wire.set('newHarga', number);
                },
                init() {
                    this.raw = this.raw;
                    // Listen untuk reset dari Livewire
                    Livewire.on('reset-harga-field', () => {
                        this.formatted = '';
                    });
                    // Watch perubahan newHarga dari Livewire
                    this.$watch('$wire.newHarga', (value) => {
                        if (!value || value === '' || value === 0) {
                            this.formatted = '';
                        }
                    });
                }
            }">
                    <label class="block text-sm font-medium">Harga Satuan</label>
                    <input type="text" x-model="formatted" @input="raw = $event.target.value"
                        class="w-full p-2 border border-gray-300 rounded-md text-sm" placeholder="Rp 0">
                </div>

                <div>
                    <label class="block text-sm font-medium">PPN</label>
                    <select wire:model.live="newPpn" class="w-full p-2 border border-gray-300 rounded-md text-sm">
                        <option value="0">Termasuk PPN</option>
                        <option value="11">PPN 11%</option>
                        <option value="12">PPN 12%</option>
                    </select>
                </div>


                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <livewire:searchable-select wire:model.live="specifications.nama" :options="$specNamaOptions"
                        placeholder="Ketik atau pilih nama..." />
                </div>

                <div>
                    <label class="block text-sm font-medium">Tipe</label>
                    <livewire:searchable-select wire:model.live="specifications.tipe" :options="$specTipeOptions"
                        placeholder="Ketik atau pilih tipe..." />
                </div>

                <div>
                    <label class="block text-sm font-medium">Ukuran</label>
                    <livewire:searchable-select wire:model.live="specifications.ukuran" :options="$specUkuranOptions"
                        placeholder="Ketik atau pilih ukuran..." />
                </div>

            </div>

            <div class="flex justify-end">
                @if ($barang_id && $newSatuan && $jumlah && $newHarga)
                <button wire:click="addToList"
                    class="mt-2 bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700 transition">
                    <i class="fa fa-plus mr-1"></i> Tambah ke Daftar Barang
                </button>
                @else
                <span class="text-sm text-gray-500 mt-2">Lengkapi semua data sebelum menambahkan</span>
                @endif
            </div>
        </x-card>
    </div>

    <x-card title="Daftar Barang" class="mt-6">
        <table class="min-w-full text-sm border border-gray-300">
            <thead class="bg-primary-700 text-white">
                <tr>
                    <th class="p-2 text-left">Barang</th>
                    <th class="p-2 text-left">Spesifikasi</th>
                    <th class="p-2 text-center">Jumlah</th>
                    @if ($isAdendum)
                    <th class="p-2 text-center">Jumlah Terkirim</th>
                    @endif
                    <th class="p-2 text-right">Harga</th>
                    <th class="p-2 text-center">PPN</th>
                    <th class="p-2 text-right">Total</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if (count($list) > 0)
                @foreach ($list as $index => $item)
                @php
                $harga = (int) str_replace('.', '', $item['harga']);
                $subtotal = $harga * $item['jumlah'];
                $ppn = $item['ppn'] ? ($subtotal * ((int) $item['ppn'] / 100)) : 0;
                $total = $subtotal + $ppn;
                @endphp
                <tr class="bg-white border-b hover:bg-gray-50">
                    @php
                    $barangName = $item['barang'];
                    if (is_numeric($barangName)) {
                    $found = collect($barangs ?? [])->firstWhere('id', (int) $barangName);
                    if ($found) {
                    $barangName = $found->nama ?? $found['name'] ?? $found['label'] ?? $barangName;
                    }
                    }
                    @endphp
                    <td class="p-2">{{ $barangName }}</td>
                    <td class="p-2">{{ collect($item['specifications'])->filter()->implode(', ') }}</td>
                    <td class="p-2 text-center">{{ $item['jumlah'] }} {{ $item['satuan'] }}</td>
                    @if ($isAdendum)
                    <td class="p-2 text-center">{{ $item['jumlah_terkirim'] }} {{ $item['satuan'] }}</td>
                    @endif
                    <td class="p-2 text-right">Rp {{ number_format($harga, 0, '', '.') }}</td>
                    <td class="p-2 text-center">{{ $item['ppn'] == 0 ? 'Termasuk PPN' : $item['ppn'] . '%' }}</td>
                    <td class="p-2 text-right font-semibold">Rp {{ number_format($total, 0, '', '.') }}</td>
                    <td class="p-2 text-center">
                        @if (!empty($item['can_delete']) && $item['can_delete'])
                        <button wire:click="removeFromList({{ $index }})" class="text-red-600 hover:text-red-800">
                            <i class="fa fa-trash"></i>
                        </button>
                        @elseif (!empty($item['readonly']))
                        <span class="text-xs text-gray-800 font-semibold italic">Barang sudah selesai
                            dikirim</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="{{ $isAdendum ? 8 : 7 }}" class="text-center p-4 text-gray-500">
                        Belum ada barang ditambahkan
                    </td>
                </tr>
                @endif
                @if (count($list) > 0)
                <tr class="bg-gray-100 font-bold">
                    <td colspan="{{ $isAdendum ? 6 : 5 }}" class="p-2 text-right">TOTAL</td>
                    <td class="p-2 text-right">Rp {{ $nominal_kontrak }}</td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>
    </x-card>

    <div class="flex justify-center mt-6">
        <button wire:click="saveKontrak" @disabled(!$hasil_cari_api)
            class="bg-primary-600 text-white px-5 py-2 rounded hover:bg-primary-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
            <i class="fa fa-save mr-2"></i> Simpan Kontrak
        </button>
    </div>
</div>

@push('scripts')
<script>
    // Event listener untuk kontrak tidak ditemukan
        Livewire.on('kontrak-tidak-ditemukan', function () {
            Swal.fire({
                title: 'Kontrak Tidak Ditemukan',
                text: 'Nomor tidak terdaftar pada EMonev, isi manual?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.lanjutkanModeManual();
                } else {
                    @this.batalkanPencarian();
                }
            });
        });

        Livewire.on('saveDokumen', function ({ kontrak_id }) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Kontrak berhasil disimpan.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = `/kontrak-vendor-stok/${kontrak_id}`;
            });
        });

        Livewire.on('alert', function (e) {
            var { type, message } = e[0]
            Swal.fire({
                title: type === 'error' ? 'Gagal' : (type === 'warning' ? 'Perhatian' : 'Informasi'),
                text: message,
                icon: type,
                confirmButtonText: 'Tutup'
            });
        });
</script>
@endpush