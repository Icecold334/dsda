<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kategori;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class AddKategori extends Component
{
    public $id;
    public $kategori;
    public $tipe;
    public $kategoris;
    public $keterangan;
    public $kategori_id;
    public $parent_id;
    public $utama;
    public $sub;

    public function mount()
    {
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;
        if ($this->tipe == 'sub') {
            $this->kategoris = Kategori::whereNUll('parent_id')
                ->when(Auth::user()->id != 1, function ($query) use ($parentUnitId) {
                    $query->whereHas('user', function ($query) use ($parentUnitId) {
                        filterByParentUnit($query, $parentUnitId);
                    });
                })->get();
            if ($this->id) {
                $sub = Kategori::find($this->id);
                $this->sub = $sub->nama;
                $this->kategori_id = $sub->kategori_id;
                $this->parent_id = $sub->parent_id;
                $this->keterangan = $sub->keterangan;
            }
        } else {
            if ($this->id) {
                $utama = Kategori::find($this->id);
                $this->utama = $utama->nama;
                $this->keterangan = $utama->keterangan;
            }
        }
    }
    public function removeKategori()
    {
        if ($this->tipe == 'utama') {
            Kategori::destroy($this->id);
        } else {
            Kategori::destroy($this->id);
        }
        return redirect()->route('kategori.index');
    }

    public function saveKategori()
    {
        // Tentukan apakah kategori ini "utama" atau "sub"
        $data = [
            'nama' => $this->tipe == 'utama' ? $this->utama : $this->sub,
            'keterangan' => $this->keterangan,
        ];

        if ($this->tipe != 'utama') {
            $data['parent_id'] = $this->parent_id;
        }

        // Jika ID diberikan, cari kategori
        $kategori = Kategori::find($this->id);

        // Set user_id
        $data['user_id'] = $kategori ? $kategori->user_id : Auth::id();

        // Update atau create dengan data
        Kategori::updateOrCreate(['id' => $this->id ?? 0], $data);

        return redirect()->route('kategori.index');
    }

    public function render()
    {
        return view('livewire.add-kategori');
    }
}
