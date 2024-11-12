<?php

namespace App\Livewire;

use App\Models\BagianStok;
use App\Models\LokasiStok;
use App\Models\PosisiStok;
use Livewire\Component;

class AddLokasi extends Component
{
    public $tipe;
    public $lokasi;
    public $alamat;

    public $lokasis;
    public $lokasi_id;
    public $bagian;

    public $bagians;
    public $bagian_id;
    public $posisi_id;
    public $posisi;

    public function mount()
    {
        if ($this->tipe == 'bagian') {
            $this->lokasis = LokasiStok::all();
        } elseif ($this->tipe == 'posisi') {
            $this->lokasis = LokasiStok::all();
            // $this->bagians = BagianStok::all();
        }
    }
    public function saveLokasi()
    {
        if ($this->tipe == 'lokasi') {
            LokasiStok::updateOrCreate(
                ['id' => $this->lokasi_id], // Unique field to check for existing record
                [
                    'nama' => $this->lokasi,
                    'alamat' => $this->alamat,
                ]
            );
        } elseif ($this->tipe == 'bagian') {
            BagianStok::updateOrCreate(
                ['id' => $this->bagian_id], // Unique fields to check
                [
                    'lokasi_id' => $this->lokasi_id,
                    'nama' => $this->bagian,
                ]
            );
        } elseif ($this->tipe == 'posisi') {
            PosisiStok::updateOrCreate(
                ['id' => $this->posisi_id], // Unique fields to check
                [
                    'bagian_id' => $this->bagian_id,
                    'nama' => $this->posisi,
                ]
            );
        }

        return redirect()->route('lokasi-stok.index');
    }
    public function render()
    {
        return view('livewire.add-lokasi');
    }
}
