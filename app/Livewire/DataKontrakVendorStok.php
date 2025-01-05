<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JenisStok;
use App\Models\KontrakVendorStok;
use App\Models\MetodePengadaan;

class DataKontrakVendorStok extends Component
{
    public $search = ''; // Add a search property
    public $jenis = '';
    public $metode = '';
    public $tanggal = '';
    public $groupedTransactions;
    public $unit_id;
    public $jenisOptions = [];
    public $metodeOptions = [];

    public function mount()
    {
        $this->jenisOptions = JenisStok::pluck('nama')->toArray(); // Fetch all jenis
        $this->metodeOptions = MetodePengadaan::pluck('nama')->toArray(); // Fetch all jenis
        // Initial data fetch
        $this->fetchData();
    }

    public function fetchData()
    {
        // Fetch data based on unitKerja and optional search filtering
        $this->groupedTransactions = KontrakVendorStok::whereHas('transaksiStok')->when($this->unit_id, function ($kontrak) {
            return $kontrak->whereHas('user', function ($user) {
                return $user->whereHas('unitKerja', function ($unit) {
                    return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            });
        })

            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('nomor_kontrak', 'like', '%' . $this->search . '%')
                        ->orWhereHas('vendorStok', function ($vendor) {
                            $vendor->where('nama', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->jenis, function ($query) {
                $query->whereHas('transaksiStok.merkStok.barangStok.jenisStok', function ($jenisQuery) {
                    $jenisQuery->where('nama', $this->jenis);
                });
            })
            ->when($this->metode, function ($query) {
                $query->whereHas('metodePengadaan', function ($metodeQuery) {
                    $metodeQuery->where('nama', $this->metode);
                });
            })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($q) {
                $q->tanggal_search = date('Y-m-d', $q->tanggal_kontrak);
                return $q;
            })->when($this->tanggal, function ($query) {
                return $query->where('tanggal_search', $this->tanggal);
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
