<?php

namespace App\Livewire;

use Livewire\Component;

class AddKeteranganAset extends Component
{
    public $keterangan;
    public function render()
    {
        return view('livewire.add-keterangan-aset');
    }
}
