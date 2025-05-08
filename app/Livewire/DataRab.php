<?php

namespace App\Livewire;

use App\Models\Rab;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class DataRab extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $Rkb, $RKB, $sudin;


    public function mount()
    {
        // dd($this->Rkb);
    }

    public function fetchData()
    {
        $rabs =  Rab::whereHas('user.unitKerja', function ($unit) {
            $unit->where('parent_id', $this->unit_id)
                ->orWhere('id', $this->unit_id);
        })->orderBy('created_at', 'desc')->paginate(5);

        $rabs->getCollection()->transform(function ($rab) {
            $statusMap = [
                null => ['label' => 'Diproses', 'color' => 'warning'],
                0 => ['label' => 'Ditolak', 'color' => 'danger'],
                1 => ['label' => 'Dibatalkan', 'color' => 'secondary'],
                2 => ['label' => 'Disetujui', 'color' => 'success'],
                3 => ['label' => 'Selesai', 'color' => 'primary'],
            ];

            // Tambahkan properti dinamis ke dalam object
            $rab->status_teks = $statusMap[$rab->status]['label'] ?? 'Tidak diketahui';
            $rab->status_warna = $statusMap[$rab->status]['color'] ?? 'gray';
            return $rab;
        });

        return $rabs;
    }
    public function render()
    {
        $rabs = $this->fetchData();
        return view('livewire.data-rab', compact('rabs'));
    }
}
