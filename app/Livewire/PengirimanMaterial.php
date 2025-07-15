<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LokasiStok;
use App\Models\MerkStok;
use App\Models\BagianStok;
use App\Models\PosisiStok;
use App\Models\ListKontrakStok;
use App\Models\KontrakVendorStok;
use App\Models\PengirimanStok;
use App\Models\DetailPengirimanStok;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class PengirimanMaterial extends Component
{
    public $nomor_kontrak, $kontrak_id, $gudang_id, $listGudang = [], $kontrak;
    public $barangs = [], $merks = [], $bagians = [], $posisis = [];
    public $newBarangId, $newMerkId, $newJumlah, $newBagianId, $newPosisiId;
    public $maxJumlah = 0, $list = [];
    public $tanggal_pengiriman;


    #[On('kontrakId')]
    public function setKontrakId($kontrakId)
    {
        $id = $kontrakId;
        // $this->kontrak_id = $id;
        // dd($this->kontrak_id);

        $list = ListKontrakStok::with('merkStok.barangStok')
            ->where('kontrak_id', $id)->get();

        $this->barangs = $list->pluck('merkStok.barangStok')->unique('id')->values();
    }

    #[On('gudangId')]
    public function setGudangId($id)
    {
        $this->gudang_id = $id;
        $this->bagians = BagianStok::where('lokasi_id', $id)->get();
    }

    public function updatedNomorKontrak($value)
    {
        $this->kontrak = null;

        if (!$value) return;

        // Ambil kontrak terakhir (paling baru) yang punya nomor kontrak sama
        $kontrak = KontrakVendorStok::with(['vendorStok', 'metodePengadaan'])
            ->where('nomor_kontrak', $value)
            ->orderByDesc('id') // diasumsikan adendum selalu punya ID lebih besar
            ->first();

        // Cek jika kontrak ini adalah kontrak utama dan sudah memiliki adendum, maka tolak
        if ($kontrak && !$kontrak->is_adendum && $kontrak->adendums()->exists()) {
            // Reset karena kontrak usang
            $this->reset('kontrak');
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Nomor kontrak ini sudah memiliki adendum. Gunakan nomor dari adendum terakhir.'
            ]);
            return;
        }
        // dd($kontrak);
        // Jika kontrak valid, tetapkan
        if ($kontrak) {
            $this->kontrak = $kontrak;
            $this->tanggal_pengiriman = now()->format('Y-m-d'); // default hari ini
            // $this->dispatch('kontrakId', kontrakId: $this->kontrak->id);
            $this->dispatch('kontrakId', kontrakId: $kontrak->id);
        }
    }



    // public function updatedGudangId()
    // {
    //     $this->dispatch('gudangId', gudangId: $this->gudang_id);
    // }

    public function updatedNewBarangId()
    {
        $this->newMerkId = null;
        $this->maxJumlah = 0;
        // dd($this->kontrak);
        $list = ListKontrakStok::with('merkStok')
            ->where('kontrak_id', $this->kontrak->id)->get();

        $this->merks = $list->pluck('merkStok')
            ->filter(fn($merk) => $merk && $merk->barang_id == $this->newBarangId)
            ->unique('id')->values();
    }

    public function updatedNewBagianId()
    {
        $this->posisis = PosisiStok::where('bagian_id', $this->newBagianId)->get();
    }

    public function updatedNewMerkId()
    {
        if (!$this->kontrak->id || !$this->newMerkId) {
            $this->maxJumlah = 0;
            return;
        }

        // Ambil kontrak aktif
        $currentKontrak = KontrakVendorStok::find($this->kontrak->id);

        // Dapatkan parent kontrak (induk) jika ini adendum, atau kontrak itu sendiri
        $parentId = $currentKontrak->parent_kontrak_id ?: $currentKontrak->id;

        // Ambil semua ID kontrak terkait (induk + adendum)
        $kontrakIds = KontrakVendorStok::where('id', $parentId)
            ->orWhere('parent_kontrak_id', $parentId)
            ->pluck('id');

        // Total yang sudah dikirim
        $jumlahTerkirim = \App\Models\PengirimanStok::whereIn('kontrak_id', $kontrakIds)
            ->where('merk_id', $this->newMerkId)
            ->sum('jumlah');

        // Jumlah maksimal berdasarkan kontrak saat ini
        $jumlahKontrak = \App\Models\ListKontrakStok::where('kontrak_id', $this->kontrak->id)
            ->where('merk_id', $this->newMerkId)
            ->value('jumlah') ?? 0;

        // Hitung sisa jumlah yang masih bisa dikirim
        $this->maxJumlah = max(0, $jumlahKontrak - $jumlahTerkirim);
    }


    public function addToList()
    {
        $this->validate([
            'newBarangId' => 'required',
            'newMerkId' => 'required',
            'newJumlah' => 'required|numeric|min:1|max:' . $this->maxJumlah,
        ]);

        $this->list[] = [
            'merk' => MerkStok::with('barangStok')->find($this->newMerkId),
            'jumlah' => $this->newJumlah,
            'lokasi_id' => $this->gudang_id,
            'bagian_id' => $this->newBagianId,
            'posisi_id' => $this->newPosisiId,
        ];

        $this->reset(['newBarangId', 'newMerkId', 'newJumlah', 'newBagianId', 'newPosisiId', 'maxJumlah']);
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);
    }

    public function save()
    {
        $this->validate([
            // 'kontrak_id' => 'required',
            'gudang_id' => 'required',
            'tanggal_pengiriman' => 'required|date',
        ]);

        $timestamp = strtotime($this->tanggal_pengiriman);

        $detailPengiriman = \App\Models\DetailPengirimanStok::create([
            'kode_pengiriman_stok' => fake()->bothify('KP#######'),
            'kontrak_id' => $this->kontrak->id,
            'gudang_id' => $this->gudang_id,
            'tanggal' => $timestamp,
            'user_id' => Auth::id(),
        ]);

        foreach ($this->list as $item) {
            \App\Models\PengirimanStok::create([
                'detail_pengiriman_id' => $detailPengiriman->id,
                'kontrak_id' => $this->kontrak->id,
                'merk_id' => $item['merk']->id,
                'jumlah' => $item['jumlah'],
                'tanggal_pengiriman' => $timestamp,
                'lokasi_id' => $item['lokasi_id'],
                'bagian_id' => $item['bagian_id'] ?? null,
                'posisi_id' => $item['posisi_id'] ?? null,
            ]);
        }

        $this->reset([
            'list',
            'newBarangId',
            'newMerkId',
            'newJumlah',
            'newBagianId',
            'newPosisiId',
            'tanggal_pengiriman'
        ]);

        $this->dispatch('saveDoc', id: $detailPengiriman->id);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Pengiriman berhasil disimpan.']);
    }


    public function mount()
    {
        $this->listGudang = LokasiStok::where('unit_id', Auth::user()->unit_id)->get();
    }

    public function render()
    {
        return view('livewire.pengiriman-material');
    }
}
