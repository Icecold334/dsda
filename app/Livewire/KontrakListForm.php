<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use App\Models\Kategori;
use App\Models\SatuanBesar;
use App\Models\SatuanKecil;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\KategoriStok;
use App\Models\TransaksiStok;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\Auth;

class KontrakListForm extends Component
{
    public $list = []; // List untuk menampung data kontrak
    public $barangs;
    public $barang_id;
    public $barang_item;
    public $merk_id;
    public $kategori_id;
    public $jumlah;
    public $newBarang = '';
    public $newKategori;
    public $barangSuggestions = [];
    public $showAddBarang = false;
    public $specifications = [
        'merek' => '',
        'tipe' => '',
        'ukuran' => '',
    ];
    public $suggestions = [
        'merek' => [],
        'tipe' => [],
        'ukuran' => [],
        'satuanBesar' => [],
        'satuanKecil' => [],
        'kategori' => [],
    ];

    public function fetchSuggestions($field, $value)
    {
        $this->suggestions[$field] = [];
        if ($value) {
            $key = Str::slug($value);

            if ($field === 'satuanBesar') {
                $this->suggestions[$field] = SatuanBesar::where('slug', 'like', '%' . $key . '%')
                    ->pluck('nama')->toArray();
            } elseif ($field === 'satuanKecil') {
                $this->suggestions[$field] = SatuanBesar::where('slug', 'like', '%' . $key . '%')
                    ->pluck('nama')->toArray();
            } elseif ($field === 'kategori') {
                $this->suggestions[$field] = KategoriStok::where('slug', 'like', '%' . $key . '%')
                    ->pluck('nama')->toArray();
            }
        }
    }

    public $tanggal_kontrak;
    public $nomor_kontrak;
    public $jenis_id;
    public $metode_id;
    public $vendor_id;
    public $dokumenCount;
    public $penulis;

    public $showBarangModal;
    public $newBarangName = '';
    public $newBarangSatuanBesar = '';
    public $newBarangSatuanKecil = '';
    public $satuanBesarOptions;
    public $satuanKecilOptions;
    public $jumlahKecilDalamBesar;

    public function mount()


    {
        $this->barang_id = null;
        $this->newBarang = null;
        $this->penulis = Auth::user()->name;
        $this->tanggal_kontrak = Carbon::now()->format('Y-m-d');
        $this->barangs = BarangStok::all();
        $this->satuanBesarOptions = SatuanBesar::all();
        $this->satuanKecilOptions = SatuanBesar::all();
    }



    // Update barang suggestions saat mengetik
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

    // Pilih barang dari suggestions
    public function selectBarang($barangId, $barangName)
    {
        $this->barang_id = $barangId;
        $this->newBarang = $barangName;
        $this->barangSuggestions = [];
        $this->resetSpecifications();
    }

    // Update spesifikasi (merek, tipe, ukuran)
    public function updateSpecification($key, $value)
    {
        if ($this->barang_id) {
            $this->specifications[$key] = $value;
            $this->merk_id = null;
            // Ambil suggestions untuk spesifikasi yang dimasukkan
            $this->suggestions[$key] = MerkStok::where('barang_id', $this->barang_id)
                ->where($key === 'merek' ? 'nama' : $key, 'like', '%' . $value . '%')
                ->pluck($key === 'merek' ? 'nama' : $key)
                ->unique()
                ->toArray();
        }
    }

    public function selectSuggestion($field, $value)
    {
        if ($field === 'satuanBesar') {
            $this->newBarangSatuanBesar = $value;
        } elseif ($field === 'satuanKecil') {
            $this->newBarangSatuanKecil = $value;
        } elseif ($field === 'kategori') {
            $this->newKategori = $value;
        }
        $this->suggestions[$field] = [];
    }



    // Pilih spesifikasi berdasarkan saran
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
            'kategori' => [],
        ];
    }

    // Reset spesifikasi
    private function resetSpecifications()
    {
        $this->specifications = [
            'merek' => '',
            'tipe' => '',
            'ukuran' => '',
        ];
        $this->merk_id = null;
        $this->suggestions = [
            'merek' => [],
            'tipe' => [],
            'ukuran' => [],
            'satuanBesar' => [],
            'satuanKecil' => [],
            'kategori' => [],
        ];
    }

    // Cek atau buat merk_id
    private function checkOrCreateMerkId()
    {
        $existingMerk = MerkStok::where('barang_id', $this->barang_id)
            ->where('nama', $this->specifications['merek'])
            ->where('tipe', $this->specifications['tipe'])
            ->where('ukuran', $this->specifications['ukuran'])
            ->first();

        if ($existingMerk) {
            $this->merk_id = $existingMerk->id;
        } else {
            $newMerk = MerkStok::create([
                'barang_id' => $this->barang_id,
                'nama' => $this->specifications['merek'] ?? null,
                'tipe' => $this->specifications['tipe'] ?? null,
                'ukuran' => $this->specifications['ukuran'] ?? null,
            ]);
            $this->merk_id = $newMerk->id;
        }
    }

    // Tambahkan ke list kontrak
    public function addToList()
    {
        $this->validate([
            'barang_id' => 'required',
            // 'merk_id' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);

        $this->list[] = [
            'barang_id' => $this->barang_id,
            'barang' => BarangStok::find($this->barang_id)->nama,
            'kategori_id' => $this->kategori_id,
            'specifications' => $this->specifications,
            'jumlah' => $this->jumlah,
            'satuan' => BarangStok::find($this->barang_id)->satuanBesar->nama,
        ];

        // $this->reset(['barang_id', 'merk_id', 'jumlah', 'newBarang']);
        $this->reset(['barang_id',  'jumlah', 'newBarang']);
        $this->resetSpecifications();
        $this->dispatch('listCount', count: count($this->list));
    }

    // Hapus item dari list
    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);
    }

    public function saveNewBarang()
    {
        $this->validate([
            'newBarangName' => 'required|string|max:255',
            'newBarangSatuanBesar' => 'required|string',
            'newKategori' => 'required|string',
            'newBarangSatuanKecil' => 'nullable|string',
            // 'jumlahKecilDalamBesar' => 'required_with:newBarangSatuanKecil|integer|min:1',
        ]);

        // Handle satuan besar
        $satuanBesar = SatuanBesar::updateOrCreate(
            ['nama' => $this->newBarangSatuanBesar],
            ['nama' => $this->newBarangSatuanBesar]
        );

        // Handle satuan kecil
        $satuanKecil = null;
        if ($this->newBarangSatuanKecil) {
            $satuanKecil = SatuanBesar::updateOrCreate(
                ['nama' => $this->newBarangSatuanKecil],
                ['nama' => $this->newBarangSatuanKecil]
            );
        }
        $faker = Faker::create();
        if ($this->jenis_id == 3) {

            $nama = $this->newKategori;
            $kategori = KategoriStok::firstOrCreate(
                ['slug' => Str::slug($nama)], // Pencarian berdasarkan field slug
                [
                    'nama' => $nama, // Data yang diisi jika tidak ditemukan
                    'slug' => Str::slug($nama) // Slug tetap digunakan untuk pembuatan
                ]
            );
        }

        // Simpan barang
        $barang = BarangStok::create([
            'nama' => $this->newBarangName,
            'kode_barang' => $faker->unique()->numerify('BRG-#####-#####'),
            'jenis_id' => $this->jenis_id,
            'kategori_id' => $this->jenis_id == 3 ? $kategori->id : null,
            'satuan_besar_id' => $satuanBesar->id,
            'satuan_kecil_id' => $satuanKecil ? $satuanKecil->id ?? null : null,
            'konversi' => $satuanKecil ? $this->jumlahKecilDalamBesar ?? null : null,
        ]);

        // Reset input fields
        $this->reset(['newBarangName', 'newBarangSatuanBesar', 'newBarangSatuanKecil', 'jumlahKecilDalamBesar']);
        $this->closeBarangModal();
        $this->selectBarang($barang->id, $barang->nama); // Select newly created barang
        // session()->flash('message', 'Barang berhasil ditambahkan!');
    }


    public function saveKontrak()
    {
        $this->validate([
            'vendor_id' => 'required',
            'list' => 'required|array|min:1',
            'list.*.barang_id' => 'required|integer',
            'list.*.jumlah' => 'required|integer|min:1',
        ]);

        $kontrak = KontrakVendorStok::create([
            'vendor_id' => $this->vendor_id,
            'tanggal_kontrak' => strtotime($this->tanggal_kontrak),
            'metode_id' => $this->metode_id,
            'user_id' => Auth::user()->id,
            'nomor_kontrak' => $this->nomor_kontrak,
            'type' => 1,
            'status' => 1,
        ]);

        foreach ($this->list as $item) {
            $merk = MerkStok::updateOrCreate(
                [
                    'barang_id' => $item['barang_id'],
                    'nama' => empty($item['specifications']['merek']) ? null : $item['specifications']['merek'],
                    'tipe' => empty($item['specifications']['tipe']) ? null : $item['specifications']['tipe'],
                    'ukuran' => empty($item['specifications']['ukuran']) ? null : $item['specifications']['ukuran'],
                ],
                [] // Tidak ada kolom tambahan untuk diperbarui
            );
            TransaksiStok::create([
                'merk_id' => $merk->id,
                'vendor_id' => $this->vendor_id,
                'user_id' => Auth::user()->id,
                'jumlah' => $item['jumlah'],
                'kontrak_id' => $kontrak->id,
                'tanggal' => strtotime(now()),
                'tipe' => 'Pemasukan',
            ]);
        }
        return $this->dispatch('saveDokumen', kontrak_id: $kontrak->id);
    }
    public function closeBarangModal()
    {
        $this->reset(['newBarangName', 'newBarangSatuanBesar', 'newBarangSatuanKecil']);
        $this->showBarangModal = false;
    }

    public function openBarangModal()
    {
        $this->showBarangModal = true;
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
        return view('livewire.kontrak-list-form');
    }
}
