<?php

namespace App\Livewire;

use App\Models\UnitKerja;
use Livewire\Component;

class DataUnitKerja extends Component
{
    // public $units = [];
    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $query = UnitKerja::with(['children'])
            ->whereNull('parent_id')  // Ambil hanya parent UnitKerja
            ->when($this->search, function ($query) {
                $query
                    ->where('nama', 'like', '%' . $this->search . '%')  // Filter parent berdasarkan pencarian
                    ->orWhereHas('children', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');  // Filter children berdasarkan pencarian
                    });
            });

        // Lakukan paginasi terlebih dahulu
        $paginated = $query->paginate(3);

        // Manipulasi hasil setelah paginasi
        $paginated->getCollection()->transform(function ($unit) {
            // Jika parent cocok, kembalikan semua children
            if ($this->search && stripos($unit->nama, $this->search) !== false) {
                return $unit;
            }

            // Jika children yang cocok, filter children dan tetap tampilkan parent
            $unit->children = $unit->children->filter(function ($child) {
                return stripos($child->nama, $this->search) !== false;
            })->values();

            return $unit;
        });

        return $paginated;
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function render()
    {
        $units = $this->loadData();
        return view('livewire.data-unit-kerja', compact('units'));
    }
}
