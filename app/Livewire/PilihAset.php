<?php

namespace App\Livewire;

use App\Models\Aset;
use Livewire\Component;
use App\Models\Kategori;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class PilihAset extends Component
{
    public $search = '';
    public $selectedCategory = '';
    public $assets = [];
    public $selectedAssets = [];

    public function mount()
    {
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;
        $this->assets = Aset::with('kategori')->where('status', true)->when($unit, function ($query) use ($parentUnitId) {
            $query->whereHas('user', function ($query) use ($parentUnitId) {
                filterByParentUnit($query, $parentUnitId);
            });
        })->get(); // Ambil semua aset dengan kategori
    }

    public function updatedSearch()
    {
        $this->filterAssets();
    }

    public function updatedSelectedCategory()
    {
        $this->filterAssets();
    }

    public function filterAssets()
    {
        $query = Aset::with('kategori');

        if ($this->selectedCategory) {
            $query->where('kategori_id', $this->selectedCategory);
        }

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%');
        }

        $this->assets = $query->get();
    }

    // public function addToSelected($assetId)
    // {
    //     $asset = Aset::find($assetId);

    //     if ($asset && !in_array($assetId, array_column($this->selectedAssets, 'id'))) {
    //         $this->selectedAssets[] = $asset;
    //         $this->dispatch('tambahAset', aset:$asset);
    //     }
    // }

    public function addToSelected($assetId)
    {
        $asset = Aset::find($assetId);

        if ($asset) {
            // Tambahkan aset langsung ke selectedAssets tanpa memeriksa duplikasi
            $this->selectedAssets[] = $asset;
            $this->dispatch('tambahAset', aset: $asset); // Trigger event jika diperlukan
        }
    }


    public function render()
    {
        $kategoris = Kategori::whereNull('parent_id')->with('children')->get();
        return view('livewire.pilih-aset', compact('kategoris'));
    }
}
