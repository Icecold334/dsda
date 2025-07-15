<?php

namespace App\Livewire;

use App\Models\Driver;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DataDriver extends Component
{
    public $drivers, $nama, $nopol, $unit_id;
    public $driverId; // untuk edit
    public $isEdit = false;
    public $showModal = false;
    public function mount()
    {
        $this->drivers =
            Driver::where('unit_id', $this->unit_id)->get();;
    }

    public function openModalCreate()
    {
        $this->reset(['nama', 'nopol', 'unit_id', 'driverId', 'isEdit']);
        $this->showModal = true;
    }

    public function openModalEdit($id)
    {
        $driver = \App\Models\Driver::findOrFail($id);
        $this->nama = $driver->nama;
        $this->nopol = $driver->nopol;
        $this->unit_id = $driver->unit_id;
        $this->driverId = $driver->id;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string',
        ]);

        Driver::updateOrCreate(
            ['id' => $this->driverId],
            [
                'nama' => $this->nama,
                'unit_id' => $this->unit_id,
            ]
        );

        $this->showModal = false;
        $this->mount(); // reload data
    }


    public function delete($id)
    {
        \App\Models\Driver::findOrFail($id)->delete();
        $this->mount(); // reload data
    }


    public function render()
    {
        return view('livewire.data-driver');
    }
}
