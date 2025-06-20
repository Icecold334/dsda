<?php

namespace App\Livewire;

use App\Models\BarangStok;
use App\Models\PermintaanMaterial;
use App\Models\TransaksiStok;
use Livewire\Component;

class DashboardMaterial extends Component
{
    public $keluarMasukList = [];
    public $stokMenipisList = [];
    public $permintaanTerbaru = [];
    public $unit_id;

    public function mount()
    {
        // $this->unit_id = auth()->user()->unitKerja_;
        $this->prepareKeluarMasuk();
        $this->prepareStokMenipis();
        $this->preparePermintaanTerbaru();
    }

    public function prepareKeluarMasuk()
    {
        $this->keluarMasukList = TransaksiStok::with(['merkStok.barangStok', 'lokasiStok.unitKerja'])
            ->whereDate('tanggal', '>=', now()->subDays(30))
            ->where(function ($query) {
                $query->where('tipe', 'Pemasukan')
                    ->orWhere(function ($q) {
                        $q->where('tipe', 'Pengeluaran');
                        // ->whereHas('permintaanMaterial.detailPermintaan', function ($detail) {
                        //     $detail->where('status', '>=', 2);
                        // });
                    });
            })
            ->whereHas('lokasiStok.unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })
            ->orderByDesc('tanggal')
            ->limit(15)
            ->get();
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

        $this->permintaanTerbaru = PermintaanMaterial::with('detailPermintaan.lokasiStok.unitKerja')
            ->whereHas('detailPermintaan.lokasiStok.unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($item) use ($statusMap) {
                $status = $item->detailPermintaan->status;
                // dd($status);
                $item->status_label = $statusMap[$status]['label'] ?? 'Tidak Diketahui';
                $item->status_color = $statusMap[$status]['color'] ?? 'secondary';
                return $item;
            });
    }

    public function render()
    {
        return view('livewire.dashboard-material');
    }
}
