<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use Livewire\Attributes\On;

class KontrakListForm extends Component
{
    public $list = [];
    public $barangs;
    public $barang_item;
    public $barang_id;
    public $merks;
    public $merk_item;
    public $merk_id;
    public $jumlah;
    public $vendor_id;
    #[On('vendor_id')]
    public function fillVendor($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }
    public function mount()
    {
        $this->barangs = BarangStok::whereHas('merkStok', function ($query) {
            // Optionally, add conditions to the query if needed
        })->get()->sortBy('jenis_id');
    }

    public function updatedBarangId()
    {
        if ($this->barang_id) {
            $this->merk_id = null;
            $this->merks = MerkStok::where('barang_id', $this->barang_id)->get();

            // Exclude already selected merks in the list
            $selectedMerks = collect($this->list)->pluck('merk_id')->filter()->all();
            $this->merks = $this->merks->reject(function ($merk) use ($selectedMerks) {
                return in_array($merk->id, $selectedMerks);
            });

            $this->jumlah = 1; // Reset jumlah to default value
        }
    }

    public function updatedMerkId()
    {
        if ($this->merk_id) {
            $this->merk_item = MerkStok::find($this->merk_id);
            $this->jumlah = 1; // Reset jumlah to default value

        }
    }

    public function addToList()
    {
        $this->validate([
            'barang_id' => 'required',
            'merk_id' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);

        // Add a new item to the list with selected barang, merk, and quantity
        $this->list[] = [
            'barang_id' => $this->barang_id,
            'merk_id' => $this->merk_id,
            'merks' => MerkStok::where('barang_id', $this->barang_id)->whereNotIn('id', collect($this->list)->pluck('merk_id'))->get(),
            'jumlah' => $this->jumlah,
        ];

        // Reset selected values to add a new item cleanly
        $this->reset(['barang_id', 'merk_id', 'jumlah', 'merks', 'barang_item', 'merk_item']);
    }

    public function updateList($index, $field, $value)
    {
        if ($field === 'barang') {
            $this->list[$index]['barang_id'] = $value;
            $this->list[$index]['merk_id'] = null; // Reset merk when barang changes

            // Retrieve all Merks associated with the selected Barang
            $allMerks = MerkStok::where('barang_id', $value)->get();

            // Collect all Merk IDs that are already in use in the list except the current item
            $usedMerkIds = collect($this->list)->except($index)->pluck('merk_id')->filter()->all();

            // Filter out Merks that are already in the list
            $this->list[$index]['merks'] = $allMerks->reject(function ($merk) use ($usedMerkIds) {
                return in_array($merk->id, $usedMerkIds);
            });
            $this->list[$index]['jumlah'] = 1;
        } elseif ($field === 'merk') {
            $this->list[$index]['merk_id'] = $value;
            $this->list[$index]['jumlah'] = 1;
        } elseif ($field === 'jumlah') {
            $this->list[$index]['jumlah'] = $value;
        }
    }

    public function removeFromList($index)
    {
        // Remove the item at the given index from the list
        unset($this->list[$index]);

        // Re-index the array to maintain correct indexing
        $this->list = array_values($this->list);
    }



    public function render()
    {
        return view('livewire.kontrak-list-form');
    }
}
