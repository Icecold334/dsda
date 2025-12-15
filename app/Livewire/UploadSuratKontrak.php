<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DokumenKontrakStok;
use App\Models\LampiranPermintaan;
use App\Models\LampiranRab;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;

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
        // Only validate if there are attachments
        if (!empty($this->attachments)) {
            $this->validate([
                'attachments.*' => 'file|max:5024',  // Validate before saving
            ]);
        }

        foreach ($this->attachments as $file) {
            $path = str_replace($isRab ? 'lampiranRab' : ($isMaterial ? 'lampiranMaterial' : 'dokumenKontrak') . '/', '', $file->storeAs($isRab ? 'lampiranRab' : ($isMaterial ? 'lampiranMaterial' : 'dokumenKontrak') . '', $file->getClientOriginalName(), 'public'));  // Store the file

            // Versi yang lebih sederhana
            // $folder = $isRab ? 'lampiranRab' : ($isMaterial ? 'lampiranMaterial' : 'dokumenKontrak');
            // $path = $file->storeAs($folder, $file->getClientOriginalName(), 'public');
            
            if ($isRab) {
                LampiranRab::create([
                    'rab_id' => $kontrak_id,  // Associate with kontrak
                    'path' => $path,
                ]);
            } elseif ($isMaterial) {
                LampiranPermintaan::create([
                    'permintaan_id' => $kontrak_id,  // Associate with kontrak
                    'path' => $path,
                ]);
            } else {
                DokumenKontrakStok::create([
                    'kontrak_id' => $kontrak_id,  // Associate with kontrak
                    'file' => $path,
                ]);
            }
        }

        // Optionally reset the attachments after saving
        $this->reset('attachments');
        
        // For kontrak-vendor-stok, re-dispatch the event so JavaScript can handle redirect
        // For RAB and Material, redirect to index
        if ($isRab) {
            return redirect()->to('rab');
        } elseif ($isMaterial) {
            return redirect()->to('permintaan/material');
        }
        // Re-dispatch saveDokumen event so JavaScript listener can handle redirect
        $this->dispatch('saveDokumen', kontrak_id: $kontrak_id);
        return null;
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
