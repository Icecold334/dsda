<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Security;
use Illuminate\Support\Facades\Auth;

class DataSecurity extends Component
{
    public $securities, $nama, $securityId;
    public $showModal = false, $isEdit = false, $unit_id;

    public function mount()
    {
        $this->unit_id = Auth::user()->unit_id;
        $this->securities = Security::where('unit_id', $this->unit_id)->get();
    }

    public function openModalCreate()
    {
        $this->reset(['nama', 'securityId', 'isEdit']);
        $this->showModal = true;
    }

    public function openModalEdit($id)
    {
        $data = Security::findOrFail($id);
        $this->nama = $data->nama;
        $this->securityId = $data->id;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string',
        ]);

        Security::updateOrCreate(
            ['id' => $this->securityId],
            ['nama' => $this->nama, 'unit_id' => $this->unit_id]
        );

        $this->showModal = false;
        $this->mount();
    }

    public function delete($id)
    {
        Security::findOrFail($id)->delete();
        $this->mount();
    }

    public function render()
    {
        return view('livewire.data-security');
    }
}
