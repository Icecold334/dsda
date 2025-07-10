<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LokasiStok;
use App\Models\TransaksiStok;
use App\Models\MerkStok;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShowStokMaterial extends Component
{
    public $lokasi_id;
    public $lokasi, $search;
    public $showModal = false;
    public $showFormPenyesuaian = false;
    public $modalBarangNama;
    public $modalRiwayat = [];
    public $penyesuaian = [
        'merk_id' => null,
        'jumlah' => null,
        'deskripsi' => null,
    ];
    public $stokAwal = null;


    public function mount()
    {
        $this->lokasi = LokasiStok::with('unitKerja')->findOrFail($this->lokasi_id);
    }
    public function updatedPenyesuaianMerkId($value)
    {
        $this->stokAwal = $this->getStokByMerkId($value);
    }
    protected function getStokByMerkId($merkId)
    {
        $stok = 0;

        foreach ($this->barangStok as $barang) {
            foreach ($barang['spesifikasi'] as $spec => $info) {
                if ($info['merk_id'] == $merkId) {
                    return $info['jumlah'];
                }
            }
        }

        return $stok;
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

            $jumlah = 0;
            if ($trx->tipe === 'Penyesuaian') {
                $jumlah = (int) $trx->jumlah;
            } elseif ($trx->tipe === 'Pemasukan') {
                $jumlah = (int) $trx->jumlah;
            } elseif ($trx->tipe === 'Pengeluaran' || $trx->tipe === 'Pengajuan') {
                $jumlah = -(int) $trx->jumlah;
            }

            if (!isset($result[$key])) {
                $result[$key] = [
                    'id' => $barang->id,
                    'kode' => $barang->kode_barang,
                    'nama' => $barang->nama,
                    'satuan' => $barang->satuanBesar->nama,
                    'spesifikasi' => [],
                    'jumlah' => [],
                ];
            }

            $result[$key]['spesifikasi'][$spec] = [
                'jumlah' => ($result[$key]['spesifikasi'][$spec]['jumlah'] ?? 0) + $jumlah,
                'merk_id' => $trx->merkStok->id,
            ];
        }

        foreach ($result as $barangId => &$data) {
            $data['spesifikasi'] = collect($data['spesifikasi'])
                ->filter(fn($spec) => $spec['jumlah'] > 0)
                ->all();
        }

        $result = array_filter($result, fn($data) => count($data['spesifikasi']) > 0);

        $search = strtolower($this->search);
        if ($search) {
            $result = array_filter($result, function ($item) use ($search) {
                $kode = strtolower($item['kode']);
                $nama = strtolower($item['nama']);
                $specs = implode(' ', array_keys($item['spesifikasi']));
                $specs = strtolower($specs);

                return str_contains($kode, $search) ||
                    str_contains($nama, $search) ||
                    str_contains($specs, $search);
            });
        }

        return $result;
    }

    public function getMerkStokSiapPenyesuaianProperty()
    {
        $list = [];

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

            $jumlah = 0;
            if ($trx->tipe === 'Penyesuaian') {
                $jumlah = (int) $trx->jumlah;
            } elseif ($trx->tipe === 'Pemasukan') {
                $jumlah = (int) $trx->jumlah;
            } elseif ($trx->tipe === 'Pengeluaran' || $trx->tipe === 'Pengajuan') {
                $jumlah = -(int) $trx->jumlah;
            }

            if (!isset($result[$key])) {
                $result[$key] = [
                    'id' => $barang->id,
                    'kode' => $barang->kode_barang,
                    'nama' => $barang->nama,
                    'satuan' => $barang->satuanBesar->nama,
                    'spesifikasi' => [],
                    'jumlah' => [],
                ];
            }

            $result[$key]['spesifikasi'][$spec] = [
                'jumlah' => ($result[$key]['spesifikasi'][$spec]['jumlah'] ?? 0) + $jumlah,
                'merk_id' => $trx->merkStok->id,
            ];
        }

        foreach ($result as $barangId => &$data) {
            $data['spesifikasi'] = collect($data['spesifikasi'])
                ->filter(fn($spec) => $spec['jumlah'] > 0)
                ->all();
        }

        $result = array_filter($result, fn($data) => count($data['spesifikasi']) > 0);

        foreach ($result as $barang) {
            foreach ($barang['spesifikasi'] as $spec => $info) {
                $list[] = [
                    'id' => $info['merk_id'],
                    'label' => "{$barang['nama']} - {$spec}",
                ];
            }
        }

        return collect($list)->unique('id')->values();
    }

    public function simpanPenyesuaian()
    {
        $this->validate([
            'penyesuaian.merk_id' => 'required|exists:merk_stok,id',
            'penyesuaian.jumlah' => 'required|numeric|min:0',
            'penyesuaian.deskripsi' => 'nullable|string',
        ]);

        $stokBaru = (int) $this->penyesuaian['jumlah'];
        $selisih = $stokBaru - (int) $this->stokAwal;

        if ($selisih === 0) {
            session()->flash('info', 'Tidak ada perubahan stok.');
            return;
        }

        TransaksiStok::create([
            'tipe' => 'Penyesuaian',
            'merk_id' => $this->penyesuaian['merk_id'],
            'jumlah' => $selisih,
            'deskripsi' => $this->penyesuaian['deskripsi'],
            'lokasi_id' => $this->lokasi_id,
            'user_id' => Auth::id(),
            'tanggal' => now()->format('Y-m-d'),
            'kode_transaksi_stok' => 'SO-' . now()->format('Ymd'),
        ]);

        $this->dispatch('toast', [
            // 'title' => 'Berhasil',
            'message' => 'Penyesuaian stok berhasil disimpan.',
            'type' => 'success'
        ]);

        $this->reset('penyesuaian', 'stokAwal', 'showFormPenyesuaian');

        $this->reset('penyesuaian', 'stokAwal', 'showFormPenyesuaian');
        // session()->flash('success', 'Penyesuaian stok berhasil disimpan.');
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
            ->orderByDesc('tanggal')
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
                    'deskripsi' => $trx->deskripsi ?? '-',
                    'user' => $trx->user->name ?? '-',
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
            'merkStokSiapPenyesuaian' => $this->merkStokSiapPenyesuaian,
        ]);
    }
}
