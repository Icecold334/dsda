<?php

namespace App\Livewire;

use App\Models\Toko;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class DataToko extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = ''; // Properti untuk menyimpan nilai input pencarian
    // public $tokos = [];

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

        $lists = Toko::withCount(['aset' => function ($query) use ($parentUnitId, $userUnitId) {
            // Jika unit_id user null, jangan filter unit_id (ambil semua aset)
            if (!is_null($userUnitId)) {
                $query->whereHas('user', function ($query) use ($parentUnitId) {
                    $query->where('unit_id', $parentUnitId);
                });
            }
        }])
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('petugas', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%')
                    ->orWhere('telepon', 'like', '%' . $this->search . '%');
            })
            ->paginate(5);
            return $lists;
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }
    public function render()
    {
        $tokos = $this->loadData();
        return view('livewire.data-toko', compact('tokos'));
    }
}
