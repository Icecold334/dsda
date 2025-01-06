<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiStok;

class DataTransaksiDaruratStok extends Component
{
    public $search = ''; // Search term
    public $transaksi; // Transactions
    public $unit_id; // Transactions
    public $groupedTransactions; // Grouped transactions

    public function mount()
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
        $this->groupedTransactions = $sortedTransaksi->groupBy('vendor_id');
        // Initial data fetch
        // $this->fetchData();
    }

    public function updated($propertyName)
    {
        // Re-fetch data whenever a filter property is updated
        $this->fetchData();
    }

    public function fetchData()
    {
        // Fetch data based on unitKerja and optional search filtering
        // $this->transaksi = TransaksiStok::whereNull('kontrak_id')
        //     ->when($this->unit_id, function ($query) {
        //         return $query->whereHas('user', function ($userQuery) {
        //             $userQuery->whereHas('unitKerja', function ($unitQuery) {
        //                 $unitQuery->where('parent_id', $this->unit_id)
        //                     ->orWhere('id', $this->unit_id);
        //             });
        //         });
        //     })
        //     ->when($this->search, function ($query) {
        //         return $query->whereHas('vendorStok', function ($vendorQuery) {
        //             $vendorQuery->where('nama', 'like', '%' . $this->search . '%');
        //         });
        //     })
        //     ->get();

        // Group transactions by vendor_id and then by unit_id
        // $this->groupedTransactions = $this->transaksi->sortByDesc('tanggal')->groupBy('vendor_id');
        // $this->groupedTransactions = $this->transaksi->sortByDesc('tanggal');
        // $this->groupedTransactions = $this->transaksi->groupBy('vendor_id');
    }

    public function render()
    {
        return view('livewire.data-transaksi-darurat-stok');
    }
}
