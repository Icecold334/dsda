<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BarangStok;
use App\Models\KategoriStok;

class DataKategoriStok extends Component
{

    public $search = '';
    public $kategoris = [];

    public function mount()
    {
        // Memuat data awal saat komponen di-mount
        $this->loadData();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    private function loadData()
    {
        // Memuat data barang dengan filter pencarian
        $this->kategoris = KategoriStok::with('barangStok')->when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                // ->orWhereHas('barangStok.jenisStok', function ($query) {
                //     $query->where('nama', 'like', '%' . $this->search . '%');
                // })
                ->orWhereHas('barangStok', function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%');
                });
        })->get();
        // dd($this->kategoris);
    }

    public function render()
    {
        return view('livewire.data-kategori-stok');
    }
}
