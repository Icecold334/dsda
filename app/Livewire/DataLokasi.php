<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lokasi;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class DataLokasi extends Component
{

    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    public $lokasis = []; // Properti untuk menyimpan data lokasi

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
        $this->lokasis = Lokasi::withCount(['history as aset_count' => function ($query) use ($parentUnitId, $userUnitId) {
            // Jika parentUnitId null, jangan filter berdasarkan unit_id
            if (!is_null($parentUnitId)) {
                $query->whereHas('aset.user', function ($query) use ($parentUnitId) {
                    $query->where('unit_id', $parentUnitId);
                });
            }
        }])
            ->when($unit, function ($query) use ($parentUnitId) {
                $query->whereHas('user', function ($query) use ($parentUnitId) {
                    filterByParentUnit($query, $parentUnitId);
                });
            })
            ->when($this->search, function ($query) use ($parentUnitId) {
                // Filter berdasarkan pencarian, sesuai dengan parentUnitId jika tidak null
                $query->where(function ($query) use ($parentUnitId) {
                    if (!is_null($parentUnitId)) {
                        $query->whereHas('user', function ($query) use ($parentUnitId) {
                            filterByParentUnit($query, $parentUnitId);
                        });
                    }
                })
                    ->where(function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%')
                            ->orWhere('keterangan', 'like', '%' . $this->search . '%');
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
        return view('livewire.data-lokasi');
    }
}
