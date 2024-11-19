<?php

namespace App\Livewire;

use App\Models\Toko;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AddToko extends Component
{
    public $id;
    public $tipe;
    public $tokos;
    public $keterangan;
    public $alamat;
    public $telepon;
    public $email;
    public $petugas;
    public $toko;

    public function mount()
    {
        $this->tokos = Toko::all();

        if ($this->id) {
            $toko = Toko::find($this->id);
            $this->toko = $toko->nama;
            $this->alamat = $toko->alamat;
            $this->telepon = $toko->telepon;
            $this->email = $toko->email;
            $this->petugas = $toko->petugas;
            $this->keterangan = $toko->keterangan;
        }
    }

    public function removeToko()
    {
       
            Toko::destroy($this->id);
        return redirect()->route('Toko.index');
    }
    public function saveToko()
    {
            Toko::updateOrCreate(
                ['id' => $this->id ?? 0], // Unique field to check for existing record
                [
                    'user_id' => Auth::user()->id,
                    'nama' => $this->toko,
                    'alamat' => $this->alamat,
                    'telepon' => $this->telepon,
                    'email' => $this->email,
                    'petugas' => $this->petugas,
                    'keterangan' => $this->keterangan,
                    'nama_nospace' => strtolower(str_replace(' ', '-', $this->toko)),
                ]
            );
       
    }

    public function render()
    {
        return view('livewire.add-toko');
    }
}
