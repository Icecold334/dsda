<?php

namespace App\Livewire;

use App\Models\LokasiStok;
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

        // Konversi filter tanggal ke timestamp untuk dicocokkan
        if ($this->filterFromDate && $this->filterToDate) {
            $from = strtotime($this->filterFromDate);
            $to = strtotime($this->filterToDate . ' 23:59:59');
            $permintaan->whereHas(
                'detailPermintaan',
                fn($q) =>
                $q->whereBetween('tanggal_permintaan', [$from, $to])
            );
        } elseif ($this->filterMonth && $this->filterYear) {
            $start = strtotime("{$this->filterYear}-{$this->filterMonth}-01");
            $end = strtotime(date("Y-m-t", $start) . ' 23:59:59');
            $permintaan->whereHas(
                'detailPermintaan',
                fn($q) =>
                $q->whereBetween('tanggal_permintaan', [$start, $end])
            );
        } elseif ($this->filterYear) {
            $start = strtotime("{$this->filterYear}-01-01");
            $end = strtotime("{$this->filterYear}-12-31 23:59:59");
            $permintaan->whereHas(
                'detailPermintaan',
                fn($q) =>
                $q->whereBetween('tanggal_permintaan', [$start, $end])
            );
        }

        $permintaan = $permintaan->get()
            ->groupBy(function ($item) {
                $timestamp = optional($item->detailPermintaan->first())->tanggal_permintaan;
                return $timestamp ? Carbon::createFromTimestamp($timestamp)->format('Y-m-d') . '|' . $item->detailPermintaan->gudang_id : null;
            })
            ->map(function ($items, $key) {
                [$tanggal, $gudang_id] = explode('|', $key);
                return [
                    'tanggal' => $tanggal,
                    'uuid' => fake()->uuid,
                    'jenis' => 0,
                    'gudang_id' => $gudang_id,
                    'gudang_nama' => LokasiStok::find($gudang_id)->nama,
                    'jumlah' => $items->sum('jumlah'),
                ];
            })
            ->values();

        $pengiriman = PengirimanStok::whereHas('detailPengirimanStok', fn($q) => $q->where('status', 1));

        if ($this->filterFromDate && $this->filterToDate) {
            $pengiriman->whereBetween('tanggal', [$this->filterFromDate, $this->filterToDate]);
        } elseif ($this->filterMonth && $this->filterYear) {
            $pengiriman->whereMonth('tanggal', $this->filterMonth)
                ->whereYear('tanggal', $this->filterYear);
        } elseif ($this->filterYear) {
            $pengiriman->whereYear('tanggal', $this->filterYear);
        }

        $pengiriman = $pengiriman->get()
            ->groupBy(function ($item) {
                $timestamp = optional($item->detailPengirimanStok->first())->tanggal;

                return $timestamp ? Carbon::createFromTimestamp($timestamp)->format('Y-m-d') . '|' . $item->lokasi_id : null;
            })
            ->map(function ($items, $key) {
                [$tanggal, $lokasi_id] = explode('|', $key);
                return [
                    'tanggal' => $tanggal,
                    'uuid' => fake()->uuid,
                    'jenis' => 1,
                    'gudang_id' => $lokasi_id,
                    'gudang_nama' => LokasiStok::find($lokasi_id)->nama,
                    'jumlah' => $items->sum('jumlah'),
                ];
            })
            ->values();

        $this->list = collect($permintaan)->merge($pengiriman)->sortByDesc('tanggal')->values();
    }

    public function resetFilters()
    {
        $this->filterFromDate = null;
        $this->filterToDate = null;
        $this->filterMonth = null;
        $this->filterYear = null;

        $this->applyFilters();
    }
    public function selectedTanggal($tanggal = null, $jenis = null, $gudangId = null)
    {
        $this->modalVisible = true;
        $this->tanggalDipilih = Carbon::parse($tanggal)->translatedFormat('l, d F Y');
        $this->jenisDipilih = $jenis;
        // dd($tanggal);

        if ($jenis == 0) {
            $this->detailList = PermintaanMaterial::whereHas('detailPermintaan', function ($dp) use ($gudangId) {
                return $dp->where('gudang_id', $gudangId);
            })
                ->whereHas('detailPermintaan', function ($query) {
                    $query->where('status', '>=', 2)
                        ->whereHas('user.unitKerja', function ($unit) {
                            $unit->where('parent_id', $this->unit_id)
                                ->orWhere('id', $this->unit_id);
                        });
                })
                ->get();
        } else {
            $this->detailList = PengirimanStok::whereHas('detailPengirimanStok', function ($dp) use ($gudangId) {
                return $dp->where('lokasi_id', $gudangId);
            })
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
