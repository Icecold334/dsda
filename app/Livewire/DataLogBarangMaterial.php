<?php

namespace App\Livewire;

use App\Models\Stok;
use Livewire\Component;
use App\Models\PengirimanStok;
use App\Models\PermintaanMaterial;
use Carbon\Carbon;

class DataLogBarangMaterial extends Component
{
    public $sudin, $Rkb, $RKB, $isSeribu, $noteModalVisible, $selectedItemHistory, $list;
    public $modalVisible = false;
    public $detailList = [];
    public $tanggalDipilih;
    public $jenisDipilih;
    public $filterFromDate;
    public $filterToDate;
    public $filterMonth;
    public $filterYear;


    public function mount()
    {
        $this->applyFilters();
    }

    public function updated($property)
    {
        $this->applyFilters();
    }

    public function applyFilters()
    {
        $permintaan = PermintaanMaterial::whereHas('detailPermintaan', function ($detail) {
            $detail->where('status', '>=', 2)
                ->whereHas('user.unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                });
        });
        if ($this->filterFromDate && $this->filterToDate) {
            $permintaan->whereBetween('created_at', [$this->filterFromDate, $this->filterToDate]);
        } elseif ($this->filterMonth && $this->filterYear) {
            $permintaan->whereMonth('created_at', $this->filterMonth)
                ->whereYear('created_at', $this->filterYear);
        } elseif ($this->filterYear) {
            $permintaan->whereYear('created_at', $this->filterYear);
        }

        $permintaan = $permintaan->get()
            ->groupBy(fn($item) => $item->created_at->format('Y-m-d'))
            ->map(fn($items, $tanggal) => [
                'tanggal' => $tanggal,
                'uuid' => fake()->uuid,
                'jenis' => 0,
                'jumlah' => $items->sum('jumlah'),
            ])
            ->values();

        $pengiriman = PengirimanStok::whereHas('detailPengirimanStok', fn($q) => $q->where('status', 1));

        if ($this->filterFromDate && $this->filterToDate) {
            $pengiriman->whereBetween('created_at', [$this->filterFromDate, $this->filterToDate]);
        } elseif ($this->filterMonth && $this->filterYear) {
            $pengiriman->whereMonth('created_at', $this->filterMonth)
                ->whereYear('created_at', $this->filterYear);
        } elseif ($this->filterYear) {
            $pengiriman->whereYear('created_at', $this->filterYear);
        }

        $pengiriman = $pengiriman->get()
            ->groupBy(fn($item) => $item->created_at->format('Y-m-d'))
            ->map(fn($items, $tanggal) => [
                'tanggal' => $tanggal,
                'uuid' => fake()->uuid,
                'jenis' => 1,
                'jumlah' => $items->sum('jumlah'),
            ])
            ->values();

        $this->list = $permintaan->merge($pengiriman);
    }
    public function resetFilters()
    {
        $this->filterFromDate = null;
        $this->filterToDate = null;
        $this->filterMonth = null;
        $this->filterYear = null;

        $this->applyFilters();
    }
    public function selectedTanggal($tanggal = null, $jenis = null)
    {
        // dd($tanggal);
        $this->modalVisible = true;
        $this->tanggalDipilih = Carbon::parse($tanggal)->translatedFormat('l, d F Y');
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
