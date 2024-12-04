<?php

namespace App\Livewire;

use App\Models\Merk;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AddMerk extends Component
{
    public $id;
    public $tipe;
    public $merks;
    public $keterangan;
    public $merk;

    public function mount()
    {
        $this->merks = Merk::all();

        if ($this->id) {
            $merk = Merk::find($this->id);
            $this->merk = $merk->nama;
            $this->keterangan = $merk->keterangan;
        }
    }

    public function removeMerk()
    {
        Merk::destroy($this->id);
        return redirect()->route('merk.index')->with('success', 'Berhasil Dihapus');
    }
    public function saveMerk()
    {
        $merk=Merk::updateOrCreate(
            ['id' => $this->id ?? 0], // Unique field to check for existing record
            [
                'user_id' => Auth::user()->id,
                'nama' => $this->merk,
                'keterangan' => $this->keterangan,
                'nama_nospace' => strtolower(str_replace(' ', '-', $this->merk)),
            ]
        );
        if ($merk->wasRecentlyCreated && $this->merk){
            return redirect()->route('merk.index')->with('success', 'Berhasil Menambah Merk');
        }
        else {
            return redirect()->route('merk.index')->with('success', 'Berhasil Mengubah Merk');
        }
 //       return redirect()->route('merk.index')->with('success', 'Berhasil Menambah Merk');
    }
    public function render()
    {
        return view('livewire.add-merk');
    }
}
