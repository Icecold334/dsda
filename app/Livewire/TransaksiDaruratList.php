<?php

namespace App\Livewire;

use id;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use App\Models\SatuanBesar;
use Faker\Factory as Faker;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class TransaksiDaruratList extends Component
{
    use WithFileUploads;
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
    public $merkSuggestions = [];
    public $satuanBesarOptions;
    public $newBarangSatuanBesar = '';
    public $newBarangSatuanKecil = '';
    public $showBarangModal = false;
    public $jumlahKecilDalamBesar;

    public function mount()
    {
        $this->satuanBesarOptions = SatuanBesar::all();
    }

    public $barang_id;
    public $newBarangName;

    public function updatedNewBarang()
    {
        $this->barang_id = null;
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

    public function saveNewBarang()
    {
        $this->validate([
            'newBarang' => 'required|string|max:255',
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
            'satuan_besar_id' => $satuanBesar->id,
            'satuan_kecil_id' => $satuanKecil ? $satuanKecil->id : null,
            'konversi' => $this->jumlahKecilDalamBesar ?? null,
        ]);

        $this->selectBarang($barang->id, $barang->nama);
        $this->closeBarangModal();
    }

    public function addToList()
    {
        $this->validate([
            'newBarangId' => 'required',
            'specifications.merek' => 'required|string',
            'newJumlah' => 'required|integer|min:1',
            'newLokasiPenerimaan' => 'required|string',
            'newKeterangan' => 'nullable|string',
        ]);

        $this->list[] = [
            'barang' => BarangStok::find($this->newBarangId)->nama,
            'specifications' => $this->specifications,
            'jumlah' => $this->newJumlah,
            'satuan' => BarangStok::find($this->newBarangId)->satuanBesar->nama,
            'lokasi_penerimaan' => $this->newLokasiPenerimaan,
            'keterangan' => $this->newKeterangan,
            'bukti' => $this->newBukti ? $this->newBukti->getClientOriginalName() : null,
        ];

        $this->reset(['newBarang', 'newBarangId', 'specifications', 'newJumlah', 'newLokasiPenerimaan', 'newKeterangan', 'newBukti']);
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
        return view('livewire.transaksi-darurat-list');
    }
}
