<?php

namespace App\Livewire;

use Livewire\Component;

class Navbar extends Component
{
    public function getUnitKerjaHak(): ?int
    {
        if (!auth()->check()) {
            return null;
        }

        return auth()->user()->unitKerja?->hak ?? null;
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}
