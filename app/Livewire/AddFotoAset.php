<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class AddFotoAset extends Component
{
    use WithFileUploads;

    #[Validate]
    public $img;

    public function updatedImg()
    {
        // Check if the file is an image
        if ($this->img && in_array($this->img->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
            // Proceed with any operations such as saving the file
            // $path = $this->img->store('public/images', 'public');
            // Optionally perform additional actions like updating the database
        } else {
            // If the file is not an image, reset the image and show an error
            $this->reset('img');  // This will clear the current image without affecting other properties
            $this->dispatch('swal:error', [
                'text' => 'File yang diunggah bukan gambar. Silakan coba lagi dengan file gambar.',
            ]);
        }
    }

    public function rules()
    {

        return [
            'img' => 'image|max:1024', // Restricts the upload to images and file size to 1024 kilobytes
        ];
    }

    public function messages()
    {
        return [
            'img.image' => 'File yang diunggah harus berupa gambar.',
            'img.max' => 'Ukuran gambar tidak boleh lebih dari 1MB.',
        ];
    }
    public function uploadImage()
    {
        $validatedData = $this->validate([
            'img' => 'image|max:1024', // Ensures the file is an image and does not exceed 1MB
        ]);

        // Proceed only if the file is an image
        if ($this->img && in_array($this->img->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
            // Save the image and possibly update database records
            // $path = $this->img->store('public/images', 'public');
        } else {
            // Reset the image upload input if it's not a valid image
            $this->img = null;

            // Emit an event to the frontend to trigger a SweetAlert error
            $this->dispatchBrowserEvent('swal:error', [
                'title' => 'Upload Gagal',
                'text' => 'File yang diunggah bukan gambar. Silakan coba lagi dengan file gambar.',
            ]);
        }
    }


    public function removeImg()
    {
        $this->img = null;
    }
    public function render()
    {
        return view('livewire.add-foto-aset');
    }
}
