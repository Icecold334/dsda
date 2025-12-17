<?php

namespace App\Livewire;

use App\Models\Rab;
use App\Models\AdendumHistory;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class HistoryAdendumRab extends Component
{
    public $rabId;
    public $rab;
    public $histories;
    public $showModal = false;
    public $selectedHistory = null;

    public function mount($rabId)
    {
        $this->rabId = $rabId;
        $this->rab = Rab::with('user')->findOrFail($rabId);
        $this->loadHistories();
    }

    public function loadHistories()
    {
        $this->histories = AdendumHistory::where('rab_id', $this->rabId)
            ->with(['user', 'adendumRab.user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function showDetail($historyId)
    {
        $this->selectedHistory = AdendumHistory::with(['user', 'adendumRab.user'])
            ->findOrFail($historyId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedHistory = null;
    }

    public function render()
    {
        return view('livewire.history-adendum-rab');
    }
}
