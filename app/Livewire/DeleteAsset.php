<?php

namespace App\Livewire;

use Livewire\Component;

class DeleteAsset extends Component
{
    public $model; // Model yang akan dihapus
    public $route; // Route untuk penghapusan
    
    public function delete()
    {
        if ($this->model) {
            // Hapus data
            $this->model->delete();

            // Emit event untuk refresh halaman atau data
            $this->emit('itemDeleted');

            // Berikan feedback ke pengguna
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => 'Data berhasil dihapus.'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.delete-asset');
    }
}
