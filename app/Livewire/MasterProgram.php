<?php

namespace App\Livewire;

use App\Models\Program;
use App\Models\UnitKerja;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class MasterProgram extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $selectedUnitId = '';
    public $selectedProgram = null;
    public $editingProgram = null;
    public $newUnitId = '';

    public function mount()
    {
        // Pastikan hanya superadmin yang bisa mengakses
        if (!auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized access');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedUnitId()
    {
        $this->resetPage();
    }

    public function editProgram($programId)
    {
        $this->editingProgram = $programId;
        $program = Program::find($programId);
        $this->newUnitId = $program->bidang_id;
    }

    public function updateProgram()
    {
        $this->validate([
            'newUnitId' => 'required|exists:unit_kerja,id'
        ]);

        $program = Program::find($this->editingProgram);
        $program->update([
            'bidang_id' => $this->newUnitId
        ]);

        $this->editingProgram = null;
        $this->newUnitId = '';

        session()->flash('success', 'Program berhasil diperbarui');
    }

    public function cancelEdit()
    {
        $this->editingProgram = null;
        $this->newUnitId = '';
    }

    public function render()
    {
        $unitKerjas = UnitKerja::orderBy('nama')->get();

        $programs = Program::with('parent')
            ->when($this->search, function ($query) {
                $query->where('program', 'like', '%' . $this->search . '%')
                    ->orWhere('kode', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedUnitId, function ($query) {
                $query->where('bidang_id', $this->selectedUnitId);
            })
            ->orderBy('program')
            ->paginate(15);

        return view('livewire.master-program', [
            'programs' => $programs,
            'unitKerjas' => $unitKerjas
        ]);
    }
}
