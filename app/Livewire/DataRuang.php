<?php

namespace App\Livewire;

use App\Models\Ruang;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class DataRuang extends Component
{
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    // public $merks = [];

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
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Query untuk mengambil daftar Ruang berdasarkan parent unit dan pencarian
        $lists = Ruang::with(['user'])
            ->when($parentUnitId, function ($query) use ($parentUnitId) {
                $query->whereHas('user', function ($query) use ($parentUnitId) {
                    $this->filterByParentUnit($query, $parentUnitId);
                });
            })
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return $lists;
    }

    function filterByParentUnit($query, $parentUnitId)
    {
        return $query->whereHas('unitKerja', function ($unitQuery) use ($parentUnitId) {
            // Pastikan kita selalu memfilter berdasarkan unit parent
            $unitQuery->where('parent_id', $parentUnitId)
                ->orWhere('id', $parentUnitId); // Menampilkan kategori yang terkait dengan parent atau child
        });
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }
    public function render()
    {
        $ruangs = $this->loadData();
        return view('livewire.data-ruang', compact('ruangs'));
    }
}
