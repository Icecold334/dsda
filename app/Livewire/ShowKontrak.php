<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\KontrakVendorStok;

class ShowKontrak extends Component
{
    public $kontrakId;
    public $kontrak;
    public $total;


    public function mount($kontrakId)
    {
        $this->kontrakId = $kontrakId;
        $this->loadKontrak();
    }

    public $riwayatKontrak = [];
    public function updatedKontrakId($value)
    {
        if ($value) {
            $this->loadKontrak(); // fungsi yang sudah kamu buat
        }
    }

    public function loadKontrak()
    {
        $this->kontrak = KontrakVendorStok::with([
            'listKontrak.merkStok.barangStok.satuanBesar',
            'metodePengadaan',
            'vendorStok',
            'dokumen'
        ])->findOrFail($this->kontrakId);

        // Ambil semua versi dari kontrak ini (parent + adendum)
        $parentId = $this->kontrak->parent_kontrak_id ?? $this->kontrak->id;

        $this->riwayatKontrak = KontrakVendorStok::where('id', $parentId)
            ->orWhere('parent_kontrak_id', $parentId)
            ->orderBy('tanggal_kontrak', 'desc')
            ->get();

        $this->total = $this->kontrak->listKontrak->sum(function ($item) {
            $subtotal = $item->harga * $item->jumlah;
            $ppn = $item->ppn ? ($subtotal * ((int) $item->ppn) / 100) : 0;
            return $subtotal + $ppn;
        });
    }

    public function render()
    {
        return view('livewire.show-kontrak');
    }
}
