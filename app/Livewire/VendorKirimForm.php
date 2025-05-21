<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LokasiStok;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\Auth;

class VendorKirimForm extends Component
{
    public $nomor_kontrak;
    public $kontrak;
    public $gudang_id;
    public $listGudang = [];

    public function mount()
    {
        $this->loadGudangUser();
    }
    public function loadGudangUser()
    {
        $user = Auth::user();
        $this->listGudang = LokasiStok::where('unit_id', $this->unit_id)->get();
    }


    public function updatedNomorKontrak($value)
    {
        // Reset data kontrak sebelumnya
        $this->kontrak = null;

        if ($value) {
            $this->kontrak = KontrakVendorStok::with(['vendorStok', 'metodePengadaan'])
                ->where('nomor_kontrak', $value)
                ->first();
        }
        if ($this->kontrak) {
            $this->dispatch('kontrakId', kontrakId: $this->kontrak->id);
        }
    }

    public function updatedGudangId()
    {
        $this->dispatch('gudangId', gudangId: $this->gudang_id);
    }

    public function render()
    {
        return view('livewire.vendor-kirim-form');
    }
}
