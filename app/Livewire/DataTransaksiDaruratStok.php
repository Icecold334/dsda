<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiStok;

class DataTransaksiDaruratStok extends Component
{
    public $search = ''; // Search term
    public $transaksi; // Transactions
    public $unit_id; // Transactions
    // public $grouped; // Grouped transactions

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
    $query = TransaksiStok::whereNull('kontrak_id')
        ->whereHas('user', function ($user) {
            if ($this->unit_id) {
                $user->whereHas('unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                });
            }
        });

    // Gunakan paginate sebelum sorting dan grouping
    $transaksi = $query->paginate(5);

    // Sort transactions by date
    $sortedTransaksi = $transaksi->getCollection()->sortByDesc('tanggal');

    // Replace the collection of paginator with sorted data
    $transaksi->setCollection($sortedTransaksi);

    // Group transactions by vendor_id
    $groupedTransactions = $sortedTransaksi->groupBy('vendor_id');

    return [
        'pagination' => $transaksi,
        'grouped' => $groupedTransactions
    ];
}


public function render()
{
    $data = $this->fetchData();
    $pagination = $data['pagination'];
    $grouped = $data['grouped'];

    return view('livewire.data-transaksi-darurat-stok', compact('grouped', 'pagination'));
}

}