<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kategori;

class DataKategori extends Component
{
    public $kategoris = [];
    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->kategoris = Kategori::with(['children' => function ($query) {
            $query->withCount('aset');
            if ($this->search) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            }
        }])
            ->whereNull('parent_id')
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhereHas('children', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    });
            })
            ->withCount('aset')
            ->get()
            ->toArray();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }
    public function render()
    {
        return view('livewire.data-kategori');
    }
}
