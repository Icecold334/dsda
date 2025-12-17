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
        $this->filteredOptions = collect($this->options)->values()->toArray();
        $this->setInitialLabel();
    }

    public function updatedOptions()
    {
        // Update filteredOptions ketika options berubah
        $this->filteredOptions = collect($this->options)->values()->toArray();
        $this->setInitialLabel();
    }

    public function updatedSearch()
    {
        $this->open = true;
        $this->updateFilteredOptions();
    }

    private function updateFilteredOptions()
    {
        // If search is empty, show all options
        if (empty($this->search)) {
            $this->filteredOptions = collect($this->options)->values()->toArray();
        } else {
            // Filter options based on search
            $this->filteredOptions = collect($this->options)->filter(function ($item) {
                $label = is_array($item) ? ($item[$this->label] ?? null) : ($item->{$this->label} ?? null);
                if (!is_string($label)) {
                    return false;
                }
                return stripos($label, $this->search) !== false;
            })->values()->toArray();
        }
    }

    public function select($id)
    {
        $this->selected = $id;
        $this->setInitialLabel();
        $this->open = false;
    }

    public function updatedSelected()
    {
        $this->setInitialLabel();
    }

    public function setInitialLabel()
    {
        if ($this->selected === null || $this->selected === '') {
            // If no selection, keep the search value (allows free text input)
            return;
        }
        $found = collect($this->options)->firstWhere('id', $this->selected);
        if ($found) {
            $this->search = $found[$this->label] ?? $found?->{$this->label} ?? '';
        }
    }

    public function handleFocus()
    {
        $this->open = true;
        // Ensure filteredOptions is populated when user focuses
        $this->updateFilteredOptions();
    }

    public function handleBlur()
    {
        // If user typed a value but didn't select from dropdown, use the typed value
        if (!empty($this->search) && ($this->selected === null || $this->selected === '')) {
            // Check if the search value matches any option
            $found = collect($this->options)->first(function ($item) {
                $label = is_array($item) ? ($item[$this->label] ?? null) : ($item->{$this->label} ?? null);
                return $label === $this->search;
            });

            if ($found) {
                // If exact match found, use its ID
                $itemId = is_array($found) ? ($found['id'] ?? null) : ($found->id ?? null);
                $this->selected = $itemId;
            } else {
                // If no match, use the typed value as the selected value (allows new values)
                $this->selected = $this->search;
            }
        }
    }

    public function render()
    {
        // Ensure filteredOptions is always an array and up-to-date
        $this->updateFilteredOptions();
        return view('livewire.searchable-select');
    }
}
