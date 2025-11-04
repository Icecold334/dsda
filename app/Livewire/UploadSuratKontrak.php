<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LampiranRab;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\DokumenKontrakStok;
use App\Models\LampiranPermintaan;
use function Laravel\Prompts\error;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Storage\StorageClient;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class UploadSuratKontrak extends Component
{

    use WithFileUploads;


    public $attachments = [], $isRab;
    public $newAttachments = [];

    public function mount()
    {
        $this->isRab = Request::is('rab/create');
        // $this->text = $this->isRab ? "" : "Anda bisa mengunggah dokumen, invoice, sertifikat, atau foto tambahan di sini.";
    }

    #[On('saveDokumen')]
    public function saveAttachments($kontrak_id, $isRab = false, $isMaterial = false)
    {
        $this->validate([
            'attachments.*' => 'file|max:5024',
        ]);

        try {
            // 🔑 Inisialisasi client langsung dari SDK Google
            $storage = new StorageClient([
                'projectId'   => env('GOOGLE_CLOUD_PROJECT_ID'),
                'keyFilePath' => base_path(env('GOOGLE_CLOUD_KEY_FILE')),
            ]);

            $bucket = $storage->bucket(env('GOOGLE_CLOUD_STORAGE_BUCKET'));

            foreach ($this->attachments as $file) {
                $folder = $isRab
                    ? 'lampiranRab'
                    : ($isMaterial ? 'lampiranMaterial' : 'dokumenKontrak');

                // Nama file unik
                $ext = $file->getClientOriginalExtension();
                $newFileName = date('Ymd_His') . '_' . Str::random(8) . '.' . $ext;
                $prefix = config('filesystems.disks.gcs.path_prefix'); // otomatis ambil dsda/dev dari .env
                $objectPath = trim("{$prefix}/{$folder}/{$newFileName}", '/');


                // Upload langsung ke GCS (pakai publicRead ACL)
                $bucket->upload(
                    file_get_contents($file->getRealPath()),
                    [
                        'name' => $objectPath,
                        'predefinedAcl' => 'publicRead', // 🔑 auto publik
                    ]
                );

                // Simpan hanya nama file ke DB
                if ($isRab) {
                    LampiranRab::create([
                        'rab_id' => $kontrak_id,
                        'path' => $newFileName,
                    ]);
                } elseif ($isMaterial) {
                    LampiranPermintaan::create([
                        'permintaan_id' => $kontrak_id,
                        'path' => $newFileName,
                    ]);
                } else {
                    DokumenKontrakStok::create([
                        'kontrak_id' => $kontrak_id,
                        'file' => $newFileName,
                    ]);
                }

                Log::info("✅ Upload sukses: {$objectPath}");
            }

            $this->reset('attachments');

            return redirect()->to(
                $isRab
                    ? 'rab'
                    : ($isMaterial ? 'permintaan/material' : 'kontrak-vendor-stok')
            )->with('success', 'Berhasil mengunggah lampiran.');
        } catch (\Throwable $e) {
            Log::error('Upload GCS gagal', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->reset('attachments');
            session()->flash('error', 'Gagal mengunggah file: ' . $e->getMessage());
            return;
        }
    }





    public function updatedNewAttachments()
    {
        $this->validate([
            'newAttachments.*' => 'max:5024', // Validation for each new attachment
        ]);

        foreach ($this->newAttachments as $file) {
            // $this->attachments[] = $file->store('attachments', 'public');
            $this->attachments[] = $file;
        }

        $this->dispatch('dokumenCount', count: count($this->attachments));

        // Clear the newAttachments to make ready for next files
        $this->reset('newAttachments');
    }

    public function removeAttachment($index)
    {
        if (isset($this->attachments[$index])) {
            // If it's a file path, delete the file
            // Storage::disk('public')->delete($this->attachments[$index]);
            // Remove from the array
            unset($this->attachments[$index]);
            // Reindex array
            $this->attachments = array_values($this->attachments);
            $this->dispatch('dokumenCount', count: count($this->attachments));
        }
    }
    public function render()
    {
        return view('livewire.upload-surat-kontrak');
    }
}
