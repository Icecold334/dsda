<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BagianStok;
use App\Models\LokasiStok;
use App\Models\PosisiStok;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use App\Models\PengirimanStok;
use App\Models\DetailPengirimanStok;
use Illuminate\Support\Facades\Auth;

class ListPengirimanForm extends Component
{
    public $list = [];
    public $lokasis = [];
    public $errorsList = [];
    public $vendor_id;


    public function savePengiriman()
    {


        // Validate that all items have required data
        foreach ($this->list as $index => $item) {
            if (!$item['jumlah'] || !$item['lokasi_id']) {
                $this->dispatch('error');
                return;
            }
        }

        // Create a new detail_pengiriman_stok record as the "parent" for this shipment
        $detailPengiriman = DetailPengirimanStok::create([
            'kode_pengiriman_stok' => 'PGS' . mt_rand(10000, 99999), // Generate random kode
            'kontrak_id' => 2,
            'tanggal' => strtotime(now()),
            'user_id' => Auth::id(),
            // 'created_at' => now(),
        ]);

        foreach ($this->list as $item) {
            $requestedQuantity = $item['jumlah'];
            $merkId = $item['merk_id'];

            // Ambil transaksi untuk kontrak yang bertipe `Pemasukan`, urutkan berdasarkan yang paling lama
            $transactions = \App\Models\TransaksiStok::where('vendor_id', $this->vendor_id)
                ->where('merk_id', $merkId)
                ->where('tipe', 'Pemasukan')
                ->whereNotNull('kontrak_id')
                ->orderBy('tanggal')
                ->get();

            foreach ($transactions as $transaction) {
                // Hitung jumlah sisa di transaksi kontrak ini
                $shippedQty = \App\Models\PengirimanStok::where('kontrak_id', $transaction->kontrak_id)
                    ->where('merk_id', $merkId)
                    ->sum('jumlah');

                $remainingQty = $transaction->jumlah - $shippedQty;

                // Jika tidak ada jumlah tersisa di kontrak ini, lanjut ke kontrak berikutnya
                if ($remainingQty <= 0) {
                    continue;
                }

                // Jumlah yang bisa dipenuhi dari kontrak ini
                $fulfillableQty = min($remainingQty, $requestedQuantity);

                // Buat entri PengirimanStok
                \App\Models\PengirimanStok::create([
                    'detail_pengiriman_id' => $detailPengiriman->id,
                    'kontrak_id' => $transaction->kontrak_id,
                    'merk_id' => $merkId,
                    'tanggal_pengiriman' => strtotime(now()),
                    'jumlah' => $fulfillableQty,
                    'lokasi_id' => $item['lokasi_id'],
                    'bagian_id' => $item['bagian_id'] ?? null,
                    'posisi_id' => $item['posisi_id'] ?? null,
                ]);

                // Kurangi jumlah yang diminta dengan jumlah yang sudah dipenuhi
                $requestedQuantity -= $fulfillableQty;

                // Jika semua jumlah yang diminta sudah terpenuhi, berhenti
                if ($requestedQuantity <= 0) {
                    break;
                }
            }

            // Jika masih ada jumlah yang belum terpenuhi, tampilkan pesan error
            if ($requestedQuantity > 0) {
                session()->flash('error', "Jumlah stok tidak mencukupi di kontrak untuk merk ID $merkId.");
            }
        }

        // Kosongkan list dan reset pilihan vendor
        $this->list = [];
        $this->vendor_id = null;

        // Tampilkan pesan sukses
        session()->flash('success', 'Pengiriman berhasil disimpan.');
    }

    public function mount()
    {
        $this->lokasis = LokasiStok::all();
    }

    #[On('vendor_id')]
    public function fillVendor($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }
    #[On('merkId')]
    public function appendList($merkId)
    {
        // Avoid duplicates by checking if the merk with the given ID is already in the list
        if (!collect($this->list)->contains('merk_id', $merkId)) {
            $transaksi = MerkStok::findOrFail($merkId);
            $this->list[] = [
                'merk_id' => $transaksi->id,
                'merk' => $transaksi->nama,
                'lokasi_id' => null,
                'bagian_id' => null,
                'posisi_id' => null,
                'bagians' => collect(),
                'posisis' => collect(),
                'jumlah' => 1,
                'max_jumlah' => $this->calculateMaxJumlah($merkId)
            ];
            $this->dispatch('listCount', count: count($this->list));
        } else {
            $this->dispatch('merkExist');
        }
    }

    public function calculateMaxJumlah($merkId)
    {
        // Get the total contracted quantity from `transaksi_stok` for this merk and vendor
        $contractTotal = TransaksiStok::where('vendor_id', $this->vendor_id)
            ->where('merk_id', $merkId)
            ->where('tipe', 'Pemasukan') // Assuming 'Pemasukan' represents contracted quantities
            ->sum('jumlah');


        // Get the total quantity already sent for this merk and vendor
        $sentTotal = PengirimanStok::where('merk_id', $merkId)
            ->whereHas('kontrakVendorStok', function ($query) {
                $query->where('vendor_id', $this->vendor_id);
            })
            ->sum('jumlah');

        // Calculate the maximum quantity allowed for this item
        return max($contractTotal - $sentTotal, 0);
    }


    public function updateLokasi($index, $lokasiId)
    {
        // Update lokasi, reset related fields, and load associated bagians
        $this->list[$index]['lokasi_id'] = $lokasiId;
        $this->list[$index]['bagian_id'] = null;
        $this->list[$index]['posisi_id'] = null;

        // Load bagians and reset posisis
        $this->list[$index]['bagians'] = BagianStok::where('lokasi_id', $lokasiId)->get();
        $this->list[$index]['posisis'] = collect();
    }

    public function updateBagian($index, $bagianId)
    {
        // Update bagian, reset posisi, and load associated posisis
        $this->list[$index]['bagian_id'] = $bagianId;
        $this->list[$index]['posisi_id'] = null;
        $this->list[$index]['posisis'] = PosisiStok::where('bagian_id', $bagianId)->get();
    }

    public function updatePosisi($index, $posisiId)
    {
        // Update selected posisi
        $this->list[$index]['posisi_id'] = $posisiId;
    }

    public function updateJumlah($index, $value)
    {
        // Ensure the quantity does not exceed the maximum allowed quantity
        $maxAllowed = $this->list[$index]['max_jumlah'];

        // If the input value is greater than the allowed maximum, set it to the maximum value
        if ($value > $maxAllowed) {
            $this->list[$index]['jumlah'] = $maxAllowed;
            $this->errorsList[$index] = 'Jumlah maksimal : ' . $maxAllowed;
        } else {
            $this->list[$index]['jumlah'] = $value;
            unset($this->errorsList[$index]); // Clear error if value is valid
        }
    }


    public function removeFromList($index)
    {
        // Use unset to remove the item at the given index from the list
        unset($this->list[$index]);

        // Reindex the array to ensure continuous indices
        $this->list = array_values($this->list);
        $this->dispatch('listCount', count: count($this->list));
    }


    public function render()
    {
        return view('livewire.list-pengiriman-form');
    }
}
