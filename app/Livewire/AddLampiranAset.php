<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;


class AddLampiranAset extends Component
{
    use WithFileUploads;

    public $attachments = [];
    public $newAttachments = [];

    public function render()
    {
        return view('livewire.add-lampiran-aset');
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
        }
    }
}
