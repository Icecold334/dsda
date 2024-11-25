<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\PermintaanStok;
use App\Models\DetailPermintaanStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ListPermintaanForm extends Component
{
    use WithFileUploads;

    public $unit_id;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $keterangan;
    public $permintaan;

    public $list = []; // List of items
    public $newBarangId; // Input for new barang
    public $newBarang; // Input for new barang
    public $newJumlah; // Input for new jumlah
    public $newDokumen; // Input for new dokumen
    public $barangSuggestions = []; // Suggestions for barang
    public function removeNewDokumen()
    {
        if ($this->newDokumen && Storage::exists($this->newDokumen)) {
            Storage::delete($this->newDokumen);
        }
        $this->newDokumen = null; // Reset variable
    }
    public function focusBarang()
    {
        // Clear previous suggestions
        $this->barangSuggestions = [];

        if (!empty($this->newBarang)) {
            // Retrieve records that match user input from related MerkStok fields
            $this->barangSuggestions = BarangStok::whereHas('merkStok', function ($query) {
                $query->where('nama', 'like', '%' . $this->newBarang . '%')
                    ->orWhere('tipe', 'like', '%' . $this->newBarang . '%')
                    ->orWhere('ukuran', 'like', '%' . $this->newBarang . '%');
            })
                ->with('merkStok') // Assuming you might want to display some details from MerkStok
                ->limit(10)
                ->get();
        } else {
            // Show top 10 records by default if no input is provided
            $this->barangSuggestions = BarangStok::with('merkStok')
                ->limit(10)
                ->get();
        }
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
            'sub_unit_id' => $this->sub_unit_id ?? null,
            'keterangan' => $this->keterangan,
            'status' => null
        ]);
        foreach ($this->list as $item) {
            // Assuming $item['barang_name'] is a string like "Merk|Tipe|Ukuran"
            list($merk, $tipe, $ukuran) = array_pad(explode('|', $item['barang_name']), 3, null);

            $merkStok = MerkStok::updateOrCreate(
                [
                    'barang_id' => $item['barang_id'],
                    'nama' => $merk == '-' ? null : $merk,
                    'tipe' => $tipe == '-' ? null : $tipe,
                    'ukuran' => $ukuran == '-' ? null : $ukuran
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
        if ($this->permintaan) {
            foreach ($this->permintaan->permintaanStok as $key => $value) {
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
                    'id' => $value->id,
                    'barang_id' => $value->merkStok->barangStok->id, // Assuming a dropdown for selecting existing barang
                    'barang_name' => $merkCollect,
                    'jumlah' => $value->jumlah,
                    'satuan' => $value->merkStok->barangStok->satuanBesar->nama,
                    'dokumen' => $value->img ?? null,
                ];
            }
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

    public function render()
    {
        return view('livewire.list-permintaan-form', [
            'barangs' => MerkStok::all(), // Assuming you have a Barang model
        ]);
    }
}
