<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiStok;

class VendorKontrakForm extends Component
{
    public $vendors;
    public $vendor_id;
    public $nama;
    public $alamat;
    public $kontak;


    public function mount()
    {
        // $transaksi = TransaksiStok::all();

        // // Filter transactions to get only those with a filled kontrak_id
        // $vendorIdsWithKontrak = $transaksi->filter(function ($transaction) {
        //     return $transaction->kontrak_id !== null;
        // })->pluck('vendor_id')->unique(); // Get unique vendor IDs

        // // Filter vendors collection based on these vendor IDs
        // $this->vendors = $this->vendors->filter(function ($vendor) use ($vendorIdsWithKontrak) {
        //     return $vendorIdsWithKontrak->contains($vendor->id);
        // });
    }

    public function updatedVendorId()
    {
        $this->dispatch('vendor_id', vendor_id: $this->vendor_id);
    }

    public function render()
    {
        return view('livewire.vendor-kontrak-form');
    }
}
