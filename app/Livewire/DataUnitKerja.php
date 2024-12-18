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
        // Ambil data unit kerja tanpa filter unit_id dan tanpa menghitung aset
        $this->units = UnitKerja::with(['children' => function ($query) {
            // Filter berdasarkan nama jika pencarian dilakukan
            if ($this->search) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            }
        }])
            ->whereNull('parent_id') // Hanya ambil parent unit kerja
            ->when($this->search, function ($query) {
                // Filter parent unit berdasarkan pencarian
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhereHas('children', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    });
            })
            ->get()
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
