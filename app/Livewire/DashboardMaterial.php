<?php

namespace App\Livewire;

use App\Models\BarangStok;
use App\Models\PermintaanMaterial;
use App\Models\TransaksiStok;
use Livewire\Component;
use Illuminate\Support\Carbon;

class DashboardMaterial extends Component
{
    public $pemasukanList = [];
    public $pengeluaranList = [];
    public $stokMenipisList = [];
    public $permintaanTerbaru = [];
    public $unit_id;
    public $filterDate;

    public function mount()
    {
        $this->unit_id = auth()->user()->unit_kerja_id;
        $this->filterDate = now()->format('Y-m-d'); // default: hari ini

        $this->preparePemasukan();
        $this->preparePengeluaran();

        $this->prepareStokMenipis();
        $this->preparePermintaanTerbaru();
    }

    public function updatedFilterDate()
    {
        $this->preparePemasukan();
        $this->preparePengeluaran();
    }

    public function preparePemasukan()
    {
        $tanggal = $this->filterDate;

        $this->pemasukanList = TransaksiStok::selectRaw('merk_stok.barang_id, SUM(transaksi_stok.jumlah) as total')
            ->join('merk_stok', 'transaksi_stok.merk_id', '=', 'merk_stok.id')
            ->join('lokasi_stok', 'transaksi_stok.lokasi_id', '=', 'lokasi_stok.id')
            ->join('unit_kerja', 'lokasi_stok.unit_id', '=', 'unit_kerja.id')
            ->where('transaksi_stok.tipe', 'Pemasukan')
            ->whereRaw("strftime('%Y-%m-%d', transaksi_stok.tanggal) = ?", [$tanggal]) // 👈 FIX
            ->where(function ($q) {
                $q->where('unit_kerja.parent_id', $this->unit_id)
                    ->orWhere('unit_kerja.id', $this->unit_id);
            })
            ->groupBy('merk_stok.barang_id')
            ->get()
            ->map(function ($row) {
                $barang = BarangStok::with('satuanBesar')->find($row->barang_id);
                return (object)[
                    'nama' => $barang->nama ?? '-',
                    'satuan' => $barang->satuanBesar->nama ?? '',
                    'jumlah' => $row->total,
                    'tanggal' => \Carbon\Carbon::parse($this->filterDate)->translatedFormat('d M Y'),
                ];
            });
    }


    public function preparePengeluaran()
    {
        $tanggal = $this->filterDate;
        $timestampStart = strtotime($tanggal . ' 00:00:00');
        $timestampEnd = strtotime($tanggal . ' 23:59:59');

        $permintaan = PermintaanMaterial::whereHas('detailPermintaan', function ($detail) use ($timestampStart, $timestampEnd) {
            $detail->where('status', '>=', 2)
                ->whereBetween('tanggal_permintaan', [$timestampStart, $timestampEnd])
                ->whereHas('user.unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                });
        })
            // ->with('detailPermintaan.merk.barang.satuanBesar')
            ->get();

        $this->pengeluaranList = $permintaan
            ->map(function ($permintaan) {
                $detail = $permintaan->detailPermintaan;
                $barang = optional(optional($permintaan->merkStok)->barangStok);
                return [
                    'barang_id' => $barang->id,
                    'nama' => $barang->nama ?? '-',
                    'satuan' => $barang->satuanBesar->nama ?? '',
                    'jumlah' => $permintaan->jumlah ?? 0,
                ];
            })
            ->groupBy('barang_id')
            ->map(function ($items) {
                $first = $items->first();
                return (object)[
                    'nama' => $first['nama'],
                    'satuan' => $first['satuan'],
                    'jumlah' => collect($items)->sum('jumlah'),
                    'tanggal' => \Carbon\Carbon::parse($this->filterDate)->translatedFormat('d M Y'),
                ];
            })
            ->values();
    }






    public function prepareStokMenipis()
    {
        $data = TransaksiStok::selectRaw('merk_stok.barang_id,
                SUM(CASE WHEN transaksi_stok.tipe = "Pemasukan" THEN jumlah ELSE 0 END) as masuk,
                SUM(CASE WHEN transaksi_stok.tipe = "Pengeluaran" THEN jumlah ELSE 0 END) as keluar')
            ->join('merk_stok', 'transaksi_stok.merk_id', '=', 'merk_stok.id')
            ->join('users', 'transaksi_stok.user_id', '=', 'users.id')
            ->join('unit_kerja', 'users.unit_id', '=', 'unit_kerja.id')
            ->where(function ($q) {
                $q->where('unit_kerja.parent_id', $this->unit_id)
                    ->orWhere('unit_kerja.id', $this->unit_id);
            })
            ->groupBy('merk_stok.barang_id')
            ->get()
            ->map(function ($row) {
                $row->stok = $row->masuk - $row->keluar;
                return $row;
            });

        $barangs = BarangStok::whereIn('id', $data->pluck('barang_id'))->get()->keyBy('id');

        $this->stokMenipisList = $data->filter(function ($row) use ($barangs) {
            $barang = $barangs[$row->barang_id] ?? null;
            return $barang && $row->stok < $barang->minimal;
        })->map(function ($row) use ($barangs) {
            $barang = $barangs[$row->barang_id];
            return (object)[
                'nama' => $barang->nama,
                'stok' => $row->stok,
                'minimal' => $barang->minimal,
            ];
        })->values();
    }

    public function preparePermintaanTerbaru()
    {
        $statusMap = [
            null => ['label' => 'Diproses', 'color' => 'warning'],
            0 => ['label' => 'Ditolak', 'color' => 'danger'],
            1 => ['label' => 'Disetujui', 'color' => 'success'],
            2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
            3 => ['label' => 'Selesai', 'color' => 'primary'],
        ];

        $this->permintaanTerbaru = \App\Models\DetailPermintaanMaterial::with(['permintaanMaterial.user', 'lokasiStok.unitKerja'])
            ->whereHas('user.unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })
            ->whereHas('permintaanMaterial')
            ->orderByDesc(
                \DB::raw('(SELECT created_at FROM permintaan_material WHERE permintaan_material.detail_permintaan_id = detail_permintaan_material.id)')
            )
            ->take(10)
            ->get()
            ->map(function ($detail) use ($statusMap) {
                $permintaan = $detail;
                $status = $detail->status;
                return (object)[
                    'id' => $permintaan->id,
                    'nodin' => $detail->nodin,
                    'user' => $detail->user ?? '-',
                    'created_at' => $detail->created_at,
                    'status_label' => $statusMap[$status]['label'] ?? 'Tidak Diketahui',
                    'status_color' => $statusMap[$status]['color'] ?? 'secondary',
                ];
            });
    }


    public function render()
    {
        return view('livewire.dashboard-material');
    }
}
