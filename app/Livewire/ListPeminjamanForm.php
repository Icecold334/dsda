<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use Livewire\Component;
use Livewire\Attributes\On;

class ListPeminjamanForm extends Component
{


    public $unit_id;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $keterangan;
    public $permintaan;
    public $list = [];
    public $newAset;
    public $newMerkJenis;
    public $newPermintaan;
    public $newDisetujui;
    public $newTanggalPeminjaman;
    public $newTanggalPengembalian;
    public $newKeterangan;
    public $newBarangId; // Input for new barang
    public $newBarang; // Input for new barang
    public $newJumlah; // Input for new jumlah
    public $newDokumen; // Input for new dokumen
    public $barangSuggestions = []; // Suggestions for barang
    public $assetSuggestions = [];

    public function updatedNewAset($value)
    {
        $this->assetSuggestions = Aset::where('nama', 'like', "%{$value}%")
            ->get();
    }

    public function selectAsset($asetId)
    {
        $aset = Aset::find($asetId);
        $this->newAset = $aset->nama;
        $this->assetSuggestions = [];
    }
    public function addToList()
    {
        $this->validate([
            'newAset' => 'required',
            'newPermintaan' => 'required|integer|min:1',
            'newTanggalPeminjaman' => 'required|date',
            'newTanggalPengembalian' => 'required|date|after:newTanggalPeminjaman',
            'newKeterangan' => 'nullable|string',
        ]);

        $this->list[] = [
            'aset_id' => Aset::where('nama', $this->newAset)->first()->id,
            'aset_name' => $this->newAset,
            'merk_jenis' => $this->newMerkJenis,
            'permintaan' => $this->newPermintaan,
            'disetujui' => $this->newDisetujui,
            'tanggal_peminjaman' => $this->newTanggalPeminjaman,
            'tanggal_pengembalian' => $this->newTanggalPengembalian,
            'keterangan' => $this->newKeterangan,
        ];

        $this->reset(['newAset', 'newMerkJenis', 'newPermintaan', 'newDisetujui', 'newTanggalPeminjaman', 'newTanggalPengembalian', 'newKeterangan']);
    }
    public function blurAsset()
    {
        // This method can be used to perform actions when the asset input field loses focus.
        // For example, it might validate the input or clear/hide suggestions.
        // Below is a simple example that might just log the current state or do nothing.

        // Log the current newAset value, or perform other logic as needed.
        // \Log::info("Asset input lost focus with value: {$this->newAset}");

        // Optionally, you can clear suggestions here if applicable.
        $this->assetSuggestions = [];
    }


    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
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
    #[On('keterangan')]
    public function fillKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
    }

    #[On('tanggal_permintaan')]
    public function fillTanggalPermintaan($tanggal_permintaan)
    {
        $this->tanggal_permintaan = $tanggal_permintaan;
    }

    public function mount()
    {
        $this->newTanggalPeminjaman = Carbon::today()->toDateString();
        $this->newTanggalPengembalian = Carbon::today()->addWeek(1)->toDateString(); // Default to 1 week later
        $this->tanggal_permintaan = Carbon::now()->format('Y-m-d');
    }
    public function render()
    {
        return view('livewire.list-peminjaman-form');
    }
}
