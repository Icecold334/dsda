<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BarangStok;
use Illuminate\Support\Str;
use App\Models\KategoriStok;

class AddKategoriStok extends Component
{
    public $id;
    public $nama;
    public $tipe;
    public $kategoris;
    public $barang;
    public $barang_id;
    public $kode_barang;
    public $satuan_besar;
    public $satuan_kecil;
    public $kategori_id;

    public $suggestions = [
        'barang' => [],
    ];

    public function fetchSuggestions($field, $value)
    {

        $this->suggestions[$field] = [];
        $key = Str::slug($value);
        if ($value && $field == 'barang') {
            $this->suggestions[$field] = BarangStok::where('jenis_id', 3) // Filter jenis_id = 3
                ->where('slug', 'like', '%' . $key . '%') // Filter berdasarkan slug
                ->pluck('nama')->toArray();
        }
    }

    public function selectSuggestion($field, $value)
    {
        if ($field === 'barang') {
            // Cari barang berdasarkan nama
            $barang = BarangStok::where('nama', $value)->first();

            if ($barang) {
                // Simpan data barang ke properti Livewire
                $this->barang_id = $barang->id;
                $this->barang = $barang->nama;
                $this->kode_barang = $barang->kode_barang;
                $this->satuan_besar = $barang->satuan_besar;
                $this->satuan_kecil = $barang->satuan_kecil;
            }
        }
        $this->suggestions[$field] = [];
    }


    public function hideSuggestions($field)
    {
        $this->suggestions[$field] = [];
        // $this->showSuggestionsMerk = false;
    }


    public function mount()
    {
        if ($this->id) {
            $kategori = KategoriStok::find($this->id);
            if ($kategori) {
                $this->nama = $kategori->nama;
                $this->kategori_id = $kategori->id;
                // $this->selectedBarang = BarangStok::find($kategori->id);
            }
        }
    }

    public function save()
    {
        // Validasi data sebelum menyimpan
        $this->validate([
            'nama' => 'required|string|max:255', // Nama kategori stok wajib diisi
        ]);

        // Buat slug dari nama
        $slug = Str::slug($this->nama);

        // Gunakan updateOrCreate untuk membuat atau memperbarui kategori stok
        $kategori = KategoriStok::updateOrCreate(
            ['id' => $this->id], // Kondisi untuk update jika ID ada
            [
                'nama' => $this->nama,
                'slug' => $slug, // Tambahkan slug
            ]
        );


        if (!empty($this->barang)) {

            // Periksa apakah barang sudah ada
            $existingBarangInCategory = BarangStok::where('kategori_id', $kategori->id)
                ->where('nama', $this->barang)
                ->first();
            if ($existingBarangInCategory) {
                // Jika barang dengan nama yang sama sudah ada dalam kategori, tampilkan error
                session()->flash('error', 'Barang sudah ada dalam kategori ini.');
                return;
            }

            // Periksa apakah barang sudah ada secara global untuk mendapatkan informasi tambahan
            $existingBarang = BarangStok::where('nama', $this->barang)->first();

            $kode_barang = $existingBarang->kode_barang;
            $satuan_besar = $existingBarang->satuan_besar_id;
            $satuan_kecil = $existingBarang->satuan_kecil_id;

            BarangStok::create([
                'kategori_id' => $kategori->id, // Hubungkan barang ke kategori
                'jenis_id' => 3, // Pastikan jenis_id sesuai
                'nama' => $this->barang, // Nama barang
                'slug' => Str::slug($this->barang), // Buat slug dari nama barang
                'kode_barang' => $kode_barang, // Kode barang
                'satuan_besar_id' => $satuan_besar, // Satuan besar
                'satuan_kecil_id' => $satuan_kecil, // Satuan kecil
            ]);
        }

        // Flash message untuk notifikasi sukses
        session()->flash('message', $this->id ? 'Kategori Stok dan barang berhasil diperbarui!' : 'Kategori Stok dan barang berhasil ditambahkan!');

        // Redirect atau refresh halaman
        return redirect()->route('kategori-stok.index');
    }


    public function render()
    {
        return view('livewire.add-kategori-stok');
    }
}
