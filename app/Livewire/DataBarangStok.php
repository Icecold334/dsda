<?php

namespace App\Livewire;

use App\Models\Aset;
use Livewire\Component;
use App\Models\BarangStok;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Auth;

class DataBarangStok extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = '';
    // public $barangs = [];

    public function mount()
    {
        // Memuat data awal saat komponen di-mount
        $this->loadData();
    }

    public function updateSearch($value)
    {
        $this->search = $value;
        $this->loadData();
    }

    private function loadData()
    {
        // Memuat data barang dengan filter pencarian
        $lists = BarangStok::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhereHas('jenisStok', function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('merkStok', function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%');
                });
        })->paginate(3);

        if (!Auth::user()->unitKerja->hak) {
            $lists = BarangStok::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhereHas('merkStok', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    });
            })->whereHas('jenisStok', function ($query) {
                $query->where('nama', 'Material');
            })->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhereHas('merkStok', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    });
            })->paginate(3);
            # code...
        }
        // $this->barangs = $lists;
        return $lists;
    }

    public function render()
    {
        $barangs = $this->loadData();
        // dd($barans);
        return view('livewire.data-barang-stok', compact('barangs'));
    }
}
