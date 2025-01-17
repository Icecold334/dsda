<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\BarangStok;

class DataBarangStok extends Component
{
    use WithPagination;
    public $search = '';
    public $barangs = [];

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
        $this->barangs = BarangStok::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhereHas('jenisStok', function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('merkStok', function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%');
                });
        })->paginate(5);
    }

    public function render()
    {
        return view('livewire.data-barang-stok');
    }
}
