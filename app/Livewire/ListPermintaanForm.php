<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MerkStok;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;

class ListPermintaanForm extends Component
{
    public $unit_id;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $keterangan;

    public $list = []; // List of items
    public $newBarang; // Input for new barang
    public $newJumlah; // Input for new jumlah
    public $newDokumen; // Input for new dokumen
    public $barangSuggestions = []; // Suggestions for barang
    public function focusBarang()
    {
        // Fetch suggestions for the input
        if ($this->newBarang) {
            $this->barangSuggestions = MerkStok::where('nama', 'like', '%' . $this->newBarang . '%')->get();
        } else {
            $this->barangSuggestions = MerkStok::all(); // Show top 10 if no input
        }
    }
    #[On('unit_id')]
    public function fillUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }

    #[On('sub_unit_id')]
    public function fillSubUnitId($sub_unit_id)
    {
        $this->sub_unit_id = $sub_unit_id;
    }

    #[On('tanggal_permintaan')]
    public function fillTanggalPermintaan($tanggal_permintaan)
    {
        $this->tanggal_permintaan = $tanggal_permintaan;
    }

    #[On('keterangan')]
    public function fillKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
    }

    public function addToList()
    {
        $this->validate([
            'newBarang' => 'required|string|max:255',
            'newJumlah' => 'required|integer|min:1',
            'newDokumen' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt',
        ]);

        $this->list[] = [
            'barang_id' => null, // Assuming a dropdown for selecting existing barang
            'barang_name' => $this->newBarang,
            'jumlah' => $this->newJumlah,
            'dokumen' => $this->newDokumen ? $this->newDokumen->store('permintaan_dokumen', 'public') : null,
        ];

        // Reset inputs after adding to the list
        $this->reset(['newBarang', 'newJumlah', 'newDokumen']);
    }

    public function updateList($index, $field, $value)
    {
        $this->list[$index][$field] = $value;
    }

    public function removeFromList($index)
    {
        if (isset($this->list[$index]['dokumen'])) {
            Storage::delete('public/' . $this->list[$index]['dokumen']);
        }
        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
    }

    public function blurBarang()
    {
        if ($this->newBarang) {
            $this->barangSuggestions = MerkStok::where('nama', 'like', '%' . $this->newBarang . '%')->get();
        } else {
            $this->barangSuggestions = [];
        }
    }

    public function selectBarang($barangId, $barangName)
    {
        $this->newBarang = $barangName;
        $this->barangSuggestions = [];
    }

    public function render()
    {
        return view('livewire.list-permintaan-form', [
            'barangs' => MerkStok::all(), // Assuming you have a Barang model
        ]);
    }
}
