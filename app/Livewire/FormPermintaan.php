<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\UnitKerja;

class FormPermintaan extends Component
{
    public $units;
    public $unit_id;
    public $subUnits;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $keterangan;

    public function updatedUnitId()
    {
        if ($this->unit_id) {
            $this->subUnits = UnitKerja::where('parent_id', $this->unit_id)->get();
        }
        $this->dispatch('unit_id', unit_id: $this->unit_id);
    }
    public function updatedTanggalPermintaan()
    {
        $this->dispatch('tanggal_permintaan', tanggal_permintaan: $this->tanggal_permintaan);
    }
    public function updatedKeterangan()
    {
        $this->dispatch('keterangan', keterangan: $this->keterangan);
    }

    public function updatedSubUnitId()
    {
        $this->dispatch('sub_unit_id', sub_unit_id: $this->sub_unit_id);
    }

    public function mount()
    {

        $this->tanggal_permintaan = Carbon::now()->format('Y-m-d');;
        $this->units = UnitKerja::whereNull('parent_id')->whereHas('children', function ($sub) {
            return $sub;
        })->get();
    }
    public function render()
    {
        return view('livewire.form-permintaan');
    }
}
