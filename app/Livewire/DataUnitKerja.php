<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UnitKerja;

class DataUnitKerja extends Component
{
    public $units = [];
    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->units = UnitKerja::with(['children'])
            ->whereNull('parent_id') // Ambil hanya parent UnitKerja
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%') // Filter parent berdasarkan pencarian
                    ->orWhereHas('children', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%'); // Filter children berdasarkan pencarian
                    });
            })
            ->get()
            ->map(function ($unit) {
                // Jika parent cocok, kembalikan semua children
                if ($this->search && stripos($unit->nama, $this->search) !== false) {
                    return $unit;
                }

                // Jika children yang cocok, filter children dan tetap tampilkan parent 
                $unit->children = $unit->children->filter(function ($child) {
                    return stripos($child->nama, $this->search) !== false;
                })->values();

                return $unit;
            })
            ->filter(function ($unit) {
                // Jika parent dan semua children tidak cocok, hapus dari hasil
                return $this->search
                    ? stripos($unit->nama, $this->search) !== false || $unit->children->isNotEmpty()
                    : true;
            })
            ->toArray();
    }



    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.data-unit-kerja');
    }
}
