<?php

namespace App\Livewire;

use App\Models\Stok;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BagianStok;
use App\Models\LokasiStok;
use App\Models\PosisiStok;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use Livewire\WithFileUploads;
use App\Models\PengirimanStok;
use App\Models\DetailPengirimanStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class ListPengirimanForm extends Component
{
    use WithFileUploads;
    public $old = [];
    public $list = [];
    public $lokasis = [];
    public $errorsList = [];
    public $vendor_id;
    public $penulis;
    public $pj1;
    public $pj2;
    public $showRemove;

    #[On('merkSelected')]
    public function addMerkToList($merkId)
    {
        $this->appendList($merkId);
    }


    public function removePhoto($index)
    {
        // dd($this->list[$index]);
        if (isset($this->list[$index]['bukti'])) {
            // $filePath = 'buktiPengiriman/' . $this->list[$index]['bukti'];

            // // Remove the file from storage if it exists
            // if (Storage::disk('public')->exists($filePath)) {
            //     $pengiriman = PengirimanStok::find($this->list[$index]['id']);
            //     // if ($pengiriman) {
            //     //     $pengiriman->update(['img' => null]);
            //     // }
            //     Storage::disk('public')->delete($filePath);
            // }

            // Remove the photo from the list
            unset($this->list[$index]['bukti']);

            // Provide feedback if needed
            // session()->flash('success', 'File berhasil dihapus.');
        }
    }



    public function savePengiriman()
    {
        // Validate that all items have required data
        foreach ($this->list as $index => $item) {
            if (!$item['jumlah'] || !$item['lokasi_id']) {
                $this->dispatch('error');
                return;
            }
        }

        // Create a new detail_pengiriman_stok record if needed
        $this->validate([
            'penulis' => 'nullable|string',
            'pj1' => 'nullable|string',
            'pj2' => 'nullable|string',
        ]);
        $detailPengiriman = DetailPengirimanStok::updateOrCreate(
            [
                'id' => count($this->old) > 0 ? $this->old[0]->detail_pengiriman_id : 0,
            ],
            [
                'kode_pengiriman_stok' => fake()->bothify('KP#######'),
                'kontrak_id' => 2,
                'tanggal' => strtotime(date('Y-m-d H:i:s')),
                'user_id' => Auth::id(),
                'penerima' => $this->penulis,
                'pj1' => $this->pj1,
                'pj2' => $this->pj2,
            ]
        );


        foreach ($this->list as $item) {
            $requestedQuantity = $item['jumlah'];
            $merkId = $item['merk_id'];

            if (isset($item['detail'])) {
                // Update existing PengirimanStok record if `detail` is set
                $pengirimanStok = PengirimanStok::find($item['id']);
                $pengirimanStok->update([
                    // 'jumlah' => $requestedQuantity,
                    // 'lokasi_id' => $item['lokasi_id'],
                    'bagian_id' => $item['bagian_id'] ?? null,
                    'posisi_id' => $item['posisi_id'] ?? null,
                    'img' => isset($item['bukti']) && !is_string($item['bukti']) ? str_replace('buktiPengiriman/', '', $item['bukti']->storeAs('buktiPengiriman', $item['bukti']->getClientOriginalName(), 'public')) : null,
                    // 'img' => isset($item['bukti']) && !is_string($item['bukti']) ? str_replace('buktiPengiriman/', '', $item['bukti']->storeAs('buktiPengiriman', $item['bukti']->getClientOriginalName(), 'public')) : $pengirimanStok->img,
                    // 'tanggal_pengiriman' => strtotime(date('Y-m-d H:i:s')),
                ]);
            } else {
                $transactions = \App\Models\TransaksiStok::where('vendor_id', $this->vendor_id)
                    ->where('merk_id', $merkId)
                    ->where('tipe', 'Pemasukan')
                    ->whereNotNull('kontrak_id')
                    ->orderBy('tanggal')
                    ->get();
                foreach ($transactions as $transaction) {
                    $shippedQty = PengirimanStok::where('kontrak_id', $transaction->kontrak_id)
                        ->where('merk_id', $merkId)
                        ->sum('jumlah');
                    $remainingQty = $transaction->jumlah - $shippedQty;

                    if ($remainingQty <= 0) {
                        continue;
                    }

                    $fulfillableQty = min($remainingQty, $requestedQuantity);

                    PengirimanStok::create([
                        'detail_pengiriman_id' => $detailPengiriman->id,
                        'kontrak_id' => $transaction->kontrak_id,
                        'merk_id' => $merkId,
                        'img' => isset($item['bukti']) && !is_string($item['bukti'])  ? str_replace('buktiPengiriman/', '', $item['bukti']->storeAs('buktiPengiriman', $item['bukti']->getClientOriginalName(), 'public')) : null,
                        'tanggal_pengiriman' => strtotime(date('Y-m-d H:i:s')),
                        'jumlah' => $fulfillableQty,
                        'lokasi_id' => $item['lokasi_id'],
                        'bagian_id' => $item['bagian_id'] ?? null,
                        'posisi_id' => $item['posisi_id'] ?? null,
                    ]);

                    $requestedQuantity -= $fulfillableQty;

                    if ($requestedQuantity <= 0) {
                        break;
                    }
                }
            }
        }
        if ($detailPengiriman->penerima && $detailPengiriman->pj1 && $detailPengiriman->pj2) {
            // Menambahkan stok
            $pengirimanItems = PengirimanStok::where('detail_pengiriman_id', $detailPengiriman->id)->get();

            foreach ($pengirimanItems as $pengiriman) {
                // Menambahkan stok sesuai lokasi, bagian, dan posisi
                $stok = Stok::firstOrCreate(
                    [
                        'merk_id' => $pengiriman->merk_id,
                        'lokasi_id' => $pengiriman->lokasi_id,
                        'bagian_id' => $pengiriman->bagian_id,
                        'posisi_id' => $pengiriman->posisi_id,
                    ],
                    ['jumlah' => 0]  // Atur stok awal jika belum ada
                );

                $stok->jumlah += $pengiriman->jumlah;
                $stok->save();
            }
        }
        // Clear the list and reset the vendor selection
        $this->list = [];
        $this->vendor_id = null;

        return redirect()->route('pengiriman-stok.index');
    }


    public function mount()
    {
        $this->showRemove = !Request::routeIs('pengiriman-stok.show');
        $this->lokasis = LokasiStok::all();
        if (count($this->old)) {
            foreach ($this->old as $old) {
                // if (!collect($this->list)->contains('merk_id', $merkId)) {
                $transaksi = PengirimanStok::findOrFail($old->id);
                $isEditable = is_null($transaksi->bagian_id) && is_null($transaksi->posisi_id);
                $this->list[] = [
                    'id' => $transaksi->id,
                    'merk_id' => $transaksi->merkStok->id,
                    'merk' => $transaksi->merkStok->nama,
                    'detail' => $transaksi->detail_pengiriman_id ?? null,
                    'bukti' => $transaksi->img,
                    'lokasi_id' => $transaksi->lokasi_id,
                    'bagian_id' => $transaksi->bagian_id,
                    'posisi_id' => $transaksi->posisi_id,
                    'bagians' => BagianStok::where('lokasi_id', $transaksi->lokasi_id)->get(),
                    'posisis' => collect(),
                    'jumlah' => $transaksi->jumlah ?? 1,
                    'max_jumlah' => $this->calculateMaxJumlah($old->merkStok->id),
                    'editable' => $isEditable,
                ];

                $this->dispatch('listCount', count: count($this->list));
                // }
            }
        }
    }

    #[On('vendor_id')]
    public function fillVendor($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }
    // #[On('merkId')]
    public function appendList($merkId)
    {
        if (!collect($this->list)->contains('merk_id', $merkId)) {
            $transaksi = MerkStok::findOrFail($merkId);
            $isEditable = is_null($transaksi->bagian_id) && is_null($transaksi->posisi_id);

            $this->list[] = [
                'id' => null,
                'merk_id' => $transaksi->id,
                'merk' => $transaksi->nama,
                'lokasi_id' => null,
                'detail' => null,
                'bagian_id' => null,
                'posisi_id' => null,
                'bagians' => collect(),
                'posisis' => collect(),
                'jumlah' => 1,
                'max_jumlah' => $this->calculateMaxJumlah($merkId),
                'editable' => $isEditable,
            ];

            $this->dispatch('listCount', count: count($this->list));
        } else {
            $this->dispatch('merkExist');
        }
    }


    public function calculateMaxJumlah($merkId)
    {
        // Get the total contracted quantity from `transaksi_stok` for this merk and vendor
        $contractTotal = TransaksiStok::where('vendor_id', $this->vendor_id)->whereHas('kontrakStok', function ($kontrakQuery) {
            $kontrakQuery->where('vendor_id', $this->vendor_id)->where('status', true)
                ->where('type', true) // Assuming 'type' is a boolean field
            ; // Assuming 'type' is a boolean field
        })
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
    public function updated($propertyName)
    {
        // Check if the updated property matches the specific file input
        // if (preg_match('/^list\.\d+\.bukti$/', $propertyName)) {
        //     // Extract the index from the property name
        //     preg_match('/^list\.(\d+)\.bukti$/', $propertyName, $matches);
        //     $index = $matches[1];

        //     // Validate the file
        //     $this->validate([
        //         "list.{$index}.bukti" => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
        //     ]);

        //     // Process the file upload
        //     $file = $this->list[$index]['bukti'];
        //     if ($file) {

        //         $storedFilePath = str_replace('buktiPengiriman/', '', $file->storeAs(
        //             'buktiPengiriman', // Directory
        //             $file->getClientOriginalName(), // File name
        //             'public' // Storage disk
        //         ));

        //         // Update the list with the stored file path
        //         $this->list[$index]['bukti'] = $storedFilePath;

        //         // Provide feedback
        //         session()->flash('success', 'File berhasil diunggah.');
        //     }
        // }
    }



    public function removeFromList($index)
    {

        $merkId = $this->list[$index]['merk_id'];
        // Use unset to remove the item at the given index from the list
        unset($this->list[$index]);

        // Reindex the array to ensure continuous indices
        $this->list = array_values($this->list);
        $this->dispatch('listCount', count: count($this->list));
        // Emit event ke ListKontrakBarang untuk mengembalikan merk yang dihapus
        $this->dispatch('merkRemoved', merkId: $merkId);
    }


    public function render()
    {
        return view('livewire.list-pengiriman-form');
    }
}
