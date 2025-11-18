<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Toko;
use App\Models\Program;
use Livewire\Component;
use App\Models\Kegiatan;
use App\Models\MerkStok;
use App\Models\SubKegiatan;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\UraianRekening;
use App\Models\ListKontrakStok;
use App\Models\MetodePengadaan;
use App\Models\KontrakVendorStok;
use App\Models\AktivitasSubKegiatan;
use App\Models\SatuanBesar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CreateKontrakVendor extends Component
{
    use WithFileUploads;
    public $id;
    // === SECTION: VENDOR ===
    public $vendor_id, $nama, $alamat, $kontak, $showAddVendorForm = false;

    // === SECTION: KONTRAK ===
    public $nomor_kontrak, $tanggal_kontrak, $metode_id, $jenis_id = 1, $nominal_kontrak;
    public $nomor_kontrak_baru = null;

    public $nomor_spk_api;
    public $hasil_cari_api = false;
    public $barangSuggestions = [];
    public $satuanSuggestions = [];

    // === SECTION: MODE MANUAL ===
    public $mode_manual = false;
    public $readonly_fields = true;

    public $mode_api = true;
    public $tahun_api;
    public $kontrak_api_list = [];
    public $selected_api_kontrak;
    // === SECTION: API FIELDS ===
    public $nama_penyedia, $jenis_pengadaan, $nama_paket;
    public $tahun_anggaran, $dinas_sudin, $nama_bidang_seksi;

    public $program, $kegiatan, $sub_kegiatan, $aktivitas_sub_kegiatan, $rekening;

    public $program_id, $programs = [];
    public $kegiatan_id, $kegiatans = [];
    public $sub_kegiatan_id, $sub_kegiatans = [];
    public $aktivitas_id, $aktivitass = [];
    public $rekening_id, $rekenings = [];
    public $tanggal_akhir_kontrak;
    public $durasi_kontrak;
    public $newSatuan;

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

    public $specNamaOptions = [];
    public $specTipeOptions = [];
    public $specUkuranOptions = [];
    public $satuanOptions = [];

    public function cariKontrakApi()
    {
        if (!$this->tahun_api || !$this->nomor_spk_api) {
            $this->dispatch('alert', [
                'type' => 'warning',
                'message' => 'Tahun dan nomor kontrak harus diisi terlebih dahulu.'
            ]);
            return;
        }

        $url = "https://emonev.dsdajakarta.id/api/kontrak/{$this->tahun_api}";

        $response = Http::timeout(180)
            ->withOptions(['verify' => public_path('cacert.pem')])
            ->withBasicAuth('inventa', 'aF7xPq92LmZTkw38RbCn0vMUyJDg1shKXtbEWuAQ5oYclVGriHzSmNd6jeLfOBT3')
            ->get($url);

        if (!$response->successful()) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Gagal mengambil data dari API.'
            ]);
            return;
        }

        $data = collect($response->json()['data'] ?? [])
            ->firstWhere('no_spk', $this->nomor_spk_api);

        if (!$data) {
            // Dispatch event untuk menampilkan konfirmasi manual entry
            $this->dispatch('kontrak-tidak-ditemukan');
            return;
        }

        $this->hasil_cari_api = true;
        $this->mode_manual = false;
        $this->readonly_fields = true;

        // Set semua field dari API
        $this->tanggal_kontrak = $data['tgl_spk'];
        $this->tanggal_akhir_kontrak = $data['tgl_akhir_spk'];
        $this->nominal_kontrak = number_format((int) $data['nilai_kontrak'], 0, '', '.');
        $this->nama_paket = $data['nama_paket'];
        $this->jenis_pengadaan = $data['jenis_pengadaan'];
        $this->nama_penyedia = $data['nama_penyedia'];
        $this->tahun_anggaran = $data['tahun_anggaran'];
        $this->dinas_sudin = $data['dinas_sudin'];
        $this->nama_bidang_seksi = $data['nama_bidang_seksi'];

        $this->program = $data['kode_program'] . ' - ' . $data['program'];
        $this->kegiatan = $data['kode_kegiatan'] . ' - ' . $data['kegiatan'];
        $this->sub_kegiatan = $data['kode_sub_kegiatan'] . ' - ' . $data['sub_kegiatan'];
        $this->aktivitas_sub_kegiatan = $data['kode_aktivitas_sub_kegiatan'] . ' - ' . $data['aktivitas_sub_kegiatan'];
        $this->rekening = $data['kode_rekening'] . ' - ' . $data['uraian_kode_rekening'];

        $this->hitungDurasiKontrak();
    }

    public function lanjutkanModeManual()
    {
        $this->mode_manual = true;
        $this->readonly_fields = false;
        $this->hasil_cari_api = true;

        // Reset field-field yang akan diisi manual
        $this->resetManualFields();

        $this->dispatch('alert', [
            'type' => 'info',
            'message' => 'Mode pengisian manual diaktifkan. Silakan isi data kontrak secara manual.'
        ]);
    }

    public function batalkanPencarian()
    {
        $this->nomor_spk_api = '';
        $this->hasil_cari_api = false;
        $this->mode_manual = false;
        $this->readonly_fields = true;
        $this->resetManualFields();
    }

    private function resetManualFields()
    {
        $this->tanggal_kontrak = '';
        $this->tanggal_akhir_kontrak = '';
        $this->nominal_kontrak = '';
        $this->nama_paket = '';
        $this->jenis_pengadaan = '';
        $this->nama_penyedia = '';
        $this->tahun_anggaran = '';
        $this->dinas_sudin = '';
        $this->nama_bidang_seksi = '';
        $this->program = '';
        $this->kegiatan = '';
        $this->sub_kegiatan = '';
        $this->aktivitas_sub_kegiatan = '';
        $this->rekening = '';
        $this->durasi_kontrak = null;
    }

    public function updatedTanggalAkhirKontrak()
    {
        $this->hitungDurasiKontrak();
    }

    public function updatedTanggalKontrak()
    {
        $this->hitungDurasiKontrak();
    }

    public function hitungDurasiKontrak()
    {
        if (!$this->tanggal_kontrak || !$this->tanggal_akhir_kontrak) {
            $this->durasi_kontrak = null;
            return;
        }

        $start = Carbon::parse($this->tanggal_kontrak);
        $end = Carbon::parse($this->tanggal_akhir_kontrak);

        if ($end->lessThan($start)) {
            $this->durasi_kontrak = 'Tanggal akhir tidak valid';
            return;
        }

        $diff = $start->diff($end);
        $this->durasi_kontrak = $diff->y . ' tahun, ' . $diff->m . ' bulan, ' . $diff->d . ' hari';
    }

    public function updatedTahunApi()
    {
        $this->kontrak_api_list = [];

        if (!$this->tahun_api)
            return;

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
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Gagal mengambil data dari API.'
            ]);
        }
    }

    public function updatedSelectedApiKontrak($index)
    {
        $data = $this->kontrak_api_list['data'][$index] ?? null;

        if (!$data)
            return;

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

    public function prosesAdendum($id)
    {
        $this->isAdendum = true;

        $kontrak = KontrakVendorStok::with(['listKontrak.merkStok.barangStok'])->findOrFail($id);
        $this->kontrakLama = $kontrak;

        // Nilai utama
        $this->vendor_id = $kontrak->vendor_id;
        $this->metode_id = $kontrak->metode_id;
        $this->jenis_id = $kontrak->jenis_id;
        $this->tanggal_kontrak = now()->format('Y-m-d');
        $this->nomor_kontrak = $kontrak->nomor_kontrak;
        $this->nomor_kontrak_baru = null;

        // Data umum
        $this->tahun_anggaran = $kontrak->tahun_anggaran;
        $this->dinas_sudin = $kontrak->dinas_sudin;
        $this->nama_bidang_seksi = $kontrak->nama_bidang_seksi;
        $this->nama_paket = $kontrak->nama_paket;
        $this->jenis_pengadaan = $kontrak->jenis_pengadaan;

        // Ambil semua program (dropdown pertama)
        $this->programs = \App\Models\Program::all();

        // Ambil ID berdasarkan string "kode - nama"
        $this->program_id = Program::whereRaw("kode || ' - ' || program = ?", [$kontrak->program])->value('id');
        $this->kegiatan_id = Kegiatan::whereRaw("kode || ' - ' || kegiatan = ?", [$kontrak->kegiatan])->value('id');
        $this->sub_kegiatan_id = SubKegiatan::whereRaw("kode || ' - ' || sub_kegiatan = ?", [$kontrak->sub_kegiatan])->value('id');
        $this->aktivitas_id = AktivitasSubKegiatan::whereRaw("kode || ' - ' || aktivitas = ?", [$kontrak->aktivitas_sub_kegiatan])->value('id');
        $this->rekening_id = UraianRekening::whereRaw("kode || ' - ' || uraian = ?", [$kontrak->rekening])->value('id');

        // Isi dropdown cascade setelah ID didapat
        $this->kegiatans = Kegiatan::where('program_id', $this->program_id)->get();
        $this->sub_kegiatans = SubKegiatan::where('kegiatan_id', $this->kegiatan_id)->get();
        $this->aktivitass = AktivitasSubKegiatan::where('sub_kegiatan_id', $this->sub_kegiatan_id)->get();
        $this->rekenings = UraianRekening::where('aktivitas_sub_kegiatan_id', $this->aktivitas_id)->get();

        // Durasi kontrak
        $this->hitungDurasiKontrak();

        // Ambil list barang lama
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
        $this->barangs = [];
        $this->programs = [];
        $this->satuanOptions = [];
        $this->specNamaOptions = [];
        $this->specTipeOptions = [];
        $this->specUkuranOptions = [];
        $this->barangSuggestions = [];
        $this->satuanSuggestions = [];
        $this->specOptions = [
            'nama' => [],
            'tipe' => [],
            'ukuran' => [],
        ];

        if ($this->id) {
            $this->loadListBarangAdendum($this->id);
        }
    }

    public function loadListBarangAdendum($id)
    {
        $this->isAdendum = true;

        $kontrak = KontrakVendorStok::with('listKontrak.merkStok.barangStok')->findOrFail($id);
        $this->kontrakLama = $kontrak;

        $this->list = $kontrak->listKontrak->map(function ($item) use ($kontrak) {
            $merk_id = $item->merkStok->id;
            $kontrakIds = $this->getKontrakChainIds($kontrak);

            $jumlah_terkirim = \App\Models\PengirimanStok::where('merk_id', $merk_id)
                ->whereHas('detailPengirimanStok', function ($q) use ($kontrakIds) {
                    $q->whereIn('kontrak_id', $kontrakIds)->where('status', 1);
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

        $this->nomor_kontrak = $kontrak->nomor_kontrak;
        $this->nomor_kontrak_baru = null;
    }

    public function updated($property)
    {
        if ($property === 'program_id') {
            $this->kegiatans = \App\Models\Kegiatan::where('program_id', $this->program_id)->get();
            $this->kegiatan_id = $this->sub_kegiatan_id = $this->aktivitas_id = $this->rekening_id = null;
            $this->sub_kegiatans = $this->aktivitass = $this->rekenings = [];
        }

        if ($property === 'kegiatan_id') {
            $this->sub_kegiatans = \App\Models\SubKegiatan::where('kegiatan_id', $this->kegiatan_id)->get();
            $this->sub_kegiatan_id = $this->aktivitas_id = $this->rekening_id = null;
            $this->aktivitass = $this->rekenings = [];
        }

        if ($property === 'sub_kegiatan_id') {
            $this->aktivitass = \App\Models\AktivitasSubKegiatan::where('sub_kegiatan_id', $this->sub_kegiatan_id)->get();
            $this->aktivitas_id = $this->rekening_id = null;
            $this->rekenings = [];
        }

        if ($property === 'aktivitas_id') {
            $this->rekenings = \App\Models\UraianRekening::where('aktivitas_sub_kegiatan_id', $this->aktivitas_id)->get();
            $this->rekening_id = null;
        }
    }

    // === VENDOR ===
    public function toggleAddVendorForm()
    {
        $this->showAddVendorForm = !$this->showAddVendorForm;
    }

    public function selectSpecification($field, $value)
    {
        $this->specifications[$field] = $value;
        $this->suggestions[$field] = [];
    }

    public function addNewVendor()
    {
        $this->validate([
            'nama' => 'required',
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
        $this->updatedBarangId();
        $this->specifications = ['nama' => '', 'tipe' => '', 'ukuran' => ''];
        $this->specOptions = ['nama' => [], 'tipe' => [], 'ukuran' => []];
        $this->fetchSpesifikasiOptions();
    }

    public function updatedBarangId()
    {
        $this->specRenderKey = now()->timestamp;
        $this->specifications = ['nama' => '', 'tipe' => '', 'ukuran' => ''];
        $this->specOptions = ['nama' => [], 'tipe' => [], 'ukuran' => []];
        $this->fetchSpesifikasiOptions();
    }

    public function fetchSpesifikasiOptions()
    {
        if (!$this->barang_id)
            return;

        $this->specOptions['nama'] = MerkStok::where('barang_id', $this->barang_id)->pluck('nama')->unique()->values()->toArray();
        $this->specOptions['tipe'] = MerkStok::where('barang_id', $this->barang_id)->pluck('tipe')->unique()->values()->toArray();
        $this->specOptions['ukuran'] = MerkStok::where('barang_id', $this->barang_id)->pluck('ukuran')->unique()->values()->toArray();
    }

    public function addToList()
    {
        if (!$this->barang_id || !$this->newSatuan) {
            return;
        }

        $barang = \App\Models\BarangStok::find($this->barang_id);

        if (!$barang) {
            return;
        }

        $satuanId = $this->getOrCreateSatuan($this->newSatuan);

        if (is_null($barang->satuan_besar_id)) {
            $barang->satuan_besar_id = $satuanId;
            $barang->save();
        }

        $this->list[] = [
            'barang_id' => $barang->id,
            'barang' => $barang->nama,
            'specifications' => $this->specifications,
            'jumlah' => $this->jumlah,
            'jumlah_terkirim' => 0,
            'harga' => $this->newHarga,
            'ppn' => $this->newPpn,
            'satuan' => $this->newSatuan,
            'can_delete' => true,
        ];

        $this->reset(['newBarang', 'newSatuan', 'jumlah', 'newHarga', 'newPpn', 'specifications']);
        $this->calculateTotal();

        $this->dispatch('reset-harga-field');
    }

    public function getOrCreateSatuan($nama)
    {
        return SatuanBesar::firstOrCreate(['slug' => Str::slug($nama)], ['nama' => $nama])->id;
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
            'list' => 'required|array|min:1',
        ]);

        $kontrak = KontrakVendorStok::create([
            'vendor_id' => $this->vendor_id,
            'nomor_kontrak' => $this->nomor_spk_api,
            'tanggal_kontrak' => strtotime($this->tanggal_kontrak),
            'tanggal_akhir_kontrak' => strtotime($this->tanggal_akhir_kontrak),
            'metode_id' => $this->metode_id,
            'jenis_id' => $this->jenis_id,
            'user_id' => Auth::id(),
            'type' => 1,
            'status' => 1,
            'nominal_kontrak' => (int) str_replace('.', '', $this->nominal_kontrak),
            'is_adendum' => $this->isAdendum,
            'parent_kontrak_id' => $this->isAdendum ? $this->kontrakLama->id : null,

            // === FIELD TAMBAHAN ===
            'tahun_anggaran' => $this->tahun_anggaran,
            'dinas_sudin' => $this->dinas_sudin,
            'nama_bidang_seksi' => $this->nama_bidang_seksi,

            // === DARI API/MANUAL ===
            'program' => $this->program,
            'kegiatan' => $this->kegiatan,
            'sub_kegiatan' => $this->sub_kegiatan,
            'aktivitas_sub_kegiatan' => $this->aktivitas_sub_kegiatan,
            'rekening' => $this->rekening,

            'nama_paket' => $this->nama_paket,
            'nama_penyedia' => $this->nama_penyedia,
            'jenis_pengadaan' => $this->jenis_pengadaan,
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
    }

    protected function getKontrakChainIds($kontrak)
    {
        $ids = [$kontrak->id];

        while ($kontrak->is_adendum && $kontrak->parent_kontrak_id) {
            $kontrak = KontrakVendorStok::find($kontrak->parent_kontrak_id);
            if (!$kontrak)
                break;
            $ids[] = $kontrak->id;
        }

        return $ids;
    }

    public function render()
    {
        return view('livewire.create-kontrak-vendor');
    }
}
