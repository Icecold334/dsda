<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use App\Models\SatuanBesar;
use App\Models\SatuanKecil;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\Auth;

class KontrakListForm extends Component
{
    public $list = [];
    public $barangs;
    public $tanggal_kontrak;
    public $nomor_kontrak;
    public $barang_item;
    public $barang_id;
    public $merks;
    public $merk_item;
    public $merk_id;
    public $jumlah;
    public $jenis_id;
    public $metode_id;
    public $vendor_id;
    public $dokumenCount;
    public $barangSuggestions = [];
    public $newMerk, $newTipe, $newUkuran, $merkSuggestions = [];
    public $newBarang = '';
    public $penulis;
    public $pj1;
    public $pj2;

    public $specifications = [
        'merek' => '',
        'tipe' => '',
        'ukuran' => '',
    ];

    public $suggestions = [
        'merek' => [],
        'tipe' => [],
        'ukuran' => [],
    ];
    public function updateSpecification($key, $value)
    {

        if ($this->barang_id) {
            $this->specifications[$key] = $value;

            // Fetch suggestions for the current input across all fields
            $this->suggestions = MerkStok::where('barang_id', $this->barang_id)
                ->when($key !== 'merek', fn($query) => $query->where('nama', 'like', '%' . $this->specifications['merek'] . '%'))
                ->when($key !== 'tipe', fn($query) => $query->where('tipe', 'like', '%' . $this->specifications['tipe'] . '%'))
                ->when($key !== 'ukuran', fn($query) => $query->where('ukuran', 'like', '%' . $this->specifications['ukuran'] . '%'))
                ->get()
                ->toArray();

            // Query for matching records in the merk_stok table
            $matchingMerk = MerkStok::where('barang_id', $this->barang_id)
                ->when($key !== 'merek', fn($query) => $query->where('nama', $this->specifications['merek']))
                ->when($key !== 'tipe', fn($query) => $query->where('tipe', $this->specifications['tipe']))
                ->when($key !== 'ukuran', fn($query) => $query->where('ukuran', $this->specifications['ukuran']))
                ->first();

            if ($matchingMerk) {
                // Only set fields if they are empty, avoid overwriting user-modified fields
                $this->specifications['merek'] = $this->specifications['merek'] ?? $matchingMerk->nama;
                $this->specifications['tipe'] = $this->specifications['tipe'] ?? $matchingMerk->tipe;
                $this->specifications['ukuran'] = $this->specifications['ukuran'] ?? $matchingMerk->ukuran;

                // Set merk_id if all specifications match the row
                if (
                    $this->specifications['merek'] === $matchingMerk->nama &&
                    $this->specifications['tipe'] === $matchingMerk->tipe &&
                    $this->specifications['ukuran'] === $matchingMerk->ukuran
                ) {
                    $this->merk_id = $matchingMerk->id;
                } else {
                    $this->merk_id = null; // Clear merk_id if not all fields match
                }
            } else {
                $this->merk_id = null; // Clear merk_id if no match
            }
        }
    }

    public function selectSpecification($merek, $tipe, $ukuran)
    {
        // Update the fields based on the selected suggestion
        $this->specifications['merek'] = $merek ?? $this->specifications['merek'];
        $this->specifications['tipe'] = $tipe ?? $this->specifications['tipe'];
        $this->specifications['ukuran'] = $ukuran ?? $this->specifications['ukuran'];

        // Find the matching row to set the merk_id
        $matchingMerk = MerkStok::where('barang_id', $this->barang_id)
            ->where('nama', $merek)
            ->where('tipe', $tipe)
            ->where('ukuran', $ukuran)
            ->first();

        // Set merk_id if an exact match is found
        $this->merk_id = $matchingMerk ? $matchingMerk->id : null;

        // Clear suggestions after selection
        $this->suggestions = [];
    }

    // For new Barang Modal
    public $showBarangModal = false;
    public $newBarangName = '';
    public $newBarangSatuanBesar = '';
    public $newBarangSatuanKecil = '';
    public $satuanBesarOptions;
    public $satuanKecilOptions;

    public function mount()
    {

        $this->barang_id = null;
        $this->newBarang = null;
        $this->merk_id = null;
        $this->newMerk = null;
        $this->penulis = Auth::user()->name;
        $this->tanggal_kontrak = Carbon::now()->format('Y-m-d');
        $this->barangs = BarangStok::where('jenis_id', $this->jenis_id)->get()->sortBy('jenis_id');
        $this->satuanBesarOptions = SatuanBesar::all();
        $this->satuanKecilOptions = SatuanKecil::all();
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

    public function openBarangModal()
    {
        $this->showBarangModal = true;
    }

    public function closeBarangModal()
    {
        $this->reset(['newBarangName', 'newBarangSatuanBesar', 'newBarangSatuanKecil']);
        $this->showBarangModal = false;
    }

    public function saveNewBarang()
    {
        $this->validate([
            'newBarangName' => 'required|string|max:255',
            'newBarangSatuanBesar' => 'required',
            'newBarangSatuanKecil' => 'required',
        ]);
        $faker = Faker::create();

        $barang = BarangStok::create([
            'nama' => $this->newBarangName,
            'kode_barang' => $faker->unique()->numerify('BRG-#####-#####'),
            'jenis_id' => $this->jenis_id,
            'satuan_besar_id' => $this->newBarangSatuanBesar,
            'satuan_kecil_id' => $this->newBarangSatuanKecil,
        ]);

        $this->selectBarang($barang->id, $barang->nama); // Select newly created barang
        $this->closeBarangModal();
    }

    public function updatedNewBarang()
    {
        $this->barangSuggestions = BarangStok::where('jenis_id', $this->jenis_id)
            ->where('nama', 'like', '%' . $this->newBarang . '%')
            ->get();
        $this->newBarangName = $this->newBarang;
    }

    public function selectBarang($barangId, $barangName)
    {
        $this->barang_id = $barangId;
        $this->merk_id = null;
        $this->specifications = [
            'merek' => null,
            'tipe' => null,
            'ukuran' => null,
        ];
        $this->barang_item = BarangStok::find($barangId);
        $this->newBarang = $barangName;
        $this->barangSuggestions = [];
    }

    public function blurSpecification($key)
    {
        // if ($this->specifications[$key]) {
        $this->suggestions[$key] = [];
        // }
    }

    public function blurBarang()
    {
        $barang = BarangStok::where('jenis_id', $this->jenis_id)->where('nama', $this->newBarang)->get();
        if ($barang->count() == 0) {
            $this->barang_id = null;
        } else {
            $this->barang_id = $barang->first()->id;
            $this->barang_item = $barang->first();
            $this->jumlah = 1;
        }
        $this->barangSuggestions = [];
    }

    public function blurMerk()
    {
        $merk = MerkStok::where('barang_id', $this->barang_id)->where('nama', $this->newMerk)->get();
        if ($merk->count() == 0) {
            $this->merk_id = null;
        } else {
            $this->merk_id = $merk->first()->id;
        }
        $this->merkSuggestions = [];
    }

    public function blurTipe()
    {
        if ($this->newTipe) {
            $this->dispatch('fieldBlurred', field: 'tipe', value: $this->newTipe);
        }
    }

    public function blurUkuran()
    {
        if ($this->newUkuran) {
            $this->dispatch('fieldBlurred', field: 'ukuran', value: $this->newUkuran);
        }
    }

    public function selectMerk($merkId, $merkName)
    {
        $this->merk_id = $merkId;
        $this->jumlah = 1;
        $this->newMerk = $merkName;
        $this->merkSuggestions = [];
    }

    public function updatedNewMerk()
    {
        if ($this->barang_id) {
            $this->merkSuggestions = MerkStok::where('barang_id', $this->barang_id)
                ->where('nama', 'like', '%' . $this->newMerk . '%')
                ->get();
        }
    }

    public function createNewMerk()
    {
        // Validate that at least one of the fields is filled
        $this->validate([
            'specifications.merek' => 'nullable|string|max:255|required_without_all:specifications.tipe,specifications.ukuran',
            'specifications.tipe' => 'nullable|string|max:255|required_without_all:specifications.merek,specifications.ukuran',
            'specifications.ukuran' => 'nullable|string|max:255|required_without_all:specifications.merek,specifications.tipe',
        ]);

        // Create the new merk record
        $merk = MerkStok::create([
            'barang_id' => $this->barang_id,
            'nama' => $this->specifications['merek'],
            'tipe' => $this->specifications['tipe'],
            'ukuran' => $this->specifications['ukuran'],
        ]);

        // Select the newly created merk
        $this->selectMerk($merk->id, $merk->nama);
    }


    public function addToList()
    {
        $this->validate([
            'barang_id' => 'required',
            'merk_id' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);

        $this->list[] = [
            'barang_id' => $this->barang_id,
            'merk_id' => $this->merk_id,
            'merks' => MerkStok::where('barang_id', $this->barang_id)->get(),
            'jumlah' => $this->jumlah,
        ];
        $this->reset(['barang_id', 'merk_id', 'jumlah', 'newBarang', 'newMerk']);
        $this->dispatch('listCount', count: count($this->list));
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);
        $this->dispatch('listCount', count: count($this->list));
    }

    public function saveKontrak()
    {
        $this->validate([
            'vendor_id' => 'required',
            'list' => 'required|array|min:1',
            'list.*.barang_id' => 'required|integer',
            'list.*.merk_id' => 'required|integer',
            'list.*.jumlah' => 'required|integer|min:1',
        ]);

        $kontrak = KontrakVendorStok::create([
            'vendor_id' => $this->vendor_id,
            'tanggal_kontrak' => strtotime($this->tanggal_kontrak),
            'metode_id' => $this->metode_id,
            'penulis' => $this->penulis,
            'pj1' => $this->pj1,
            'pj2' => $this->pj2,
            'nomor_kontrak' => $this->nomor_kontrak ?? $this->generateContractNumber(),
            'user_id' => Auth::id(),
            'type' => true
        ]);

        foreach ($this->list as $item) {
            TransaksiStok::create([
                'merk_id' => $item['merk_id'],
                'vendor_id' => $this->vendor_id,
                'user_id' => Auth::id(),
                'kontrak_id' => $kontrak->id,
                'tanggal' => strtotime(now()),
                'jumlah' => $item['jumlah'],
                'tipe' => 'Pemasukan'
            ]);
        }

        return $this->dispatch('saveDokumen', kontrak_id: $kontrak->id);
    }

    protected function generateContractNumber()
    {
        return 'CN-' . strtoupper(Str::random(6));
    }

    public function render()
    {
        return view('livewire.kontrak-list-form');
    }
}
