<?php

namespace App\Livewire;

use App\Models\BarangStok;
use App\Models\ListRab as ModelsListRab;
use App\Models\MerkStok;
use App\Models\Rab;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ListRab extends Component
{

    public $rab_id, $barangs, $dokumenCount, $newMerkId, $newJumlah, $newUnit = 'Satuan', $showRule = false, $ruleAdd = false, $list = [], $dataKegiatan = [];
    public function mount()
    {
        if ($this->rab_id) {
            $rab = Rab::find($this->rab_id);
            foreach ($rab->list as $item) {
                $this->list[] = [
                    'id' => $item->id,
                    'merk' => MerkStok::find($item->merk_id),
                    'jumlah' => $item->jumlah
                ];
            }
        }
        $this->checkAdd();
        $this->barangs = BarangStok::where('jenis_id', 1)->get();
    }

    public function updated($field)
    {
        if (!$this->newMerkId) {
            $this->newJumlah = null;
            $this->newUnit = 'Satuan';
        } else {
            $this->newUnit = MerkStok::find($this->newMerkId)->barangStok->satuanBesar->nama;
        }
        $this->checkAdd();
    }

    public function checkShow()
    {
        $this->showRule = count(array_filter($this->dataKegiatan, fn($value) => $value !== null && $value !== '')) === count($this->dataKegiatan);
    }
    public function checkAdd()
    {
        $this->ruleAdd = $this->newMerkId && $this->newJumlah;
    }
    public function removeFromList($index)
    {

        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
        $this->dispatch('listCount', count: count($this->list));
    }

    #[On('dataKegiatan')]
    public function fillDataKegiatan($data)
    {
        $this->dataKegiatan = $data;
        // Cek jika ada nilai yang null atau kosong

        $this->checkShow();
    }


    #[On('dokumenCount')]
    public function fillDokumenCount($count)
    {
        $this->dokumenCount = $count;
    }
    public function addToList()
    {
        $this->list[] = [
            'id' => null,
            'merk' => MerkStok::find($this->newMerkId),
            'jumlah' => $this->newJumlah
        ];
        $this->dispatch('listCount', count: count($this->list));
        $this->reset(['newMerkId', 'newJumlah', 'newUnit']);
        $this->checkAdd();
    }

    public function saveData()
    {
        $rab = Rab::create([
            'user_id' => Auth::id(),
            'nama' => $this->dataKegiatan['nama'],
            'lokasi' => $this->dataKegiatan['lokasi'],
            'mulai' => $this->dataKegiatan['mulai'],
            'selesai' => $this->dataKegiatan['selesai'],
        ]);
        $data = [];
        foreach ($this->list as $item) {
            $data[] = [
                'rab_id' => $rab->id,
                'merk_id' => $item['merk']->id,
                'jumlah' => $item['jumlah'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        ModelsListRab::insert($data);
        $this->reset('list');
        $this->dispatch('saveDokumen', kontrak_id: $rab->id, isRab: true);
    }
    public function render()
    {
        return view('livewire.list-rab');
    }
}
