<?php

namespace App\Livewire;

use App\Models\Stok;
use Livewire\Component;
use App\Models\PengirimanStok;
use App\Models\PermintaanMaterial;

class DataLogBarangMaterial extends Component
{
    public $sudin, $Rkb, $RKB, $isSeribu, $noteModalVisible, $selectedItemHistory, $list;

    public function mount()
    {
        $permintaan = PermintaanMaterial::whereHas('detailPermintaan', function ($detail) {
            return $detail->where('status', '>=', 2)->whereHas('user.unitKerja', function ($unit) {
                return
                    $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            });
        })->get()->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        })->map(function ($items, $tanggal) {
            return [
                'tanggal' => $tanggal,
                'uuid' => fake()->uuid,
                'jenis' => 0,
                'jumlah' => $items->sum('jumlah'),
            ];
        })
            ->values();
        $pengiriman = PengirimanStok::query()
            ->whereHas('detailPengirimanStok', function ($query) {
                $query->where('status', 1);
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function ($items, $tanggal) {
                return [
                    'tanggal' => $tanggal,
                    'uuid' => fake()->uuid,
                    'jenis' => 1,
                    'jumlah' => $items->sum('jumlah'),
                ];
            })
            ->values(); // reset key numerik


        $this->list = $permintaan->merge($pengiriman);
    }

    public function historyStok($id)
    {
        $stok = Stok::find($id);
        // dd($stok);

        // Ambil data dari model Pengiriman
        $pengiriman = PengirimanStok::query()
            ->where('merk_id', $stok->merk_id)
            ->where('lokasi_id', $stok->lokasi_id)
            ->when($stok->bagian_id, function ($query) use ($stok) {
                return $query->where('bagian_id', $stok->bagian_id);
            })
            ->when($stok->posisi_id, function ($query) use ($stok) {
                return $query->where('posisi_id', $stok->posisi_id);
            })
            ->whereHas('detailPengirimanStok', function ($query) {
                return $query->where('status', 1);
            })
            ->get();

        $pengiriman = $pengiriman->isNotEmpty() ? $pengiriman->map(function ($data) {
            return [
                'id' => $data->id,
                'merk_id' => $data->merk_id,
                'merk' => $data,
                'jumlah' => $data->jumlah,
                'tanggal' => $data->created_at->format('Y-m-d H:i:s'),
                'type' => 'in',
            ];
        }) : collect([]);








        // Ambil data dari model StokDisetujui
        $permintaan = PermintaanMaterial::where('merk_id', $stok->merk_id)
            ->whereHas('detailPermintaan', function ($detail) use ($stok) {
                return $detail->where('gudang_id', $stok->lokasi_id);
            })
            ->whereHas('detailPermintaan', function ($query) {
                return $query->where('status', '>=', 1);
            })
            ->get();
        $permintaan = $permintaan->isNotEmpty() ? $permintaan->map(function ($data) {
            return [
                'id' => $data->id,
                'merk_id' => $data->merk_id,
                'merk' => $data,
                'jumlah' => $data->jumlah,
                'tanggal' => $data->created_at->format('Y-m-d H:i:s'),
                'type' => 'out',
            ];
        }) : collect([]);
        // Ambil data dari model StokDisetujui
        $permintaanMaterialPending = PermintaanMaterial::where('merk_id', $stok->merk_id)
            ->whereHas('detailPermintaan', function ($detail) use ($stok) {
                return $detail->where('gudang_id', $stok->lokasi_id);
            })
            ->whereHas('detailPermintaan', function ($query) {
                return $query->whereNull('status');
            })
            ->get();
        $permintaanMaterialPending = $permintaanMaterialPending->isNotEmpty() ? $permintaanMaterialPending->map(function ($data) {
            return [
                'id' => $data->id,
                'merk_id' => $data->merk_id,
                'merk' => $data,
                'jumlah' => $data->jumlah,
                'tanggal' => $data->created_at->format('Y-m-d H:i:s'),
                'type' => 'pending',
            ];
        }) : collect([]);

        // Gabungkan data
        $history = $pengiriman->merge($permintaan)->merge($permintaanMaterialPending);


        // dd($history);
        $this->selectedItemHistory = $history;
        $this->noteModalVisible = true;
    }
    public function render()
    {
        return view('livewire.data-log-barang-material');
    }
}
