<?php

namespace App\Livewire;

use App\Models\Aset;
use Livewire\Component;
use App\Models\History;
use App\Models\Agenda;
use App\Models\Keuangan;
use App\Models\Jurnal;

class AsetDetails extends Component
{
    public $aset;

    public $histories = [];
    public $agendas = [];
    public $keuangans = [];
    public $jurnals = [];

    
    public function mount(Aset $aset)
    {
        $this->aset = $aset;

        // Load related data
        $this->histories = $aset->histories; // Pastikan ada relasi histories
        $this->agendas = $aset->agendas; // Pastikan ada relasi agendas
        $this->keuangans = $aset->keuangans; // Pastikan ada relasi keuangan
        $this->jurnals = $aset->jurnals; // Pastikan ada relasi jurnals
    }
    public function render()
    {
        return view('livewire.aset-details');
    }
}
