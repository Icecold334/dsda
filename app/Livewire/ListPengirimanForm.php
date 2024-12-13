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
    public $pengiriman;
    public $old = [];
    public $list = [];
    public $lokasis = [];
    public $errorsList = [];
    public $vendor_id;
    public $penulis;
    public $pj1;
    public $pj2;
    public $showRemove;
    public $showDokumen;


    #[On('merkSelected')]
    public function addMerkToList($merkId)
    {
        $merk = MerkStok::find($merkId);

        if ($merk) {
            $this->list[] = [
                'id' => null,
                'merk_id' => $merk->id,
                'merk' => $merk,
                'lokasi_id' => null,
                'bagian_id' => null,
                'posisi_id' => null,
                'detail' => null,
                'bagians' => [],
                'posisis' => [],
                'jumlah' => 1,
                'max_jumlah' => $this->calculateMaxJumlah($merk->id),
                'editable' => true,
            ];
        }

        $this->dispatch('pengirimanUpdated', merkId: $merkId, usedJumlah: 1);
        $this->dispatch('listCount', count: count($this->list));
    }


    public function removePhoto($index)
    {
        // dd($this->list[$index]);
        if (isset($this->list[$index]['bukti'])) {
            $filePath = 'buktiPengiriman/' . $this->list[$index]['bukti'];

            // Remove the file from storage if it exists
            if (Storage::disk('public')->exists($filePath)) {
                $pengiriman = PengirimanStok::find($this->list[$index]['id']);
                if ($pengiriman) {
                    $pengiriman->update(['img' => null]);
                }
                Storage::disk('public')->delete($filePath);
            }

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
                $this->dispatch('error', pesan: 'Lengkapi data pengiriman!');
                return;
            }
        }


        if (!$this->showDokumen) {
            $merkTotals = [];
            foreach ($this->list as $item) {
                $merkId = $item['merk_id'];
                $maxAllowed = $item['max_jumlah'];

                // Hitung akumulasi jumlah untuk merk yang sama
                if (!isset($merkTotals[$merkId])) {
                    $merkTotals[$merkId] = 0;
                }
                $merkTotals[$merkId] += $item['jumlah'];

                // Periksa apakah akumulasi melebihi batas
                if ($merkTotals[$merkId] > $maxAllowed) {
                    $nama = $item['merk']->nama ?? 'Tidak diketahui';
                    $tipe = $item['merk']->tipe ?? 'Tidak diketahui';
                    $ukuran = $item['merk']->ukuran ?? 'Tidak diketahui';

                    $this->dispatch('error', pesan: "Jumlah untuk barang {$nama}, {$tipe}, {$ukuran} melebihi batas maksimal {$maxAllowed}!");
                    return;
                }
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
                $transactions = TransaksiStok::where('vendor_id', $this->vendor_id)
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
        // $this->list = [];
        // $this->vendor_id = null;
        // $this->mount();
        if (!$this->showDokumen) {
            return redirect()->route('pengiriman-stok.index');
        }
    }

    public $roles;

    public function mount()
    {
        // dd($this->old);
        $this->showDokumen = !Request::routeIs('pengiriman-stok.create');
        $this->showRemove = !Request::routeIs('pengiriman-stok.show');
        $this->lokasis = LokasiStok::all();
        if (count($this->old)) {
            $this->pengiriman = collect($this->old)->first()->detailPengirimanStok;
            foreach ($this->old as $old) {
                // if (!collect($this->list)->contains('merk_id', $merkId)) {
                $transaksi = PengirimanStok::findOrFail($old->id);
                $isEditable = is_null($transaksi->bagian_id) && is_null($transaksi->posisi_id);
                $this->list[] = [
                    'id' => $transaksi->id,
                    'merk_id' => $transaksi->merkStok->id,
                    'merk' => $transaksi->merkStok,
                    'detail' => $transaksi->detail_pengiriman_id ?? null,
                    'bukti' => $transaksi->img,
                    'lokasi_id' => $transaksi->lokasi_id,
                    'bagian_id' => $transaksi->bagian_id,
                    'posisi_id' => $transaksi->posisi_id,
                    'bagians' => BagianStok::where('lokasi_id', $transaksi->lokasi_id)->get(),
                    'posisis' => PosisiStok::where('bagian_id', $transaksi->bagian_id)->get(),
                    'jumlah' => $transaksi->jumlah ?? 1,
                    'jumlah_diterima' => $transaksi->jumlah_diterima ?? ($transaksi->jumlah ?? 1),
                    'max_jumlah' => $this->calculateMaxJumlah($old->merkStok->id),
                    'editable' => true,
                ];

                $this->dispatch('listCount', count: count($this->list));
                // }
            }

            $this->roles = Auth::user()->roles->pluck('name')->first();
            // dd($this->list);
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
        // if (!collect($this->list)->contains('merk_id', $merkId)) {
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
        // } else {
        //     $this->dispatch('merkExist');
        // }
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
        if ($this->showDokumen) {
            $this->savePengiriman();
        }
    }

    public function updatePengirimanStok($index)
    {
        $data = $this->list[$index];

        $id_pengiriman = $data['id'];

        $attr = [
            'jumlah_diterima' => $data['jumlah_diterima'],
            'bagian_id' => $data['bagian_id'],
            'posisi_id' => $data['posisi_id']
        ];

        if ($attr['bagian_id']) {
            $this->list[$index]['posisis'] = PosisiStok::where('bagian_id', $attr['bagian_id'])->get();
        }

        PengirimanStok::where('id', $id_pengiriman)->update($attr);

        session()->flash('success', 'Persetujuan berhasil.');
    }

    public function updateBagian($index, $bagianId)
    {
        // Update bagian, reset posisi, and load associated posisis
        $this->list[$index]['bagian_id'] = $bagianId;
        $this->list[$index]['posisi_id'] = null;
        $this->list[$index]['posisis'] = PosisiStok::where('bagian_id', $bagianId)->get();

        if ($this->showDokumen) {
            $this->savePengiriman();
        }
    }

    public function updatePosisi($index, $posisiId)
    {
        // Update selected posisi
        $this->list[$index]['posisi_id'] = $posisiId;
        if ($this->showDokumen) {
            $this->savePengiriman();
        }
    }

    // #[On('maxJumlahUpdated')]
    // public function updateMaxJumlah($merkId, $maxJumlah)
    // {
    //     $this->list = collect($this->list)->map(function ($item) use ($merkId, $maxJumlah) {
    //         if ($item['merk_id'] == $merkId) {
    //             $item['max_jumlah'] = $maxJumlah; // Perbarui max_jumlah
    //         }
    //         return $item;
    //     })->toArray();
    // }

    public function updateJumlah($index, $value)
    {



        $maxAllowed = $this->list[$index]['max_jumlah'];
        $merkId = $this->list[$index]['merk_id'];
        $previousJumlah = $this->list[$index]['jumlah'];

        // Validasi nilai input
        $value = $value === '' ? 0 : (int)$value; // Default ke 0 jika kosong
        // if ($value > $maxAllowed) {
        //     $value = $maxAllowed; // Set ke max jika melebihi
        // }

        // Hitung selisih
        $diff = $value - $previousJumlah;

        // Perbarui nilai di list
        $this->list[$index]['jumlah'] = $value;
        $this->dispatch('pengirimanUpdated', merkId: $merkId, usedJumlah: $diff);
        // }
    }

    public function updated($propertyName)
    {
        if (preg_match('/^list\.\d+\.bukti$/', $propertyName)) {
            // Extract the index from the property name
            preg_match('/^list\.(\d+)\.bukti$/', $propertyName, $matches);
            $index = $matches[1];

            // Validate the file
            // $this->validate([
            //     "list.{$index}.bukti" => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            // ]);

            // Process the file upload
            $file = $this->list[$index]['bukti'];
            $pengiriman = $this->pengiriman->pengirimanStok->find($this->list[$index]['id']);
            if ($file) {

                $storedFilePath = str_replace('buktiPengiriman/', '', $file->storeAs(
                    'buktiPengiriman', // Directory
                    $file->getClientOriginalName(), // File name
                    'public' // Storage disk
                ));
                $pengiriman->update(['img' => $storedFilePath]);
                // Update the list with the stored file path
                $this->list[$index]['bukti'] = $storedFilePath;

                // Provide feedback
                session()->flash('success', 'File berhasil diunggah.');
            }
        }
    }



    public function removeFromList($index)
    {
        $merkId = $this->list[$index]['merk_id'];
        $jumlah = $this->list[$index]['jumlah'];
        // dd($this->list);

        unset($this->list[$index]);
        $this->list = array_values($this->list);
        $this->dispatch('listCount', count: count($this->list));

        $this->dispatch('pengirimanUpdated', merkId: $merkId, usedJumlah: -$jumlah);
    }



    public function render()
    {
        return view('livewire.list-pengiriman-form');
    }
}
