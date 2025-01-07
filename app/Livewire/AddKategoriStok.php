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
    public $kategori;
    public $barang;
    public $barang_id;
    public $kode_barang;
    public $satuan_besar;
    public $satuan_kecil;
    public $kategori_id;

    public $suggestions = [
        'barang' => [],
    ];

    public $filteredBarang = []; // Properti untuk menyimpan data barang yang difilter

    public function fetchSuggestions($field, $value)
    {
        $key = Str::slug($value); // Ubah input menjadi slug untuk pencarian

        if ($field === 'barang') {
            if (empty($key)) {
                // Jika input kosong, tampilkan semua barang
                $this->filteredBarang = $this->kategori->barangStok;
            } else {
                // Filter barang berdasarkan input
                $this->filteredBarang = $this->kategori->barangStok->filter(function ($barang) use ($key) {
                    return Str::contains(Str::slug($barang->nama), $key);
                });
            }
        }
    }
    public function hideSuggestions($field)
    {
        $this->suggestions[$field] = [];
    }


    public function mount()
    {
        if ($this->id) {
            $kategoris = KategoriStok::with('BarangStok.satuanBesar', 'BarangStok.satuanKecil')->find($this->id);
            // dd($kategoris);
            if ($kategoris) {
                $this->nama = $kategoris->nama;
                $this->kategori_id = $kategoris->id;
                $this->kategori = $kategoris;
                // Set default barang yang ditampilkan
                $this->filteredBarang = $kategoris->barangStok;
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
        KategoriStok::updateOrCreate(
            ['id' => $this->id], // Kondisi untuk update jika ID ada
            [
                'nama' => $this->nama,
                'slug' => $slug, // Tambahkan slug
            ]
        );

        // Redirect atau refresh halaman
        return redirect()->route('kategori-stok.index')->with('success', 'Kategori Stok berhasil disimpan!');
    }

    public function remove()
    {
        // Jika tidak digunakan, hapus kategori
        KategoriStok::destroy($this->id);

        // Redirect dengan pesan sukses
        return redirect()->route('kategori-stok.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.add-kategori-stok');
    }
}
