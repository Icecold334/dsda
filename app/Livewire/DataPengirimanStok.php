<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DetailPengirimanStok;
use Carbon\Carbon;

class DataPengirimanStok extends Component
{
    public $search = ''; // Add a search property
    public $jenis = '';
    public $tanggal = '';
    public $datangs;

    public function updatedSearch()
    {
        // Update the data whenever the search term is updated
        $this->fetchData();
    }
    public function updatedTanggal()
    {
        // dd(strtotime($this->tanggal));
        // Update the data whenever the search term is updated
        $this->fetchData();
    }

    public function mount()
    {
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

            // ->when($this->search, function ($query) {
            //     $query->where('kode_pengiriman_stok', 'like', '%' . $this->search . '%')
            //         ->orWhereHas('pengirimanStok.kontrakVendorStok.vendorStok', function ($vendor) {
            //             $vendor->where('nama', 'like', '%' . $this->search . '%');
            //         });
            // })
            ->when($this->search, function ($query) {
                $query->where('kode_pengiriman_stok', 'like', '%' . $this->search . '%');
            })
            ->when($this->jenis, function ($query) {
                $query->whereHas('pengirimanStok.merkStok.barangStok.jenisStok', function ($jenisQuery) {
                    $jenisQuery->where('nama', $this->jenis);
                });
            })
            // ->when($this->jenis, function ($query) {
            //     $query->whereHas('pengirimanStok.merkStok.barangStok.jenisStok', function ($jenisQuery) {
            //         $jenisQuery->where('nama', $this->jenis);
            //     });
            // })
            // ->when($this->tanggal, function ($query) {
            //     // Mengonversi tanggal dari format Y-m-d ke timestamp
            //     $timestamp = strtotime($this->tanggal);
            //     $query->where('tanggal', $timestamp); // Bandingkan dengan timestamp di database
            // })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($q) {
                $q->tanggal_search = date('Y-m-d', $q->tanggal);
                return $q;
            })->when($this->tanggal, function ($query) {
                return $query->where('tanggal_search', $this->tanggal);
            });
    }



    public function render()
    {
        return view('livewire.data-pengiriman-stok', ['datangs' => $this->datangs]);
    }
}