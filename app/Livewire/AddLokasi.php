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
        return redirect()->route('lokasi-stok.index')->with('success', 'Berhasil Dihapus');
    }
    public function saveLokasi()
    {
        if ($this->tipe === 'lokasi') {
            $exist=LokasiStok::where('id', $this->id)->first();
            if($exist) {
                $lokasistok=LokasiStok::updateOrCreate(
                    ['id' => $this->id], // Unique field to check for existing record
                    [
                        'nama' => $this->lokasi,
                        'alamat' => $this->alamat,
                    ]
                );
                    return redirect()->route('lokasi-stok.index')->with('success', 'Berhasil Mengubah Lokasi');
            } else {
                $lokasistok=LokasiStok::updateOrCreate(
                    ['id' => $this->lokasi_id], // Unique field to check for existing record
                    [
                        'nama' => $this->lokasi,
                        'alamat' => $this->alamat,
                    ]
                );
                    return redirect()->route('lokasi-stok.index')->with('success', 'Berhasil Menambah Lokasi');
            }
            
        } elseif ($this->tipe == 'bagian') {
            $exist=BagianStok::where('id', $this->id)->first();
            if($exist) {
                $bagianstok=BagianStok::updateOrCreate(
                    ['id' => $this->id], // Unique field to check for existing record
                    [
                        'lokasi_id' => $this->lokasi_id,
                        'nama' => $this->bagian,
                    ]
                );
                    return redirect()->route('lokasi-stok.index')->with('success', 'Berhasil Mengubah Bagian');
            } else {
                $bagianstok=BagianStok::updateOrCreate(
                    ['id' => $this->lokasi_id], // Unique field to check for existing record
                    [
                        'lokasi_id' => $this->lokasi_id,
                        'nama' => $this->bagian,
                    ]
                );
                    return redirect()->route('lokasi-stok.index')->with('success', 'Berhasil Menambah Bagian');
            }
        } elseif ($this->tipe == 'posisi') {
            $exist=PosisiStok::where('id', $this->id)->first();
            if($exist) {
                $posisistok=PosisiStok::updateOrCreate(
                    ['id' => $this->id], // Unique field to check for existing record
                    [
                        'bagian_id' => $this->bagian_id,
                        'nama' => $this->posisi,
                    ]
                );
                    return redirect()->route('lokasi-stok.index')->with('success', 'Berhasil Mengubah Posisi');
            } else {
                $posisistok=PosisiStok::updateOrCreate(
                    ['id' => $this->lokasi_id], // Unique field to check for existing record
                    [
                        'bagian_id' => $this->bagian_id,
                        'nama' => $this->posisi,
                    ]
                );
                    return redirect()->route('lokasi-stok.index')->with('success', 'Berhasil Menambah Posisi');
            }
        }
    }
    public function render()
    {
        return view('livewire.add-lokasi');
    }
}
