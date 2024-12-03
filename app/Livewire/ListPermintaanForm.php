<?php

namespace App\Livewire;

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

class ListPermintaanForm extends Component
{
    use WithFileUploads;

    public $unit_id;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $keterangan;
    public $permintaan;
    public $showAdd;

    public $list = []; // List of items
    public $newBarangId; // Input for new barang
    public $newBarang; // Input for new barang
    public $newJumlah; // Input for new jumlah
    public $newDokumen; // Input for new dokumen
    public $barangSuggestions = []; // Suggestions for barang

    public $showApprovalModal = false;
    public $selectedItemId; // ID dari item yang dipilih
    public $approvalData = []; // Data untuk lokasi dan stok
    public $catatan; // Catatan opsional
    public $noteModalVisible = false; // Untuk mengatur visibilitas modal catatan
    public $selectedItemNotes; // M

    public $approvals = [];

    public function openNoteModal($itemId)
    {
        $item = StokDisetujui::where('permintaan_id', $itemId)
            ->get();

        $this->selectedItemNotes = $item->map(function ($stok) {
            return [
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

        $this->approvalData = [
            'merk' => $item->merkStok,
            'stok' => Stok::where('merk_id', $item->merk_id)->get()->map(function ($stok) {
                return [
                    'lokasi' => $stok->lokasiStok->nama,
                    'bagian' => $stok->bagianStok->nama ?? null,
                    'posisi' => $stok->posisiStok->nama ?? null,
                    'jumlah_tersedia' => $stok->jumlah,
                    'lokasi_id' => $stok->lokasi_id,
                    'bagian_id' => $stok->bagian_id,
                    'posisi_id' => $stok->posisi_id,
                ];
            }),
            'jumlah_permintaan' => $item->jumlah,
        ];
    }


    public function approveItems()
    {
        foreach ($this->approvalData['stok'] as $stok) {
            if (isset($stok['jumlah_disetujui']) && $stok['jumlah_disetujui'] > 0) {
                StokDisetujui::create([
                    'permintaan_id' => $this->selectedItemId,
                    'lokasi_id' => $stok['lokasi_id'],
                    'jumlah_disetujui' => $stok['jumlah_disetujui'],
                    'bagian_id' => $stok['bagian_id'] ?? null,
                    'posisi_id' => $stok['posisi_id'] ?? null,
                    'catatan' => $stok['catatan'] ?? null, // Simpan catatan per stok
                ]);

                // Kurangi stok sesuai dengan lokasi, bagian, dan posisi (jika ada)
                // $stokModel = Stok::where('lokasi_id', $stok['lokasi_id'])
                //     ->when(isset($stok['bagian_id']), function ($query) use ($stok) {
                //         return $query->where('bagian_id', $stok['bagian_id']);
                //     })
                //     ->when(isset($stok['posisi_id']), function ($query) use ($stok) {
                //         return $query->where('posisi_id', $stok['posisi_id']);
                //     })
                //     ->where('merk_id', $this->approvalData['merk_id'])
                //     ->first();

                // if ($stokModel) {
                //     $stokModel->jumlah -= $stok['jumlah_disetujui'];
                //     $stokModel->save();
                // }
            }
        }

        $this->showApprovalModal = false; // Tutup modal
        $this->catatan = null; // Reset catatan
        return redirect()->route('permintaan-stok.edit', ['permintaan_stok' => $this->permintaan->id]);
    }



    public function removeNewDokumen()
    {
        if ($this->newDokumen && Storage::exists($this->newDokumen)) {
            Storage::delete($this->newDokumen);
        }
        $this->newDokumen = null; // Reset variable
    }
    public function focusBarang()
    {
        $this->barangSuggestions = [];

        $this->barangSuggestions = BarangStok::whereHas('merkStok', function ($merkQuery) {
            $merkQuery->where('nama', 'like', '%' . $this->newBarang . '%')
                ->orWhere('tipe', 'like', '%' . $this->newBarang . '%')
                ->orWhere('ukuran', 'like', '%' . $this->newBarang . '%')
                ->join('stok', 'merk_stok.id', '=', 'stok.merk_id')
                ->groupBy('merk_stok.id')
                ->havingRaw('SUM(stok.jumlah) > 0'); // Filter stok total > 0
        })
            ->with([
                'merkStok' => function ($merkQuery) {
                    $merkQuery->join('stok', 'merk_stok.id', '=', 'stok.merk_id')
                        ->groupBy('merk_stok.id')
                        ->havingRaw('SUM(stok.jumlah) > 0')
                        ->with(['stok' => function ($stokQuery) {
                            $stokQuery->select('merk_id', DB::raw('SUM(jumlah) as total_jumlah'))
                                ->groupBy('merk_id');
                        }]);
                }
            ])
            ->get();



        // dd(collect($this->barangSuggestions)->toJson());
    }





    #[On('unit_id')]
    public function fillUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }

    #[On('sub_unit_id')]
    public function fillSubUnitId($sub_unit_id)
    {
        $this->sub_unit_id = $sub_unit_id;
    }

    #[On('tanggal_permintaan')]
    public function fillTanggalPermintaan($tanggal_permintaan)
    {
        $this->tanggal_permintaan = $tanggal_permintaan;
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
            'sub_unit_id' => $this->sub_unit_id ?? null,
            'keterangan' => $this->keterangan,
            'status' => null
        ]);
        foreach ($this->list as $item) {
            // Assuming $item['barang_name'] is a string like "Merk|Tipe|Ukuran"
            list($merk, $tipe, $ukuran) = array_pad(explode('|', $item['barang_name']), 3, null);

            $merkStok = MerkStok::firstOrCreate(
                [
                    'barang_id' => $item['barang_id'],
                    'nama' => trim($merk) === '-' ? null : trim($merk),
                    'tipe' => trim($tipe) === '-' ? null : trim($tipe),
                    'ukuran' => trim($ukuran) === '-' ? null : trim($ukuran)

                ],
                []
            );

            PermintaanStok::create([
                'detail_permintaan_id' => $detailPermintaan->id,
                'user_id' => Auth::id(),
                'merk_id' => $merkStok->id,
                'jumlah' => $item['jumlah'],
                // 'lokasi_id' => $this->lokasiId
            ]);
        }
        return redirect()->to('/permintaan/permintaan');
        // $this->reset(['list', 'detailPermintaan']);
        // session()->flash('message', 'Permintaan Stok successfully saved.');
    }



    #[On('keterangan')]
    public function fillKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
    }
    public $newUnit = 'Satuan'; // Default unit

    public function selectMerk($merkId)
    {
        $merk = MerkStok::find($merkId);
        if ($merk) {
            $this->newBarangId = $merk->barangStok->id;
            $this->newUnit = optional($merk->barangStok->satuanBesar)->nama; // Set the new unit from the selected merk

            $this->resetBarangSuggestions();
        }
        if ($merk) {
            // Concatenate merk, tipe, and ukuran into one string, use '-' for any null values
            $this->newBarang = collect([$merk->nama, $merk->tipe, $merk->ukuran])
                ->map(function ($value) {
                    return $value ?? '-';
                })
                ->join(' | '); // Join the values with ' | ' as separator

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
            'barang_id' => $this->newBarangId, // Assuming a dropdown for selecting existing barang
            'barang_name' => $this->newBarang,
            'jumlah' => $this->newJumlah,
            'satuan' => $this->newUnit,
            'dokumen' => $this->newDokumen ?? null,
        ];

        // Reset inputs after adding to the list
        $this->reset(['newBarang', 'newJumlah', 'newDokumen']);
    }

    public function updateList($index, $field, $value)
    {
        $this->list[$index][$field] = $value;
    }

    public function mount()
    {
        $this->showAdd = Request::is('permintaan/permintaan');
        if ($this->permintaan) {
            foreach ($this->permintaan->permintaanStok as $key => $value) {
                $this->unit_id = $this->permintaan->unit_id;
                $this->keterangan = $this->permintaan->keterangan;
                $this->tanggal_permintaan = $this->permintaan->tanggal_permintaan;
                $merk = MerkStok::find($value->merk_id);
                if ($merk) {
                    // Concatenate merk, tipe, and ukuran into one string, use '-' for any null values
                    $merkCollect = collect([$merk->nama, $merk->tipe, $merk->ukuran])
                        ->map(function ($value) {
                            return $value ?? '-';
                        })
                        ->join(' | ');
                }
                $this->list[] = [
                    'detail_permintaan_id' => $value->detail_permintaan_id,
                    'jumlah_approve' => $value->stokDisetujui->sum('jumlah_disetujui'),
                    'status' => $value->status,
                    'id' => $value->id,
                    'barang_id' => $value->merkStok->barangStok->id, // Assuming a dropdown for selecting existing barang
                    'barang_name' => $merkCollect,
                    'jumlah' => $value->jumlah,
                    'satuan' => $value->merkStok->barangStok->satuanBesar->nama,
                    'dokumen' => $value->img ?? null,
                ];
            }
            $this->approvals = PersetujuanPermintaanStok::where('status', true)->where('detail_permintaan_id', $this->permintaan->id)
                ->whereHas('user', function ($query) {
                    $query->role('kepala_seksi'); // Muat hanya persetujuan dari kepala_seksi
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

    public function removeDocument($index)
    {
        $item = $this->list[$index];

        // Optional: Delete the file from storage if necessary
        Storage::delete($item['dokumen']);

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
