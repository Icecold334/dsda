<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JenisStok;
use App\Models\MetodePengadaan;
use App\Models\KontrakVendorStok;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class DataKontrakVendorStok extends Component
{
    public $search = ''; // Add a search property
    public $jenis = '';
    public $metode = '';
    public $tanggal = '';
    public $groupedTransactions, $sudin;
    public $unit_id;
    public $jenisOptions = [];
    public $metodeOptions = [];

    public function mount()
    {
        // Set unit_id dan sudin berdasarkan user
        $user = Auth::user();
        if ($user && $user->unitKerja) {
            $this->unit_id = $user->unitKerja->id;
            $this->sudin = $user->unitKerja->nama;
        } else {
            $this->unit_id = null;
            $this->sudin = 'Semua Unit Kerja';
        }

        // Cek apakah user memiliki unitKerja dan bukan superadmin
        if ($user && $user->unitKerja && !($user->unitKerja->hak ?? 0)) {
            $this->jenis = 'Material';
        }

        $this->jenisOptions = JenisStok::pluck('nama')->toArray(); // Fetch all jenis
        $this->metodeOptions = MetodePengadaan::pluck('nama')->toArray(); // Fetch all jenis
        // Initial data fetch
        $this->fetchData();
    }

    public function updatedSearch()
    {
        $this->fetchData();
    }

    public function fetchData()
    {
        $user = Auth::user();

        // Tentukan jenis berdasarkan hak user
        // Default jenis = 1 jika user tidak punya unitKerja atau bukan superadmin
        $jenis = 1; // Default untuk Material
        if ($user && $user->unitKerja && ($user->unitKerja->hak ?? 0)) {
            $jenis = 3; // Untuk Aset
        }

        // Fetch data based on unitKerja and optional search filtering
        $query = KontrakVendorStok::where('jenis_id', $jenis)->whereHas('listKontrak')->where(function ($queryBuilder) {
            $queryBuilder->where(function ($q) {
                $q->where('is_adendum', false)
                    ->whereDoesntHave('adendums');
            })
                ->orWhere(function ($q) {
                    $q->where('is_adendum', true)
                        ->whereDoesntHave('adendums');
                });
        });

        // Filter berdasarkan unit hanya jika user bukan superadmin dan memiliki unitKerja
        $isSuperadmin = $user && ($user->hasRole('superadmin') || !$user->unitKerja || ($user->unitKerja->hak ?? 0) == 1);

        if (!$isSuperadmin && $this->unit_id) {
            $query->whereHas('user', function ($userQuery) {
                return $userQuery->whereHas('unitKerja', function ($unit) {
                    return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            });
        }

        $this->groupedTransactions = $query
            ->when($this->search, function ($searchQuery) {
                $searchQuery->where(function ($subQuery) {
                    $subQuery->where('nomor_kontrak', 'like', '%' . $this->search . '%')
                        ->orWhereHas('vendorStok', function ($vendor) {
                            $vendor->where('nama', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->metode, function ($metodeQuery) {
                $metodeQuery->whereHas('metodePengadaan', function ($metodeSubQuery) {
                    $metodeSubQuery->where('nama', $this->metode);
                });
            })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($q) {
                $q->tanggal_search = date('Y-m-d', strtotime($q->tanggal_kontrak));
                // $q->tanggal_search = date('Y-m-d', $q->tanggal_kontrak);
                return $q;
            })->when($this->tanggal, function ($collection) {
                return $collection->where('tanggal_search', $this->tanggal);
            });
    }

    public function applyFilters()
    {
        $this->fetchData();
    }
    public function render()
    {
        return view('livewire.data-kontrak-vendor-stok', ['groupedTransactions' => $this->groupedTransactions]);
    }
}
