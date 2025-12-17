<?php

namespace App\Livewire;

use App\Models\Rab;
use App\Models\User;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\SubKegiatan;
use App\Models\UraianRekening;
use App\Models\AktivitasSubKegiatan;
use App\Models\LampiranRab;
use App\Models\ListRab;
use App\Models\MerkStok;
use App\Models\PermintaanDetail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\RabHistory;

class EditRab extends Component
{
    use WithFileUploads;

    public $rab;
    public $rabId;

    // Form fields
    public $program_id, $programs = [];
    public $kegiatan_id, $kegiatans = [];
    public $sub_kegiatan_id, $sub_kegiatans = [];
    public $aktivitas_sub_kegiatan_id, $aktivitas_sub_kegiatans = [];
    public $uraian_rekening_id, $uraian_rekenings = [];
    public $kelurahan_id, $kelurahans = [];
    public $kecamatan_id, $kecamatans = [];

    // For super admin - display unit context (readonly, cannot be changed)
    public $selected_unit_id = null;
    public $all_units = [];

    public $jenis_pekerjaan;
    public $lokasi;
    public $mulai;
    public $selesai;
    public $p, $l, $k;

    // Admin fields
    public $keterangan_perubahan = '';
    public $reason_delete = '';
    public $showDeleteModal = false;

    // UI feedback messages
    public $cascadeMessage = '';

    // File upload properties
    public $newAttachments = [];
    public $attachments = [];

    // Detail RAB editing
    public $detailRabs = [];
    public $showDetailModal = false;
    public $editingDetailId = null;
    public $detailForm = [
        'merk_id' => '',
        'kuantitas' => '',
        'keterangan' => ''
    ];

    // Dropdown data for detail form
    public $barangStoks = [];
    public $merkStoks = [];
    public $selectedBarangId = null;

    // Authorization
    public $canEdit = false;
    public $canDelete = false;
    public $isSuperAdmin = false;

    protected $rules = [
        'program_id' => 'required|exists:programs,id',
        'kegiatan_id' => 'required|exists:kegiatans,id',
        'sub_kegiatan_id' => 'required|exists:sub_kegiatans,id',
        'aktivitas_sub_kegiatan_id' => 'required|exists:aktivitas_sub_kegiatans,id',
        'uraian_rekening_id' => 'required|exists:uraian_rekenings,id',
        'kelurahan_id' => 'required|exists:kelurahans,id',
        'jenis_pekerjaan' => 'required|string|max:255',
        'lokasi' => 'required|string|max:255',
        'mulai' => 'required|date',
        'selesai' => 'required|date|after:mulai',
        'p' => 'nullable|numeric|min:0',
        'l' => 'nullable|numeric|min:0',
        'k' => 'nullable|numeric|min:0',
        'keterangan_perubahan' => 'required_if:isSuperAdmin,true|string|max:500',
    ];

    public function mount($rabId)
    {
        $this->rabId = $rabId;
        $this->rab = Rab::with(['user.unitKerja'])->findOrFail($rabId);

        $user = Auth::user();
        $this->isSuperAdmin = $user->hasRole('superadmin') || $user->unit_id === null;

        // Check authorization
        $this->checkAuthorization();

        // Load dropdown data
        $this->loadDropdownData();

        // Fill form with existing data
        $this->fillForm();

        // Load detail RABs
        $this->loadDetailRabs();

        // Load barang stok for dropdown
        $this->loadBarangStok();
    }

    private function loadBarangStok()
    {
        // Load semua barang stok material (jenis_id = 1)
        $this->barangStoks = \App\Models\BarangStok::where('jenis_id', 1)
            ->orderBy('nama')
            ->get();
    }

    private function loadDetailRabs()
    {
        if ($this->rab) {
            $this->detailRabs = ListRab::with(['merkStok.barangStok.satuanBesar'])
                ->where('rab_id', $this->rab->id)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama_barang' => $item->merkStok->barangStok->nama ?? '',
                        'spesifikasi' => $item->merkStok->spesifikasi ?? '',
                        'satuan' => $item->merkStok->barangStok->satuanBesar->nama ?? '',
                        'kuantitas' => $item->jumlah,
                        'harga_satuan' => 0, // ListRab doesn't have price
                        'keterangan' => ''
                    ];
                })->toArray();
        }
    }

    private function checkAuthorization()
    {
        $user = Auth::user();

        if ($this->isSuperAdmin) {
            // Super admin can edit/delete all RABs regardless of status
            $this->canEdit = true;
            $this->canDelete = true;
        } else {
            // Regular users can only edit/delete their own unit's RABs
            $rabUnitId = $this->rab->user->unit_id;
            $userUnitId = $user->unit_id;

            // Check if user can access this RAB
            $canAccess = ($rabUnitId === $userUnitId) ||
                ($this->rab->user->unitKerja->parent_id === $userUnitId);

            if (!$canAccess) {
                abort(403, 'Unauthorized access to this RAB');
            }

            // Regular users can only edit if RAB is not yet approved (status = null)
            $this->canEdit = is_null($this->rab->status);
            $this->canDelete = is_null($this->rab->status);
        }
    }

    private function loadDropdownData()
    {
        $user = Auth::user();

        if ($this->isSuperAdmin) {
            // Load all units for display purposes only (readonly)
            $this->all_units = \App\Models\UnitKerja::whereNull('parent_id')->get();

            // Debug log
            \Log::info('Super Admin editing RAB - Units loaded for display only', [
                'unit_count' => $this->all_units->count(),
                'units' => $this->all_units->pluck('nama', 'id')->toArray()
            ]);

            // For editing, we always use the RAB's original unit context
            // Unit context cannot be changed when editing existing RAB
            $rabUnit = $this->rab->user->unitKerja;
            // Use parent_id for main unit, fallback to unit_id if no parent
            $unitId = $rabUnit->parent_id ?: $this->rab->user->unit_id;

            // Set the selected unit to RAB's original unit (readonly)
            $this->selected_unit_id = $unitId;

            // Always show data from the original RAB's unit (cannot be changed)
            $this->programs = Program::where('bidang_id', $unitId)->get();
            $this->kecamatans = Kecamatan::where('unit_id', $unitId)->get();
        } else {
            // Regular users see only their unit's data
            // Use parent_id for main unit, fallback to unit_id if no parent
            $unitId = $user->unitKerja->parent_id ?: $user->unit_id;
            $this->programs = Program::where('bidang_id', $unitId)->get();
            $this->kecamatans = Kecamatan::where('unit_id', $unitId)->get();
        }
    }

    private function fillForm()
    {
        $this->program_id = $this->rab->program_id;
        $this->kegiatan_id = $this->rab->kegiatan_id;
        $this->sub_kegiatan_id = $this->rab->sub_kegiatan_id;
        $this->aktivitas_sub_kegiatan_id = $this->rab->aktivitas_sub_kegiatan_id;
        $this->uraian_rekening_id = $this->rab->uraian_rekening_id;
        $this->kelurahan_id = $this->rab->kelurahan_id;
        $this->kecamatan_id = $this->rab->kelurahan->kecamatan_id ?? null;

        $this->jenis_pekerjaan = $this->rab->jenis_pekerjaan;
        $this->lokasi = $this->rab->lokasi;
        $this->mulai = $this->rab->mulai ? $this->rab->mulai->format('Y-m-d') : null;
        $this->selesai = $this->rab->selesai ? $this->rab->selesai->format('Y-m-d') : null;
        $this->p = $this->rab->p;
        $this->l = $this->rab->l;
        $this->k = $this->rab->k;

        // Load dependent dropdowns
        if ($this->program_id) {
            $this->loadKegiatans();
        }
        if ($this->kegiatan_id) {
            $this->loadSubKegiatans();
        }
        if ($this->sub_kegiatan_id) {
            $this->loadAktivitasSubKegiatans();
        }
        if ($this->aktivitas_sub_kegiatan_id) {
            $this->loadUraianRekenings();
        }
        if ($this->kecamatan_id) {
            $this->loadKelurahans();
        }
    }

    public function updatedProgramId($value)
    {
        // Set warning message
        if ($this->kegiatan_id || $this->sub_kegiatan_id || $this->aktivitas_sub_kegiatan_id || $this->uraian_rekening_id) {
            $this->cascadeMessage = 'Program telah diubah. Silakan pilih ulang Kegiatan, Sub Kegiatan, Aktivitas, dan Uraian Rekening.';
        }

        // Reset semua dependent fields ketika program berubah
        $this->kegiatan_id = null;
        $this->sub_kegiatan_id = null;
        $this->aktivitas_sub_kegiatan_id = null;
        $this->uraian_rekening_id = null;

        // Clear arrays
        $this->kegiatans = [];
        $this->sub_kegiatans = [];
        $this->aktivitas_sub_kegiatans = [];
        $this->uraian_rekenings = [];

        // Load new data for kegiatan
        $this->loadKegiatans();

        // Clear message after short delay
        $this->dispatch('clearCascadeMessage');
    }

    public function updatedKegiatanId()
    {
        // Set warning message
        if ($this->sub_kegiatan_id || $this->aktivitas_sub_kegiatan_id || $this->uraian_rekening_id) {
            $this->cascadeMessage = 'Kegiatan telah diubah. Silakan pilih ulang Sub Kegiatan, Aktivitas, dan Uraian Rekening.';
        }

        // Reset dependent fields ketika kegiatan berubah
        $this->sub_kegiatan_id = null;
        $this->aktivitas_sub_kegiatan_id = null;
        $this->uraian_rekening_id = null;

        // Clear arrays
        $this->sub_kegiatans = [];
        $this->aktivitas_sub_kegiatans = [];
        $this->uraian_rekenings = [];

        // Load new data for sub kegiatan
        $this->loadSubKegiatans();

        // Clear message after short delay
        $this->dispatch('clearCascadeMessage');
    }

    public function updatedSubKegiatanId()
    {
        // Set warning message
        if ($this->aktivitas_sub_kegiatan_id || $this->uraian_rekening_id) {
            $this->cascadeMessage = 'Sub Kegiatan telah diubah. Silakan pilih ulang Aktivitas dan Uraian Rekening.';
        }

        // Reset dependent fields ketika sub kegiatan berubah
        $this->aktivitas_sub_kegiatan_id = null;
        $this->uraian_rekening_id = null;

        // Clear arrays
        $this->aktivitas_sub_kegiatans = [];
        $this->uraian_rekenings = [];

        // Load new data for aktivitas
        $this->loadAktivitasSubKegiatans();

        // Clear message after short delay
        $this->dispatch('clearCascadeMessage');
    }

    public function updatedAktivitasSubKegiatanId()
    {
        // Set warning message
        if ($this->uraian_rekening_id) {
            $this->cascadeMessage = 'Aktivitas telah diubah. Silakan pilih ulang Uraian Rekening.';
        }

        // Reset dependent fields ketika aktivitas berubah
        $this->uraian_rekening_id = null;

        // Clear array
        $this->uraian_rekenings = [];

        // Load new data for uraian rekening
        $this->loadUraianRekenings();

        // Clear message after short delay
        $this->dispatch('clearCascadeMessage');
    }

    public function updatedKecamatanId()
    {
        // Set warning message
        if ($this->kelurahan_id) {
            $this->cascadeMessage = 'Kecamatan telah diubah. Silakan pilih ulang Kelurahan.';
        }

        // Reset kelurahan ketika kecamatan berubah
        $this->kelurahan_id = null;

        // Clear array
        $this->kelurahans = [];

        // Load new data for kelurahan
        $this->loadKelurahans();

        // Clear message after short delay
        $this->dispatch('clearCascadeMessage');
    }

    public function clearCascadeMessage()
    {
        $this->cascadeMessage = '';
    }

    // Methods for handling barang/merk selection
    public function updatedSelectedBarangId($value)
    {
        if ($value) {
            // Load merk stok untuk barang yang dipilih
            $this->merkStoks = \App\Models\MerkStok::where('barang_id', $value)
                ->with(['barangStok.satuanBesar'])
                ->orderBy('nama')
                ->get();

            // Reset merk selection
            $this->detailForm['merk_id'] = '';
        } else {
            $this->merkStoks = [];
            $this->resetDetailFormFields();
        }
    }

    public function updated($field, $value)
    {
        // Handle nested array updates that might not trigger specific updated methods
        if ($field === 'detailForm.merk_id') {
            $this->handleMerkSelection($value);
        }
    }

    public function updatedDetailFormMerkId($value)
    {
        // Method untuk handle perubahan merk selection
        // Karena sekarang hanya menggunakan foreign key, tidak perlu update field lain
    }

    // Alternative method to handle merk selection
    public function handleMerkSelection($value)
    {
        // Method ini sudah tidak dibutuhkan karena hanya menggunakan foreign key
        // Tidak ada field yang perlu di-update saat merk dipilih
    }

    private function resetDetailFormFields()
    {
        $this->detailForm['merk_id'] = '';
    }

    // Detail RAB Methods
    public function addDetailRab()
    {
        $this->resetDetailForm();
        $this->editingDetailId = null;
        $this->showDetailModal = true;
    }

    public function editDetailRab($detailId)
    {
        $detail = collect($this->detailRabs)->firstWhere('id', $detailId);
        if ($detail) {
            // Get original ListRab record
            $listRab = ListRab::find($detailId);

            if ($listRab && $listRab->merk_id) {
                // Load the related data from merk_id
                $merk = $listRab->merkStok;
                $this->selectedBarangId = $merk->barang_id;
                $this->detailForm['merk_id'] = $listRab->merk_id;

                // Load merk stok untuk barang yang dipilih
                $this->merkStoks = \App\Models\MerkStok::where('barang_id', $merk->barang_id)
                    ->with(['barangStok.satuanBesar'])
                    ->orderBy('nama')
                    ->get();

                // Set form values from merk relation
                $this->detailForm['nama_barang'] = $merk->barangStok->nama ?? '';
                $this->detailForm['spesifikasi'] = $merk->spesifikasi ?? '';
                $this->detailForm['satuan'] = $merk->barangStok->satuanBesar->nama ?? '';
                $this->detailForm['kuantitas'] = $listRab->jumlah;
                $this->detailForm['harga_satuan'] = 0;
                $this->detailForm['keterangan'] = '';

                $this->editingDetailId = $detailId;
                $this->showDetailModal = true;
            }
        }
    }

    public function saveDetailRab()
    {
        // Validation rules - merk_id is required for foreign key integrity
        $rules = [
            'detailForm.merk_id' => 'required|exists:merk_stok,id',
            'detailForm.kuantitas' => 'required|numeric|min:1',
            'detailForm.keterangan' => 'nullable|string|max:500'
        ];

        $this->validate($rules);

        if ($this->editingDetailId) {
            // Update existing detail
            $detail = ListRab::find($this->editingDetailId);
            if ($detail) {
                $updateData = [
                    'merk_id' => $this->detailForm['merk_id'],
                    'jumlah' => $this->detailForm['kuantitas'],
                    'keterangan' => $this->detailForm['keterangan']
                ];

                $detail->update($updateData);
            }
        } else {
            // Create new detail
            $createData = [
                'rab_id' => $this->rab->id,
                'merk_id' => $this->detailForm['merk_id'],
                'jumlah' => $this->detailForm['kuantitas'],
                'keterangan' => $this->detailForm['keterangan']
            ];

            ListRab::create($createData);
        }

        $this->loadDetailRabs();
        $this->showDetailModal = false;
        $this->resetDetailForm();

        session()->flash('success', 'Detail barang berhasil disimpan.');
    }

    public function deleteDetailRab($detailId)
    {
        $detail = ListRab::find($detailId);
        if ($detail) {
            $detail->delete();
            $this->loadDetailRabs();
            session()->flash('success', 'Detail barang berhasil dihapus.');
        }
    }

    public function cancelDetailModal()
    {
        $this->showDetailModal = false;
        $this->resetDetailForm();
    }

    private function resetDetailForm()
    {
        $this->detailForm = [
            'merk_id' => '',
            'kuantitas' => '',
            'keterangan' => ''
        ];
        $this->selectedBarangId = null;
        $this->merkStoks = [];
    }

    // File Upload Methods
    public function updatedNewAttachments()
    {
        $this->validate([
            'newAttachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx'
        ]);

        foreach ($this->newAttachments as $file) {
            $this->attachments[] = $file;
        }

        $this->newAttachments = [];
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function saveAttachments()
    {
        if (empty($this->attachments)) {
            session()->flash('error', 'Tidak ada file yang dipilih.');
            return;
        }

        foreach ($this->attachments as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('lampiran_rab', $filename, 'public');

            LampiranRab::create([
                'rab_id' => $this->rab->id,
                'nama_file' => $file->getClientOriginalName(),
                'path' => $path,
                'ukuran' => $file->getSize(),
                'tipe' => $file->getClientOriginalExtension()
            ]);
        }

        $this->attachments = [];
        session()->flash('success', 'Lampiran berhasil disimpan.');
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = LampiranRab::find($attachmentId);
        if ($attachment) {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
            session()->flash('success', 'Lampiran berhasil dihapus.');
        }
    }

    private function loadKegiatans()
    {
        $this->kegiatans = $this->program_id
            ? Kegiatan::where('program_id', $this->program_id)->get()
            : [];
    }

    private function loadSubKegiatans()
    {
        $this->sub_kegiatans = $this->kegiatan_id
            ? SubKegiatan::where('kegiatan_id', $this->kegiatan_id)->get()
            : [];
    }

    private function loadAktivitasSubKegiatans()
    {
        $this->aktivitas_sub_kegiatans = $this->sub_kegiatan_id
            ? AktivitasSubKegiatan::where('sub_kegiatan_id', $this->sub_kegiatan_id)->get()
            : [];
    }

    private function loadUraianRekenings()
    {
        $this->uraian_rekenings = $this->aktivitas_sub_kegiatan_id
            ? UraianRekening::where('aktivitas_sub_kegiatan_id', $this->aktivitas_sub_kegiatan_id)->get()
            : [];
    }

    private function loadKelurahans()
    {
        $this->kelurahans = $this->kecamatan_id
            ? Kelurahan::where('kecamatan_id', $this->kecamatan_id)->get()
            : [];
    }

    public function save()
    {
        if (!$this->canEdit) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit RAB ini.');
            return;
        }

        // Validate with additional rule for super admin
        if ($this->isSuperAdmin) {
            $this->validate([
                'keterangan_perubahan' => 'required|string|max:500'
            ]);
        }

        $this->validate();

        // Store original data for history
        $originalData = $this->rab->toArray();

        // Update RAB
        $this->rab->update([
            'program_id' => $this->program_id,
            'kegiatan_id' => $this->kegiatan_id,
            'sub_kegiatan_id' => $this->sub_kegiatan_id,
            'aktivitas_sub_kegiatan_id' => $this->aktivitas_sub_kegiatan_id,
            'uraian_rekening_id' => $this->uraian_rekening_id,
            'kelurahan_id' => $this->kelurahan_id,
            'jenis_pekerjaan' => $this->jenis_pekerjaan,
            'lokasi' => $this->lokasi,
            'mulai' => $this->mulai,
            'selesai' => $this->selesai,
            'p' => $this->p,
            'l' => $this->l,
            'k' => $this->k,
        ]);

        // Create audit trail
        $this->createAuditTrail('update', $originalData, $this->rab->fresh()->toArray());

        session()->flash('success', 'RAB berhasil diperbarui.');
        return redirect()->route('rab.show', $this->rab->id);
    }

    public function confirmDelete()
    {
        if (!$this->canDelete) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus RAB ini.');
            return;
        }

        $this->showDeleteModal = true;
    }

    public function deleteRab()
    {
        if (!$this->canDelete) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus RAB ini.');
            return;
        }

        if ($this->isSuperAdmin) {
            $this->validate([
                'reason_delete' => 'required|string|max:500'
            ]);
        }

        // Store original data for history
        $originalData = $this->rab->toArray();

        // Create audit trail before deletion
        $this->createAuditTrail('delete', $originalData, null);

        try {
            // Delete related records first to avoid foreign key constraint violations

            // Delete RAB history records
            \App\Models\RabHistory::where('rab_id', $this->rab->id)->delete();

            // Delete approvals (polymorphic relationship - must be deleted manually)
            \App\Models\Persetujuan::where('approvable_id', $this->rab->id)
                ->where('approvable_type', \App\Models\Rab::class)
                ->delete();

            // Delete lampiran files and records
            foreach ($this->rab->lampiran as $lampiran) {
                // Delete physical file
                if (Storage::exists('public/lampiranRab/' . $lampiran->path)) {
                    Storage::delete('public/lampiranRab/' . $lampiran->path);
                }
                $lampiran->delete();
            }

            // Delete list RAB records
            \App\Models\ListRab::where('rab_id', $this->rab->id)->delete();

            // Check for and handle PermintaanMaterial records
            $permintaanMaterials = \App\Models\PermintaanMaterial::where('rab_id', $this->rab->id)->get();
            foreach ($permintaanMaterials as $permintaan) {
                // Delete detail permintaan material records
                \App\Models\DetailPermintaanMaterial::where('rab_id', $this->rab->id)->delete();
                $permintaan->delete();
            }

            // Finally delete the RAB itself
            $this->rab->delete();

            session()->flash('success', 'RAB beserta semua data terkait berhasil dihapus.');
            return redirect()->route('rab.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus RAB: ' . $e->getMessage());
            $this->showDeleteModal = false;
            return;
        }
    }

    private function createAuditTrail($action, $oldData, $newData)
    {
        $user = Auth::user();

        RabHistory::create([
            'rab_id' => $this->rab->id,
            'user_id' => $user->id,
            'action' => $action,
            'old_data' => json_encode($oldData),
            'new_data' => json_encode($newData),
            'keterangan' => $action === 'update' ? $this->keterangan_perubahan : $this->reason_delete,
            'is_admin_action' => $this->isSuperAdmin,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->reason_delete = '';
    }

    public function render()
    {
        return view('livewire.edit-rab');
    }
}
