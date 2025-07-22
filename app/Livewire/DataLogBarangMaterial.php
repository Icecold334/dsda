<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use App\Models\TransaksiStok;
use App\Models\DetailPermintaanMaterial;
use App\Models\DetailPengirimanStok;
use App\Models\PermintaanMaterial;
use App\Models\PengirimanStok;

class DataLogBarangMaterial extends Component
{
    use WithPagination;

    public $sudin, $Rkb, $RKB, $isSeribu;
    public $noteModalVisible, $selectedItemHistory, $list, $withRab;
    public $modalVisible = false;
    public $detailList = [], $dataSelected;
    public $tanggalDipilih, $jenisDipilih;
    public $filterFromDate, $filterToDate, $filterMonth, $filterYear, $filterJenis;
    public $perPage = 5, $page = 1;

    public function mount()
    {
        $this->applyFilters();
    }

    public function updating($field)
    {
        if (in_array($field, ['filterFromDate', 'filterToDate', 'filterMonth', 'filterYear', 'filterJenis'])) {
            $this->resetPage();
        }
    }

    public function updated($property)
    {
        $this->applyFilters();
    }

    public function applyFilters()
    {
        // dd($this->unit_id);
        $transaksi = TransaksiStok::with([
            'pengirimanStok.detailPengirimanStok',
            'permintaanMaterial.detailPermintaan',
            'lokasiStok'
        ])
            ->whereHas('lokasiStok.unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            })
            ->when($this->filterFromDate && $this->filterToDate, function ($q) {
                $q->whereBetween('tanggal', [$this->filterFromDate, $this->filterToDate]);
            })
            ->when($this->filterMonth && $this->filterYear, function ($q) {
                $start = "{$this->filterYear}-{$this->filterMonth}-01";
                $end = date("Y-m-t", strtotime($start));
                $q->whereBetween('tanggal', [$start, $end]);
            })
            ->when($this->filterYear, function ($q) {
                $start = "{$this->filterYear}-01-01";
                $end = "{$this->filterYear}-12-31";
                $q->whereBetween('tanggal', [$start, $end]);
            })
            ->get();

        $list = $transaksi
            ->filter(function ($trx) {
                if ($trx->tipe === 'Pemasukan') {
                    // return $trx->pengirimanStok?->detailPengirimanStok?->status === 1;
                    return true;
                } elseif ($trx->tipe === 'Pengeluaran') {
                    // return $trx->permintaanMaterial?->detailPermintaan?->status === 2;
                    return true;
                } elseif ($trx->tipe === 'Penyesuaian') {
                    return true;
                }
                return false;
            })
            ->groupBy(function ($trx) {
                $datetime = Carbon::parse($trx->created_at)->format('Y-m-d H:i');
                return "{$datetime}|{$trx->lokasi_id}|{$trx->tipe}";
            })
            ->map(function ($items, $key) {
                [$datetime, $gudang_id, $tipe] = explode('|', $key);
                $tanggal = Carbon::parse($datetime)->format('Y-m-d');

                return [
                    'datetime' => $datetime,
                    'tanggal' => $tanggal,
                    'gudang_id' => $gudang_id,
                    'gudang_nama' => optional($items->first()?->lokasiStok)->nama ?? '-',
                    'jenis' => match ($tipe) {
                        'Pemasukan' => 1,
                        'Pengeluaran' => 0,
                        'Penyesuaian' => 2,
                    },
                    'volume' => $items->sum(fn($i) => (int) $i->jumlah),
                    'list' => $items->values(),
                    'uuid' => fake()->uuid,
                ];
            })->sortByDesc('datetime')->values();


        if ($this->filterJenis !== null && $this->filterJenis !== '') {
            $list = $list->filter(fn($item) => $item['jenis'] == $this->filterJenis);
        }

        $this->list = $list->forPage($this->page, $this->perPage)->values();
    }

    public function resetFilters()
    {
        $this->filterFromDate = null;
        $this->filterToDate = null;
        $this->filterMonth = null;
        $this->filterJenis = null;
        $this->filterYear = null;

        $this->applyFilters();
    }

    public function selectedTanggal($tanggal, $jenis, $gudangId)
    {
        $this->modalVisible = true;
        $this->tanggalDipilih = Carbon::parse($tanggal)->translatedFormat('l, d F Y');
        $this->jenisDipilih = $jenis;

        $this->detailList = $this->list->firstWhere(
            fn($item) =>
            $item['tanggal'] === $tanggal &&
                $item['jenis'] == $jenis &&
                $item['gudang_id'] == $gudangId
        )['list'] ?? collect();

        $this->dataSelected = collect($this->detailList)->first();
    }

    public function render()
    {
        return view('livewire.data-log-barang-material');
    }
}
