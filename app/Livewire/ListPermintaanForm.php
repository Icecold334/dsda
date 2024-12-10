<?php

namespace App\Livewire;

use App\Models\Aset;
use Carbon\Carbon;
use App\Models\Stok;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\StokDisetujui;
use Livewire\WithFileUploads;
use App\Models\PermintaanStok;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPermintaanStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PersetujuanPermintaanStok;
use App\Models\UnitKerja;

class ListPermintaanForm extends Component
{
    use WithFileUploads;

    public $unit_id;
    public $kategori_id;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $keterangan;
    public $permintaan;
    public $showAdd;
    public $kdos;

    public $list = []; // List of items
    public $newDeskripsi; // Input for new barang
    public $newCatatan; // Input for new barang
    public $newAset;
    public $newAsetId;
    public $asetSuggestions = []; // Input for new barang
    public $lokasiSuggestions = []; // Input for new barang
    public $newBarangId; // Input for new barang
    public $newBarang;
    public $newLokasiId;
    public $newLokasi;
    public $newJumlah; // Input for new jumlah
    public $newDokumen; // Input for new dokumen
    public $newBukti; // Input for new dokumen
    public $barangSuggestions = []; // Suggestions for barang

    public $showApprovalModal = false;
    public $ruleShow;
    public $ruleAdd;
    public $selectedItemId; // ID dari item yang dipilih
    public $approvalData = []; // Data untuk lokasi dan stok
    public $catatan; // Catatan opsional
    public $noteModalVisible = false; // Untuk mengatur visibilitas modal catatan
    public $selectedItemNotes;
    public $requestIs;
    public $approvals = [];

    public function openNoteModal($itemId)
    {
        $item = StokDisetujui::where('permintaan_id', $itemId)
            ->get();

        $this->selectedItemNotes = $item->map(function ($stok) {
            return [
                'merk' => $stok->merkStok,
                'lokasi' => $stok->lokasiStok->nama ?? '-',
                'bagian' => $stok->bagianStok->nama ?? '-',
                'posisi' => $stok->posisiStok->nama ?? '-',
                'jumlah_disetujui' => $stok->jumlah_disetujui,
                'catatan' => $stok->catatan ?? 'Tidak ada catatan',
            ];
        });

        $this->noteModalVisible = true; // Tampilkan modal
    }

    public function openApprovalModal($itemId)
    {
        $this->selectedItemId = $itemId;
        $this->loadApprovalData($itemId);
        $this->showApprovalModal = true;
    }

    public function loadApprovalData($itemId)
    {
        $item = PermintaanStok::find($itemId);
        $barang = $item->barangStok;
        // Ambil merk-merk yang tersedia untuk barang tersebut yang ada di stok
        $merkTersedia = MerkStok::where('barang_id', $barang->id) // Ambil merk berdasarkan barang yang dipilih
            ->whereHas('stok', function ($query) {
                $query->where('jumlah', '>', 0)->whereHas('lokasiStok', function ($stokQuery) {
                    $stokQuery->whereHas('unitKerja', function ($unit) {
                        return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                    });
                });
            })
            ->get();

        $this->approvalData = [
            'barang' => $barang, // Data barang yang dipilih
            'jumlah_permintaan' => $item->jumlah, // Jumlah yang diminta
            'stok' => $merkTersedia->map(function ($merk) use ($barang) {
                // Ambil stok yang tersedia untuk merk terkait
                return Stok::whereHas('lokasiStok', function ($stokQuery) {
                    $stokQuery->whereHas('unitKerja', function ($unit) {
                        return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                    });
                })->where('merk_id', $merk->id) // Ambil stok berdasarkan merk_id
                    ->get()
                    ->map(function ($stok) use ($merk) {
                        return [
                            'id' => $merk->id,
                            'nama' => $merk->nama, // Nama merk
                            'tipe' => $merk->tipe, // Tipe merk
                            'ukuran' => $merk->ukuran, // Ukuran merk
                            'lokasi' => $stok->lokasiStok->nama, // Lokasi terkait stok
                            'bagian' => $stok->bagianStok->nama ?? null, // Bagian jika ada
                            'posisi' => $stok->posisiStok->nama ?? null, // Posisi jika ada
                            'jumlah_tersedia' => $stok->jumlah, // Jumlah stok yang tersedia
                            'lokasi_id' => $stok->lokasi_id, // ID lokasi
                            'bagian_id' => $stok->bagian_id, // ID bagian
                            'posisi_id' => $stok->posisi_id, // ID posisi
                        ];
                    });
            })->flatten(1),
        ];
    }


    public function approveItems()
    {
        foreach ($this->approvalData['stok'] as $stok) {
            $jumlahDisetujui = $stok['jumlah_disetujui'] ?? null;

            if (is_null($jumlahDisetujui)) {
                $this->dispatch(
                    'swal:error',
                    message: "Jumlah disetujui tidak ditemukan untuk lokasi {$stok['lokasi']}.",
                );
                return; // Hentikan proses jika key tidak ada
            }

            // Validasi jumlah disetujui
            if ($jumlahDisetujui > $stok['jumlah_tersedia']) {
                $this->dispatch(
                    'swal:error',
                    message: "Jumlah disetujui untuk lokasi {$stok['lokasi']} tidak boleh melebihi jumlah tersedia ({$stok['jumlah_tersedia']}).",
                );
                return; // Hentikan proses jika validasi gagal
            }

            if ($jumlahDisetujui <= 0) {
                $this->dispatch(
                    'swal:error',
                    message: "Jumlah disetujui untuk lokasi {$stok['lokasi']} harus lebih dari 0.",
                );
                return; // Hentikan proses jika validasi gagal
            }
        }

        foreach ($this->approvalData['stok'] as $stok) {
            if (isset($stok['jumlah_disetujui']) && $stok['jumlah_disetujui'] > 0) {
                StokDisetujui::create([
                    'permintaan_id' => $this->selectedItemId,
                    'merk_id' => $stok['id'],
                    'lokasi_id' => $stok['lokasi_id'],
                    'bagian_id' => $stok['bagian_id'] ?? null,
                    'posisi_id' => $stok['posisi_id'] ?? null,
                    'catatan' => $stok['catatan'] ?? null, // Simpan catatan per stok
                    'jumlah_disetujui' => $stok['jumlah_disetujui'],
                ]);
            }
        }

        $this->showApprovalModal = false; // Tutup modal
        $this->catatan = null; // Reset catatan
        return redirect()->route('permintaan-stok.show', ['permintaan_stok' => $this->permintaan->id]);
    }



    public function removeNewDokumen()
    {
        if ($this->newDokumen && Storage::exists($this->newDokumen)) {
            Storage::delete($this->newDokumen);
        }
        $this->newDokumen = null; // Reset variable
    }

    public function focusLokasi()
    {
        $this->lokasiSuggestions = [];

        $suggest = UnitKerja::where('parent_id', $this->unit_id)->get();
        $this->lokasiSuggestions = $suggest;
    }
    public function focusAset()
    {
        $this->asetSuggestions = [];

        $suggest = Aset::where('nama', 'like', '%' . $this->newAset . '%')->get();
        $this->asetSuggestions = $suggest;
    }
    public function focusBarang()
    {

        $this->barangSuggestions = [];
        $suggest
            = BarangStok::whereHas('merkStok', function ($merkQuery) {
                $merkQuery->where('nama', 'like', '%' . $this->newBarang . '%')
                    ->orWhere('tipe', 'like', '%' . $this->newBarang . '%')
                    ->orWhere('ukuran', 'like', '%' . $this->newBarang . '%')
                    ->join('stok', 'merk_stok.id', '=', 'stok.merk_id')
                    ->groupBy('merk_stok.id')
                    ->havingRaw('SUM(stok.jumlah) > 0'); // Filter stok total > 0
            })->with([
                'merkStok' => function ($merkQuery) {
                    $merkQuery->join('stok', 'merk_stok.id', '=', 'stok.merk_id')
                        ->groupBy('merk_stok.id')
                        ->havingRaw('SUM(stok.jumlah) > 0')
                        ->with(['stok' => function ($stokQuery) {
                            $stokQuery->select('merk_id', DB::raw('SUM(jumlah) as total_jumlah'))
                                ->groupBy('merk_id');
                        }]);
                }
            ]);
        if ($this->requestIs === 'permintaan') {
            $this->barangSuggestions = $suggest->where('kategori_id', $this->kategori_id)->get();
        } elseif ($this->requestIs === 'spare-part') {
            $this->barangSuggestions = $suggest->where('jenis_id', 2)->get();
        } elseif ($this->requestIs === 'material') {
            $this->barangSuggestions = $suggest->where('jenis_id', 1)->get();
        }
    }
    #[On('unit_id')]
    public function fillUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
        $this->fillShowRule();
    }
    #[On('kategori_id')]
    public function fillKategoriId($kategori_id)
    {
        $this->kategori_id = $kategori_id;
        $this->fillShowRule();
    }

    #[On('sub_unit_id')]
    public function fillSubUnitId($sub_unit_id)
    {
        $this->sub_unit_id = $sub_unit_id;
        $this->fillShowRule();
    }

    #[On('tanggal_permintaan')]
    public function fillTanggalPermintaan($tanggal_permintaan)
    {
        $this->tanggal_permintaan = $tanggal_permintaan;
        $this->fillShowRule();
    }


    public function saveData()
    {


        $kodePermintaan = Str::random(10); // Generate a unique code

        // Create Detail Permintaan Stok
        $detailPermintaan = DetailPermintaanStok::create([
            'kode_permintaan' => $kodePermintaan,
            'tanggal_permintaan' => strtotime($this->tanggal_permintaan),
            'unit_id' => $this->unit_id,
            'user_id' => Auth::id(),
            'jenis_id' => $this->requestIs == 'permintaan' ? 3 : ($this->requestIs == 'spare-part' ? 2 : 1),
            'kategori_id' => $this->kategori_id,
            'sub_unit_id' => $this->sub_unit_id ?? null,
            'keterangan' => $this->keterangan,
            'status' => null
        ]);
        foreach ($this->list as $item) {
            $storedFilePath = $item['img'] ? str_replace('kondisiKdo/', '', $item['img']->storeAs(
                'kondisiKdo', // Directory
                $item['img']->getClientOriginalName(), // File name
                'public' // Storage disk
            )) : null;
            PermintaanStok::create([
                'detail_permintaan_id' => $detailPermintaan->id,
                'user_id' => Auth::id(),
                'aset_id' => $item['aset_id'] ?? null,
                'deskripsi' => $item['deskripsi'] ?? null,
                'catatan' => $item['catatan'] ?? null,
                'img' => $storedFilePath,
                'barang_id' => $item['barang_id'],
                'jumlah' => $item['jumlah'],
                // 'lokasi_id' => $this->lokasiId
            ]);
        }
        return redirect()->route('permintaan-stok.show', $detailPermintaan);
        // $this->reset(['list', 'detailPermintaan']);
        // session()->flash('message', 'Permintaan Stok successfully saved.');
    }



    #[On('keterangan')]
    public function fillKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
        $this->fillShowRule();
    }
    public $newUnit = 'Satuan'; // Default unit

    public function selectMerk($merkId)
    {
        $merk = BarangStok::find($merkId);
        if ($merk) {
            $this->newBarangId = $merk->id;
            $this->newUnit = optional($merk->satuanBesar)->nama; // Set the new unit from the selected merk

            $this->resetBarangSuggestions();
        }
        if ($merk) {
            // Concatenate merk, tipe, and ukuran into one string, use '-' for any null values
            // $this->newBarang = collect([$merk->nama, $merk->tipe, $merk->ukuran])
            //     ->map(function ($value) {
            //         return $value ?? '-';
            //     })
            //     ->join(' | '); // Join the values with ' | ' as separator
            $this->newBarang = $merk->nama;

            $this->resetBarangSuggestions();
        }
    }
    public function selectLokasi($merkId)
    {
        $lokasi = Aset::find($merkId);
        if ($lokasi) {
            $this->newLokasiId = $lokasi->id;

            $this->resetBarangSuggestions();
        }
        if ($lokasi) {

            $this->newLokasi = $lokasi->nama;

            $this->resetBarangSuggestions();
        }
    }
    public function selectAset($merkId)
    {
        $aset = Aset::find($merkId);
        if ($aset) {
            $this->newAsetId = $aset->id;

            $this->resetBarangSuggestions();
        }
        if ($aset) {

            $this->newAset = $aset->nama;

            $this->resetBarangSuggestions();
        }
    }
    private function resetBarangSuggestions()
    {
        $this->barangSuggestions = [];
    }
    public function addToList()
    {

        $this->validate([
            'newBarang' => 'required|string|max:255',
            'newJumlah' => 'required|integer|min:1',
            'newDokumen' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt',
        ]);
        $this->list[] = [
            'jumlah_approve' => $this->newJumlah,
            'status' => null,
            'id' => null,
            'aset_id' => $this->newAsetId,
            'aset_name' => $this->newAset ?? null,
            'deskripsi' => $this->newDeskripsi ?? null,
            'catatan' => $this->newCatatan ?? null,
            'img' => $this->newBukti,
            'barang_id' => $this->newBarangId, // Assuming a dropdown for selecting existing barang
            'barang_name' => $this->newBarang,
            'jumlah' => $this->newJumlah,
            'satuan' => $this->newUnit,
            'dokumen' => $this->newDokumen ?? null,
        ];
        $this->ruleAdd = false;
        // Reset inputs after adding to the list
        $this->reset(['newBarang', 'newJumlah', 'newDokumen', 'newAset', 'newAsetId', 'newDeskripsi', 'newCatatan', 'newBukti']);
    }

    public function updateList($index, $field, $value)
    {
        $this->list[$index][$field] = $value;
    }

    public function fillShowRule()
    {
        $this->ruleShow = Request::is('permintaan/add/permintaan') ? $this->tanggal_permintaan && $this->keterangan && $this->unit_id && $this->kategori_id : $this->tanggal_permintaan && $this->keterangan && $this->unit_id;
    }
    public function updated()
    {
        $this->ruleAdd = $this->requestIs == 'permintaan' ? $this->newBarang && $this->newJumlah : ($this->requestIs == 'spare-part' ? $this->newBarang && $this->newJumlah && $this->newAsetId && $this->newBukti && $this->newDeskripsi : $this->newBarang && $this->newJumlah  && $this->newBukti && $this->newDeskripsi);
    }
    public $tipe;
    public function mount()
    {

        $this->fillShowRule();
        $expl = explode('/', Request::getUri());
        $this->requestIs = $expl[count($expl) - 1];
        $this->showAdd = Request::is('permintaan/add/*');

        if ($this->requestIs == 'spare-part') {
            $this->kdos = Aset::all();
        }
        if ($this->permintaan) {
            $tipe = $this->permintaan->jenisStok->nama;
            $this->tipe = $tipe;
            foreach ($this->permintaan->permintaanStok as $key => $value) {
                $this->unit_id = $this->permintaan->unit_id;
                $this->keterangan = $this->permintaan->keterangan;
                $this->tanggal_permintaan = $this->permintaan->tanggal_permintaan;

                $this->list[] = [
                    'detail_permintaan_id' => $value->detail_permintaan_id,
                    'jumlah_approve' => $value->stokDisetujui->sum('jumlah_disetujui'),
                    'status' => $value->status,
                    'id' => $value->id,
                    'aset_id' => $value->aset_id ?? null,
                    'aset_name' => $value->aset->nama ?? null,
                    'deskripsi' => $value->deskripsi ?? null,
                    'catatan' => $value->catatan ?? null,
                    'img' => $value->img ?? null,
                    'barang_id' => $value->barangStok->id, // Assuming a dropdown for selecting existing barang
                    'barang_name' => $value->barangStok->nama,
                    'jumlah' => $value->jumlah,
                    'satuan' => $value->barangStok->satuanBesar->nama,
                    'dokumen' => $value->img ?? null,
                ];
            }
            $role = $tipe == 'Umum' ? 'kepala_seksi' : ($tipe == 'Spare Part' ? 'kepala_sub_bagian_tata_usaha' : 'kepala_seksi_pemeliharaan');

            $this->approvals = PersetujuanPermintaanStok::where('status', true)->where('detail_permintaan_id', $this->permintaan->id)
                ->whereHas('user', function ($query) use ($role) {
                    $query->role($role); // Muat hanya persetujuan dari kepala_seksi
                })
                ->pluck('detail_permintaan_id') // Ambil hanya detail_permintaan_id yang sudah disetujui
                ->toArray();
        }
        $this->tanggal_permintaan = Carbon::now()->format('Y-m-d');
    }

    public function removeFromList($index)
    {
        if (isset($this->list[$index]['dokumen'])) {
            Storage::delete('public/' . $this->list[$index]['dokumen']);
        }
        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
    }

    public function blurLokasi()
    {
        // if ($this->newBarang) {
        //     $this->barangSuggestions = MerkStok::where('nama', 'like', '%' . $this->newBarang . '%')->get();
        // } else {
        $this->lokasiSuggestions = [];
        // }
    }
    public function blurAset()
    {
        // if ($this->newBarang) {
        //     $this->barangSuggestions = MerkStok::where('nama', 'like', '%' . $this->newBarang . '%')->get();
        // } else {
        $this->asetSuggestions = [];
        // }
    }
    public function blurBarang()
    {
        if ($this->newBarang) {
            $this->barangSuggestions = MerkStok::where('nama', 'like', '%' . $this->newBarang . '%')->get();
        } else {
            $this->barangSuggestions = [];
        }
    }

    public function selectBarang($barangId, $barangName)
    {

        $this->newBarang = $barangName;
        $this->barangSuggestions = [];
    }
    public function approveItem($index)
    {
        $item = $this->list[$index];
        $permintaan = PermintaanStok::find($item['id']);
        $this->list[$index]['status'] = true;
        $permintaan->update(['status' => true, 'jumlah_approve' => $this->list[$index]['jumlah_approve']]);
        // Optionally, remove the item from the list or mark it as approved
        // $this->list[$index]['jumlah_approve'] = true;

        // Provide feedback
        session()->flash('message', 'Item approved successfully!');
    }

    public function removePhoto()
    {
        $this->newBukti = null;
    }
    public function removeDocument($index)
    {
        $item = $this->list[$index];

        // Optional: Delete the file from storage if necessary
        // Storage::delete($item['dokumen']);

        // Remove the document path from the item in the list
        $this->list[$index]['dokumen'] = null;
    }

    public function render()
    {
        return view('livewire.list-permintaan-form', [
            'barangs' => MerkStok::all(), // Assuming you have a Barang model
        ]);
    }
}
