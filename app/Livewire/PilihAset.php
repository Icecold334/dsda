<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Aset;
use App\Models\Kategori;

class PilihAset extends Component
{
    public $search = '';
    public $selectedCategory = '';
    public $assets = [];
    public $selectedAssets = [];

    public function mount()
    {
        $this->assets = Aset::with('kategori')->where('status', true)->get(); // Ambil semua aset dengan kategori
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
