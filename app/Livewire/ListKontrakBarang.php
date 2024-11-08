<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ListKontrakBarang extends Component
{
    public $vendor_id;
    #[On('vendor_id')]
    public function fillVendor($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }
    public function render()
    {
        return view('livewire.list-kontrak-barang');
    }
}
