<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Rab;
use App\Models\User;
use Livewire\Component;
use App\Models\ListRab;
use App\Models\MerkStok;
use App\Models\BarangStok;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use Livewire\Attributes\On;
use App\Models\PermintaanMaterial;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailPermintaanMaterial;
use App\Models\StokDisetujui;
use Illuminate\Support\Facades\DB;

class EditFormPermintaanMaterial extends Component
{
    public $permintaan;
    public $tanggal_permintaan;
    public $keterangan;
    public $gudang_id;
    public $gudangs = [];
    public $rab;
    public $availableRabs = [];
    public $nodin;
    public $jenisPekerjaan;
    public $lokasiMaterial;
    public $kelurahan_id;
    public $kecamatan_id;
    public $kecamatans = [];
    public $kelurahans = [];
    public $isSeribu;
    public $unit_id;
    public $dokumenCount = 0;

    // Volume pekerjaan
    public $p;
    public $l;
    public $k;

    // List items
    public $list = [];
    public $barangs = [];
    public $merks = [];
    public $newBarangId;
    public $newMerkId;
    public $newJumlah;
    public $newMerkMax; // Maksimal jumlah berdasarkan RAB
    public $newUnit = 'Satuan';
    public $newKeterangan;
    public $newRabId;
    public $originalJumlah = []; // Track original quantities for change detection

    protected $rules = [
        'tanggal_permintaan' => 'required|date',
        'keterangan' => 'nullable|string',
        'gudang_id' => 'required|exists:lokasi_stok,id',
        'nodin' => 'nullable|string',
        'jenisPekerjaan' => 'nullable|string',
        'lokasiMaterial' => 'nullable|string',
        'kelurahan_id' => 'nullable|exists:kelurahans,id',
        'p' => 'nullable|numeric|min:0',
        'l' => 'nullable|numeric|min:0',
        'k' => 'nullable|numeric|min:0',
        'list.*.merk_id' => 'required|exists:merk_stok,id',
        'list.*.jumlah' => 'required|numeric|min:1',
        'list.*.keterangan' => 'nullable|string',
    ];

    public function mount()
    {
        // Load data dari permintaan yang akan diedit
        $this->tanggal_permintaan = Carbon::parse($this->permintaan->tanggal_permintaan)->format('Y-m-d');
        $this->keterangan = $this->permintaan->keterangan;
        $this->gudang_id = $this->permintaan->gudang_id;
        $this->rab = $this->permintaan->rab_id ? Rab::find($this->permintaan->rab_id) : null;
        $this->nodin = $this->permintaan->nodin;
        $this->jenisPekerjaan = $this->permintaan->nama;
        $this->lokasiMaterial = $this->permintaan->lokasi;

        // Set kelurahan dan kecamatan berdasarkan RAB atau permintaan
        if ($this->rab && $this->rab->kelurahan_id) {
            // Jika ada RAB, ambil dari RAB
            $this->kelurahan_id = $this->rab->kelurahan_id;
            // Auto-set kecamatan dari kelurahan (parent relationship)
            $kelurahan = Kelurahan::find($this->rab->kelurahan_id);
            $this->kecamatan_id = $kelurahan ? $kelurahan->kecamatan_id : null;
        } else {
            // Jika tidak ada RAB, ambil dari permintaan
            $this->kelurahan_id = $this->permintaan->kelurahan_id;
            $this->kecamatan_id = $this->permintaan->kecamatan_id;
        }

        $this->unit_id = $this->permintaan->user->unit_id;

        // Load volume pekerjaan
        $this->p = $this->permintaan->p;
        $this->l = $this->permintaan->l;
        $this->k = $this->permintaan->k;

        // Set isSeribu based on unit
        $this->isSeribu = $this->permintaan->user->unitKerja->nama === 'Suku Dinas Kepulauan Seribu';

        // Load related data - pastikan dimuat dalam urutan yang benar
        $this->loadGudangs();
        $this->loadAvailableRabs();
        $this->loadKecamatans();
        if ($this->kelurahan_id || $this->kecamatan_id) {
            $this->loadKelurahans();
        }
        $this->loadExistingItems();
        $this->fillBarangs();

        // Debug info untuk memastikan data terload
        if (empty($this->gudangs)) {
            session()->flash('warning', 'Tidak ada gudang yang tersedia untuk unit kerja ini.');
        }
    }

    private function loadGudangs()
    {
        // Load semua gudang untuk unit ini terlebih dahulu
        $this->gudangs = LokasiStok::where('unit_id', $this->unit_id)
            ->orderBy('nama', 'asc')
            ->get();

        // Jika tidak ada gudang untuk unit ini, load semua gudang
        if ($this->gudangs->isEmpty()) {
            $this->gudangs = LokasiStok::orderBy('nama', 'asc')->get();
        }
    }

    private function loadAvailableRabs()
    {
        // Load RABs untuk keperluan display di Kepulauan Seribu
        if ($this->isSeribu) {
            $this->availableRabs = Rab::where('status', 2)
                ->whereHas('user.unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    private function loadKecamatans()
    {
        // Load semua kecamatan untuk unit ini terlebih dahulu
        $this->kecamatans = Kecamatan::where('unit_id', $this->unit_id)
            ->orderBy('kecamatan', 'asc')
            ->get();

        // Jika tidak ada kecamatan untuk unit ini, load semua kecamatan
        if ($this->kecamatans->isEmpty()) {
            $this->kecamatans = Kecamatan::orderBy('kecamatan', 'asc')->get();
        }
    }

    private function loadKelurahans()
    {
        if ($this->kecamatan_id) {
            $this->kelurahans = Kelurahan::where('kecamatan_id', $this->kecamatan_id)->get();
        } elseif ($this->kelurahan_id) {
            $kelurahan = Kelurahan::find($this->kelurahan_id);
            if ($kelurahan) {
                $this->kecamatan_id = $kelurahan->kecamatan_id;
                $this->kelurahans = Kelurahan::where('kecamatan_id', $this->kecamatan_id)->get();
            }
        }
    }

    private function loadExistingItems()
    {
        $this->list = $this->permintaan->permintaanMaterial->map(function ($item) {
            return [
                'id' => $item->id,
                'merk_id' => $item->merk_id,
                'merk' => $item->merkStok,
                'jumlah' => $item->jumlah,
                'unit' => $item->unit,
                'keterangan' => $item->keterangan,
                'rab_id' => $item->rab_id,
            ];
        })->toArray();

        // Store original quantities for change detection
        $this->originalJumlah = [];
        foreach ($this->list as $index => $item) {
            $this->originalJumlah[$index] = $item['jumlah'];
        }
    }

    private function fillBarangs()
    {
        // Dapatkan barang yang sudah ada di list
        $usedBarangIds = collect($this->list)->map(function ($item) {
            return $item['merk']->barang_id ?? null;
        })->filter()->unique()->toArray();

        if ($this->rab) {
            // Jika menggunakan RAB, ambil semua barang dari RAB
            // tanpa peduli stok di gudang (validasi stok dilakukan di level input)
            $rabItems = \App\Models\ListRab::with(['merkStok.barangStok'])
                ->where('rab_id', $this->rab->id)
                ->whereHas('merkStok.barangStok', function ($q) {
                    $q->where('jenis_id', 1); // hanya material
                })
                ->get();

            $barangIds = $rabItems->pluck('merkStok.barangStok.id')->unique()->filter();
            $this->barangs = \App\Models\BarangStok::whereIn('id', $barangIds)
                ->whereNotIn('id', $usedBarangIds)
                ->get();
        } else {
            // Jika tidak menggunakan RAB, gunakan logika berdasarkan stok gudang
            $this->barangs = BarangStok::where('jenis_id', 1)
                ->whereNotIn('id', $usedBarangIds)
                ->whereHas('merkStok', function ($merk) {
                    $merk->whereHas('transaksiStok', function ($transaksi) {
                        $transaksi->where('lokasi_id', $this->gudang_id ?? 0)
                            ->where('jumlah', '>', 0);
                    });
                })
                ->get();
        }
    }

    public function updatedGudangId()
    {
        // Reset form tambah item saat gudang berubah
        $this->reset(['newBarangId', 'newMerkId', 'newJumlah', 'newKeterangan', 'newRabId']);
        $this->newUnit = 'Satuan';
        $this->newMerkMax = null;
        $this->merks = [];

        $this->fillBarangs();
    }

    public function updatedNewBarangId()
    {
        if ($this->newBarangId) {
            // Dapatkan merk yang sudah ada di list untuk barang ini
            $usedMerkIds = collect($this->list)->filter(function ($item) {
                return isset($item['merk']->barang_id) && $item['merk']->barang_id == $this->newBarangId;
            })->pluck('merk_id')->toArray();

            if ($this->rab) {
                // Jika menggunakan RAB, ambil merk dari RAB yang sesuai dengan barang yang dipilih
                $rabItems = \App\Models\ListRab::with(['merkStok'])
                    ->where('rab_id', $this->rab->id)
                    ->whereHas('merkStok', function ($q) {
                        $q->where('barang_id', $this->newBarangId);
                    })
                    ->get();

                $merkIds = $rabItems->pluck('merk_id')->unique()->filter();
                $this->merks = MerkStok::whereIn('id', $merkIds)
                    ->whereNotIn('id', $usedMerkIds)
                    ->get();
            } else {
                // Jika tidak menggunakan RAB, gunakan logika berdasarkan stok gudang
                $this->merks = MerkStok::where('barang_id', $this->newBarangId)
                    ->whereNotIn('id', $usedMerkIds)
                    ->whereHas('transaksiStok', function ($transaksi) {
                        $transaksi->where('lokasi_id', $this->gudang_id)
                            ->where('jumlah', '>', 0);
                    })
                    ->get();
            }
        } else {
            $this->merks = [];
        }
        $this->reset(['newMerkId', 'newJumlah']);
        $this->newMerkMax = null;
    }

    public function updatedNewMerkId()
    {
        if ($this->newMerkId) {
            $merk = MerkStok::find($this->newMerkId);
            $this->newUnit = $merk->barangStok->satuanBesar->nama ?? 'Satuan';

            // Jika menggunakan RAB, ambil maksimal jumlah dari RAB
            if ($this->rab) {
                $rabItem = ListRab::where('rab_id', $this->rab->id)
                    ->where('merk_id', $this->newMerkId)
                    ->first();

                if ($rabItem) {
                    // Hitung jumlah yang sudah digunakan untuk merk ini
                    $jumlahTerpakai = collect($this->list)->where('merk_id', $this->newMerkId)->sum('jumlah');
                    $this->newMerkMax = max(0, $rabItem->jumlah - $jumlahTerpakai);
                } else {
                    $this->newMerkMax = 0;
                }
            } else {
                $this->newMerkMax = null; // Tidak ada limit jika tanpa RAB
            }
        } else {
            $this->newUnit = 'Satuan';
            $this->newMerkMax = null;
        }
        $this->newJumlah = null;
    }

    public function updatedKecamatanId()
    {
        $this->kelurahans = Kelurahan::where('kecamatan_id', $this->kecamatan_id)->get();
        $this->kelurahan_id = null;
    }

    public function updatedp()
    {
        // Auto calculate if needed
    }

    public function updatedl()
    {
        // Auto calculate if needed
    }

    public function updatedk()
    {
        // Auto calculate if needed
    }

    #[On('dokumenCount')]
    public function fillDokumenCount($count)
    {
        $this->dokumenCount = $count;
    }

    public function addToList()
    {
        // Validasi dasar
        $rules = [
            'newMerkId' => 'required|exists:merk_stok,id',
            'newJumlah' => 'required|numeric|min:1',
        ];

        // Jika menggunakan RAB, tambahkan validasi maksimal
        if ($this->rab && $this->newMerkMax !== null) {
            $rules['newJumlah'] .= '|max:' . $this->newMerkMax;
        }

        $this->validate($rules);

        // Jika menggunakan RAB, lakukan pengecekan manual juga
        if ($this->rab && $this->newMerkMax !== null && $this->newJumlah > $this->newMerkMax) {
            $this->addError('newJumlah', 'Jumlah yang diminta melebihi sisa yang tersedia di RAB (maksimal: ' . $this->newMerkMax . ')');
            return;
        }

        $merk = MerkStok::find($this->newMerkId);

        $this->list[] = [
            'id' => null, // null for new items
            'merk_id' => $this->newMerkId,
            'merk' => $merk,
            'jumlah' => $this->newJumlah,
            'unit' => $this->newUnit,
            'keterangan' => $this->newKeterangan,
            'rab_id' => $this->newRabId,
        ];

        // Add originalJumlah entry for new item
        $newIndex = count($this->list) - 1;
        $this->originalJumlah[$newIndex] = $this->newJumlah;

        // Reset semua field form tambah item
        $this->reset(['newBarangId', 'newMerkId', 'newJumlah', 'newKeterangan', 'newRabId']);
        $this->newUnit = 'Satuan';
        $this->newMerkMax = null;
        $this->barangs = [];
        $this->merks = [];

        // Refresh dropdown setelah menambah item
        $this->fillBarangs();

        session()->flash('success', 'Item berhasil ditambahkan ke daftar permintaan.');
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);

        // Update originalJumlah array after reindexing
        $this->originalJumlah = [];
        foreach ($this->list as $i => $item) {
            $this->originalJumlah[$i] = $item['jumlah'];
        }

        // Reset form tambah item
        $this->reset(['newBarangId', 'newMerkId', 'newJumlah', 'newKeterangan', 'newRabId']);
        $this->newUnit = 'Satuan';
        $this->newMerkMax = null;
        $this->merks = [];

        // Refresh dropdown setelah menghapus item
        $this->fillBarangs();

        session()->flash('success', 'Item berhasil dihapus dari daftar permintaan.');
    }

    public function saveItemChange($index)
    {
        // Validasi dasar
        $rules = [
            "list.{$index}.jumlah" => 'required|numeric|min:1',
        ];

        // Jika menggunakan RAB, validasi maksimal
        if ($this->rab) {
            $maxAllowed = $this->getMaxJumlahForItem($index);
            if ($maxAllowed !== null && $this->list[$index]['jumlah'] > $maxAllowed) {
                $this->addError("list.{$index}.jumlah", 'Jumlah melebihi sisa yang tersedia di RAB (maksimal: ' . $maxAllowed . ')');
                return;
            }
        }

        $this->validate($rules);

        // Update original quantity to current quantity (mark as saved)
        $this->originalJumlah[$index] = $this->list[$index]['jumlah'];

        session()->flash('success', 'Perubahan berhasil disimpan.');
    }

    /**
     * Hitung maksimal jumlah yang diperbolehkan untuk item tertentu berdasarkan RAB
     */
    public function getMaxJumlahForItem($index)
    {
        if (!$this->rab || !isset($this->list[$index])) {
            return null;
        }

        $merkId = $this->list[$index]['merk_id'];
        $currentJumlah = $this->originalJumlah[$index] ?? 0; // Jumlah asli sebelum edit

        // Cari item di RAB
        $rabItem = ListRab::where('rab_id', $this->rab->id)
            ->where('merk_id', $merkId)
            ->first();

        if (!$rabItem) {
            return 0;
        }

        // Hitung total yang sudah digunakan untuk merk ini (kecuali item yang sedang diedit)
        $jumlahTerpakai = collect($this->list)
            ->where('merk_id', $merkId)
            ->reject(function ($item, $idx) use ($index) {
                return $idx == $index; // Abaikan item yang sedang diedit
            })
            ->sum('jumlah');

        // Kembalikan sisa yang tersedia + jumlah asli item ini
        return max(0, $rabItem->jumlah - $jumlahTerpakai);
    }

    public function hasChanges($index)
    {
        return isset($this->originalJumlah[$index]) &&
            $this->originalJumlah[$index] != $this->list[$index]['jumlah'];
    }

    private function isMerkTersebar($merkId)
    {
        $lokasiId = $this->gudang_id;

        $transaksis = \App\Models\TransaksiStok::where('merk_id', $merkId)
            ->where(function ($q) use ($lokasiId) {
                $q->where('lokasi_id', $lokasiId)
                    ->orWhereHas('bagianStok', fn($q) => $q->where('lokasi_id', $lokasiId))
                    ->orWhereHas('posisiStok.bagianStok', fn($q) => $q->where('lokasi_id', $lokasiId));
            })
            ->get();

        $stokBagian = $transaksis->whereNull('posisi_id')->whereNotNull('bagian_id');
        $stokPosisi = $transaksis->whereNotNull('posisi_id');

        $jumlahBagian = $stokBagian->reduce(function ($carry, $trx) {
            return $carry + match ($trx->tipe) {
                'Pemasukan' => (int) $trx->jumlah,
                'Pengeluaran' => -(int) $trx->jumlah,
                'Penyesuaian' => (int) $trx->jumlah,
                default => 0,
            };
        }, 0);

        $jumlahPosisi = $stokPosisi->reduce(function ($carry, $trx) {
            return $carry + match ($trx->tipe) {
                'Pemasukan' => (int) $trx->jumlah,
                'Pengeluaran', 'Pengajuan' => -(int) $trx->jumlah,
                'Penyesuaian' => (int) $trx->jumlah,
                default => 0,
            };
        }, 0);

        return ($jumlahBagian + $jumlahPosisi) > 0;
    }

    public function updateData()
    {
        // Hanya bisa update jika status draft
        if ($this->permintaan->status !== 4 && $this->permintaan->status !== null) {
            session()->flash('error', 'Hanya permintaan dengan status Draft yang dapat diedit.');
            return;
        }

        $this->validate();

        if (empty($this->list)) {
            session()->flash('error', 'Minimal harus ada satu item permintaan.');
            return;
        }

        try {
            // Update detail permintaan material
            $this->permintaan->update([
                'tanggal_permintaan' => strtotime($this->tanggal_permintaan),
                'keterangan' => $this->keterangan,
                'gudang_id' => $this->gudang_id,
                'rab_id' => $this->rab ? $this->rab->id : null,
                'nodin' => $this->nodin,
                'nama' => $this->jenisPekerjaan,
                'lokasi' => $this->lokasiMaterial,
                'kelurahan_id' => $this->kelurahan_id,
                'kecamatan_id' => $this->kecamatan_id,
                'p' => $this->p,
                'l' => $this->l,
                'k' => $this->k,
            ]);

            // Hapus item lama
            $this->permintaan->permintaanMaterial()->delete();

            // Tambah item baru
            foreach ($this->list as $item) {
                PermintaanMaterial::create([
                    'detail_permintaan_id' => $this->permintaan->id,
                    'user_id' => $this->permintaan->user_id,
                    'merk_id' => $item['merk_id'],
                    'jumlah' => $item['jumlah'],
                    'rab_id' => $item['rab_id'],
                ]);
            }

            session()->flash('success', 'Permintaan material berhasil diperbarui.');

            // Save documents if any
            $this->dispatch('saveDokumen', kontrak_id: $this->permintaan->id, isRab: false, isMaterial: true);

            // Redirect ke halaman show
            return redirect()->route('showPermintaan', ['tipe' => 'material', 'id' => $this->permintaan->id]);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function submitData()
    {
        $this->validate();

        if (empty($this->list)) {
            session()->flash('error', 'Minimal harus ada satu item permintaan.');
            return;
        }

        try {

            DB::beginTransaction();
            // Update detail permintaan material dan ubah status ke diproses
            $this->permintaan->update([
                'tanggal_permintaan' => strtotime($this->tanggal_permintaan),
                'keterangan' => $this->keterangan,
                'gudang_id' => $this->gudang_id,
                'rab_id' => $this->rab ? $this->rab->id : null,
                'nodin' => $this->nodin,
                'nama' => $this->jenisPekerjaan,
                'lokasi' => $this->lokasiMaterial,
                'kelurahan_id' => $this->kelurahan_id,
                'kecamatan_id' => $this->kecamatan_id,
                'p' => $this->p,
                'l' => $this->l,
                'k' => $this->k,
                'status' => null, // Set ke diproses
            ]);

            // Hapus item lama
            $this->permintaan->permintaanMaterial()->delete();

            // Tambah item baru
            foreach ($this->list as $item) {
                $merkId = $item['merk_id'];

                // 1. Simpan ke permintaan_material
                $pm = PermintaanMaterial::create([
                    'detail_permintaan_id' => $this->permintaan->id,
                    'user_id' => $this->permintaan->user_id,
                    'merk_id' => $merkId,
                    'jumlah' => $item['jumlah'],
                    'rab_id' => $item['rab_id'],
                    'alocated' => !$this->isMerkTersebar($merkId) ? 1 : 0,
                    'deskripsi' => $item['keterangan'] ?? null,
                ]);

                // 2. PENTING: Buat transaksi stok (tipe: Pengajuan)
                \App\Models\TransaksiStok::create([
                    'kode_transaksi_stok' => fake()->unique()->numerify('TRX#####'),
                    'permintaan_id' => $pm->id,
                    'tipe' => 'Pengajuan',
                    'merk_id' => $merkId,
                    'jumlah' => $item['jumlah'],
                    'lokasi_id' => $this->gudang_id,
                    'bagian_id' => null,
                    'posisi_id' => null,
                    'user_id' => $this->permintaan->user_id,
                    'tanggal' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 3. PENTING: Cek jika stok tidak tersebar → langsung buat StokDisetujui
                if (!$this->isMerkTersebar($merkId)) {
                    StokDisetujui::create([
                        'permintaan_id' => $pm->id,
                        'merk_id' => $merkId,
                        'lokasi_id' => $this->gudang_id,
                        'bagian_id' => null,
                        'posisi_id' => null,
                        'catatan' => 'Stok hanya tersedia di lokasi utama',
                        'jumlah_disetujui' => $item['jumlah'],
                    ]);
                }
            }

            // Logic untuk approval (seperti di ListPermintaanMaterial)
            $pemohon = $this->permintaan->user;
            $creatorRoles = $pemohon->roles->pluck('name')->toArray();
            $hasRab = $this->rab !== null;
            $isKasatpel = in_array('Kepala Satuan Pelaksana', $creatorRoles);

            // Tentukan role approval pertama
            if ($hasRab && $isKasatpel) {
                $firstRole = 'Kepala Seksi';
            } elseif ($hasRab && !$isKasatpel) {
                $firstRole = 'Kepala Suku Dinas';
            } elseif (!$hasRab && $isKasatpel) {
                $firstRole = 'Kepala Seksi';
            } else {
                $firstRole = 'Kepala Suku Dinas';
            }

            // Cari user untuk approval
            $approvalUser = User::whereHas('roles', function ($query) use ($firstRole) {
                $query->where('name', 'LIKE', '%' . $firstRole . '%');
            })
                ->whereHas('unitKerja', function ($unit) {
                    $unit->where('id', $this->unit_id);
                })
                ->first();

            // Kirim notifikasi jika ketemu
            if ($approvalUser) {
                $approvalUser->notify(new \App\Notifications\PermintaanMaterialNotification($this->permintaan));
            }

            DB::commit();

            session()->flash('success', 'Permintaan material berhasil disubmit untuk approval.');

            // Save documents if any
            $this->dispatch('saveDokumen', kontrak_id: $this->permintaan->id, isRab: false, isMaterial: true);

            // Redirect ke halaman show
            return redirect()->route('showPermintaan', ['tipe' => 'material', 'id' => $this->permintaan->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-form-permintaan-material');
    }
}
