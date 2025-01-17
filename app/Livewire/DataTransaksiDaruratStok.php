<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiStok;
use Illuminate\Support\Facades\Request;

class DataTransaksiDaruratStok extends Component
{
    public $isCreate;
    public $search = ''; // Search term
    public $transaksi; // Transactions
    public $unit_id; // Transactions
    public $grouped; // Grouped transactions

    public function mount()
    {

        $this->fetchData();
    }

    public function updated($propertyName)
    {
        // Re-fetch data whenever a filter property is updated
        $this->fetchData();
    }

    public function fetchData()
    {
        if ($this->unit_id) {
            # code...
            $transaksi = TransaksiStok::whereNull('kontrak_id')
                ->whereHas('user', function ($user) {
                    return $user->whereHas('unitKerja', function ($unit) {
                        return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                    });
                })
                ->get();
        } else {
            $transaksi = TransaksiStok::whereNull('kontrak_id')
                ->whereHas('user', function ($user) {
                    return $user;
                })
                ->get();
        }

        // Sort transactions by date
        $sortedTransaksi = $transaksi->sortByDesc('tanggal');

        // Group transactions by vendor_id first, then by unit_id
        // $groupedTransactions = $sortedTransaksi->groupBy('vendor_id')->map(function ($vendorGroup) {
        //     return $vendorGroup->groupBy('user.unit_id'); // Group by user unit_id
        // });
        // dd($sortedTransaksi->groupBy('vendor_id'));
        $this->grouped = $sortedTransaksi->groupBy('vendor_id')->all();
    }

    public function render()
    {
        return view('livewire.data-transaksi-darurat-stok',);
    }
}
