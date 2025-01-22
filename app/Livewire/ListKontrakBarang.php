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
    public $jenis_id;
    public $merkList, $unit_id;
    #[On('jenis_id')]
    public function fillJenis($jenis_id)
    {
        $this->jenis_id = $jenis_id;


        $merks = MerkStok::whereHas('transaksiStok', function ($query) {
            $query->whereHas('kontrakStok', function ($kontrakQuery) {
                $kontrakQuery->where('vendor_id', $this->vendor_id)->where('status', true)->where('type', true)->whereHas('user', function ($user) {
                    return $user->whereHas('unitKerja', function ($unit) {
                        return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                    });
                });
            });
        })->get();


        $this->merkList = $merks->map(function ($merk) {
            return [
                'id' => $merk->id,
                'barang_stok' => $merk->barangStok,
                'merk' => $merk,
                'max_jumlah' => $this->calculateMaxJumlah($merk->id), // Hitung sekali
                'satuan' => optional($merk->barangStok->satuanBesar)->nama,
            ];
        })
            // ->filter(function ($merk) {
            //     return $merk['max_jumlah'] > 0 && $merk['barang_stok']->jenis_id == $this->jenis_id;
            // })
            ->values(); // Reset key array agar tetap rapi


    }
    #[On('vendor_id')]
    public function fillVendor($vendor_id)
    {
        $this->vendor_id = $vendor_id;
        $this->fillJenis($this->jenis_id);
    }

    public function calculateMaxJumlah($merkId)
    {
        // Get the total contracted quantity from `transaksi_stok` for this merk and vendor
        $contractTotal = TransaksiStok::where('vendor_id', $this->vendor_id)->whereHas('kontrakStok', function ($kontrakQuery) {
            $kontrakQuery->where('vendor_id', $this->vendor_id)->where('status', true)
                ->where('type', true) // Assuming 'type' is a boolean field
            ; // Assuming 'type' is a boolean field
        })->where('merk_id', $merkId)
            ->where('tipe', 'Pemasukan') // Assuming 'Pemasukan' represents contracted quantities
            ->sum('jumlah');
        // ->sum('jumlah_diterima');
        $sentTotal = PengirimanStok::where('merk_id', $merkId)
            ->whereHas('kontrakVendorStok', function ($query) {
                $query->where('vendor_id', $this->vendor_id);
            })
            ->sum('jumlah_diterima');
        // Calculate the maximum quantity allowed for this item
        return max($contractTotal - $sentTotal, 0);
    }
    #[On('pengirimanUpdated')]
    public function updateJumlahSisa($merkId, $usedJumlah)
    {
        $this->merkList = $this->merkList->map(function ($merk) use ($merkId, $usedJumlah) {
            if ($merk['id'] == $merkId) {
                $merk['max_jumlah'] -= $usedJumlah; // Kurangi jumlah
            }
            return $merk;
        });

        // Dispatch update max_jumlah ke komponen List Pengiriman
        $merk = $this->merkList->firstWhere('id', $merkId);
        if ($merk) {
            $this->dispatch('maxJumlahUpdated', merkId: $merkId, maxJumlah: $merk['max_jumlah']);
        }
    }
    // public function hydrate()
    // {
    //     if ($this->jenis_id) {
    //         $this->fillJenis($this->jenis_id);
    //     }
    // }
    public function mount()
    {
        $this->merkList = [];
    }

    // #[On('merkRemoved')]
    // public function addMerkBackToList($merkId, $jumlah)
    // {
    //     // Ambil merk berdasarkan ID dan tambahkan ke merkList
    //     $merk = MerkStok::find($merkId);
    //     if ($merk) {
    //         $this->merkList->map(function ($merk) use ($jumlah) {
    //             $merk->max_jumlah = $this->calculateMaxJumlah($merk->id) + $jumlah;
    //             return $merk;
    //         });
    //     }
    // }

    public function merkClick($id)
    {

        $this->dispatch('merkSelected', merkId: $id);
    }
    public function render()
    {
        return view('livewire.list-kontrak-barang');
    }
}
