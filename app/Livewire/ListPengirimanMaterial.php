<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ListPengirimanMaterial extends Component
{
    public $kontrak_id;
    public $gudang_id;

    public $barangs = [];
    public $merks = [];
    public $bagians = [];
    public $posisis = [];

    public $newBarangId, $newMerkId, $newJumlah, $newBagianId, $newPosisiId, $saluran_jenis, $saluran_id;
    public $maxJumlah = 0;
    public $list = [];
    #[On('kontrakId')]
    public function setKontrakId($kontrakId)
    {
        $this->kontrak_id = $kontrakId;

        $list = \App\Models\ListKontrakStok::with('merkStok.barangStok')
            ->where('kontrak_id', $kontrakId)
            ->get();

        $this->barangs = $list
            ->pluck('merkStok.barangStok')
            ->filter()
            ->unique('id')
            ->values();
    }


    #[On('gudangId')]
    public function setGudangId($gudangId)
    {
        $this->gudang_id = $gudangId;
        $this->bagians = \App\Models\BagianStok::where('lokasi_id', $gudangId)->get();
    }


    public function updatedNewBarangId()
    {
        $list = \App\Models\ListKontrakStok::with('merkStok')
            ->where('kontrak_id', $this->kontrak_id)
            ->get();

        $this->merks = $list->pluck('merkStok')
            ->filter(fn($merk) => $merk && $merk->barang_id == $this->newBarangId)
            ->unique('id')
            ->values();
    }


    public function updatedNewBagianId()
    {
        $this->posisis = \App\Models\PosisiStok::where('bagian_id', $this->newBagianId)->get();
    }

    public function updatedNewMerkId()
    {
        $this->maxJumlah = \App\Models\PengirimanStok::query()
            ->where('kontrak_id', $this->kontrak_id)
            ->where('merk_id', $this->newMerkId)
            ->sum('jumlah');

        $jumlahKontrak = \App\Models\ListKontrakStok::where('kontrak_id', $this->kontrak_id)
            ->where('merk_id', $this->newMerkId)
            ->value('jumlah');

        $this->maxJumlah = max(0, $jumlahKontrak - $this->maxJumlah);
    }

    public function addToList()
    {
        $this->validate([
            'newBarangId' => 'required',
            'newMerkId' => 'required',
            'newJumlah' => 'required|numeric|min:1|max:' . $this->maxJumlah,
        ]);

        $this->list[] = [
            'merk' => \App\Models\MerkStok::find($this->newMerkId),
            'jumlah' => $this->newJumlah,
            'lokasi_id' => $this->gudang_id,
            'bagian_id' => $this->newBagianId,
            'posisi_id' => $this->newPosisiId,
        ];

        $this->reset(['newBarangId', 'newMerkId', 'newJumlah', 'newBagianId', 'newPosisiId']);
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list); // reset index agar rapi
    }
    public function save()
    {
        $this->validate([
            'kontrak_id' => 'required',
            'gudang_id' => 'required',
        ]);

        $detailPengiriman = \App\Models\DetailPengirimanStok::create([
            'kode_pengiriman_stok' => fake()->bothify('KP#######'),
            'kontrak_id' => $this->kontrak_id,
            'gudang_id' => $this->gudang_id,
            'tanggal' => strtotime(now()),
            'user_id' => Auth::id(),
        ]);

        foreach ($this->list as $item) {
            \App\Models\PengirimanStok::create([
                'detail_pengiriman_id' => $detailPengiriman->id,
                'kontrak_id' => $this->kontrak_id,
                'merk_id' => $item['merk']->id,
                'jumlah' => $item['jumlah'],
                'tanggal_pengiriman' => strtotime(now()),
                'lokasi_id' => $item['lokasi_id'],
                'bagian_id' => $item['bagian_id'] ?? null,
                'posisi_id' => $item['posisi_id'] ?? null,
            ]);
        }

        $this->reset(['list', 'newBarangId', 'newMerkId', 'newJumlah', 'newBagianId', 'newPosisiId']);
        $this->dispatch('saveDoc', id: $detailPengiriman->id);

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Pengiriman berhasil disimpan.']);
    }

    public function render()
    {
        return view('livewire.list-pengiriman-material');
    }
}
