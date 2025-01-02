<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BarangStok;
use App\Models\Stok;
use App\Models\JenisStok;
use App\Models\LokasiStok;

class DataStok extends Component
{
    public $search = ''; // Search term
    public $jenis = ''; // Selected jenis
    public $lokasi = ''; // Selected jenis
    public $unit_id; // Current user's unit ID
    public $barangs = []; // Filtered barangs
    public $stoks  = [];
    public $jenisOptions = []; // List of jenis options
    public $lokasiOptions = []; // List of jenis options

    public function mount()
    {
        $this->jenisOptions = JenisStok::pluck('nama')->toArray(); // Fetch all available jenis
        $this->lokasiOptions = LokasiStok::pluck('nama')->toArray(); // Fetch all available lokasi
        $this->applyFilters(); // Fetch initial data
        // $this->fetchBarangs();
        // $this->fetchStoks();
    }

    public function fetchBarangs()
    {
        $this->barangs = BarangStok::whereHas('merkStok', function ($merkQuery) {
            $merkQuery->whereHas('stok', function ($stokQuery) {
                $stokQuery->where('jumlah', '>', 0)->whereHas('lokasiStok.unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                });
            });
        })
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%') // Filter by name
                    ->orWhere('kode_barang', 'like', '%' . $this->search . '%'); // Filter by kode_barang
            })
            ->when($this->jenis, function ($query) {
                $query->whereHas('jenisStok', function ($jenisQuery) {
                    $jenisQuery->where('nama', $this->jenis);
                });
            })
            ->get();
    }

    // public function fetchStoks()
    // {
    //     $this->stoks = Stok::where('jumlah', '>', 0)
    //         ->whereHas('lokasiStok.unitKerja', function ($unit) {
    //             $unit->where('parent_id', $this->unit_id)
    //                 ->orWhere('id', $this->unit_id);
    //         })
    //         ->when($this->search, function ($query) {
    //             $query->whereHas('merkStok.barangStok', function ($barangQuery) {
    //                 $barangQuery->where('nama', 'like', '%' . $this->search . '%')
    //                     ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
    //             });
    //         })
    //         ->when($this->jenis, function ($query) {
    //             $query->whereHas('merkStok.barangStok.jenisStok', function ($jenisQuery) {
    //                 $jenisQuery->where('nama', $this->jenis);
    //             });
    //         })
    //         ->with(['merkStok.barangStok'])
    //         ->get()
    //         ->groupBy('merkStok.barangStok.id');
    // }

    public function fetchStoks()
    {
        $stoks = Stok::where('jumlah', '>', 0)
            ->whereHas('lokasiStok.unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })
            ->when($this->lokasi, function ($query) {
                $query->whereHas('lokasiStok', function ($lokasiQuery) {
                    $lokasiQuery->where('nama', $this->lokasi);
                });
            })
            ->with(['merkStok', 'merkStok.barangStok']) // Eager load necessary relationships
            ->get();

        // Transform the collection into a grouped array
        $groupedStoks = [];
        foreach ($stoks as $stok) {
            if ($stok->merkStok && $stok->merkStok->barangStok) {
                $barangId = $stok->merkStok->barangStok->id;
                $groupedStoks[$barangId][] = [
                    'id' => $stok->id,
                    'jumlah' => $stok->jumlah,
                    'merk' => $stok->merkStok->nama ?? null,
                    'tipe' => $stok->merkStok->tipe ?? null,
                    'ukuran' => $stok->merkStok->ukuran ?? null,
                    'lokasi' => $stok->lokasiStok->nama ?? null,
                    'satuan' => $stok->merkStok->barangStok->satuanBesar->nama ?? null,
                ];
            }
        }

        $this->stoks = $groupedStoks;

        // Debug the resulting array
        // dd($this->stoks);
    }



    public function applyFilters()
    {
        $this->fetchBarangs();
        $this->fetchStoks();
    }


    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'jenis'])) {
            $this->applyFilters();
        }
    }

    public function render()
    {
        return view('livewire.data-stok', [
            'barangs' => $this->barangs,
            'stoks' => $this->stoks,
        ]);
    }
}
