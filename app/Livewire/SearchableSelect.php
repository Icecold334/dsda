<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;

class SearchableSelect extends Component
{
    public $options = [];      // List opsi (array/collection)
    public $label = 'nama'; // Nama  label
    #[Modelable]
    public $selected = null;   // wire:model
    public $search = '';
    public $disabled = false;
    public $filteredOptions = [];
    public bool $open = false;

    public function mount()
    {
        $this->filteredOptions = collect($this->options);
        $this->setInitialLabel();
    }

    public function updatedSearch()
    {
        $this->open = true;
        $this->filteredOptions = collect($this->options)->filter(function ($item) {
            $label = is_array($item) ? ($item[$this->label] ?? null) : ($item->{$this->label} ?? null);
            if (!is_string($label)) {
                return false;
            }
            return stripos($label, $this->search) !== false;
        })->values();
    }

    public function select($id)
    {
        $this->selected = $id;
        $this->setInitialLabel();
        $this->open = false;
    }

    public function setInitialLabel()
    {
        if ($this->selected === null) {
            $this->search = '';
            return;
        }
        $found = collect($this->options)->firstWhere('id', $this->selected);
        $this->search = $found[$this->label] ?? $found?->{$this->label} ?? '';
    }

    public function render()
    {
        if ($this->search === '') {
            $this->filteredOptions = collect($this->options);
        }
        return view('livewire.searchable-select');
    }
}
