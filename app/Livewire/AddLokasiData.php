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
        return redirect()->route('lokasi.index');
    }
    public function saveLokasi()
    {
        $data = [
            'nama' => $this->lokasi,
            'keterangan' => $this->keterangan,
            'nama_nospace' => strtolower(str_replace(' ', '-', $this->lokasi)),
        ];
        // Jika ID diberikan, cari kategori
        $lokasi = Lokasi::find($this->id);

        // Set user_id
        $data['user_id'] = $lokasi ? $lokasi->user_id : Auth::id();
        // Update atau create dengan data
        Lokasi::updateOrCreate(['id' => $this->id ?? 0], $data);

        return redirect()->route('lokasi.index');
    }
    public function render()
    {
        return view('livewire.add-lokasi-data');
    }
}
