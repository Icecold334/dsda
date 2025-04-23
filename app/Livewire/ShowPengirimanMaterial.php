<?php

namespace App\Livewire;

use Livewire\Component;

class ShowPengirimanMaterial extends Component
{
    public $pengiriman;
    public function render()
    {
        return view('livewire.show-pengiriman-material');
    }
}
