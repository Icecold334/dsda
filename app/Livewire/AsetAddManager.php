<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class AsetAddManager extends Component
{
    public $assetsData = [];

    // protected $listeners = ['save-data'];
    #[On('send-umum')]
    public function umumReceived($umum)
    {
        
        $this->assetsData[] = $umum;
        if (count($this->assetsData) == 7) {
            dd($this->assetsData);
        }
    }
    #[On('send-detail')]
    public function detailReceived($detail)
    {
        $this->assetsData[] = $detail;
        if (count($this->assetsData) == 7) {
            dd($this->assetsData);
        }
    }
    #[On('send-pembelian')]
    public function pembelianReceived($pembelian)
    {
        $this->assetsData[] = $pembelian;
        if (count($this->assetsData) == 7) {
            dd($this->assetsData);
        }
    }
    #[On('send-foto')]
    public function fotoReceived($foto)
    {
        $this->assetsData[] = $foto;
        if (count($this->assetsData) == 7) {
            dd($this->assetsData);
        }
    }
    #[On('send-lampiran')]
    public function lampiranReceived($lampiran)
    {
        $this->assetsData[] = $lampiran;
        if (count($this->assetsData) == 7) {
            dd($this->assetsData);
        }
    }
    #[On('send-keterangan')]
    public function keteranganReceived($keterangan)
    {
        $this->assetsData[] = $keterangan;
        if (count($this->assetsData) == 7) {
            dd($this->assetsData);
        }
    }
    #[On('send-penyusutan')]
    public function penyusutanReceived($penyusutan)
    {
        $this->assetsData[] = $penyusutan;
        if (count($this->assetsData) == 7) {
            dd($this->assetsData);
        }
    }

    public function saveAssets()
    {
        // Logic to save all assets data
        // This could involve iterating over $this->assetsData and saving each item to the database
    }
    public function render()
    {
        return view('livewire.aset-add-manager');
    }
}
