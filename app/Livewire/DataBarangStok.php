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
        $user = Auth::user();

        // Memuat data barang dengan filter pencarian - default untuk superadmin
        $lists = BarangStok::when($this->search, function ($query) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                ->orWhereHas('jenisStok', function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('merkStok', function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%');
                });
        })->paginate(3);

        // Jika user tidak memiliki unitKerja atau hak = 0 (bukan superadmin), filter hanya Material
        if (!$user || !$user->unitKerja || !($user->unitKerja->hak ?? 0)) {
            $lists = BarangStok::when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhereHas('merkStok', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    });
            })->whereHas('jenisStok', function ($query) {
                $query->where('nama', 'Material');
            })->paginate(3);
        }

        return $lists;
    }

    public function render()
    {
        $barangs = $this->loadData();
        // dd($barans);
        return view('livewire.data-barang-stok', compact('barangs'));
    }
}
