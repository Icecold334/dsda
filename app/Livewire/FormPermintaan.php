<?php

namespace App\Livewire;

use App\Models\KategoriStok;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;

class FormPermintaan extends Component
{
    public $permintaan;
    public $units;
    public $unit_id;
    public $kategoris;
    public $kategori_id;
    public $tipePeminjaman;
    public $subUnits;
    public $tipe;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $keterangan;
    public $listCount;

    #[On('listCount')]
    public function updateListCount($count)
    {
        $this->listCount = $count;
    }

    public function updatedUnitId()
    {

        if ($this->unit_id) {
            $this->subUnits = UnitKerja::where('parent_id', $this->unit_id)->get();
        }
        $this->dispatch('unit_id', unit_id: $this->unit_id);
    }
    public function updatedTipePeminjaman()
    {
        $this->dispatch('peminjaman', peminjaman: $this->tipePeminjaman);
    }
    public function updatedKategoriId()
    {
        $this->dispatch('kategori_id', kategori_id: $this->kategori_id);
    }
    public function updatedTanggalPermintaan()
    {

        $this->dispatch('tanggal_permintaan', tanggal_permintaan: $this->tanggal_permintaan);
    }
    public function updatedKeterangan()
    {
        $this->dispatch('keterangan', keterangan: $this->keterangan);
    }

    public function updatedSubUnitId()
    {

        $this->dispatch('sub_unit_id', sub_unit_id: $this->sub_unit_id);
    }

    public $showKategori;

    public function mount()
    {
        $this->showKategori = Request::is('permintaan/add/permintaan');
        $this->tanggal_permintaan = Carbon::now()->format('Y-m-d');;
        $this->units = UnitKerja::whereNull('parent_id')->whereHas('children', function ($sub) {
            return $sub;
        })->get();
        $this->kategoris = KategoriStok::whereHas('barangStok', function ($barang) {
            return $barang->whereHas('merkStok', function ($merk) {
                return $merk;
            });
        })->get();
        if ($this->unit_id) {
            $this->subUnits = UnitKerja::where('parent_id', $this->unit_id)->get();
        }
        if ($this->permintaan) {
            $this->updatedUnitId();
            $this->dispatch('unit_id', unit_id: $this->unit_id);
            $this->dispatch('tanggal_permintaan', tanggal_permintaan: $this->tanggal_permintaan);
            $this->dispatch('keterangan', keterangan: $this->keterangan);
            $this->dispatch('sub_unit_id', sub_unit_id: $this->sub_unit_id);
        }
    }
    public function render()
    {
        return view('livewire.form-permintaan');
    }
}
