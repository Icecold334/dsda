<?php

namespace App\Livewire;

use App\Models\History;
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
        // Cek apakah ID kategori sedang digunakan di tabel aset
        $isUsedInLokasi = History::where('lokasi_id', $this->id)->exists();

        if ($isUsedInLokasi) {
            // Berikan pesan error jika kategori sedang digunakan
            return redirect()->route('lokasi.index')->with('error', 'Lokasi ini sedang digunakan di tabel aset dan tidak dapat dihapus.');
        }
        Lokasi::destroy($this->id);
        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil dihapus.');
    }
    public function saveLokasi()
    {
        // <<<<<<< support
        //         $data = [
        //             'nama' => $this->lokasi,
        //             'keterangan' => $this->keterangan,
        //             'nama_nospace' => strtolower(str_replace(' ', '-', $this->lokasi)),
        //         ];
        //         // Jika ID diberikan, cari kategori
        //         $lokasi = Lokasi::find($this->id);

        //         // Set user_id
        //         $data['user_id'] = $lokasi ? $lokasi->user_id : Auth::id();
        //         // Update atau create dengan data
        //         Lokasi::updateOrCreate(['id' => $this->id ?? 0], $data);

        //         return redirect()->route('lokasi.index');
        // =======
        $lokasi = Lokasi::updateOrCreate(
            ['id' => $this->id ?? 0], // Unique field to check for existing record
            [
                'user_id' => Auth::user()->id,
                'nama' => $this->lokasi,
                'keterangan' => $this->keterangan,
                'nama_nospace' => strtolower(str_replace(' ', '-', $this->lokasi)),
            ]
        );
        if ($lokasi->wasRecentlyCreated && $this->lokasi) {
            return redirect()->route('lokasi.index')->with('success', 'Berhasil Menambah Lokasi');
        } else {
            return redirect()->route('lokasi.index')->with('success', 'Berhasil Mengubah Lokasi');
        }
        //        return redirect()->route('lokasi.index');
        // >>>>>>> main
    }
    public function render()
    {
        return view('livewire.add-lokasi-data');
    }
}
