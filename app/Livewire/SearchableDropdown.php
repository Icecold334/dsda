<?php

namespace App\Livewire;

use App\Models\Aset;
use Livewire\Component;
use Illuminate\Support\Str;

class SearchableDropdown extends Component
{
    public $options = [];
    public $query = '';
    public $model = 'barang_stok';
    public $modelName;
    public $show = false;
    public $selectedOption = null;

    public function mount()
    {

        $this->modelName = 'App\\Models\\' . Str::studly($this->model);
        // $this->options = app($this->modelName)::all()->toArray();
    }
    public function showSuggestion()
    {
        // Ganti ini dengan logika pengambilan data dari database Anda
        $this->options = app($this->modelName)::where('nama', 'like', '%' . $this->query . '%')
            // Batas jumlah data yang ditampilkan
            ->get()
            ->toArray();
        $this->show = true;
        // dd($this->options);
    }
    public function hideSuggestion()
    {
        // Ganti ini dengan logika pengambilan data dari database Anda
        $this->options = [];
        $this->show = false;
        // dd($this->options);
    }

    // public function selectOption($id)
    // {
    //     $this->selectedOption = $id;
    //     $this->query = $this->options->firstWhere('id', $id)['nama'] ?? '';
    //     $this->options = [];
    // }

    public function render()
    {
        return view('livewire.searchable-dropdown');
    }
}
