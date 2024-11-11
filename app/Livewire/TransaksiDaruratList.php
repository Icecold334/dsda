<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class TransaksiDaruratList extends Component
{
    use WithFileUploads;
    public $transaksi = [];
    public $list = [];
    public $barangs;
    public $merks;
    public $newBarangId;
    public $newMerkId;
    public $newJumlah;
    public $newKeterangan;
    public $newLokasiPenerimaan;
    public $merk_item;
    public $vendor_id;
    public $jenis_id;
    public $dokumenCount;
    public $newBukti;

    public function removeNewPhoto()
    {
        $this->newBukti = null;
    }
    #[On('jenis_id')]
    public function fillJenis($jenis_id)
    {
        $this->jenis_id = $jenis_id;
        $this->mount();
    }
    #[On('vendor_id')]
    public function fillVendor($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }
    #[On('dokumenCount')]
    public function fillBukti($count)
    {
        $this->dokumenCount = $count;
    }
    public function mount()
    {
        $this->barangs = BarangStok::whereHas('merkStok', function ($query) {
            // Optionally, add conditions to the query if needed
        })->where('jenis_id', $this->jenis_id)->get()->sortBy('jenis_id');
        $this->newJumlah = 1;
        // $this->transaksi = $transaksi;
        $this->merks = [];

        foreach ($this->transaksi as $transaction) {
            $this->list[] = [
                'id' => $transaction->id,
                'barang_id' => $transaction->merkStok->barang_id,
                'merks' => MerkStok::where('barang_id', $transaction->merkStok->barang_id)->get(),
                'merk_id' => $transaction->merk_id,
                'bukti' => $transaction->img,
                'tanggal' => $transaction->tanggal,
                'jumlah' => $transaction->jumlah,
                'keterangan' => $transaction->deskripsi,
                'lokasi_penerimaan' => $transaction->lokasi_penerimaan,
                'editable' => false,
            ];
        }
    }

    public function updatedNewBarangId()
    {
        if ($this->newBarangId) {
            $this->newMerkId = null;
            $this->newJumlah = 1;
            // $this->newKeterangan = '';
            // $this->newLokasiPenerimaan = '';

            $this->merks = MerkStok::where('barang_id', $this->newBarangId)->get();


            // Exclude duplicates only for the latest entry where 'tanggal_pengiriman' is not set
            $selectedMerks = collect($this->list)
                ->filter(function ($item) {
                    return $item['tanggal'] == null; // Check if the transaction is new (no date)
                })
                ->pluck('merk_id')
                ->filter()
                ->all();

            // Filter out the `merks` that already exist in the unsaved transactions
            $this->merks = $this->merks->reject(function ($merk) use ($selectedMerks) {
                return in_array($merk->id, $selectedMerks);
            });
        }
    }

    public function updatedNewMerkId()
    {
        if ($this->newMerkId) {
            $this->merk_item = MerkStok::find($this->newMerkId);
            $this->newJumlah = 1;
        }
    }

    public function addToList()
    {
        $this->validate([
            'newBarangId' => 'required',
            'newMerkId' => 'required',
            'newJumlah' => 'required|integer|min:1',
            'newKeterangan' => 'nullable|string',
            'newLokasiPenerimaan' => 'nullable|string',
            'newBukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5024',
        ]);

        $this->list[] = [
            'barang_id' => $this->newBarangId,
            'merks' => MerkStok::where('barang_id', $this->newBarangId)->get(),
            'merk_id' => $this->newMerkId,
            'tanggal' => null,
            'bukti' => $this->newBukti,
            'jumlah' => $this->newJumlah,
            'keterangan' => $this->newKeterangan,
            'lokasi_penerimaan' => $this->newLokasiPenerimaan,
            'editable' => true,
        ];
        $this->merks = [];
        $this->dispatch('listCount', count: count($this->list));

        $this->reset(['newBarangId', 'newMerkId', 'newJumlah', 'newKeterangan', 'newLokasiPenerimaan', 'newBukti']);
    }

    public function updateList($index, $field, $value)
    {
        $this->list[$index][$field] = $value;
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);
    }

    public function saveKontrak()
    {
        // Validate the required fields
        $this->validate([
            'vendor_id' => 'required',
            'list' => 'required|array|min:1',
            'list.*.barang_id' => 'required|integer',
            'list.*.merk_id' => 'required|integer',
            'list.*.jumlah' => 'required|integer|min:1',
        ]);

        // Create a new contract record (assuming you have a model like `KontrakVendorStok`)
        // $kontrak = KontrakVendorStok::create([
        //     'vendor_id' => $this->vendor_id,
        //     'tanggal_kontrak' => now(),
        //     'nomor_kontrak' => $this->generateContractNumber(), // Assuming a method to generate contract number
        //     'user_id' => auth()->id(),
        //     'type' => true, // Assuming this field indicates the contract is active
        // ]);

        // Loop through each item in the list and create a related transaction
        foreach ($this->list as $item) {
            if ($item['tanggal'] !== null) {
                // Find the existing transaction and update `bukti` if available
                $transaksi = TransaksiStok::find($item['id']);
                if ($transaksi && isset($item['bukti']) && $transaksi->img == null) {
                    $transaksi->update([
                        'img' =>
                        str_replace('buktiTransaksi/', '', $item['bukti']->storeAs('buktiTransaksi', $item['bukti']->getClientOriginalName(), 'public')), // Save the file to public storage
                    ]);
                }
                // Skip further processing for older items
                continue;
            }
            if ($item['tanggal'] == null) {
                // dd('oke');
                TransaksiStok::create([
                    'tipe' => 'Penggunaan Langsung', // Assuming 'Pemasukan' represents a stock addition
                    'merk_id' => $item['merk_id'],
                    'vendor_id' => $this->vendor_id,
                    'img' => $item['bukti'] != null ? str_replace('buktiTransaksi/', '', $item['bukti']->storeAs('buktiTransaksi', $item['bukti']->getClientOriginalName(), 'public')) : null,
                    'user_id' => Auth::id(),
                    'kontrak_id' => null,
                    'tanggal' => strtotime(now()),
                    'jumlah' => $item['jumlah'],
                    'deskripsi' => $item['keterangan'] ?? '',
                    'lokasi_penerimaan' => $item['lokasi_penerimaan'] ?? '',
                ]);
            }
        }

        // Clear the list and reset input fields
        $this->reset(['list', 'vendor_id', 'newBarangId', 'newMerkId', 'newJumlah', 'merks', 'newBukti']);
        return redirect()->route('transaksi-darurat-stok.index');
        // $this->dispatchBrowserEvent('kontrakSaved'); // Trigger any frontend success indication if needed

        // session()->flash('message', 'Kontrak dan transaksi berhasil disimpan.');
    }

    public function finishKontrak()
    {
        // Step 1: Create a new contract with type `false`
        $newKontrak = KontrakVendorStok::create([
            'vendor_id' => $this->vendor_id,
            'tanggal_kontrak' => strtotime(now()),
            'type' => false,
            'user_id' => Auth::id(),
            'nomor_kontrak' => $this->generateContractNumber(), // Method to generate contract number if needed
        ]);

        // Step 2: Update each transaction in the list to set `kontrak_id` to the new contract's ID
        foreach ($this->list as $item) {
            // Only update transactions with null `kontrak_id`
            TransaksiStok::where('id', $item['id'])->update([
                'kontrak_id' => $newKontrak->id,
            ]);
        }

        $this->dispatch('saveDokumen', kontrak_id: $newKontrak->id);
    }
    public function removePhoto($index)
    {
        if (isset($this->list[$index]['bukti'])) {
            unset($this->list[$index]['bukti']);
        }
    }

    protected function generateContractNumber()
    {
        return 'CN-' . strtoupper(Str::random(6));
    }
    public function render()
    {
        return view('livewire.transaksi-darurat-list');
    }
}
