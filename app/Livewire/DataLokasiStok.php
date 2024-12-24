<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DataLokasiStok extends Component
{

    // public $search = ''; // Properti untuk menyimpan nilai input pencarian
    // public $lokasiStok = []; // Properti untuk menyimpan data lokasi
    use WithPagination; // Gunakan trait

    public $search = ''; // Properti untuk menyimpan nilai input pencarian

    // public function updatingSearch()
    // {
    //     // Reset halaman ke 1 setiap kali pencarian diperbarui
    //     $this->resetPage();
    // }

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

        // $this->lokasiStok =
        return LokasiStok::with(['bagianStok', 'bagianStok.posisiStok'])
            ->when($unit, function ($query) use ($parentUnitId) {
                $query->whereHas('unitKerja', function ($query) use ($parentUnitId) {
                    $this->filterByParentUnit($query, $parentUnitId);
                });
            })
            ->when($this->search, function ($query) use ($parentUnitId) {
                // Filter berdasarkan pencarian, sesuai dengan parentUnitId jika tidak null
                $query->where(function ($query) use ($parentUnitId) {
                    if (!is_null($parentUnitId)) {
                        $query->whereHas('unitKerja', function ($query) use ($parentUnitId) {
                            $this->filterByParentUnit($query, $parentUnitId);
                        });
                    }
                })
                    ->where(function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    });
            })
            // ->get()
            // ->toArray();
            ->paginate(10); // Ganti get() dengan paginate()
    }

    private function filterByParentUnit($query, $parentUnitId)
    {
        $query->where(function ($query) use ($parentUnitId) {
            $query->where('id', $parentUnitId)
                ->orWhere('parent_id', $parentUnitId);
        });
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    public function render()
    {
        // return view('livewire.data-lokasi-stok');
        $lokasiStok = $this->loadData(); // Ambil data paginasi
        return view('livewire.data-lokasi-stok', [
            'lokasiStok' => $lokasiStok, // Kirim data ke tampilan
        ]);
    }
}
