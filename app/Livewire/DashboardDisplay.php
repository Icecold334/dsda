<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Agenda;
use App\Models\Jurnal;
use App\Models\History;
use Livewire\Component;
use App\Models\Keuangan;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class DashboardDisplay extends Component
{
    public $agendas, $jurnals, $histories, $transactions, $asets_limit;
    public $pelayanan;
    public $KDO;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Mendapatkan query untuk aset aktif
        $query = $this->getAsetQuery();

        $this->agendas = Agenda::with('aset')
            ->where([['status', 1], ['tipe', 'mingguan']])
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get()
            ->unique('aset_id');

        foreach ($this->agendas as $agenda) {
            $agenda->formatted_date = Carbon::parse($agenda->tanggal)->translatedFormat('l, j M Y');
        }

        $this->jurnals = Jurnal::with('aset')
            ->where('status', 1)
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get()
            ->unique('aset_id');

        foreach ($this->jurnals as $jurnal) {
            $jurnal->formatted_date = Carbon::parse($jurnal->tanggal)->translatedFormat('j M Y');
        }

        $this->histories = History::with('aset', 'lokasi')
            ->where('status', 1)
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get()
            ->unique('aset_id');

        foreach ($this->histories as $histori) {
            $histori->formatted_date = Carbon::parse($histori->tanggal)->translatedFormat('j M Y');
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

        $this->asets_limit = Aset::where('status', true)
            ->orderBy('tanggalbeli', 'desc')
            ->take(5)
            ->get();

        foreach ($this->asets_limit as $aset) {
            $aset->formatted_date = Carbon::parse($aset->tanggalbeli)->translatedFormat('j M Y');
        }

        // Filter Aset Berdasarkan Kategori KDO dan UnitKerja
        $tipe = 'KDO';
        $this->KDO = $query->whereHas('kategori', function ($query) use ($tipe) {
            $query->where('nama', $tipe);
        })
            ->orderBy('tanggalbeli', 'desc')
            ->take(5)
            ->get();

        foreach ($this->KDO as $kdo) {
            $kdo->formatted_date = Carbon::parse($kdo->tanggalbeli)->translatedFormat('j M Y');
        }

        // $this->pelayanan = 
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


    public function render()
    {
        return view('livewire.dashboard-display');
    }
}
