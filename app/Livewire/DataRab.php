<?php

namespace App\Livewire;

use App\Models\Rab;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class DataRab extends Component
{
    use WithPagination, WithoutUrlPagination;


    public function mount() {}

    public function fetchData()
    {
        return Rab::whereHas('user.unitKerja', function ($unit) {
            $unit->where('parent_id', $this->unit_id)
                ->orWhere('id', $this->unit_id);
        })->paginate(5);
    }
    public function render()
    {
        $rabs = $this->fetchData();
        return view('livewire.data-rab', compact('rabs'));
    }
}
