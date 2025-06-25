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
        $this->unit_id = auth()->user()->unit_id;
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
        $this->pemasukanList = TransaksiStok::selectRaw('
                merk_stok.barang_id,
                transaksi_stok.lokasi_id,
                transaksi_stok.tanggal,
                SUM(transaksi_stok.jumlah) as total
            ')
            ->join('merk_stok', 'transaksi_stok.merk_id', '=', 'merk_stok.id')
            ->join('lokasi_stok', 'transaksi_stok.lokasi_id', '=', 'lokasi_stok.id')
            ->join('unit_kerja', 'lokasi_stok.unit_id', '=', 'unit_kerja.id')
            ->where('transaksi_stok.tipe', 'Pemasukan')
            ->where(function ($q) {
                $q->where('unit_kerja.parent_id', $this->unit_id)
                    ->orWhere('unit_kerja.id', $this->unit_id);
            })
            ->groupBy('merk_stok.barang_id', 'transaksi_stok.lokasi_id', 'transaksi_stok.tanggal')
            ->orderByDesc('transaksi_stok.tanggal')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $barang = BarangStok::with('satuanBesar')->find($row->barang_id);
                $gudang = \App\Models\LokasiStok::find($row->lokasi_id);
                return (object)[
                    'nama' => $barang->nama ?? '-',
                    'satuan' => $barang->satuanBesar->nama ?? '',
                    'jumlah' => $row->total,
                    'nama_gudang' => $gudang->nama ?? '-',
                    'tanggal' => Carbon::parse($row->tanggal)->translatedFormat('d M Y '),
                ];
            });
    }




    public function preparePengeluaran()
    {
        $permintaan = PermintaanMaterial::with(['detailPermintaan', 'merkStok.barangStok.satuanBesar'])
            ->whereHas('detailPermintaan', function ($detail) {
                $detail->where('status', '>=', 2)
                    ->whereHas('user.unitKerja', function ($unit) {
                        $unit->where('parent_id', $this->unit_id)
                            ->orWhere('id', $this->unit_id);
                    });
            })
            ->latest()
            ->limit(10)
            ->get();

        $this->pengeluaranList = $permintaan
            ->map(function ($permintaan) {
                $detail = $permintaan->detailPermintaan;
                $barang = optional(optional($permintaan->merkStok)->barangStok);
                $gudang = \App\Models\LokasiStok::find($detail->gudang_id ?? null);

                return [
                    'barang_id' => $barang->id ?? null,
                    'nama' => $barang->nama ?? '-',
                    'satuan' => $barang->satuanBesar->nama ?? '',
                    'jumlah' => $permintaan->jumlah ?? 0,
                    'nama_gudang' => $gudang->nama ?? '-',
                    'tanggal' => \Carbon\Carbon::parse($permintaan->created_at)->translatedFormat('d M Y '),
                ];
            })
            ->groupBy(fn($item) => $item['barang_id'] . '-' . $item['nama_gudang'])
            ->map(function ($items) {
                $first = collect($items)->first();
                return (object)[
                    'nama' => $first['nama'],
                    'satuan' => $first['satuan'],
                    'jumlah' => collect($items)->sum('jumlah'),
                    'nama_gudang' => $first['nama_gudang'],
                    'tanggal' => $first['tanggal'],
                ];
            })
            ->values();
    }








    public function prepareStokMenipis()
    {
        $data = TransaksiStok::selectRaw('
                merk_stok.barang_id,
                transaksi_stok.lokasi_id,
                SUM(CASE WHEN transaksi_stok.tipe = "Pemasukan" THEN jumlah ELSE 0 END) as masuk,
                SUM(CASE WHEN transaksi_stok.tipe = "Pengeluaran" THEN jumlah ELSE 0 END) as keluar
            ')
            ->join('merk_stok', 'transaksi_stok.merk_id', '=', 'merk_stok.id')
            ->join('lokasi_stok', 'transaksi_stok.lokasi_id', '=', 'lokasi_stok.id')
            ->join('unit_kerja', 'lokasi_stok.unit_id', '=', 'unit_kerja.id')
            ->where(function ($q) {
                $q->where('unit_kerja.parent_id', $this->unit_id)
                    ->orWhere('unit_kerja.id', $this->unit_id);
            })
            ->groupBy('merk_stok.barang_id', 'transaksi_stok.lokasi_id')
            ->get()
            ->map(function ($row) {
                $row->stok = $row->masuk - $row->keluar;
                return $row;
            });


        $barangList = BarangStok::whereIn('id', $data->pluck('barang_id'))->get()->keyBy('id');
        $gudangList = \App\Models\LokasiStok::whereIn('id', $data->pluck('lokasi_id'))->get()->keyBy('id');

        $this->stokMenipisList = $data->filter(function ($row) use ($barangList) {
            $barang = $barangList[$row->barang_id] ?? null;

            // Jangan tampilkan jika minimal = 0 (anggap tidak perlu warning)
            if (!$barang || $barang->minimal === 0) {
                return false;
            }

            return $row->stok <= $barang->minimal;
        })->map(function ($row) use ($barangList, $gudangList) {
            $barang = $barangList[$row->barang_id];
            $gudang = $gudangList[$row->lokasi_id] ?? null;

            return (object)[
                'nama_gudang' => $gudang->nama ?? '-',
                'barang' => $barang->nama,
                'stok' => $row->stok,
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
