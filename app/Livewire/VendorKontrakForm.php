<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\JenisStok;
use App\Models\BarangStok;
use App\Models\VendorStok;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use Illuminate\Support\Facades\Request;

class VendorKontrakForm extends Component
{
    public $vendors;
    public $nomor_kontrak;
    public $tanggal_kontrak;
    public $barangs;
    public $vendor_id;
    public $barang_id;
    public $nama;
    public $alamat;
    public $kontak;
    public $listCount;
    public $show;
    public $showNomor;
    public $showAddVendorForm = false;
    public $query = ''; // Untuk query input saran vendor
    public $suggestions = []; // Untuk menyimpan hasil saran

    public function updatedQuery()
    {
        // dd('aa');
        // Ambil data dari database berdasarkan query
        $this->suggestions = VendorStok::where('nama', 'like', '%' . $this->query . '%')
            // ->limit(5)
            ->get()
            ->toArray();

        $exactMatch = VendorStok::where('nama', $this->query)->first();

        if ($exactMatch) {
            // Jika ada kecocokan, isi vendor_id dan kosongkan suggestions
            $this->selectSuggestion($exactMatch->id, $exactMatch->nama);
        }
    }

    public function selectSuggestion($vendorId, $vendorName)
    {
        // Ketika saran dipilih, isi input dengan nilai tersebut
        $this->vendor_id = $vendorId;
        $this->query = $vendorName;
        $this->suggestions = [];
        $this->updatedVendorId();
    }

    #[On('listCount')]
    public function fillListCount($count)
    {
        $this->listCount = $count;
    }

    public function mount()
    {
        $this->tanggal_kontrak = Carbon::now()->format('Y-m-d');
        $this->show = !request()->is('pengiriman-stok/create');
        $this->showNomor = !(request()->is('transaksi-darurat-stok/create') || request()->is('pengiriman-stok/create'));
        $this->barangs = JenisStok::all();
        $this->vendors = VendorStok::all();
        // if (Request::routeIs('kontrak-vendor-stok.create') || Request::routeIs('transaksi-darurat-stok.create')) {
        //     $vendorIdsWithKontrak = TransaksiStok::whereNull('kontrak_id')->pluck('vendor_id')->unique();
        //     $this->vendors = $this->vendors->whereNotIn('id', $vendorIdsWithKontrak);
        // }
    }

    public function updatedTanggalKontrak()
    {
        $this->dispatch('tanggal_kontrak', tanggal: $this->tanggal_kontrak);
    }
    public function updatedNomorKontrak()
    {
        $this->dispatch('nomor_kontrak', nomor: $this->nomor_kontrak);
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
        $this->selectSuggestion($newVendor->id, $newVendor->nama);
        // $this->vendor_id = $newVendor->id;
        $this->showAddVendorForm = false;

        $this->updatedVendorId();

        // Reset form fields
        $this->reset(['nama', 'alamat', 'kontak']);
    }

    public function hideSuggestions()
    {
        $this->suggestions = [];
    }


    public function render()
    {
        return view('livewire.vendor-kontrak-form');
    }
}
