<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\JenisStok;
use App\Models\DetailPengirimanStok;

class DataPengirimanStok extends Component
{
    public $search = ''; // Add a search property
    public $jenis = '';
    public $tanggal = '';
    public $datangs;
    public $unit_id;
    public $jenisOptions = [];

    // public function updatedSearch()
    // {
    //     // Update the data whenever the search term is updated
    //     $this->fetchData();
    // }
    // public function updatedTanggal()
    // {
    //     // dd(strtotime($this->tanggal));
    //     // Update the data whenever the search term is updated
    //     $this->fetchData();
    // }

    public function mount()
    {
        $this->jenisOptions = JenisStok::pluck('nama')->toArray(); // Fetch all jenis
        // Initial data fetch
        $this->fetchData();
    }

    public function fetchData()
    {
        // Fetch data based on unitKerja and optional search filtering
        $this->datangs = DetailPengirimanStok::whereHas('user', function ($user) {
            $user->whereHas('unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            });
        })

            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('kode_pengiriman_stok', 'like', '%' . $this->search . '%')
                        ->orWhereHas('pengirimanStok.kontrakVendorStok.vendorStok', function ($vendor) {
                            $vendor->where('nama', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->jenis, function ($query) {
                $query->whereHas('pengirimanStok.merkStok.barangStok.jenisStok', function ($jenisQuery) {
                    $jenisQuery->where('nama', $this->jenis);
                });
            })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($q) {
                $q->tanggal_search = date('Y-m-d', $q->tanggal);
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
        return view('livewire.data-pengiriman-stok', ['datangs' => $this->datangs]);
    }
}
