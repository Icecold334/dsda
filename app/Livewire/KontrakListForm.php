<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\TransaksiStok;
use App\Models\KontrakVendorStok;
use App\Models\SatuanBesar;
use App\Models\SatuanKecil;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;

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
    public $vendor_id;
    public $dokumenCount;
    public $barangSuggestions = [];
    public $merkSuggestions = [];
    public $newBarang = '';
    public $newMerk = '';
    public $penulis;
    public $pj1;
    public $pj2;

    // For new Barang Modal
    public $showBarangModal = false;
    public $newBarangName = '';
    public $newBarangSatuanBesar = '';
    public $newBarangSatuanKecil = '';
    public $satuanBesarOptions;
    public $satuanKecilOptions;

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
        $this->barang_item = BarangStok::find($barangId);
        $this->newBarang = $barangName;
        $this->barangSuggestions = [];
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

    public function createNewBarang()
    {
        $barang = BarangStok::create([
            'nama' => $this->newBarang,
            'jenis_id' => $this->jenis_id,
        ]);

        $this->selectBarang($barang->id, $barang->nama);
    }

    public function createNewMerk()
    {
        $merk = MerkStok::create([
            'nama' => $this->newMerk,
            'barang_id' => $this->barang_id,
        ]);

        $this->selectMerk($merk->id, $merk->nama);
    }

    public function mount()
    {
        $this->tanggal_kontrak = Carbon::now()->format('Y-m-d');
        $this->barangs = BarangStok::where('jenis_id', $this->jenis_id)->get()->sortBy('jenis_id');
        $this->satuanBesarOptions = SatuanBesar::all();
        $this->satuanKecilOptions = SatuanKecil::all();
    }

    public function addToList()
    {
        // dd($this->barang_id);
        $this->validate([
            'barang_id' => 'required',
            'merk_id' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);

        $this->list[] = [
            'barang_id' => $this->barang_id,
            'merk_id' => $this->merk_id,
            'merks' => MerkStok::where('barang_id', $this->barang_id)
                // ->whereNotIn('id', collect($this->list)->pluck('merk_id'))
                ->get(),
            'jumlah' => $this->jumlah,
        ];
        $this->mount();
        $this->reset(['barang_id', 'merk_id', 'jumlah', 'newBarang', 'newMerk']);
        $this->dispatch('listCount', count: count($this->list));
    }

    public function updateList($index, $field, $value)
    {
        if ($field === 'barang') {
            $this->list[$index]['barang_id'] = $value;
            $this->list[$index]['merk_id'] = null;

            $allMerks = MerkStok::where('barang_id', $value)->get();
            $usedMerkIds = collect($this->list)->except($index)->pluck('merk_id')->filter()->all();
            $this->list[$index]['merks'] = $allMerks->reject(function ($merk) use ($usedMerkIds) {
                return in_array($merk->id, $usedMerkIds);
            });
            $this->list[$index]['jumlah'] = 1;
        } elseif ($field === 'merk') {
            $this->list[$index]['merk_id'] = $value;
            $this->list[$index]['jumlah'] = 1;
        } elseif ($field === 'jumlah') {
            $this->list[$index]['jumlah'] = $value;
        }
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
        // return redirect()->route('kontrak-vendor-stok.index');
    }

    protected function generateContractNumber()
    {
        return 'CN-' . strtoupper(Str::random(6));
    }
    #[On('nomor_kontrak')]
    public function fillNomor($nomor)
    {
        $this->nomor_kontrak = $nomor;
        $this->mount();
    }
    #[On('tanggal_kontrak')]
    public function fillTanggal($tanggal)
    {
        $this->tanggal_kontrak = $tanggal;
        $this->mount();
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

    public function render()
    {
        return view('livewire.kontrak-list-form');
    }
}
