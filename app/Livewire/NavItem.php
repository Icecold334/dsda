<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NavItem extends Component
{
    public $href = null;
    public $title;
    public $child = [];

    public function mount()
    {
        if ($this->title == 'Form') {
            if (Auth::user()->cannot('inventaris_tambah_barang_datang')) {
                $this->child =  collect($this->child)->filter(function ($child) {
                    return $child['title'] !== 'Form barang datang';
                })->toArray();
            }
        }
    }

    public function render()
    {

        return view('livewire.nav-item');
    }
}
