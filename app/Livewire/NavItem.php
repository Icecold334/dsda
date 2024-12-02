<?php

namespace App\Livewire;

use Livewire\Component;

class NavItem extends Component
{
    public $href = null;
    public $title;
    public $child = [];

    public function render()
    {

        return view('livewire.nav-item');
    }
}
