<?php

namespace App\Livewire;

use App\Models\DetailPengirimanStok;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class UploadSuratJalanMaterial extends Component
{
    use WithFileUploads;


    public $surat_jalan, $pengiriman;
    public $storedSuratJalan;
    public $foto_barang = [];
    public $newFotoBarang = [];
    public $uploaded = false;

    public function mount()
    {
        if ($this->pengiriman) {
            $pengiriman = $this->pengiriman;

            // Isi path surat jalan
            $this->storedSuratJalan = $pengiriman->surat_jalan;

            // Ambil path foto yang sudah diupload sebelumnya
            $this->foto_barang = $pengiriman->fotoPengirimanMaterial->pluck('path')->toArray();

            $this->uploaded = true;
        }
    }


    public function updatedNewFotoBarang()
    {
        $this->foto_barang = array_merge($this->foto_barang, $this->newFotoBarang);
        $this->reset('newFotoBarang');
    }

    public function removeFotoBarang($index)
    {
        unset($this->foto_barang[$index]);
        $this->foto_barang = array_values($this->foto_barang);
    }

    #[On('saveDoc')]
    public function saveDoc($id)
    {
        $pengiriman = DetailPengirimanStok::find($id);
        $this->validate([
            'surat_jalan' => 'required|image|max:2048',
            'foto_barang.*' => 'image|max:2048',
        ]);

        // Simpan Surat Jalan dengan nama asli
        $originalName = $this->surat_jalan->getClientOriginalName();
        $filenameSuratJalan = time() . '-' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $this->surat_jalan->getClientOriginalExtension();
        $this->storedSuratJalan = $this->surat_jalan->storeAs('surat-jalan', $filenameSuratJalan, 'public');
        $data = [];
        // Simpan Foto Barang dengan nama asli
        foreach ($this->foto_barang as $key => $foto) {
            $originalName = $foto->getClientOriginalName();
            $filename = time() . '-' . $key . '-' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $foto->getClientOriginalExtension();
            $this->foto_barang[$key] = $foto->storeAs('foto-barang', $filename, 'public');
            $data[] = ['path' => $this->foto_barang[$key], 'detail_pengiriman_id' => $pengiriman->id, 'created_at' => now(), 'updated_at' => now()];
        }

        $pengiriman->update(['surat_jalan' => $this->storedSuratJalan]);

        $pengiriman->fotoPengirimanMaterial()->insert($data);


        $this->uploaded = true;

        return redirect()->route('pengiriman-stok.index');
    }

    public function render()
    {
        return view('livewire.upload-surat-jalan-material');
    }
}
