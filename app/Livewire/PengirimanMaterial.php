<?php

namespace App\Livewire;

use Livewire\Component;

class PengirimanMaterial extends Component
{
    public $vendors;
    public function render()
    {
        return view('livewire.pengiriman-material');
    }
}
