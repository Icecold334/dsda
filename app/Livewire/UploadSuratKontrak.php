<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DokumenKontrakStok;
use Livewire\Attributes\On;

class UploadSuratKontrak extends Component
{

    use WithFileUploads;


    public $attachments = [];
    public $newAttachments = [];

    #[On('saveDokumen')]
    public function saveAttachments($kontrak_id)
    {
        $this->validate([
            'attachments.*' => 'file|max:5024',  // Validate before saving
        ]);

        foreach ($this->attachments as $file) {
            $path = str_replace('dokumenKontrak/', '', $file->storeAs('dokumenKontrak', $file->getClientOriginalName(), 'public'));  // Store the file

            DokumenKontrakStok::create([
                'kontrak_id' => $kontrak_id,  // Associate with kontrak
                'file' => $path,
            ]);
        }

        // Optionally reset the attachments after saving
        $this->reset('attachments');
        return redirect()->route('kontrak-vendor-stok.index');
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