<?php

namespace App\Livewire;

use App\Models\Aset;
use Livewire\Component;
use Illuminate\Support\Str;

class SearchableDropdown extends Component
{
    public $options = [];
    public $query = '';
    public $model = 'satuan besar';
    public $modelName;
    public $selectedOption = null;

    public function mount()
    {

        $this->modelName = 'App\\Models\\' . Str::studly($this->model);
        // Isi awal dropdown
        $this->options = app($this->modelName)::all()->toArray();
    }
    public function updatedQuery()
    {
        // Ganti ini dengan logika pengambilan data dari database Anda
        $this->options = app($this->modelName)::where('nama', 'like', '%' . $this->query . '%')
            // Batas jumlah data yang ditampilkan
            ->get()
            ->toArray();
        // dd($this->options);
    }

    public function selectOption($id)
    {
        $this->selectedOption = $id;
        $this->query = $this->options->firstWhere('id', $id)['nama'] ?? '';
        $this->options = [];
    }

    public function render()
    {
        return view('livewire.searchable-dropdown');
    }
}
