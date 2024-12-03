<?php

namespace App\Livewire;

use id;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use App\Models\SatuanBesar;
use Faker\Factory as Faker;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use Livewire\WithFileUploads;
use App\Models\KontrakVendorStok;
use App\Models\Persetujuan;
use Illuminate\Support\Facades\Auth;

class TransaksiDaruratList extends Component
{
    use WithFileUploads;



    public $cekApproval;
    public $tanggal_kontrak;
    public $nomor_kontrak;
    public $jenis_id;
    public $metode_id;
    public $vendor_id;
    public $dokumenCount;
    public $penulis;
    public $list = [];
    public $newBarang = '';
    public $barangSuggestions = [];
    public $newBarangId = null;
    public $suggestions = [
        'merek' => [],
        'tipe' => [],
        'ukuran' => [],
        'satuanBesar' => [],
        'satuanKecil' => [],
    ];

    public $role_name;

    public function __construct()
    {
        /**
         * mengambil array pertama untuk role name
         */
        $this->role_name = Auth::user()->roles->pluck('name')->first();
    }
    public function fetchSuggestions($field, $value)
    {
        $this->suggestions[$field] = [];
        if ($value) {
            if ($field === 'satuanBesar') {
                $this->suggestions[$field] = SatuanBesar::where('nama', 'like', '%' . $value . '%')
                    ->pluck('nama')->toArray();
            } elseif ($field === 'satuanKecil') {
                $this->suggestions[$field] = SatuanBesar::where('nama', 'like', '%' . $value . '%')
                    ->pluck('nama')->toArray();
            }
        }
    }
    public function selectSuggestion($field, $value)
    {
        if ($field === 'satuanBesar') {
            $this->newBarangSatuanBesar = $value;
        } elseif ($field === 'satuanKecil') {
            $this->newBarangSatuanKecil = $value;
        }
        $this->suggestions[$field] = [];
    }

    public $specifications = [
        'merek' => '',
        'tipe' => '',
        'ukuran' => '',
    ];
    public $newJumlah = null;
    public $newKeterangan = '';
    public $newLokasiPenerimaan = '';
    public $newBukti;
    public $transaksi;
    public $merkSuggestions = [];
    public $satuanBesarOptions;
    public $newBarangSatuanBesar = '';
    public $newBarangSatuanKecil = '';
    public $showBarangModal = false;
    public $jumlahKecilDalamBesar, $roles, $items, $id, $ppk_isapprove, $pptk_isapprove, $pj_isapprove, $statusapprove;

    public function getStatusApprov($id){
        $ppkCount = Persetujuan::where('approvable_id', $id)->where('approvable_type',TransaksiStok::class)->get();

        return $this->statusapprove = count($ppkCount);
    }

    public function mount()
    {
        $this->satuanBesarOptions = SatuanBesar::all();
        if ($this->transaksi) {

            foreach ($this->transaksi as $key => $item) {
                $this->id = $item->id;

                $this->ppk_isapprove = $this->checkApprovals('ppk');

                $this->pptk_isapprove = $this->checkApprovals('pptk');

                $this->pj_isapprove = $this->checkApprovals('penanggungjawab');

                $sumapprove = $this->getStatusApprov($this->id);

                // $dataapp = $item->approvals->filter(function ($appr){
                //     return $appr->role === auth()->user()->getRoleNames()->first();
                // });

                $filteredApprovals = $item->approvals->filter(function ($approval) {
                    return $approval->role === auth()->user()->getRoleNames()->first(); // Replace 'desired_role' with the actual role
                });

                if ($filteredApprovals->isNotEmpty()) {
                    foreach ($filteredApprovals as $approval) {
                        $img = $approval->img;
                    }
                } else {
                    $img = null;
                }

                $spec = [
                    'merek' => $item->merkStok->nama,
                    'tipe' => $item->merkStok->tipe,
                    'ukuran' => $item->merkStok->ukuran,
                ];
                $this->list[] = [
                    'id' => $item->id,
                    'barang_id' => $item->id,
                    'barang' => BarangStok::find($item->merkStok->barangStok->id)->nama,
                    'specifications' => $spec,
                    'jumlah' => $item->jumlah,
                    'satuan' => BarangStok::find($item->merkStok->barangStok->id)->satuanBesar->nama,
                    'lokasi_penerimaan' => $item->lokasi_penerimaan,
                    'keterangan' => $item->deskripsi,
                    'bukti' => $img,
                    'pptk_isapprove' => $this->pptk_isapprove ?? 0,
                    'ppk_isapprove' => $this->ppk_isapprove ?? 0,
                    'pj_isapprove' => $this->pj_isapprove ?? 0,
                    'status' => $item->status,
                    'sumApprove' => $sumapprove
                    // $item->approvals->first()->img
                ];
            }
            $this->dispatch('listCount', count: count($this->list));
        }
        $this->cekApproval =     TransaksiStok::where('vendor_id', $this->vendor_id)->where(function ($query) {
            $query->whereNull('pptk_id')
                ->orWhereNull('ppk_id');
        })->get();



        // $this->nomor_kontrak = $this->getNoKontrak($this->vendor_id)->nomor_kontrak;
    }

    private function checkApprovals($params)
    {
        $data = Persetujuan::where('approvable_id', $this->id)
            ->where('approvable_type', TransaksiStok::class)
            ->where('role', $params)
            ->get();

        if ($data->isEmpty()) { // Gunakan isEmpty() untuk Collection
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    public function getNoKontrak($idkontrak)
    {
        return KontrakVendorStok::where('vendor_id', $idkontrak)->first();
    }

    // public $barang_id;
    public $newBarangName;

    public function updatedNewBarang()
    {
        $this->newBarangId = null;
        $this->newBarangName = $this->newBarang;
        $this->barangSuggestions = BarangStok::where('nama', 'like', '%' . $this->newBarang . '%')->where('jenis_id', $this->jenis_id)
            ->limit(10)
            ->get()
            ->toArray();
        $exactMatch = BarangStok::where('nama', $this->newBarang)->where('jenis_id', $this->jenis_id)->first();

        if ($exactMatch) {
            // Jika ada kecocokan, isi vendor_id dan kosongkan suggestions
            $this->selectBarang($exactMatch->id, $exactMatch->nama);
        }
    }
    public function blurBarang()
    {

        $this->barangSuggestions = [];
    }
    public $merk_id;
    public function removeNewPhoto()
    {
        $this->newBukti = null;
    }
    public function selectSpecification($key, $value)
    {
        // Hanya update spesifikasi tertentu, tanpa menimpa yang lain
        $this->specifications[$key] = $value;
        $this->suggestions = [
            'merek' => [],
            'tipe' => [],
            'ukuran' => [],
            'satuanBesar' => [],
            'satuanKecil' => [],
        ];
    }
    public function updateSpecification($key, $value)
    {
        if ($this->newBarangId) {
            $this->specifications[$key] = $value;
            $this->merk_id = null;
            // Ambil suggestions untuk spesifikasi yang dimasukkan
            $this->suggestions[$key] = MerkStok::where('barang_id', $this->newBarangId)
                ->where($key === 'merek' ? 'nama' : $key, 'like', '%' . $value . '%')
                ->pluck($key === 'merek' ? 'nama' : $key)
                ->unique()
                ->toArray();
        }
    }
    public function blurSpecification($key)
    {
        $this->suggestions[$key] = [];
    }
    public function selectBarang($barangId, $barangName)
    {
        $this->newBarangId = $barangId;
        $this->newBarang = $barangName;
        $this->barangSuggestions = [];
        $this->resetSpecifications();
    }

    private function resetSpecifications()
    {
        $this->specifications = [
            'merek' => '',
            'tipe' => '',
            'ukuran' => '',
        ];
    }

    public function openBarangModal()
    {
        $this->showBarangModal = true;
    }

    public function closeBarangModal()
    {
        $this->reset(['newBarang', 'newBarangSatuanBesar', 'newBarangSatuanKecil', 'jumlahKecilDalamBesar']);
        $this->showBarangModal = false;
    }

    public function saveKontrak()
    {
        $this->validate([
            'vendor_id' => 'required',
            'list' => 'required|array|min:1',
            'list.*.barang_id' => 'required|integer',
            'list.*.jumlah' => 'required|integer|min:1',
        ]);

        foreach ($this->list as $item) {
            if ($item['id'] !== null) {
                // Find the existing transaction and update `bukti` if available
                $transaksi = TransaksiStok::find($item['id']);
                if ($transaksi && !is_string($item['bukti'])) {
                    $transaksi->update([
                        'img' => $item['bukti'] !== null ?
                            str_replace('buktiTransaksi/', '', $item['bukti']->storeAs('buktiTransaksi', $item['bukti']->getClientOriginalName(), 'public')) : null

                    ]);
                }
                // Skip further processing for older items
                continue;
            }
            if ($item['id'] == null) {
                $merk = MerkStok::updateOrCreate(
                    [
                        'barang_id' => $item['barang_id'],
                        'nama' => empty($item['specifications']['merek']) ? null : $item['specifications']['merek'],
                        'tipe' => empty($item['specifications']['tipe']) ? null : $item['specifications']['tipe'],
                        'ukuran' => empty($item['specifications']['ukuran']) ? null : $item['specifications']['ukuran'],
                    ],
                    [] // Tidak ada kolom tambahan untuk diperbarui
                );
                $transaksi = TransaksiStok::create([
                    'tipe' => 'Penggunaan Langsung', // Assuming 'Pemasukan' represents a stock addition
                    'merk_id' => $merk->id,
                    'vendor_id' => $this->vendor_id,
                    'img' => $item['bukti'] != null ? str_replace('buktiTransaksi/', '', $item['bukti']->storeAs('buktiTransaksi', $item['bukti']->getClientOriginalName(), 'public')) : null,
                    'user_id' => Auth::id(),
                    'kontrak_id' => null,
                    'tanggal' => strtotime(date('Y-m-d H:i:s')),
                    'jumlah' => $item['jumlah'],
                    'deskripsi' => $item['keterangan'] ?? '',
                    'lokasi_penerimaan' => $item['lokasi_penerimaan'] ?? '',
                ]);
            }
        }
        $vendor_id = $this->vendor_id;
        // Clear the list and reset input fields
        $this->reset(['list', 'vendor_id', 'newBarangId', 'newJumlah',  'newBukti']);
        //  $this->dispatch('success');
        return redirect()->route('transaksi-darurat-stok.edit', ['transaksi_darurat_stok' => $vendor_id]);
        // $this->dispatchBrowserEvent('kontrakSaved'); // Trigger any frontend success indication if needed
        // session()->flash('message', 'Kontrak dan transaksi berhasil disimpan.');
        // $this->nomor_kontrak = $nomor;
    }

    public function removePhoto($index)
    {
        if (isset($this->list[$index]['bukti'])) {
            $this->list[$index]['bukti'] = null;
        }
    }
    // public function updateBukti($index, $file)
    // {
    //     // dd($file);
    //     // Validasi file
    //     // $this->validate([
    //     //     "list.{$index}.bukti" => 'file|mimes:jpg,jpeg,png,pdf|max:5024',
    //     // ]);

    //     // Simpan file sementara dalam list
    //     $this->list[$index]['bukti'] = $file;
    // }


    public function saveNewBarang()
    {
        $this->validate([
            'newBarangName' => 'required|string|max:255',
            'newBarangSatuanBesar' => 'required|string',
            'newBarangSatuanKecil' => 'nullable|string',
        ]);

        $faker = Faker::create();

        $satuanBesar = SatuanBesar::updateOrCreate(['nama' => $this->newBarangSatuanBesar]);
        $satuanKecil = $this->newBarangSatuanKecil
            ? SatuanBesar::updateOrCreate(['nama' => $this->newBarangSatuanKecil])
            : null;

        $barang = BarangStok::create([
            'nama' => $this->newBarang,
            'kode_barang' => $faker->unique()->numerify('BRG-#####'),
            'jenis_id' => $this->jenis_id,
            'satuan_besar_id' => $satuanBesar->id,
            'satuan_kecil_id' => $satuanKecil ? $satuanKecil->id : null,
            'konversi' => $satuanKecil ? $this->jumlahKecilDalamBesar ?? null : null,
        ]);

        $this->reset(['newBarangName', 'newBarangSatuanBesar', 'newBarangSatuanKecil', 'jumlahKecilDalamBesar']);
        $this->closeBarangModal();
        $this->selectBarang($barang->id, $barang->nama);
    }

    public function addToList()
    {
        $this->validate([
            'newBarangId' => 'required',
            'newJumlah' => 'required|integer|min:1',
            'newLokasiPenerimaan' => 'required|string',
            'newKeterangan' => 'nullable|string',
        ]);

        $this->list[] = [
            'id' => null,
            'barang_id' => $this->newBarangId,
            'barang' => BarangStok::find($this->newBarangId)->nama,
            'specifications' => $this->specifications,
            'jumlah' => $this->newJumlah,
            'satuan' => BarangStok::find($this->newBarangId)->satuanBesar->nama,
            'lokasi_penerimaan' => $this->newLokasiPenerimaan,
            'keterangan' => $this->newKeterangan,
            'pptk_isapprove' => $this->pptk_isapprove ?? 1,
            'ppk_isapprove' => $this->ppk_isapprove ?? 1,
            'pj_isapprove' => $this->pj_isapprove ?? 1,
            'bukti' => $this->newBukti ? $this->newBukti : null,
            'status' => null,
        ];

        $this->reset(['newBarang', 'newBarangId', 'specifications', 'newJumlah', 'newLokasiPenerimaan', 'newKeterangan', 'newBukti']);
        $this->dispatch('listCount', count: count($this->list));
    }
    public function finishKontrak()
    {
        // Step 1: Create a new contract with type `false`
        $newKontrak = KontrakVendorStok::create([
            'nomor_kontrak' => $this->nomor_kontrak, // Method to generate contract number if needed
            'vendor_id' => $this->vendor_id,
            'tanggal_kontrak' => strtotime(date('Y-m-d H:i:s')),
            'metode_id' => $this->metode_id,
            'user_id' => Auth::id(),
            'type' => false,
            'status' => null,
        ]);

        foreach ($this->list as $item) {
            // Only update transactions with null `kontrak_id`
            TransaksiStok::where('id', $item['id'])->update([
                'kontrak_id' => $newKontrak->id,
            ]);
        }
        $this->dispatch('saveDokumen', kontrak_id: $newKontrak->id);
    }
    // public function approveTransaction($index)
    // {
    //     $transaction = TransaksiStok::find($this->list[$index]['id']);
    //     if (auth::user()->hasRole('ppk')){
    //         $transaction->ppk_id = Auth::id();
    //         $this->updated($this->list[$index]['bukti']);
    //         $transaction->img = $this->list[$index]['bukti'];
    //     }
    //     if (auth::user()->hasRole('pptk')){
    //         $transaction->pptk_id = Auth::id();
    //     }
    //     if (auth::user()->hasRole('pj')){
    //         $transaction->pj_id = Auth::id();
    //         $transaction->status = true;
    //     }

    //     // $transaction->approvals->
    //     $transaction->update();
    //     return redirect()->route('transaksi-darurat-stok.edit', ['transaksi_darurat_stok' => $this->transaksi->first()->vendor_id]);
    // }

    public function updated($propertyName)
    {
        // /Check if the updated property matches the specific file input
        if (preg_match('/^list\.\d+\.bukti$/', $propertyName)) {
            // Extract the index from the property name
            preg_match('/^list\.(\d+)\.bukti$/', $propertyName, $matches);
            $index = $matches[1];

            // Validate the file
            $this->validate([
                "list.{$index}.bukti" => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            ]);

            // Process the file upload
            $file = $this->list[$index]['bukti'];
            if ($file) {

                $storedFilePath = str_replace('buktiTransaksi/', '', $file->storeAs(
                    'buktiTransaksi', // Directory
                    $file->getClientOriginalName(), // File name
                    'public' // Storage disk
                ));

                // Update the list with the stored file path
                $this->list[$index]['bukti'] = $storedFilePath;

                // Provide feedback
                session()->flash('success', 'File berhasil diunggah.');
            }
        }
    }

    public function approveTransaction($index)
    {
        // Cari transaksi berdasarkan ID yang ada di list
        $transaction = TransaksiStok::findOrFail($this->list[$index]['id']);

        $this->updated($this->list[$index]['bukti']);
        // Inisialisasi data approval
        $approvalData = [
            'user_id' => Auth::id(),
            'role' => $this->role_name, // Ambil peran pengguna
            'is_approved' => true, // Atur status menjadi disetujui
            'img' => $this->list[$index]['bukti'], // Tambahkan remarks jika diperlukan
        ];

        // Gunakan updateOrCreate pada relasi morph
        $transaction->approvals()->updateOrCreate(
            [
                'user_id' => Auth::id(), // Kondisi pencarian
                'role' => $this->role_name,
            ],
            $approvalData // Data yang akan diperbarui atau dibuat
        );


        // Periksa peran pengguna untuk menentukan level approval
        // Simpan perubahan transaksi
        if ($this->role_name === 'penanggungjawab') {
            $transaction->status = true;
        }
        $transaction->save();

        // Redirect ke halaman edit
        return redirect()->route('transaksi-darurat-stok.edit', [
            'transaksi_darurat_stok' => $transaction->vendor_id
        ]);
    }


    public function disapproveTransaction($index, $reason)
    {
        $transaction = TransaksiStok::find($this->list[$index]['id']);
        $transaction->disapprove($reason);
        $this->refresh();  // Segarkan daftar transaksi
    }


    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);
    }
    #[On('nomor_kontrak')]
    public function fillNomor($nomor)
    {
        $this->nomor_kontrak = $nomor;
    }

    #[On('tanggal_kontrak')]
    public function fillTanggal($tanggal)
    {
        $this->tanggal_kontrak = $tanggal;
    }

    #[On('jenis_id')]
    public function fillJenis($jenis_id)
    {
        $this->jenis_id = $jenis_id;
    }

    #[On('metode_id')]
    public function fillMetode($metode_id)
    {
        $this->metode_id = $metode_id;
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
    public function render()
    {
        $roles = $this->role_name;
        return view('livewire.transaksi-darurat-list', ['roles' => $roles]);
    }
}
