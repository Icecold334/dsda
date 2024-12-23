<?php

namespace App\Livewire;

use App\Models\Aset;
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
        // Cek apakah ID kategori sedang digunakan di tabel aset
        $isUsedInAset = Aset::where('merk_id', $this->id)->exists();

        if ($isUsedInAset) {
            // dd($isUsedInAset);
            // Berikan pesan error jika kategori sedang digunakan
            return redirect()->route('merk.index')->with('error', 'Merk ini sedang digunakan di tabel aset dan tidak dapat dihapus.');
        }

        Merk::destroy($this->id);
        return redirect()->route('merk.index')->with('success', 'Merk berhasil dihapus.');
    }
    public function saveMerk()
    {
        // <<<<<<< support
        //         $data = [
        //             'nama' => $this->merk,
        //             'keterangan' => $this->keterangan,
        //             'nama_nospace' => Str::slug($this->merk),
        //         ];
        //         // Jika ID diberikan, cari kategori
        //         $merk = Merk::find($this->id);

        //         // Set user_id
        //         $data['user_id'] = $merk ? $merk->user_id : Auth::id();

        //         // Update atau create dengan data
        //         Merk::updateOrCreate(['id' => $this->id ?? 0], $data);

        //         return redirect()->route('merk.index');
        // =======
        $merk = Merk::updateOrCreate(
            ['id' => $this->id ?? 0], // Unique field to check for existing record
            [
                'user_id' => Auth::user()->id,
                'nama' => $this->merk,
                'keterangan' => $this->keterangan,
                'nama_nospace' => Str::slug($this->merk),
            ]
        );
        if ($merk->wasRecentlyCreated && $this->merk) {
            return redirect()->route('merk.index')->with('success', 'Berhasil Menambah Merk');
        } else {
            return redirect()->route('merk.index')->with('success', 'Berhasil Mengubah Merk');
        }
        //       return redirect()->route('merk.index')->with('success', 'Berhasil Menambah Merk');
        // >>>>>>> main
    }
    public function render()
    {
        return view('livewire.add-merk');
    }
}
