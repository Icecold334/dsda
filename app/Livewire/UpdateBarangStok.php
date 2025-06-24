<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MerkStok;
use App\Models\JenisStok;
use App\Models\BarangStok;
use App\Models\KategoriStok;
use App\Models\SatuanBesar;

class UpdateBarangStok extends Component
{
    public $barang;
    public $id;
    public $kode_barang;
    public $stok;
    public $jenis, $satuan, $satuans;
    public $kategori, $minimal;
    public $kategori_stok;
    public $description;
    public $tipe;
    public $tipe_stok;
    public $ukuran;
    public $jenis_stok;

    public function mount()
    {
        if ($this->tipe == 'stok') {
            if ($this->id) {
                $stok = MerkStok::find($this->id);
                $this->stok = $stok->nama;
                $this->tipe_stok = $stok->tipe;
                $this->ukuran = $stok->ukuran;
            }
        } else {
            $this->jenis_stok = JenisStok::all();
            $this->satuans = SatuanBesar::all();
            $this->kategori_stok = KategoriStok::all();
            if ($this->id) {
                $barang = BarangStok::find($this->id);
                $this->barang = $barang->nama;
                $this->satuan = $barang->satuanBesar->id;
                $this->minimal = $barang->minimal;
                $this->kode_barang = $barang->kode_barang;
                $this->jenis = $barang->jenis_id;
                $this->kategori = $barang->kategori_id;
                $this->description = $barang->deskripsi;
            }
        }
    }

    public function save()
    {
        if ($this->tipe == 'stok') {
            $stok = MerkStok::find($this->id);
            $stok->update( // Unique fields to check
                [
                    'nama' => $this->stok,
                    'tipe' => $this->tipe_stok,
                    'ukuran' => $this->ukuran,
                ]
            );
            // Mendapatkan ID barang dari relasi stok ke barang
            $barangId = $stok->barang_id;

            return redirect()->route('barang.show', ['barang' => $barangId])->with('success', 'Berhasil Mengubah Data Stok');
        } else {
            $barang = BarangStok::find($this->id);
            // dd($this->jenis);    
            $barang->update( // Unique fields to check
                [
                    'nama' => $this->barang,
                    'kode_barang' => $this->kode_barang,
                    'jenis_id' => $this->jenis,
                    'satuan_besar_id' => $this->satuan,
                    'minimal' => $this->minimal,
                    'deskripsi' => $this->description,
                    'kategori_id' => $this->kategori ?? null,
                ]
            );
            return redirect()->route('barang.show', ['barang' => $this->id])->with('success', 'Berhasil Mengubah Data Barang');
        }
    }

    public function remove()
    {
        if ($this->tipe == 'stok') {
            $stok = MerkStok::find($this->id);

            if ($stok) {
                // Mendapatkan ID barang terkait sebelum stok dihapus
                $barangId = $stok->barang_id;
                $stok->delete();

                return redirect()->route('barang.show', ['barang' => $barangId])
                    ->with('success', 'Berhasil Menghapus Data Stok');
            }

            return redirect()->route('barang.index')
                ->with('error', 'Data Stok Tidak Ditemukan');
        } else {
            $barang = BarangStok::find($this->id);

            if ($barang) {
                $barang->delete();

                return redirect()->route('barang.index')
                    ->with('success', 'Berhasil Menghapus Data Barang');
            }

            return redirect()->route('barang.index')
                ->with('error', 'Data Barang Tidak Ditemukan');
        }
    }


    public function render()
    {
        return view('livewire.update-barang-stok');
    }
}
