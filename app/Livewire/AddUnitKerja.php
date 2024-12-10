<?php

namespace App\Livewire;

use App\Models\UnitKerja;
use Livewire\Component;

class AddUnitKerja extends Component
{
    public $id;
    public $unitkerja;
    public $tipe;
    public $unitkerjas;
    public $keterangan;
    public $unitkerja_id;
    public $parent_id;
    public $utama;
    public $sub;
    public $kode;

    public function mount()
    {
        if ($this->tipe == 'sub') {
            $this->unitkerjas = UnitKerja::where('parent_id', NULL)->get();
            if ($this->id) {
                $sub = UnitKerja::find($this->id);
                $this->sub = $sub->nama;
                $this->kode = $sub->kode;
                $this->unitkerja_id = $sub->unitkerja_id;
                $this->parent_id = $sub->parent_id;
                $this->keterangan = $sub->keterangan;
            }
        } else {
            if ($this->id) {
                $utama = UnitKerja::find($this->id);
                $this->utama = $utama->nama;
                $this->kode = $utama->kode;
                $this->keterangan = $utama->keterangan;
            }
        }
    }

    public function removeUnitKerja()
    {
        if ($this->tipe == 'utama') {
            UnitKerja::destroy($this->id);
        } else {
            UnitKerja::destroy($this->id);
        }
        return redirect()->route('unit-kerja.index');
    }

    public function saveUnitKerja()
    {
        if ($this->tipe == 'utama') {
            UnitKerja::updateOrCreate(
                ['id' => $this->id ?? 0], // Unique field to check for existing record
                [
                    // 'user_id' => Auth::user()->id,
                    'kode' => $this->kode,
                    'nama' => $this->utama,
                    'keterangan' => $this->keterangan,
                ]
            );
        } else {
            UnitKerja::updateOrCreate(
                ['id' => $this->id ?? 0], // Unique fields to check
                [
                    // 'user_id' => Auth::user()->id,
                    'kode' => $this->kode,
                    'parent_id' => $this->parent_id,
                    'nama' => $this->sub,
                    'keterangan' => $this->keterangan,
                ]
            );
        }

        return redirect()->route('unit-kerja.index');
    }

    public function render()
    {
        return view('livewire.add-unit-kerja');
    }
}
