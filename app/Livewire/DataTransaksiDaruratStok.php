<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiStok;

class DataTransaksiDaruratStok extends Component
{
    public $search = ''; // Search term
    public $transaksi; // Transactions
    public $groupedTransactions; // Grouped transactions

    public function mount()
    {
        // Initial data fetch
        $this->fetchData();
    }

    public function updated($propertyName)
    {
        // Re-fetch data whenever a filter property is updated
        $this->fetchData();
    }

    public function fetchData()
    {
        // Fetch data based on unitKerja and optional search filtering
        $this->transaksi = TransaksiStok::whereNull('kontrak_id')
            ->when($this->unit_id, function ($query) {
                return $query->whereHas('user', function ($userQuery) {
                    $userQuery->whereHas('unitKerja', function ($unitQuery) {
                        $unitQuery->where('parent_id', $this->unit_id)
                            ->orWhere('id', $this->unit_id);
                    });
                });
            })
            ->when($this->search, function ($query) {
                return $query->whereHas('vendorStok', function ($vendorQuery) {
                    $vendorQuery->where('nama', 'like', '%' . $this->search . '%');
                });
            })
            ->get();

        // Group transactions by vendor_id and then by unit_id
        $this->groupedTransactions = $this->transaksi->sortByDesc('tanggal')->groupBy('vendor_id');
        // dd($this->groupedTransactions);
    }

    public function render()
    {
        return view('livewire.data-transaksi-darurat-stok', [
            'groupedTransactions' => $this->groupedTransactions,
        ]);
    }
}
