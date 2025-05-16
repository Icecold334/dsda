<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LokasiStok;
use App\Models\TransaksiStok;

class ShowStokMaterial extends Component
{
    public $lokasi_id;
    public $lokasi;
    public $showModal = false;
    public $modalBarangNama;
    public $modalRiwayat = [];
    public function mount()
    {
        $this->lokasi = LokasiStok::with('unitKerja')->findOrFail($this->lokasi_id);
        // dd($test);
    }

    public function getBarangStokProperty()
    {
        $transaksis = TransaksiStok::with(['merkStok.barangStok'])
            ->whereHas('merkStok.barangStok', function ($barang) {
                return $barang->where('jenis_id', 1);
            })
            ->where('lokasi_id', $this->lokasi_id)
            ->get();

        $result = [];

        foreach ($transaksis as $trx) {
            $barang = $trx->merkStok->barangStok;
            if (!$barang) continue;

            $key = $barang->id;
            $merk = $trx->merkStok->nama ?? 'Tanpa Merk';
            $tipe = $trx->merkStok->tipe ?? 'Tanpa Tipe';
            $ukuran = $trx->merkStok->ukuran ?? 'Tanpa Ukuran';
            $spec = "{$merk} - {$tipe} - {$ukuran}";

            // Hitung jumlah berdasarkan tipe
            $jumlah = 0;
            if ($trx->tipe === 'Penyesuaian') {
                // Penyesuaian bisa bernilai "+100" atau "-500"
                $jumlah = (int) $trx->jumlah;
            } elseif ($trx->tipe === 'Pemasukan') {
                $jumlah = (int) $trx->jumlah;
            } elseif ($trx->tipe === 'Pengeluaran') {
                $jumlah = -(int) $trx->jumlah;
            }

            if (!isset($result[$key])) {
                $result[$key] = [
                    'id' => $barang->id,
                    'kode' => $barang->kode_barang,
                    'nama' => $barang->nama,
                    'satuan' => $barang->satuanBesar->nama,
                    'spesifikasi' => [],
                    'jumlah' => [], // opsional kalau mau breakdown per merk
                ];
            }

            $result[$key]['spesifikasi'][$spec] = ($result[$key]['spesifikasi'][$spec] ?? 0) + $jumlah;
        }

        // Filter hanya spesifikasi dengan stok > 0
        foreach ($result as $barangId => &$data) {
            $data['spesifikasi'] = collect($data['spesifikasi'])
                ->filter(fn($jumlah) => $jumlah > 0)
                ->all();
        }

        // Hapus barang yang semua spesifikasinya kosong
        $result = array_filter($result, fn($data) => count($data['spesifikasi']) > 0);

        return $result;
    }

    public function showRiwayat($barangId, $namaBarang)
    {
        $this->modalBarangNama = $namaBarang;

        $this->modalRiwayat = TransaksiStok::with(['merkStok.barangStok', 'lokasiStok', 'bagianStok', 'posisiStok'])
            ->where(function ($q) {
                $q->where('lokasi_id', $this->lokasi_id)
                    ->orWhereHas('bagianStok', fn($q) => $q->where('lokasi_id', $this->lokasi_id))
                    ->orWhereHas('posisiStok.bagianStok', fn($q) => $q->where('lokasi_id', $this->lokasi_id));
            })
            ->whereHas('merkStok', fn($q) => $q->where('barang_id', $barangId))
            ->orderBy('tanggal')
            ->get()
            ->map(function ($trx) {
                return [
                    'tanggal' => $trx->tanggal,
                    'tipe' => $trx->tipe,
                    'jumlah' => $trx->jumlah . ' ' . ($trx->merkStok->barangStok->satuanBesar->nama ?? ''),
                    'merk' => $trx->merkStok->nama ?? '-',
                    'tipe_merk' => $trx->merkStok->tipe ?? '-',
                    'ukuran' => $trx->merkStok->ukuran ?? '-',
                    'kode' => $trx->kode_transaksi_stok ?? '-',
                    'lokasi' => $trx->lokasiStok?->nama ?? '-',
                    'bagian' => $trx->bagianStok?->nama ?? '-',
                    'posisi' => $trx->posisiStok?->nama ?? '-',
                ];
            })->toArray();

        $this->showModal = true;
    }


    public function closeModal()
    {
        $this->showModal = false;
        $this->modalBarangNama = null;
        $this->modalRiwayat = [];
    }
    public function render()
    {
        return view('livewire.show-stok-material', [
            'barangStok' => $this->barangStok,
        ]);
    }
}
