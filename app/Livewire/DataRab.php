<?php

namespace App\Livewire;

use App\Models\Rab;
use App\Models\DetailPermintaanMaterial;
use App\Models\PermintaanMaterial;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DataRab extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $Rkb, $RKB, $sudin, $unit_id;
    public $showHistoryModal = false;
    public $selectedRabId = null;
    public $historyData = [];
    public $searchSpb = '';

    public function mount()
    {
        $this->unit_id = Auth::user()->unit_id;
    }

    public function fetchData()
    {
        $rabs = Rab::whereHas('user.unitKerja', function ($unit) {
            $unit->where('parent_id', $this->unit_id)
                ->orWhere('id', $this->unit_id);
        })->orderBy('created_at', 'desc')->paginate(5);

        $rabs->getCollection()->transform(function ($rab) {
            $statusMap = [
                null => ['label' => 'Diproses', 'color' => 'warning'],
                0 => ['label' => 'Ditolak', 'color' => 'danger'],
                1 => ['label' => 'Dibatalkan', 'color' => 'secondary'],
                2 => ['label' => 'Disetujui', 'color' => 'success'],
                3 => ['label' => 'Selesai', 'color' => 'primary'],
            ];

            // Tambahkan properti dinamis ke dalam object
            $rab->status_teks = $statusMap[$rab->status]['label'] ?? 'Tidak diketahui';
            $rab->status_warna = $statusMap[$rab->status]['color'] ?? 'gray';
            return $rab;
        });

        return $rabs;
    }

    public function showHistory($rabId)
    {
        $this->selectedRabId = $rabId;
        $this->searchSpb = '';
        $this->loadHistoryData();
        $this->showHistoryModal = true;
    }

    public function loadHistoryData()
    {
        if (!$this->selectedRabId)
            return;

        $query = collect();

        // Ambil permintaan material berdasarkan RAB
        $permintaanMaterial = DetailPermintaanMaterial::where('rab_id', $this->selectedRabId)
            ->when($this->searchSpb, function ($q) {
                $q->where('nodin', 'like', '%' . $this->searchSpb . '%');
            })
            ->with(['user.kecamatan', 'permintaanMaterial'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil permintaan material individual (untuk kasus Seribu)
        $permintaanMaterialSeribu = PermintaanMaterial::where('rab_id', $this->selectedRabId)
            ->when($this->searchSpb, function ($q) {
                $q->whereHas('detailPermintaan', function ($detail) {
                    $detail->where('nodin', 'like', '%' . $this->searchSpb . '%');
                });
            })
            ->with(['detailPermintaan.user.kecamatan', 'detailPermintaan'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('detail_permintaan_id')
            ->map(function ($group) {
                return $group->first()->detailPermintaan;
            });

        // Gabungkan dan format data
        $allPermintaan = $permintaanMaterial->merge($permintaanMaterialSeribu)->unique('id');

        $this->historyData = $allPermintaan->map(function ($permintaan) {
            $statusMap = [
                null => ['label' => 'Diproses', 'color' => 'warning'],
                0 => ['label' => 'Ditolak', 'color' => 'danger'],
                1 => ['label' => 'Disetujui', 'color' => 'success'],
                2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
                3 => ['label' => 'Selesai', 'color' => 'primary'],
            ];

            return [
                'id' => $permintaan->id,
                'nodin' => $permintaan->nodin,
                'pemohon' => $permintaan->user->name ?? '-',
                'kecamatan' => $permintaan->user->kecamatan->kecamatan ?? '-',
                'tanggal' => $permintaan->created_at->format('d M Y'),
                'total_items' => $permintaan->permintaanMaterial->count(),
                'status' => $statusMap[$permintaan->status]['label'] ?? 'Tidak diketahui',
                'status_color' => $statusMap[$permintaan->status]['color'] ?? 'gray',
            ];
        })->toArray();
    }

    public function updatedSearchSpb()
    {
        $this->loadHistoryData();
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->selectedRabId = null;
        $this->historyData = [];
        $this->searchSpb = '';
    }

    public function render()
    {
        $rabs = $this->fetchData();
        return view('livewire.data-rab', compact('rabs'));
    }
}
