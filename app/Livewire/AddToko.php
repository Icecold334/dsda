<?php

namespace App\Livewire;

use App\Models\Toko;
use Livewire\Component;
use Illuminate\Support\Str;
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
        return redirect()->route('toko.index')->with('success', 'Berhasil Dihapus');
    }
    public function saveToko()
    {
// <<<<<<< support
//         $data = [
//             'nama' => $this->toko,
//             'alamat' => $this->alamat,
//             'telepon' => $this->telepon,
//             'email' => $this->email,
//             'petugas' => $this->petugas,
//             'keterangan' => $this->keterangan,
//             'nama_nospace' => Str::slug($this->toko),
//         ];
//         // Jika ID diberikan, cari kategori
//         $toko = Toko::find($this->id);

//         // Set user_id
//         $data['user_id'] = $toko ? $toko->user_id : Auth::id();

//         // Update atau create dengan data
//         Toko::updateOrCreate(['id' => $this->id ?? 0], $data);

//         return redirect()->route('toko.index');
// =======
        $toko=Toko::updateOrCreate(
            ['id' => $this->id ?? 0], // Unique field to check for existing record
            [
                'user_id' => Auth::user()->id,
                'nama' => $this->toko,
                'alamat' => $this->alamat,
                'telepon' => $this->telepon,
                'email' => $this->email,
                'petugas' => $this->petugas,
                'keterangan' => $this->keterangan,
                'nama_nospace' => Str::slug($this->toko),
            ]
        );
        
        if ($toko->wasRecentlyCreated && $this->toko){
            return redirect()->route('toko.index')->with('success', 'Berhasil Menambah Toko');
        }
        else {
            return redirect()->route('toko.index')->with('success', 'Berhasil Mengubah Toko');
        }
// >>>>>>> main
    }

    public function render()
    {
        return view('livewire.add-toko');
    }
}
