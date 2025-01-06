<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPeminjamanAset;
use App\Models\DetailPermintaanStok;

class DataPermintaan extends Component
{
    public $search; // Search term
    public $jenis; // Selected jenis
    public $lokasi; // Selected jenis
    public $tanggal; // Selected jenis
    public $unit_id; // Selected jenis
    public $status; // Selected jenis
    public $unitOptions = [];
    public $jenisOptions = []; // List of jenis options
    public $lokasiOptions = []; // List of jenis options

    public $permintaans;

    public function mount()
    {
        $this->unitOptions = UnitKerja::where('parent_id', $this->unit_id)->get();

        $this->applyFilters();
    }

    public function applyFilters()
    {





        $permintaanQuery = DetailPermintaanStok::select('id', 'kode_permintaan as kode', 'tanggal_permintaan as tanggal', 'kategori_id', 'unit_id', 'status', 'cancel', 'proses', 'jenis_id', DB::raw('"permintaan" as tipe'), 'created_at')
            ->where('jenis_id', 3)
            ->whereHas('unit', function ($unit) {
                $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });

        $peminjamanQuery = DetailPeminjamanAset::select('id', 'kode_peminjaman as kode', 'tanggal_peminjaman as tanggal', 'kategori_id', 'unit_id', 'status', 'cancel', 'proses', DB::raw('"peminjaman" as tipe'), DB::raw('NULL as jenis_id'), 'created_at')
            ->whereHas('unit', function ($unit) {
                $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });

        // Gabungkan kedua query menggunakan union
        $query = $permintaanQuery->union($peminjamanQuery);

        // Tambahkan kondisi tambahan pada query gabungan
        $query->orderBy('id', 'desc');
        // dd($query->where('jenis_id', 4));
        // Apply search filter if present
        if (!empty($this->search)) {
            $query->where('kode_permintaan', 'like', '%' . $this->search . '%');
        }

        // Apply jenis filter if selected
        if (!empty($this->jenis)) {
            $query->where('tipe', $this->jenis);
        }

        // Apply unit_id filter if selected
        if (!empty($this->unit_id)) {
            $query->where('unit_id', $this->unit_id);
        }
        if (!empty($this->tanggal)) {
            $tanggalFormatted = $this->tanggal; // Contoh: '2025-01-02'

            // Konversi tanggal input ke rentang waktu (awal dan akhir hari)
            $tanggalStart = strtotime($tanggalFormatted . ' 00:00:00');
            $tanggalEnd = strtotime($tanggalFormatted . ' 23:59:59');
            // dd($tanggalStart, $tanggalEnd);

            // Filter berdasarkan rentang timestamp
            $query->whereBetween('tanggal', [$tanggalStart, $tanggalEnd]);
        }

        // Apply status filter if selected
        if (!empty($this->status)) {
            $s = $this->status;

            $query->where(function ($query) use ($s) {
                if ($s === 'diproses') {
                    $query->whereNull('cancel')
                        ->whereNull('proses')
                        ->whereNull('status');
                } elseif ($s === 'disetujui') {
                    $query->whereNull('cancel')
                        ->whereNull('proses')
                        ->where('status', 1);
                } elseif ($s === 'ditolak') {
                    $query->where('cancel', 1)
                        ->whereNull('proses')
                        ->orWhere(function ($query) {
                            $query->whereNull('cancel')
                                ->whereNull('proses')
                                ->where('status', 0);
                        });
                } elseif ($s === 'selesai') {
                    $query->where('cancel', 0)
                        ->where('proses', 1);
                } elseif ($s === 'siap diambil') {
                    $query->where('cancel', 0)
                        ->whereNull('proses');
                } elseif ($s === 'dibatalkan') {
                    $query->where('cancel', 1);
                }
            });
        }


        // Fetch filtered data
        $this->permintaans = $query->get();
    }


    public function updated($propertyName)
    {
        $this->applyFilters();
    }
    public function render()
    {
        return view('livewire.data-permintaan');
    }
}
