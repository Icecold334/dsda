<?php

// App\Livewire\DashboardMaterial.php
namespace App\Livewire;

use App\Models\DetailPermintaanMaterial;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\TransaksiStok;
use App\Models\PermintaanMaterial;

class DashboardMaterial extends Component
{
    public $stokLabels = [], $sudin;
    public $stokValues = [];
    public $masukData = [];
    public $keluarData = [];
    public $tanggalLabels = [];
    public $permintaanTerbaru = [];

    public function mount()
    {
        $this->prepareStokChart();
        $this->prepareMasukKeluarChart();
        $statusMap = [
            null => ['label' => 'Diproses', 'color' => 'warning'],
            0 => ['label' => 'Ditolak', 'color' => 'danger'],
            1 => ['label' => 'Disetujui', 'color' => 'success'],
            2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
            3 => ['label' => 'Selesai', 'color' => 'primary'],
        ];

        $this->permintaanTerbaru = DetailPermintaanMaterial::with('user') // relasi jika perlu
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($item) use ($statusMap) {
                $status = $item->status;
                $item->status_label = $statusMap[$status]['label'] ?? 'Tidak Diketahui';
                $item->status_color = $statusMap[$status]['color'] ?? 'secondary';
                return $item;
            });
    }

    public function prepareStokChart()
    {
        $data = TransaksiStok::selectRaw('merk_stok.barang_id,
        SUM(CASE WHEN transaksi_stok.tipe = "Pemasukan" THEN jumlah ELSE 0 END) as masuk,
        SUM(CASE WHEN transaksi_stok.tipe = "Pengeluaran" THEN jumlah ELSE 0 END) as keluar')
            ->join('merk_stok', 'transaksi_stok.merk_id', '=', 'merk_stok.id')
            ->join('barang_stok', 'merk_stok.barang_id', '=', 'barang_stok.id')
            ->where('barang_stok.jenis_id', 1)
            ->groupBy('merk_stok.barang_id')
            ->get()
            ->map(function ($row) {
                $row->stok = $row->masuk - $row->keluar;
                return $row;
            })
            ->sortByDesc('stok')
            ->take(10); // Ambil 10 stok terbesar

        $barang = \App\Models\BarangStok::whereIn('id', $data->pluck('barang_id'))->pluck('nama', 'id');

        $this->stokLabels = $data->pluck('barang_id')->map(fn($id) => $barang[$id] ?? 'Tidak diketahui')->toArray();
        $this->stokValues = $data->pluck('stok')->toArray();
        $topLimit = 500;
        $sorted = $data->map(function ($row) {
            $row->stok = $row->masuk - $row->keluar;
            return $row;
        })->sortByDesc('stok');

        $top = $sorted->take($topLimit);
        $others = $sorted->slice($topLimit);

        $this->stokLabels = $top->pluck('barang_id')->map(fn($id) => $barang[$id] ?? 'Tidak diketahui')->toArray();
        $this->stokValues = $top->pluck('stok')->toArray();
        if ($others->sum('stok') > 0) {
            $this->stokLabels[] = 'Lainnya';
            $this->stokValues[] = $others->sum('stok');
        }
    }



    public function prepareMasukKeluarChart()
    {
        $data = TransaksiStok::selectRaw("DATE(tanggal) as tanggal,
                    SUM(CASE WHEN tipe = 'Pemasukan' THEN jumlah ELSE 0 END) as masuk,
                    SUM(CASE WHEN tipe = 'Pengeluaran' THEN jumlah ELSE 0 END) as keluar")
            ->whereDate('tanggal', '>=', now()->subDays(14))
            ->groupByRaw('DATE(tanggal)')
            ->orderByRaw('DATE(tanggal)')
            ->get();

        $this->tanggalLabels = $data->pluck('tanggal')->map(function ($tgl) {
            return Carbon::parse($tgl)->translatedFormat('l, j F Y'); // "Selasa, 7 Juni 2025"
        })->toArray();
        $this->masukData = $data->pluck('masuk')->toArray();
        $this->keluarData = $data->pluck('keluar')->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard-material');
    }
}
