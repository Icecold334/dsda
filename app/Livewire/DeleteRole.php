<?php

namespace App\Livewire;

use Livewire\Component;

class DeleteRole extends Component
{
    public $model; // Model yang akan dihapus

    public function delete()
    {
        if ($this->model) {
            // Hapus data
            $this->model->delete();
        }
        return redirect()->route('option.index');
    }
    public function render()
    {
        return view('livewire.delete-role');
    }
}
