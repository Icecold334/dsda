<?php

namespace App\Livewire;

use App\Models\Lokasi;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AddLokasiData extends Component
{
    public $id;
    public $tipe;
    public $lokasis;
    public $keterangan;
    public $lokasi;

    public function mount()
    {
        $this->lokasis = Lokasi::all();

        if ($this->id) {
            $lokasi = Lokasi::find($this->id);
            $this->lokasi = $lokasi->nama;
            $this->keterangan = $lokasi->keterangan;
        }
    }

    public function removeLokasi()
    {

        Lokasi::destroy($this->id);
        return redirect()->route('Lokasi.index');
    }
    public function saveLokasi()
    {
        Lokasi::updateOrCreate(
            ['id' => $this->id ?? 0], // Unique field to check for existing record
            [
                'user_id' => Auth::user()->id,
                'nama' => $this->lokasi,
                'keterangan' => $this->keterangan,
                'nama_nospace' => strtolower(str_replace(' ', '-', $this->lokasi)),
            ]
        );
    }
    public function render()
    {
        return view('livewire.add-lokasi-data');
    }
}