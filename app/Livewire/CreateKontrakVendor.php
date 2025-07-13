<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Toko;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\JenisStok;
use App\Models\BarangStok;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\ListKontrakStok;
use App\Models\MetodePengadaan;
use App\Models\KontrakVendorStok;
use App\Models\DetailPengirimanStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CreateKontrakVendor extends Component
{
    use WithFileUploads;

    // === SECTION: VENDOR ===
    public $vendor_id, $nama, $alamat, $kontak, $showAddVendorForm = false;

    // === SECTION: KONTRAK ===
    public $nomor_kontrak, $tanggal_kontrak, $metode_id, $jenis_id = 1, $nominal_kontrak;
    public $nomor_kontrak_baru = null;

    public $mode_api = false;
    public $tahun_api;
    public $kontrak_api_list = [];
    public $selected_api_kontrak;
    // === SECTION: API FIELDS ===
    public $nama_penyedia, $jenis_pengadaan, $nama_paket;
    public $tahun_anggaran, $dinas_sudin, $nama_bidang_seksi;

    public $program, $kegiatan, $sub_kegiatan, $aktivitas_sub_kegiatan, $rekening;


    // === SECTION: BARANG ===
    public $barang_id, $newBarang, $jumlah, $newHarga, $newPpn = 0;
    public $specifications = ['nama' => '', 'tipe' => '', 'ukuran' => ''];
    public $barangs, $list = [], $total = 0;
    public $suggestions = [
        'nama' => [],
        'tipe' => [],
        'ukuran' => [],
    ];
    public $isAdendum = false;
    public $kontrakLama = null;

    public $specRenderKey = null;
    public $specOptions = [
        'nama' => [],
        'tipe' => [],
        'ukuran' => [],
    ];

    public function updatedTahunApi()
    {
        $this->kontrak_api_list = [];

        if (!$this->tahun_api) return;

        $url = "https://emonev-dev.dsdajakarta.id/api/kontrak/{$this->tahun_api}";

        $response = Http::timeout(180)
            ->withOptions([
                'verify' => public_path('cacert.pem'),
            ])
            ->withBasicAuth(
                'inventa',
                'aF7xPq92LmZTkw38RbCn0vMUyJDg1shKXtbEWuAQ5oYclVGriHzSmNd6jeLfOBT3'
            )
            ->get($url);

        if ($response->successful()) {
            $this->kontrak_api_list = $response->json();
        } else {
            $this->kontrak_api_list = [];
            // Opsional: bisa dispatch alert ke browser
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'Gagal mengambil data dari API.'
            ]);
        }
    }

    public function updatedSelectedApiKontrak($index)
    {
        $data = $this->kontrak_api_list[$index] ?? null;

        if (!$data) return;

        $this->nomor_kontrak = $data['no_spk'];
        $this->tanggal_kontrak = $data['tgl_spk'];
        $this->nominal_kontrak = number_format((int) $data['nilai_kontrak'], 0, '', '.');

        $this->nama_penyedia = $data['nama_penyedia'];
        $this->jenis_pengadaan = $data['jenis_pengadaan'];
        $this->nama_paket = $data['nama_paket'];

        $this->tahun_anggaran = $data['tahun_anggaran'];
        $this->dinas_sudin = $data['dinas_sudin'];
        $this->nama_bidang_seksi = $data['nama_bidang_seksi'];

        $this->program = $data['kode_program'] . ' - ' . $data['program'];
        $this->kegiatan = $data['kode_kegiatan'] . ' - ' . $data['kegiatan'];
        $this->sub_kegiatan = $data['kode_sub_kegiatan'] . ' - ' . $data['sub_kegiatan'];
        $this->aktivitas_sub_kegiatan = $data['kode_aktivitas_sub_kegiatan'] . ' - ' . $data['aktivitas_sub_kegiatan'];
        $this->rekening = $data['kode_rekening'] . ' - ' . $data['uraian_kode_rekening'];
    }


    // public function updatedNomorKontrak($value)
    // {
    //     // Cek apakah kontrak dengan nomor ini ada
    //     $kontrak = KontrakVendorStok::where('nomor_kontrak', $value)->first();

    //     // Jika tidak ada, keluar saja
    //     if (!$kontrak) return;

    //     // Cek apakah kontrak ini punya adendum (artinya ini kontrak lama)
    //     $punyaAdendum = KontrakVendorStok::where('parent_kontrak_id', $kontrak->id)->exists();

    //     // Hanya trigger jika kontrak tersebut adalah adendum terakhir (tidak punya adendum lagi)
    //     if (!$punyaAdendum) {
    //         $this->dispatch('konfirmasi-adendum', id: $kontrak->id, nomor: $kontrak->nomor_kontrak);
    //     }
    // }


    public function prosesAdendum($id)
    {
        $this->isAdendum = true;

        $kontrak = KontrakVendorStok::with(['listKontrak.merkStok.barangStok'])->findOrFail($id);
        $this->kontrakLama = $kontrak;

        $this->vendor_id = $kontrak->vendor_id;
        $this->metode_id = $kontrak->metode_id;
        $this->jenis_id = $kontrak->jenis_id;
        $this->tanggal_kontrak = now()->format('Y-m-d');
        $this->nomor_kontrak = $kontrak->nomor_kontrak; // disimpan untuk display readonly
        $this->nomor_kontrak_baru = null; // inputan baru user

        // Set readonly secara manual di blade pakai $isAdendum

        // Ambil list barang lama
        // prosesAdendum()
        $this->list = $kontrak->listKontrak->map(function ($item) use ($kontrak) {
            $merk_id = $item->merkStok->id;
            $kontrakIds = $this->getKontrakChainIds($kontrak);

            $jumlah_terkirim = \App\Models\PengirimanStok::where('merk_id', $merk_id)
                ->whereHas('detailPengirimanStok', function ($q) use ($kontrakIds) {
                    $q->whereIn('kontrak_id', $kontrakIds)
                        ->where('status', 1);
                })
                ->sum('jumlah');


            return [
                'barang_id' => $item->merkStok->barang_id,
                'barang' => $item->merkStok->barangStok->nama,
                'specifications' => [
                    'nama' => $item->merkStok->nama,
                    'tipe' => $item->merkStok->tipe,
                    'ukuran' => $item->merkStok->ukuran,
                ],
                'jumlah' => $item->jumlah,
                'jumlah_terkirim' => $jumlah_terkirim,
                'harga' => $item->harga,
                'ppn' => $item->ppn ?? 0,
                'satuan' => $item->merkStok->barangStok->satuanBesar->nama,
                'readonly' => true,
                'can_delete' => $jumlah_terkirim < $item->jumlah,
            ];
        })->toArray();



        $this->calculateTotal();
    }
    public function resetAdendum()
    {
        $this->isAdendum = false;
        $this->kontrakLama = null;
        $this->nomor_kontrak = '';
        $this->list = [];
    }


    // === SECTION: DOKUMEN ===
    public $dokumen;

    public function mount()
    {
        $this->tanggal_kontrak = Carbon::now()->format('Y-m-d');
        $this->barangs = BarangStok::all();
    }

    // === VENDOR ===
    public function toggleAddVendorForm()
    {
        $this->showAddVendorForm = !$this->showAddVendorForm;
    }
    // public function updatedSpecifications($value, $key)
    // {
    //     if (!$this->barang_id) return;
    //     // $field = explode('.', $key)[1];
    //     $field = $key;

    //     $query = MerkStok::where('barang_id', $this->barang_id)
    //         ->where($field, 'like', "%$value%")
    //         ->pluck($field)
    //         ->unique()
    //         ->values()
    //         ->toArray();
    //     dump('dws');
    //     $this->suggestions[$field] = $query;
    // }

    public function selectSpecification($field, $value)
    {
        $this->specifications[$field] = $value;
        $this->suggestions[$field] = [];
    }

    public function addNewVendor()
    {
        $this->validate([
            'nama' => 'required',
            // 'alamat' => 'required',
            // 'kontak' => 'required'
        ]);

        $vendor = Toko::create([
            'user_id' => Auth::id(),
            'nama' => $this->nama,
            'nama_nospace' => Str::slug($this->nama),
            'alamat' => $this->alamat ?? null,
            'telepon' => $this->kontak ?? null,
        ]);

        $this->vendor_id = $vendor->id;
        $this->reset(['nama', 'alamat', 'kontak']);
        $this->showAddVendorForm = false;
    }

    // === BARANG ===
    public function selectBarang($id, $name)
    {
        $this->barang_id = $id;
        $this->newBarang = $name;
        $this->updatedBarangId(); // <-- ini penting
        $this->specifications = ['nama' => '', 'tipe' => '', 'ukuran' => ''];
        $this->specOptions = ['nama' => [], 'tipe' => [], 'ukuran' => []];
        $this->fetchSpesifikasiOptions();
    }


    public function updatedBarangId()
    {
        $this->specRenderKey = now()->timestamp; // ubah key setiap barang ganti
        $this->specifications = ['nama' => '', 'tipe' => '', 'ukuran' => ''];
        $this->specOptions = ['nama' => [], 'tipe' => [], 'ukuran' => []];
        $this->fetchSpesifikasiOptions();
    }

    public function fetchSpesifikasiOptions()
    {
        if (!$this->barang_id) return;

        $this->specOptions['nama'] = MerkStok::where('barang_id', $this->barang_id)->pluck('nama')->unique()->values()->toArray();
        $this->specOptions['tipe'] = MerkStok::where('barang_id', $this->barang_id)->pluck('tipe')->unique()->values()->toArray();
        $this->specOptions['ukuran'] = MerkStok::where('barang_id', $this->barang_id)->pluck('ukuran')->unique()->values()->toArray();
    }


    public function addToList()
    {

        // $this->validate([
        //     'barang_id' => 'required',
        //     'jumlah' => 'required|integer|min:1',
        // ]);

        $barang = BarangStok::find($this->barang_id);

        $this->list[] = [
            'barang_id' => $this->barang_id,
            'barang' => $barang->nama,
            'specifications' => $this->specifications,
            'jumlah' => $this->jumlah,
            'jumlah_terkirim' => 0,
            'harga' => $this->newHarga,
            'ppn' => $this->newPpn,
            'satuan' => $barang->satuanBesar->nama,
            'can_delete' => true,
        ];
        $this->specRenderKey = now()->timestamp; // juga reset saat tambah ke list
        $this->reset(['barang_id', 'newBarang', 'jumlah', 'newHarga', 'newPpn']);
        $this->specifications = ['nama' => '', 'tipe' => '', 'ukuran' => ''];
        $this->calculateTotal();
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->list as $item) {
            $harga = (int) str_replace('.', '', $item['harga']);
            $subtotal = $harga * $item['jumlah'];
            $ppn = $item['ppn'] ? ($subtotal * ((int) $item['ppn'] / 100)) : 0;
            $this->total += $subtotal + $ppn;
        }
        $this->nominal_kontrak = number_format($this->total, 0, '', '.');
    }

    // === SAVE ===
    public function saveKontrak()
    {
        $this->validate([
            'vendor_id' => 'required',
            'nomor_kontrak' => $this->isAdendum ? 'nullable' : 'required',
            'nomor_kontrak_baru' => $this->isAdendum ? 'required|different:nomor_kontrak' : 'nullable',
            'tanggal_kontrak' => 'required|date',
            'metode_id' => 'required',
            // 'jenis_id' => 'required',
            'list' => 'required|array|min:1',
        ]);

        $kontrak = KontrakVendorStok::create([
            'vendor_id' => $this->vendor_id,
            'nomor_kontrak' => $this->isAdendum ? $this->nomor_kontrak_baru : $this->nomor_kontrak,
            'tanggal_kontrak' => strtotime($this->tanggal_kontrak),
            'metode_id' => $this->metode_id,
            'jenis_id' => $this->jenis_id,
            'user_id' => Auth::id(),
            'type' => 1,
            'status' => 1,
            'nominal_kontrak' => (int) str_replace('.', '', $this->nominal_kontrak),
            'is_adendum' => $this->isAdendum,
            'parent_kontrak_id' => $this->isAdendum ? $this->kontrakLama->id : null,
        ]);

        foreach ($this->list as $item) {
            $merk = MerkStok::firstOrCreate([
                'barang_id' => $item['barang_id'],
                'nama' => $item['specifications']['nama'] ?? null,
                'tipe' => $item['specifications']['tipe'] ?? null,
                'ukuran' => $item['specifications']['ukuran'] ?? null,
            ]);

            ListKontrakStok::create([
                'kontrak_id' => $kontrak->id,
                'merk_id' => $merk->id,
                'jumlah' => $item['jumlah'],
                'harga' => (int) str_replace('.', '', $item['harga']),
                'ppn' => $item['ppn'] == '0' ? null : $item['ppn'],
            ]);
        }

        return $this->dispatch('saveDokumen', kontrak_id: $kontrak->id);

        // session()->flash('success', 'Kontrak berhasil disimpan!');
        // return redirect()->route('kontrak-vendor-stok.index');
    }
    protected function getKontrakChainIds($kontrak)
    {
        $ids = [$kontrak->id];

        while ($kontrak->is_adendum && $kontrak->parent_kontrak_id) {
            $kontrak = KontrakVendorStok::find($kontrak->parent_kontrak_id);
            if (!$kontrak) break;
            $ids[] = $kontrak->id;
        }

        return $ids;
    }

    public function render()
    {
        return view('livewire.create-kontrak-vendor', [
            'metodes' => MetodePengadaan::all(),
            'barangs' => $this->barangs,
            'vendors' => Toko::all(),
        ]);
    }
}
