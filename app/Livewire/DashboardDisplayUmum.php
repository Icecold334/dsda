<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Stok;
use App\Models\User;
use App\Models\Agenda;
use App\Models\Jurnal;
use App\Models\History;
use Livewire\Component;
use App\Models\Keuangan;
use App\Models\UnitKerja;
use App\Models\BarangStok;
use App\Models\PeminjamanAset;
use App\Models\DetailPeminjamanAset;
use App\Models\DetailPermintaanStok;
use Illuminate\Support\Facades\Auth;

class DashboardDisplayUmum extends Component
{
    public $peminjamans, $jurnals, $histories, $transactions, $asets_limit;
    public $pelayanan;
    public $KDO;
    public $unit_id;
    public $tipe;
    public $lokasi;
    public $drivers;
    public $data_nilai = [];
    public $label_nilai = [];


    public function mount()
    {
        $this->loadData();
        $this->getChartData();
    }

    public function loadData()
    {
        $this->getDrivers();
        // Mendapatkan query untuk aset aktif
        $query = $this->getAsetQuery();

        $this->peminjamans = PeminjamanAset::with(['detailPeminjaman', 'aset', 'ruang'])
            ->whereHas('detailPeminjaman', function ($query) {
                $query->where('status', 1);
            })
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get()
            ->unique('aset_id');

        foreach ($this->peminjamans as $peminjaman) {
            $peminjaman->formatted_date = Carbon::parse($peminjaman->tanggal)->translatedFormat('l, j M Y');
            if ($peminjaman->detailPeminjaman->kategori_id == 2) {
                $peminjaman->nama = $peminjaman->ruang->nama ?? '-';
            } elseif ($peminjaman->detailPeminjaman->kategori_id == 1) {
                $merk = $peminjaman->aset->merk->nama ?? '';
                $nama = $peminjaman->aset->nama ?? '-';
                $noseri = $peminjaman->aset->noseri ?? '';
                $peminjaman->nama = trim("{$merk} {$nama} - {$noseri}");
            } else {
                $peminjaman->nama = $peminjaman->aset->nama ?? '-';
            }
        }

        $this->transactions = Keuangan::with('aset')
            ->where('status', 1)
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get()
            ->unique('aset_id');

        foreach ($this->transactions as $transaksi) {
            $transaksi->formatted_date = Carbon::parse($transaksi->tanggal)->translatedFormat('j M Y');
        }

        // Filter Aset Berdasarkan Kategori KDO dan UnitKerja
        $tipe = 'KDO';
        $this->KDO = $query->whereHas('kategori', function ($query) use ($tipe) {
            $query->where('nama', $tipe);
        })
            ->orderBy('nama', 'asc')
            // ->take(5)
            ->get();

        foreach ($this->KDO as $kdo) {
            $kdo->formatted_date = Carbon::parse($kdo->tanggalbeli)->translatedFormat('j M Y');

            // Tambahkan status peminjaman
            $kdo->status_text = $this->statusText[$kdo->peminjaman] ?? 'Tidak Diketahui';
            $kdo->status_class = $this->statusClasses[$kdo->peminjaman] ?? 'text-gray-500';
            $kdo->status_icon = $this->statusIcons[$kdo->peminjaman] ?? '❓';
        }

        // Ambil data permintaan dan peminjaman
        $permintaan = $this->getPermintaanQuery();
        $peminjaman = $this->getPeminjamanQuery();

        // Gabungkan dua dataset dan urutkan berdasarkan tanggal terbaru
        $this->pelayanan = collect()
            ->merge($permintaan)
            ->merge($peminjaman)
            ->sortByDesc('created_at')
            ->take(5);
    }

    public function getDrivers()
    {
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Query untuk mengambil driver berdasarkan unit kerja
        $this->drivers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Driver'); // Ambil user dengan role "Driver"
        })
            ->whereHas('unitKerja', function ($query) use ($parentUnitId) {
                $query->where('parent_id', $parentUnitId)->orWhere('id', $parentUnitId);
            })
            ->get();
        // ->map(function ($driver) {
        //     // Menentukan status driver berdasarkan peminjaman kategori "KDO"
        //     $driver->status = $this->isDriverAvailable($driver) ? 'Tersedia' : 'Tidak Tersedia';
        //     return $driver;
        // });
    }

    private function isDriverAvailable($driver)
    {
        return !DetailPeminjamanAset::whereHas('user', function ($query) use ($driver) {
            $query->where('id', $driver->id); // Cek apakah driver terhubung ke peminjaman
        })
            ->whereHas('user.aset', function ($query) {
                $query->whereHas('kategori', function ($kategoriQuery) {
                    $kategoriQuery->where('nama', 'KDO'); // Hanya kategori "KDO"
                });
            })
            ->whereHas('unit', function ($query) {
                $query->where('id', $this->unit_id) // Hanya unit kerja yang sesuai
                    ->orWhere('parent_id', $this->unit_id);
            })
            ->where('status', 1) // Hanya peminjaman yang sedang aktif
            ->exists();
    }


    private function getPermintaanQuery()
    {
        $permintaan = DetailPermintaanStok::where('jenis_id', $this->getJenisId())
            ->when($this->unit_id, function ($query) {
                $query->whereHas('unit', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            })->get();
        return $permintaan->isNotEmpty() ? $permintaan->map(function ($item) {
            return $this->mapPelayananData($item, 'permintaan');
        }) : collect([]);
    }


    private function getPeminjamanQuery()
    {
        $peminjaman = DetailPeminjamanAset::when($this->unit_id, function ($query) {
            $query->whereHas('unit', function ($unit) {
                $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });
        })->get();

        return $peminjaman->isNotEmpty() ? $peminjaman->map(function ($item) {
            return
                $this->mapPelayananData($item, 'peminjaman');
        }) : collect([]);
    }


    private function mapPelayananData($item, $tipe)
    {
        return [
            'id' => $item->id,
            'kode' => $tipe === 'permintaan' ? $item->kode_permintaan : $item->kode_peminjaman,
            'tanggal' => $tipe === 'permintaan' ? $item->tanggal_permintaan : $item->tanggal_peminjaman,
            'unit' => $item->unit?->nama ?? 'Tidak Ada Unit',
            'kategori_id' => $item->kategori_id,
            'kategori' => $tipe === 'permintaan' ? $item->kategoriStok : $item->kategori,
            'tipe' => $tipe,
            'status' => $item->status ?? null,
            'cancel' => $item->cancel ?? null,
            'proses' => $item->proses ?? null,
            'created_at' => $item->created_at,
            'formatted_date' => Carbon::parse($item->created_at)->translatedFormat('j M Y')
        ];
    }

    private function getJenisId()
    {
        return $this->tipe === 'material' ? 1 : ($this->tipe === 'spare-part' ? 2 : 3);
    }


    private function getAsetQuery()
    {
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Debugging: Tampilkan parentUnitId untuk verifikasi
        // Query untuk mendapatkan aset berdasarkan unit parent yang dimiliki oleh user
        return Aset::where('status', true)
            ->when($this->unit_id, function ($query) use ($parentUnitId) {
                $query->whereHas('user', function ($query) use ($parentUnitId) {
                    filterByParentUnit($query, $parentUnitId);
                });
            });
    }

    // Tambahkan array mapping status
    public $statusText = [
        0 => 'Tidak Dapat Dipinjam',
        1 => 'Tersedia',
        2 => 'Dipinjam',
        3 => 'Diperbaiki'
    ];

    public $statusClasses = [
        0 => 'text-red-500',    // Dipinjam (Merah)
        1 => 'text-green-500',  // Tersedia (Hijau)
        2 => 'text-yellow-500',  // Diperbaiki (Kuning)
        3 => 'text-blue-500'  // Diperbaiki (Kuning)
    ];

    public $statusIcons = [
        0 => '❌',  // Dipinjam (Silang)
        1 => '✅',  // Tersedia (Centang)
        2 => '❌',   // Diperbaiki (Kunci)
        3 => '🔧'   // Diperbaiki (Kunci)
    ];

    public $statusMapping = [
        'dibatalkan' => ['text' => 'Dibatalkan', 'color' => 'secondary'],
        'selesai' => ['text' => 'Selesai', 'color' => 'primary'],
        'siap_diambil' => ['text' => 'Siap Diambil', 'color' => 'info'],
        'diproses' => ['text' => 'Diproses', 'color' => 'warning'],
        'disetujui' => ['text' => 'Disetujui', 'color' => 'success'],
        'ditolak' => ['text' => 'Ditolak', 'color' => 'danger'],
    ];

    // Fungsi untuk menentukan status berdasarkan data
    public function getStatus($permintaan)
    {
        if ($permintaan['cancel'] === 1) return 'dibatalkan';
        if ($permintaan['cancel'] === 0 && $permintaan['proses'] === 1) return 'selesai';
        if ($permintaan['cancel'] === 0 && is_null($permintaan['proses'])) return 'siap_diambil';
        if (is_null($permintaan['cancel']) && is_null($permintaan['proses']) && is_null($permintaan['status'])) return 'diproses';
        if (is_null($permintaan['cancel']) && is_null($permintaan['proses']) && $permintaan['status'] === 1) return 'disetujui';
        return 'ditolak';
    }

    public function getChartData()
    {
        // Ambil data BarangStok yang memiliki MerkStok dan stok yang tersedia berdasarkan unit kerja
        $barang = BarangStok::whereHas('merkStok.stok', function ($stokQuery) {
            $stokQuery->where('jumlah', '>', 0)
                ->whereHas('lokasiStok.unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                });
        })
            ->with(['merkStok.stok.lokasiStok.unitKerja']) // Eager load untuk memastikan stok memiliki unit kerja
            ->get();

        // Transformasi data untuk chart
        $data = [];
        foreach ($barang as $item) {
            // Looping melalui setiap MerkStok yang dimiliki barang
            foreach ($item->merkStok as $merk) {
                foreach ($merk->stok as $stok) {
                    // Pastikan stok hanya dihitung jika unitKerja sesuai
                    if ($stok->lokasiStok->unitKerja->id == $this->unit_id || $stok->lokasiStok->unitKerja->parent_id == $this->unit_id) {
                        $data[] = [
                            'nama_barang' => $item->nama . ' - ' . ($merk->nama ?? null) . ' - ' . ($item->satuanBesar->nama ?? null),
                            'jumlah_stok' => $stok->jumlah, // Tidak dijumlahkan, langsung diambil
                        ];
                    }
                }
            }
        }

        // Debugging untuk memastikan data valid sebelum digunakan di chart
        // dd($barang, $data);

        // Pisahkan data untuk digunakan dalam chart
        $this->label_nilai = array_column($data, 'nama_barang'); // Array nama barang
        $this->data_nilai = array_column($data, 'jumlah_stok'); // Array jumlah stok

    }

    public function render()
    {
        return view('livewire.dashboard-display-umum', [
            'data_nilai' => json_encode($this->data_nilai),
            'label_nilai' => json_encode($this->label_nilai)
        ]);
    }
}
