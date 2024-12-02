<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class AddKategori extends Component
{
    public $id;
    public $kategori;
    public $tipe;
    public $kategoris;
    public $keterangan;
    public $kategori_id;
    public $parent_id;
    public $utama;
    public $sub;

    public function mount()
    {
        if ($this->tipe === 'sub') {
            $this->kategoris = Kategori::whereNull('parent_id')->get();
            if ($this->id) {
                $sub = Kategori::find($this->id);
                if ($sub) {
                    $this->sub = $sub->nama;
                    $this->kategori_id = $sub->kategori_id;
                    $this->parent_id = $sub->parent_id;
                    $this->keterangan = $sub->keterangan;
                }
            }
        } else {
            if ($this->id) {
                $utama = Kategori::find($this->id);
                if ($utama) {
                    $this->utama = $utama->nama;
                    $this->keterangan = $utama->keterangan;
                }
            }
        }
    }

    public function removeKategori()
    {
        if ($this->id) {
            $kategori=Kategori::find($this->id);
            if ($kategori->parent_id!=null) {
                Kategori::destroy($this->id);
                return redirect()->route('kategori.index')->with('success', 'Berhasil Dihapus');
            }
            Kategori::destroy($this->id);
            return redirect()->route('kategori.index')->with('success', 'Berhasil Dihapus');
        }

        return redirect()->route('kategori.index')->with('error', 'Kategori tidak ditemukan');
    }

    public function saveKategori()
    {
        // Siapkan data kategori berdasarkan tipe
        $data = [
            'user_id' => Auth::id(),
            'nama' => $this->tipe === 'utama' ? $this->utama : $this->sub,
            'keterangan' => $this->keterangan,
        ];
    
        // Tambahkan parent_id jika tipe adalah sub
        if ($this->tipe === 'sub') {
            $data['parent_id'] = $this->parent_id;
        }
    
        // Simpan atau update data kategori
        $kategori = Kategori::updateOrCreate(
            ['id' => $this->id ?? 0], // Cek data berdasarkan ID
            $data
        );
    
        // Tentukan pesan keberhasilan
        $message = $kategori->wasRecentlyCreated
            ? 'Berhasil Menambah Kategori'
            : 'Berhasil Mengubah Kategori';
            
            if ($kategori->parent_id===null) {
                return redirect()->route('kategori.index')->with('success', $message);
            }
                
        if ($kategori->wasRecentlyCreated && $this->tipe==='sub'){
            return redirect()->route('kategori.index')->with('success', 'Berhasil Menambah Sub Kategori');
        }
        else {
            return redirect()->route('kategori.index')->with('success', 'Berhasil Mengubah Sub Kategori');
        }

//        return redirect()->route('kategori.index')->with('success', $message);
    }
    

    public function render()
    {
        return view('livewire.add-kategori');
    }
}
