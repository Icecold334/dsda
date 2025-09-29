<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DetailPermintaanMaterial;
use App\Models\PermintaanMaterial;
use App\Models\UnitKerja;
use App\Models\KategoriStok;
use App\Models\LokasiStok;
use App\Models\Rab;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Driver;

class AdminEditPermintaan extends Component
{
    public $permintaan;
    public $adminEditMode = true;

    // Form fields from detail_permintaan_material
    public $nodin;
    public $tanggal_permintaan;
    public $withRab = 0;
    public $rab_id;
    public $namaKegiatan;
    public $gudang_id;
    public $kecamatan_id;
    public $kelurahan_id;
    public $lokasi;
    public $p, $l, $k;
    public $keterangan;

    // Transport and document fields
    public $driver;
    public $nopol;
    public $security;
    public $ttd_driver;
    public $ttd_security;
    public $suratJalan;
    public $sppb;
    public $spb_path;

    // Additional fields from create form
    public $lokasiMaterial;
    public $KontakPerson;

    // Additional fields
    public $unit_id; // Read only - from original permintaan
    public $unit_nama; // Display name

    // Dropdown data
    public $unitOptions = [];
    public $gudangOptions = [];
    public $rabOptions = [];
    public $kecamatanOptions = [];
    public $kelurahanOptions = [];
    public $driverOptions = [];

    // Admin properties
    public $adminReason = '';
    public $showSaveModal = false;

    protected $rules = [
        'nodin' => 'required|string|max:255',
        'tanggal_permintaan' => 'required|date',
        'gudang_id' => 'required|exists:lokasi_stok,id',
        'lokasi' => 'nullable|string|max:500',
        'lokasiMaterial' => 'nullable|string|max:500',
        'KontakPerson' => 'nullable|string|max:500',
        'keterangan' => 'required|string',
        'p' => 'nullable|numeric|min:0',
        'l' => 'nullable|numeric|min:0',
        'k' => 'nullable|numeric|min:0',
        'driver' => 'nullable|string|max:255',
        'nopol' => 'nullable|string|max:50',
        'security' => 'nullable|string|max:255',
        'ttd_driver' => 'nullable|string|max:255',
        'ttd_security' => 'nullable|string|max:255',
        'suratJalan' => 'nullable|string|max:255',
        'sppb' => 'nullable|string|max:255',
        'spb_path' => 'nullable|string|max:255',
        'adminReason' => 'required|string|max:500'
    ];

    public function mount($permintaan)
    {
        // Check admin access
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && $user->unit_id !== null) {
            abort(403, 'Unauthorized access. Admin only.');
        }

        $this->permintaan = $permintaan;
        $this->loadFormData();
        $this->loadDropdownData();
    }

    private function loadFormData()
    {
        $this->nodin = $this->permintaan->nodin;

        // Handle tanggal_permintaan safely
        if ($this->permintaan->tanggal_permintaan) {
            // If it's already a Carbon instance
            if ($this->permintaan->tanggal_permintaan instanceof \Carbon\Carbon) {
                $this->tanggal_permintaan = $this->permintaan->tanggal_permintaan->format('Y-m-d');
            } else {
                // If it's a string, convert to Carbon first
                $this->tanggal_permintaan = \Carbon\Carbon::parse($this->permintaan->tanggal_permintaan)->format('Y-m-d');
            }
        } else {
            $this->tanggal_permintaan = null;
        }

        $this->withRab = $this->permintaan->rab_id ? 1 : 0;
        $this->rab_id = $this->permintaan->rab_id;
        $this->namaKegiatan = $this->permintaan->nama; // Assuming this is the field name
        $this->gudang_id = $this->permintaan->gudang_id;
        $this->kelurahan_id = $this->permintaan->kelurahan_id;
        $this->kecamatan_id = $this->permintaan->kelurahan ? $this->permintaan->kelurahan->kecamatan_id : null;
        $this->lokasi = $this->permintaan->lokasi;
        $this->p = $this->permintaan->p;
        $this->l = $this->permintaan->l;
        $this->k = $this->permintaan->k;
        $this->keterangan = $this->permintaan->keterangan;

        // Transport and document fields
        $this->driver = $this->permintaan->driver;
        $this->nopol = $this->permintaan->nopol;
        $this->security = $this->permintaan->security;
        $this->ttd_driver = $this->permintaan->ttd_driver;
        $this->ttd_security = $this->permintaan->ttd_security;
        $this->suratJalan = $this->permintaan->suratJalan;
        $this->sppb = $this->permintaan->sppb;
        $this->spb_path = $this->permintaan->spb_path;

        // Additional fields
        $this->lokasiMaterial = $this->permintaan->lokasi; // Sometimes stored here
        $this->KontakPerson = $this->permintaan->kontak_person;

        // Unit is readonly - from original permintaan user
        $this->unit_id = $this->permintaan->user->unit_id;
        $this->unit_nama = $this->permintaan->user->unitKerja->nama ?? 'N/A';
    }

    private function loadDropdownData()
    {
        // Load gudang options based on original unit
        $this->gudangOptions = LokasiStok::where('unit_id', $this->unit_id)
            ->whereHas('transaksiStok', function ($query) {
                $query->whereHas('merkStok.barangStok', function ($q) {
                    $q->where('jenis_id', 1); // Material only
                });
            })
            ->get();

        // Load RAB options for the unit
        $this->rabOptions = Rab::where('status', 2)
            ->whereHas('user.unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Load kecamatan options
        $this->kecamatanOptions = Kecamatan::where('unit_id', $this->unit_id)->get();

        // Load kelurahan if kecamatan is selected
        if ($this->kecamatan_id) {
            $this->kelurahanOptions = Kelurahan::where('kecamatan_id', $this->kecamatan_id)->get();
        }

        // Load driver options for the unit
        $this->driverOptions = Driver::where('unit_id', $this->unit_id)->get();
    }

    public function updatedRabId()
    {
        if ($this->rab_id) {
            $rab = Rab::find($this->rab_id);
            if ($rab) {
                $this->namaKegiatan = $rab->nama;
            }
        }
    }

    public function updatedKecamatanId()
    {
        $this->kelurahan_id = null;
        if ($this->kecamatan_id) {
            $this->kelurahanOptions = Kelurahan::where('kecamatan_id', $this->kecamatan_id)->get();
        } else {
            $this->kelurahanOptions = [];
        }
    }

    public function updatedWithRab()
    {
        if (!$this->withRab) {
            $this->rab_id = null;
            $this->namaKegiatan = null;
        }
    }

    public function confirmSave()
    {
        $rulesWithoutReason = $this->rules;
        unset($rulesWithoutReason['adminReason']);
        $this->validate($rulesWithoutReason);
        $this->showSaveModal = true;
    }

    public function cancelSave()
    {
        $this->adminReason = '';
        $this->showSaveModal = false;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $originalData = $this->permintaan->toArray();

                // Update permintaan data
                $updateData = [
                    'nodin' => $this->nodin,
                    'tanggal_permintaan' => $this->tanggal_permintaan,
                    'gudang_id' => $this->gudang_id,
                    'kelurahan_id' => $this->kelurahan_id,
                    'lokasi' => $this->lokasi,
                    'keterangan' => $this->keterangan,
                    'p' => $this->p,
                    'l' => $this->l,
                    'k' => $this->k,
                    'driver' => $this->driver,
                    'nopol' => $this->nopol,
                    'security' => $this->security,
                    'ttd_driver' => $this->ttd_driver,
                    'ttd_security' => $this->ttd_security,
                    'suratJalan' => $this->suratJalan,
                    'sppb' => $this->sppb,
                    'spb_path' => $this->spb_path,
                    'kontak_person' => $this->KontakPerson,
                ];

                // Handle RAB assignment
                if ($this->withRab && $this->rab_id) {
                    $updateData['rab_id'] = $this->rab_id;
                } else {
                    $updateData['rab_id'] = null;
                }

                $this->permintaan->update($updateData);

                // Log admin action
                \Log::info('Admin updated permintaan', [
                    'admin_id' => auth()->id(),
                    'admin_name' => auth()->user()->name,
                    'permintaan_id' => $this->permintaan->id,
                    'permintaan_nodin' => $this->permintaan->nodin,
                    'original_user_id' => $this->permintaan->user_id,
                    'reason' => $this->adminReason,
                    'original_data' => $originalData,
                    'updated_data' => $this->permintaan->fresh()->toArray(),
                    'updated_at' => now()
                ]);
            });

            session()->flash('success', 'Permintaan berhasil diperbarui oleh admin. Alasan: ' . $this->adminReason);
            $this->cancelSave();

            // Refresh data
            $this->permintaan = $this->permintaan->fresh();

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui permintaan: ' . $e->getMessage());
            $this->cancelSave();
        }
    }

    public function render()
    {
        return view('livewire.admin-edit-permintaan');
    }
}
