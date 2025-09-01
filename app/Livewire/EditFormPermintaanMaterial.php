<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Rab;
use App\Models\User;
use Livewire\Component;
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

class EditFormPermintaanMaterial extends Component
{
    public $permintaan;
    public $tanggal_permintaan;
    public $keterangan;
    public $gudang_id;
    public $gudangs = [];
    public $withRab = 0;
    public $rab_id;
    public $rabs = [];
    public $rab;
    public $nodin;
    public $namaKegiatan;
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
    public $newUnit = 'Satuan';
    public $newKeterangan;
    public $newRabId;

    protected $rules = [
        'tanggal_permintaan' => 'required|date',
        'keterangan' => 'nullable|string',
        'gudang_id' => 'required|exists:lokasi_stok,id',
        'nodin' => 'nullable|string',
        'namaKegiatan' => 'nullable|string',
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
        $this->withRab = $this->permintaan->rab_id ? 1 : 0;
        $this->rab_id = $this->permintaan->rab_id;
        $this->nodin = $this->permintaan->nodin;
        $this->namaKegiatan = $this->permintaan->nama;
        $this->lokasiMaterial = $this->permintaan->lokasi;
        $this->kelurahan_id = $this->permintaan->kelurahan_id;
        $this->unit_id = $this->permintaan->user->unit_id;

        // Load volume pekerjaan
        $this->p = $this->permintaan->p;
        $this->l = $this->permintaan->l;
        $this->k = $this->permintaan->k;

        // Set isSeribu based on unit
        $this->isSeribu = $this->permintaan->user->unitKerja->nama === 'Suku Dinas Kepulauan Seribu';

        // Load related data - pastikan dimuat dalam urutan yang benar
        $this->loadGudangs();
        $this->loadRabs();
        $this->loadKecamatans();
        if ($this->kelurahan_id) {
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

    private function loadRabs()
    {
        $this->rabs = Rab::where('status', 2)
            ->whereHas('user.unitKerja', function ($unit) {
                $unit->where('id', $this->unit_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($this->rab_id) {
            $this->rab = Rab::find($this->rab_id);
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
        if ($this->kelurahan_id) {
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
    }

    private function fillBarangs()
    {
        if ($this->withRab && $this->rab_id > 0) {
            // Load barang dari RAB
            $barangIds = collect($this->rab->detailRab ?? [])->pluck('barang_id')->unique();
            $this->barangs = BarangStok::whereIn('id', $barangIds)->get();
        } else {
            // Load semua barang material
            $this->barangs = BarangStok::where('jenis_id', 1)
                ->whereHas('merkStok', function ($merk) {
                    $merk->whereHas('transaksiStok', function ($transaksi) {
                        $transaksi->where('lokasi_id', $this->gudang_id ?? 0)
                            ->where('jumlah', '>', 0);
                    });
                })
                ->get();
        }
    }

    public function updatedWithRab()
    {
        if (!$this->withRab) {
            $this->rab_id = null;
            $this->rab = null;
        }
        $this->fillBarangs();
    }

    public function updatedRabId()
    {
        $this->rab = $this->rab_id ? Rab::find($this->rab_id) : null;
        $this->fillBarangs();
    }

    public function updatedGudangId()
    {
        $this->fillBarangs();
        $this->reset(['newMerkId', 'newJumlah']);
    }

    public function updatedNewBarangId()
    {
        if ($this->newBarangId) {
            $this->merks = MerkStok::where('barang_id', $this->newBarangId)
                ->whereHas('transaksiStok', function ($transaksi) {
                    $transaksi->where('lokasi_id', $this->gudang_id)
                        ->where('jumlah', '>', 0);
                })
                ->get();
        } else {
            $this->merks = [];
        }
        $this->reset(['newMerkId', 'newJumlah']);
    }

    public function updatedNewMerkId()
    {
        if ($this->newMerkId) {
            $merk = MerkStok::find($this->newMerkId);
            $this->newUnit = $merk->barangStok->unit ?? 'Satuan';
        } else {
            $this->newUnit = 'Satuan';
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
        $this->validate([
            'newMerkId' => 'required|exists:merk_stok,id',
            'newJumlah' => 'required|numeric|min:1',
        ]);

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

        $this->reset(['newMerkId', 'newJumlah', 'newUnit', 'newKeterangan', 'newRabId']);
        $this->newUnit = 'Satuan';
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);
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
                'rab_id' => $this->withRab ? $this->rab_id : null,
                'nodin' => $this->nodin,
                'nama' => $this->namaKegiatan,
                'lokasi' => $this->lokasiMaterial,
                'kelurahan_id' => $this->kelurahan_id,
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
            // Update detail permintaan material dan ubah status ke diproses
            $this->permintaan->update([
                'tanggal_permintaan' => strtotime($this->tanggal_permintaan),
                'keterangan' => $this->keterangan,
                'gudang_id' => $this->gudang_id,
                'rab_id' => $this->withRab ? $this->rab_id : null,
                'nodin' => $this->nodin,
                'nama' => $this->namaKegiatan,
                'lokasi' => $this->lokasiMaterial,
                'kelurahan_id' => $this->kelurahan_id,
                'p' => $this->p,
                'l' => $this->l,
                'k' => $this->k,
                'status' => null, // Set ke diproses
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

            // Logic untuk approval (seperti di ListPermintaanMaterial)
            $pemohon = $this->permintaan->user;
            $creatorRoles = $pemohon->roles->pluck('name')->toArray();
            $hasRab = $this->permintaan->rab_id !== null;
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

            session()->flash('success', 'Permintaan material berhasil disubmit untuk approval.');

            // Save documents if any
            $this->dispatch('saveDokumen', kontrak_id: $this->permintaan->id, isRab: false, isMaterial: true);

            // Redirect ke halaman show
            return redirect()->route('showPermintaan', ['tipe' => 'material', 'id' => $this->permintaan->id]);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-form-permintaan-material');
    }
}
