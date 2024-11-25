<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;


class DaftarCetak extends Component
{
    public $selectedAssets = [];

    // protected $listeners = [''];
    #[On('tambahAset')]
    public function addAsset($aset)
    {
        // Tambahkan aset tanpa memeriksa ID duplikat
        $this->selectedAssets[] = $aset;
    }

    public function removeAsset($index)
    {
        // Hapus aset berdasarkan indeks
        if (isset($this->selectedAssets[$index])) {
            unset($this->selectedAssets[$index]);
        }

        // Reindeks array agar tetap konsisten
        $this->selectedAssets = array_values($this->selectedAssets);
    }

    public function render()
    {
        return view('livewire.daftar-cetak');
    }
}
