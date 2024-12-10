<?php

namespace App\Livewire;

use App\Models\BagianStok;
use App\Models\LokasiStok;
use App\Models\PosisiStok;
use Livewire\Component;

class AddLokasi extends Component
{
    public $id;

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
            if ($this->id) {
                $bagian = BagianStok::find($this->id);
                $this->bagian = $bagian->nama;
                $this->lokasi_id = $bagian->lokasi_id;
            }
        } elseif ($this->tipe == 'posisi') {
            $this->lokasis = LokasiStok::all();
            // $this->bagians = BagianStok::all();
            if ($this->id) {
                $posisi = PosisiStok::find($this->id);
                $this->bagian_id = $posisi->bagian_id;
                $this->posisi = $posisi->nama;
            }
        } else {
            if ($this->id) {
                $lokasi = LokasiStok::find($this->id);
                $this->lokasi = $lokasi->nama;
                $this->alamat = $lokasi->alamat;
            }
        }
    }
    public function removeLokasi()
    {
        if ($this->tipe == 'lokasi') {
            LokasiStok::destroy($this->id);
        } elseif ($this->tipe == 'bagian') {
            BagianStok::destroy($this->id);
        } elseif ($this->tipe == 'posisi') {
            PosisiStok::destroy($this->id);
        }

        return redirect()->route('lokasi-stok.index');
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
