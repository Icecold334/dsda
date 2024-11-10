<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MerkStok;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use App\Models\PengirimanStok;

class ListKontrakBarang extends Component
{
    public $vendor_id;
    public $merkList = [];
    #[On('vendor_id')]
    public function fillVendor($vendor_id)
    {
        $this->vendor_id = $vendor_id;
        // Fetch merks that match the vendor and contract conditions
        $merks = MerkStok::whereHas('transaksiStok', function ($query) {
            $query->whereHas('kontrakStok', function ($kontrakQuery) {
                $kontrakQuery->where('vendor_id', $this->vendor_id)
                    ->where('type', true); // Assuming 'type' is a boolean field
            });
        })->get();

        // Filter merks to only include those with max jumlah > 0
        $this->merkList = $merks->filter(function ($merk) {
            $maxJumlah = $this->calculateMaxJumlah($merk->id);
            return $maxJumlah > 0;
        });
    }

    public function calculateMaxJumlah($merkId)
    {
        // Get the total contracted quantity from `transaksi_stok` for this merk and vendor
        $contractTotal = TransaksiStok::where('vendor_id', $this->vendor_id)
            ->where('merk_id', $merkId)
            ->where('tipe', 'Pemasukan') // Assuming 'Pemasukan' represents contracted quantities
            ->sum('jumlah');

        // dd($contractTotal);


        // Get the total quantity already sent for this merk and vendor
        $sentTotal = PengirimanStok::where('merk_id', $merkId)
            ->whereHas('kontrakVendorStok', function ($query) {
                $query->where('vendor_id', $this->vendor_id);
            })
            ->sum('jumlah');

        // Calculate the maximum quantity allowed for this item
        return max($contractTotal - $sentTotal, 0);
    }
    public function mount()
    {
        // $this->merkList = MerkStok::whereHas('transaksiStok', function ($query) {
        //     $query->whereHas('kontrakStok', function ($kontrakQuery) {
        //         $kontrakQuery->where('vendor_id', 1)
        //             ->where('type', true); // Assuming 'type' is a boolean field
        //     });
        // })->get();
        $this->merkList = [];
    }

    public function merkClick($id)
    {
        $this->dispatch('merkId', merkId: $id);
    }
    public function render()
    {
        return view('livewire.list-kontrak-barang');
    }
}
