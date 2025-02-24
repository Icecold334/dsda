<?php

namespace App\Livewire;

use Livewire\Component;

class Loading extends Component
{
    public $class;
    public function render()
    {
        return view('livewire.loading');
    }
}
