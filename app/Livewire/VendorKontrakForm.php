<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BarangStok;
use App\Models\JenisStok;
use App\Models\VendorStok;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use Illuminate\Support\Facades\Request;

class VendorKontrakForm extends Component
{
    public $vendors;
    public $barangs;
    public $vendor_id;
    public $barang_id;
    public $nama;
    public $alamat;
    public $kontak;
    public $listCount;
    public $show;
    public $showAddVendorForm = false;

    #[On('listCount')]
    public function fillListCount($count)
    {
        $this->listCount = $count;
    }

    public function mount()
    {
        $this->show = !request()->is('pengiriman-stok/create');
        $this->barangs = JenisStok::all();
        $this->vendors = VendorStok::all();
        // if (Request::routeIs('kontrak-vendor-stok.create') || Request::routeIs('transaksi-darurat-stok.create')) {
        //     $vendorIdsWithKontrak = TransaksiStok::whereNull('kontrak_id')->pluck('vendor_id')->unique();
        //     $this->vendors = $this->vendors->whereNotIn('id', $vendorIdsWithKontrak);
        // }
    }

    public function updatedBarangId()
    {
        $this->dispatch('jenis_id', jenis_id: $this->barang_id);
    }

    public function updatedVendorId()
    {
        $this->dispatch('vendor_id', vendor_id: $this->vendor_id);
    }

    public function toggleAddVendorForm()
    {
        $this->showAddVendorForm = !$this->showAddVendorForm;
    }

    public function addNewVendor()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'kontak' => 'required|string|max:50',
        ]);

        $newVendor = VendorStok::create([
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'kontak' => $this->kontak,
        ]);

        $this->vendors->push($newVendor);
        $this->vendor_id = $newVendor->id;
        $this->showAddVendorForm = false;

        $this->updatedVendorId();

        // Reset form fields
        $this->reset(['nama', 'alamat', 'kontak']);
    }

    public function render()
    {
        return view('livewire.vendor-kontrak-form');
    }
}
