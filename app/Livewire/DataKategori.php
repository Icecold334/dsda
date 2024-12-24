<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kategori;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class DataKategori extends Component
{
    public $kategoris = [];
    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user (jika tidak null)
        $unit = $userUnitId ? UnitKerja::find($userUnitId) : null;

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Ambil data kategori
        $this->kategoris = Kategori::with(['children' => function ($query) use ($parentUnitId, $userUnitId) {
            $query->withCount(['aset' => function ($query) use ($parentUnitId) {
                if (!is_null($parentUnitId)) {
                    $query->whereHas('user', function ($query) use ($parentUnitId) {
                        $query->where('unit_id', $parentUnitId);
                    });
                }
            }]);
        }])
            ->whereNull('parent_id')
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhereHas('children', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    });
            })
            ->withCount(['aset' => function ($query) use ($parentUnitId) {
                if (!is_null($parentUnitId)) {
                    $query->whereHas('user', function ($query) use ($parentUnitId) {
                        $query->where('unit_id', $parentUnitId);
                    });
                }
            }])
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
        return view('livewire.data-kategori');
    }
}
