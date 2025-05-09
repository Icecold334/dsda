<?php

namespace App\Livewire;

use App\Models\Stok;
use Livewire\Component;
use App\Models\PengirimanStok;
use App\Models\PermintaanMaterial;

class DataLogBarangMaterial extends Component
{
    public $sudin, $Rkb, $RKB, $isSeribu, $noteModalVisible, $selectedItemHistory, $list;
    public $modalVisible = false;
    public $detailList = [];
    public $tanggalDipilih;
    public $jenisDipilih;

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

    public function selectedTanggal($tanggal, $jenis)
    {
        $this->modalVisible = true;
        $this->tanggalDipilih = $tanggal;
        $this->jenisDipilih = $jenis;

        if ($jenis == 0) {
            // KELUAR = permintaan material
            $this->detailList = PermintaanMaterial::whereDate('created_at', $tanggal)
                ->whereHas('detailPermintaan', function ($query) {
                    $query->where('status', '>=', 2)
                        ->whereHas('user.unitKerja', function ($unit) {
                            $unit->where('parent_id', $this->unit_id)
                                ->orWhere('id', $this->unit_id);
                        });
                })
                ->get();
        } else {
            // MASUK = pengiriman
            $this->detailList = PengirimanStok::whereDate('created_at', $tanggal)
                ->whereHas('detailPengirimanStok', function ($query) {
                    $query->where('status', 1);
                })
                ->get();
        }
    }
    public function render()
    {
        return view('livewire.data-log-barang-material');
    }
}
