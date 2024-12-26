<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Toko;
use Livewire\Component;
use App\Models\JenisStok;
use App\Models\BarangStok;
use App\Models\MetodePengadaan;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class VendorKontrakForm extends Component
{
    public $vendors;
    public $nomor_kontrak;
    public $tanggal_kontrak;
    public $barangs;
    public $metodes;
    public $metode_id;
    public $showMetode;
    public $vendor_id;
    public $barang_id;
    public $nama;
    public $alamat;
    public $kontak;
    public $listCount;
    public $show;
    public $showNomor, $cekSemuaItem;
    public $showAddVendorForm = false;
    public $showSuggestions;
    public $query = ''; // Untuk query input saran vendor
    public $suggestions = []; // Untuk menyimpan hasil saran

    public function focus()
    {
        $this->updatedQuery();
    }

    public function updatedQuery()
    {
        // dd('aa');
        $this->showSuggestions = true;

        // Ambil data dari database berdasarkan query
        $this->suggestions = Toko::where('nama', 'like', '%' . $this->query . '%')
            // ->limit(5)
            ->get()
            ->toArray();
        $this->nama = $this->query;

        $exactMatch = Toko::where('nama', $this->query)->first();

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
        $this->vendors = Toko::all();
        $this->cekSemuaItem = $this->checkStatusbyVendor($this->vendor_id);
        if ($this->vendor_id) {
            $vendor = Toko::find($this->vendor_id);
            $this->query = $vendor->nama;
        }
        if (Request::routeIs('kontrak-vendor-stok.create') || Request::routeIs('transaksi-darurat-stok.edit')) {
            $this->showMetode = true;
            $this->metodes = MetodePengadaan::all();
        }
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

    public function updatedMetodeId()
    {
        $this->dispatch('metode_id', metode_id: $this->metode_id);
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

        $newVendor = Toko::create([
            'user_id' => Auth::user()->id,
            'nama' => $this->nama,
            'nama_nospace' => Str::slug($this->nama),
            'alamat' => $this->alamat,
            'telepon' => $this->kontak,
            'email' => null,
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
        // $this->suggestions = [];
        $this->showSuggestions = false;
    }

    private function checkStatusbyVendor($vendor_id){
        $data = TransaksiStok::where(function ($query) {
            $query->where('status', NULL)->orWhere('status', 0);
        })->where('vendor_id', $vendor_id)->get();
        if ($data->isEmpty()) { // Gunakan isEmpty() untuk Collection jika tidak ada maka sudah acc semua
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }


    public function render()
    {
        return view('livewire.vendor-kontrak-form');
    }
}
