<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\Auth;

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
    public $dokumenCount;
    #[On('vendor_id')]
    public function fillVendor($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }
    #[On('dokumenCount')]
    public function fillBukti($count)
    {
        $this->dokumenCount = $count;
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
        $this->dispatch('listCount', count: count($this->list));
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
        $this->dispatch('listCount', count: count($this->list));
    }

    public function saveKontrak()
    {
        // Validate the required fields
        $this->validate([
            'vendor_id' => 'required',
            'list' => 'required|array|min:1',
            'list.*.barang_id' => 'required|integer',
            'list.*.merk_id' => 'required|integer',
            'list.*.jumlah' => 'required|integer|min:1',
        ]);

        // Create a new contract (assuming you have a model like `KontrakVendorStok`)
        $kontrak = KontrakVendorStok::create([
            'vendor_id' => $this->vendor_id,
            'tanggal_kontrak' => strtotime(now()),
            'nomor_kontrak' => $this->generateContractNumber(), // Assuming a method to generate contract number
            'user_id' => Auth::id(), // Assuming the logged-in user's name as the author
            // 'jumlah_total' => array_sum(array_column($this->list, 'jumlah')),
            'type' => true
        ]);

        // Loop through each item in the list and create related entries
        foreach ($this->list as $item) {
            TransaksiStok::create([
                'merk_id' => $item['merk_id'],
                'vendor_id' => $this->vendor_id,
                'user_id' => Auth::id(),
                'kontrak_id' => $kontrak->id,
                'tanggal' => strtotime(now()),
                'jumlah' => $item['jumlah'],
                'tipe' => 'Pemasukan'

            ]);
        }

        // Clear the list and reset the input fields
        $this->reset(['list', 'vendor_id', 'barang_id', 'merk_id', 'jumlah']);

        // Emit a success message or redirect if desired
        session()->flash('message', 'Kontrak berhasil disimpan.');
    }

    protected function generateContractNumber()
    {
        return 'CN-' . strtoupper(Str::random(6));
    }

    public function render()
    {
        return view('livewire.kontrak-list-form');
    }
}
