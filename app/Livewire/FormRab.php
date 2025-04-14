<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class FormRab extends Component
{
    public $listCount, $nama, $mulai, $selesai, $lokasi;

    #[On('listCount')]
    public function fillCount($count)
    {
        $this->listCount = $count;
    }

    public function updated($field)
    {
        $this->dispatch('dataKegiatan', data: ['nama' => $this->nama, 'mulai' => $this->mulai, 'selesai' => $this->selesai, 'lokasi' => $this->lokasi]);
    }
    public function render()
    {
        return view('livewire.form-rab');
    }
}
