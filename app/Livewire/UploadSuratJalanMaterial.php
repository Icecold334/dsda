<?php

namespace App\Livewire;

use App\Models\DetailPengirimanStok;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;

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
        // $this->validate([
        //     'surat_jalan' => 'required|image|max:2048',
        //     'foto_barang.*' => 'image|max:2048',
        // ]);
        

        $storage = new StorageClient([
            'projectId'   => env('GOOGLE_CLOUD_PROJECT_ID'),
            'keyFilePath' => base_path(env('GOOGLE_CLOUD_KEY_FILE')),
        ]);
        $bucket = $storage->bucket(env('GOOGLE_CLOUD_STORAGE_BUCKET'));
        $prefix = config('filesystems.disks.gcs.path_prefix'); // Ambil 'dsda/dev'
        // -----------------------------------------------------------

        // Simpan Surat Jalan dengan nama asli
        $originalName = $this->surat_jalan->getClientOriginalName();
        $filenameSuratJalan = time() . '-' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $this->surat_jalan->getClientOriginalExtension();

        $folderSuratJalan = 'surat-jalan'; // Nama folder di GCS
        $gcsPathSuratJalan = "{$folderSuratJalan}/{$filenameSuratJalan}";
        $objectPathSuratJalan = trim("{$prefix}/{$gcsPathSuratJalan}", '/');

        // Upload Surat Jalan ke GCS
        $bucket->upload(
            file_get_contents($this->surat_jalan->getRealPath()),
            [
                'name' => $objectPathSuratJalan,
                'predefinedAcl' => 'publicRead', // <-- OTOMATIS PUBLIC
            ]
        );
        // Simpan path relatif GCS
        $this->storedSuratJalan = $gcsPathSuratJalan;


        $data = [];
        // Simpan Foto Barang dengan nama asli
        foreach ($this->foto_barang as $key => $foto) {
            $originalName = $foto->getClientOriginalName();
            $filename = time() . '-' . $key . '-' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $foto->getClientOriginalExtension();

            $folderFoto = 'foto-barang'; // Nama folder di GCS
            $gcsPathFoto = "{$folderFoto}/{$filename}";
            $objectPathFoto = trim("{$prefix}/{$gcsPathFoto}", '/');

            // Upload Foto Barang ke GCS
            $bucket->upload(
                file_get_contents($foto->getRealPath()),
                [
                    'name' => $objectPathFoto,
                    'predefinedAcl' => 'publicRead', // <-- OTOMATIS PUBLIC
                ]
            );

            // Simpan path relatif GCS
            $this->foto_barang[$key] = $gcsPathFoto;
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
