<?php

namespace App\Livewire;

use App\Models\Rab;
use App\Models\AdendumRab as AdendumRabModel;
use App\Models\ListRab;
use App\Models\AdendumListRab;
use App\Models\AdendumHistory;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class ApprovalAdendumRab extends Component
{
    public $rab;
    public $adendum;
    public $rabId;
    public $adendumId;
    public $isPembuatRab = false;
    public $keteranganReject = '';

    public function mount($rabId, $adendumId)
    {
        $this->rabId = $rabId;
        $this->adendumId = $adendumId;
        
        $this->rab = Rab::with(['list.merkStok.barangStok', 'user'])->findOrFail($rabId);
        $this->adendum = AdendumRabModel::with(['list.merkStok.barangStok', 'user'])->findOrFail($adendumId);

        // Cek apakah user adalah pembuat RAB
        $this->isPembuatRab = $this->rab->user_id === Auth::id();

        // Cek apakah pembuat RAB adalah Kasie/Kepala Seksi Perencanaan
        $rabCreator = $this->rab->user;
        $isKasieCreator = $rabCreator && (
            $rabCreator->hasRole('Kepala Seksi') 
            // || 
            // $rabCreator->hasRole('Kepala Seksi Perencanaan') ||
            // $rabCreator->roles->contains(function ($role) {
            //     return str_contains($role->name, 'Kepala Seksi') || str_contains($role->name, 'Kasie');
            // })
        );

        // Izinkan jika user adalah pembuat RAB (termasuk jika pembuat RAB adalah Kasie)
        if (!$this->isPembuatRab) {
            abort(403, 'Hanya pembuat RAB yang dapat mengkonfirmasi adendum');
        }

        // Cek apakah adendum sudah di-approve
        if ($this->adendum->is_approved) {
            session()->flash('info', 'Adendum ini sudah dikonfirmasi.');
        }
    }

    public function approveAdendum()
    {
        if ($this->adendum->is_approved) {
            session()->flash('error', 'Adendum ini sudah dikonfirmasi.');
            return;
        }

        // Update List RAB berdasarkan perubahan adendum
        foreach ($this->adendum->list as $adendumItem) {
            if ($adendumItem->action === 'add') {
                // Tambah material baru
                ListRab::create([
                    'rab_id' => $this->rab->id,
                    'merk_id' => $adendumItem->merk_id,
                    'jumlah' => $adendumItem->jumlah_baru,
                ]);
            } elseif ($adendumItem->action === 'edit' && $adendumItem->list_rab_id) {
                // Update jumlah material
                $listRab = ListRab::find($adendumItem->list_rab_id);
                if ($listRab) {
                    $listRab->update([
                        'jumlah' => $adendumItem->jumlah_baru,
                    ]);
                }
            } elseif ($adendumItem->action === 'delete' && $adendumItem->list_rab_id) {
                // Hapus material (soft delete atau hard delete)
                $listRab = ListRab::find($adendumItem->list_rab_id);
                if ($listRab) {
                    // Cek apakah sudah digunakan
                    $telahDigunakan = $this->hitungTelahDigunakan($listRab->merk_id, $this->rab->id);
                    if ($telahDigunakan > 0) {
                        session()->flash('error', "Material {$listRab->merkStok->nama} tidak dapat dihapus karena sudah digunakan ({$telahDigunakan} unit).");
                        return;
                    }
                    $listRab->delete();
                }
            }
        }

        // Simpan data perubahan untuk history
        $changes = [];
        foreach ($this->adendum->list as $adendumItem) {
            $change = [
                'action' => $adendumItem->action,
                'merk_id' => $adendumItem->merk_id,
                'merk_nama' => $adendumItem->merkStok->nama ?? '',
                'jumlah_lama' => $adendumItem->jumlah_lama,
                'jumlah_baru' => $adendumItem->jumlah_baru,
            ];
            $changes[] = $change;
        }

        // Update status adendum
        $this->adendum->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Catat history approve
        AdendumHistory::create([
            'adendum_rab_id' => $this->adendum->id,
            'rab_id' => $this->rab->id,
            'user_id' => Auth::id(),
            'action' => 'approve',
            'old_data' => null,
            'new_data' => [
                'changes' => $changes,
                'keterangan' => $this->adendum->keterangan,
            ],
            'keterangan' => 'Adendum disetujui dan diterapkan',
        ]);

        // Kirim notifikasi ke Kasatpel yang membuat adendum
        $kasatpel = $this->adendum->user;
        $mess = 'Adendum RAB <span class="font-semibold">' . $this->rab->jenis_pekerjaan . '</span> telah dikonfirmasi dan diterapkan.';
        
        Notification::send($kasatpel, new UserNotification(
            $mess,
            "/rab/{$this->rab->id}"
        ));

        session()->flash('success', 'Adendum RAB berhasil dikonfirmasi dan diterapkan.');
        return redirect()->route('rab.show', ['rab' => $this->rab->id]);
    }

    public function rejectAdendum()
    {
        if ($this->adendum->is_approved) {
            session()->flash('error', 'Adendum ini sudah dikonfirmasi.');
            return;
        }

        $this->validate([
            'keteranganReject' => 'required|string|min:10',
        ], [
            'keteranganReject.required' => 'Keterangan penolakan harus diisi',
            'keteranganReject.min' => 'Keterangan minimal 10 karakter',
        ]);

        // Simpan data adendum untuk history sebelum dihapus
        $adendumData = [
            'keterangan' => $this->adendum->keterangan,
            'changes' => $this->adendum->list->map(function ($item) {
                return [
                    'action' => $item->action,
                    'merk_id' => $item->merk_id,
                    'merk_nama' => $item->merkStok->nama ?? '',
                    'jumlah_lama' => $item->jumlah_lama,
                    'jumlah_baru' => $item->jumlah_baru,
                ];
            })->toArray(),
        ];

        // Simpan data kasatpel sebelum menghapus
        $kasatpel = $this->adendum->user;
        $adendumId = $this->adendum->id;

        // Catat history reject sebelum menghapus
        AdendumHistory::create([
            'adendum_rab_id' => $adendumId,
            'rab_id' => $this->rab->id,
            'user_id' => Auth::id(),
            'action' => 'reject',
            'old_data' => $adendumData,
            'new_data' => null,
            'keterangan' => $this->keteranganReject,
        ]);

        // Hapus adendum dan detailnya
        $this->adendum->list()->delete();
        $this->adendum->delete();

        // Kirim notifikasi ke Kasatpel
        $mess = 'Adendum RAB <span class="font-semibold">' . $this->rab->jenis_pekerjaan . '</span> ditolak dengan keterangan: ' . $this->keteranganReject;
        
        Notification::send($kasatpel, new UserNotification(
            $mess,
            "/rab/{$this->rab->id}"
        ));

        session()->flash('success', 'Adendum RAB ditolak.');
        return redirect()->route('rab.show', ['rab' => $this->rab->id]);
    }

    public function hitungTelahDigunakan($merkId, $rabId)
    {
        $totalTelahDigunakan = 0;

        try {
            $permintaanMaterial = \App\Models\PermintaanMaterial::where('merk_id', $merkId)
                ->where('rab_id', $rabId)
                ->whereHas('detailPermintaan', function ($query) {
                    $query->whereIn('status', [2, 3]);
                })
                ->whereHas('stokDisetujui', function ($query) {
                    $query->where('jumlah_disetujui', '>', 0);
                })
                ->with('stokDisetujui')
                ->get();

            foreach ($permintaanMaterial as $permintaan) {
                $totalTelahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
            }
        } catch (\Exception $e) {
            Log::error('Error calculating telah digunakan RAB: ' . $e->getMessage());
        }

        return $totalTelahDigunakan;
    }

    public function render()
    {
        return view('livewire.approval-adendum-rab');
    }
}
