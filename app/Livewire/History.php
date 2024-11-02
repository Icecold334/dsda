<?php

namespace App\Livewire;

use Livewire\Component;

class History extends Component
{

    public $histories;

    public function delete($id)
    {
        return dump($id);
    }
    public function render()
    {
        return view('livewire.history');
    }
}
