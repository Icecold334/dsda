<?php

namespace App\Livewire;

use App\Models\Rab;
use App\Models\DetailPermintaanMaterial;
use App\Models\PermintaanMaterial;
use Illuminate\Support\Facades\DB;
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

    public $search = '';
    public $status = '';
    public $tahun = ''; 

    public function mount()
    {
        $this->unit_id = Auth::user()->unit_id;
    }

    public function updated($propertyName) {
        if (in_array($propertyName, ['search', 'status', 'tahun'])) {
            $this->resetPage();
        }
    }

    public function fetchData()
    {
        $user = Auth::user();

        // Check if user is superadmin
        if ($user->hasRole('superadmin') || $user->unit_id === null) {
            // Superadmin dapat melihat semua RAB dari semua suku dinas
            $query = Rab::with(['user.unitKerja', 'pendingAdendums', 'approvedAdendums'])
                ->withCount('adendumHistories');

            $query->when($this->search, function ($q) {
                return $q->where('lokasi', 'like', '%' . $this->search . '%');
            });
            $query->when($this->status, function ($q) {
                if ($this->status === 'adendum') {
                    return $q->whereHas('pendingAdendums');
                }
                $statusValue = match ($this->status) {
                    'diproses' => null, 'ditolak' => 0, 'disetujui' => 2, default => 'ignore',
                };
                if ($statusValue !== 'ignore') {
                    return $q->where('status', $statusValue);
                }
            });
            $query->when($this->tahun, function ($q) {
                return $q->whereYear('created_at', $this->tahun);
            });
            $query->orderBy('created_at', 'desc');

            $rabs = $query->paginate(5);

        } else {
            // User biasa hanya bisa melihat RAB dari unit mereka/bawahan mereka
            $query = Rab::with(['user.unitKerja', 'pendingAdendums'])
                ->withCount('adendumHistories')
                ->whereHas('user.unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            });

            
            $query->when($this->search, function ($q) {
                return $q->where('lokasi', 'like', '%' . $this->search . '%');
            });
            $query->when($this->status, function ($q) {
                if ($this->status === 'adendum') {
                    return $q->whereHas('pendingAdendums');
                }
                $statusValue = match ($this->status) {
                    'diproses' => null, 'ditolak' => 0, 'disetujui' => 2, default => 'ignore',
                };
                 if ($statusValue !== 'ignore') {
                    return $q->where('status', $statusValue);
                 }
            });

            $query->when($this->tahun, function ($q) {
                return $q->whereYear('created_at', $this->tahun);
            });

            $query->orderBy('created_at', 'desc');

            $rabs = $query->paginate(5);
        }

        $rabs->getCollection()->transform(function ($rab) {
            $statusMap = [
                null => ['label' => 'Diproses', 'color' => 'warning'],
                0 => ['label' => 'Ditolak', 'color' => 'danger'],
                1 => ['label' => 'Dibatalkan', 'color' => 'secondary'],
                2 => ['label' => 'Disetujui', 'color' => 'success'],
                3 => ['label' => 'Selesai', 'color' => 'primary'],
            ];

            // Cek apakah ada pending adendum
            $hasPendingAdendum = $rab->pendingAdendums->count() > 0;
            
            // Jika ada pending adendum, tampilkan status "Adendum"
            if ($hasPendingAdendum) {
                $rab->status_teks = 'Adendum';
                $rab->status_warna = 'warning';
            } else {
                // Jika tidak ada pending adendum, tampilkan status RAB asli
            $rab->status_teks = $statusMap[$rab->status]['label'] ?? 'Tidak diketahui';
            $rab->status_warna = $statusMap[$rab->status]['color'] ?? 'gray';
            }
            
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

        // Ambil permintaan material berdasarkan RAB dengan filter status
        $permintaanMaterial = DetailPermintaanMaterial::where('rab_id', $this->selectedRabId)
            ->whereIn('status', [2, 3]) // Filter hanya status 2 (Sedang Dikirim) dan 3 (Selesai)
            ->when($this->searchSpb, function ($q) {
                $q->where('nodin', 'like', '%' . $this->searchSpb . '%');
            })
            ->with(['user.kecamatan', 'permintaanMaterial'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil permintaan material individual (untuk kasus Seribu) dengan filter status
        $permintaanMaterialSeribu = PermintaanMaterial::where('rab_id', $this->selectedRabId)
            ->whereHas('detailPermintaan', function ($detail) {
                $detail->whereIn('status', [2, 3]); // Filter hanya status 2 dan 3
            })
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
        // daftar tahun
        $daftarTahun = Rab::select(DB::raw("strftime('%Y', created_at) as tahun"))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
        return view('livewire.data-rab', [
            'rabs' => $rabs,
            'daftarTahun' => $daftarTahun,
            'Rkb' => $this->Rkb,
            'RKB' => $this->RKB,
            'sudin' => $this->sudin,
        ]);
    }
}
