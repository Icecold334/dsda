<?php

namespace App\Livewire;

use App\Models\Rab;
use App\Models\User;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use App\Models\ListRab as ModelsListRab;
use Illuminate\Support\Facades\Notification;

class ListRab extends Component
{

    public $rab_id, $barangs, $merks = [], $dokumenCount, $newMerkId, $newBarangId, $newJumlah, $newUnit = 'Satuan', $showRule = false, $ruleAdd = false, $list = [], $dataKegiatan = [];
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

        if ($field == 'newBarangId') {
            $this->newUnit = BarangStok::find($this->newBarangId)->satuanBesar->nama;
            $this->newMerkId = null;
            $this->newJumlah = null;

            $this->merks = MerkStok::where('barang_id', $this->newBarangId)->get();
        }
        $this->checkAdd();
    }

    // public function checkShow()
    // {
    //     $this->showRule = count(array_filter($this->dataKegiatan, fn($value) => $value !== null && $value !== '')) === count($this->dataKegiatan);
    // }
    public function checkShow()
    {
        $this->showRule = collect($this->dataKegiatan)
            ->except(['saluran_jenis', 'saluran_id']) // dikecualikan dari validasi
            ->every(fn($value) => $value !== null && $value !== '');
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
        $this->reset(['newBarangId', 'newMerkId', 'newJumlah', 'newUnit']);
        $this->checkAdd();
    }

    public function saveData()
    {
        $rab = Rab::create([
            'user_id' => Auth::id(),
            'program_id' => $this->dataKegiatan['program'],
            'kegiatan_id' => $this->dataKegiatan['nama'],
            'sub_kegiatan_id' => $this->dataKegiatan['sub_kegiatan'],
            'aktivitas_sub_kegiatan_id' => $this->dataKegiatan['aktivitas_sub_kegiatan'],
            'uraian_rekening_id' => $this->dataKegiatan['kode_rekening'],
            'jenis_pekerjaan' => $this->dataKegiatan['jenis'],
            'saluran_jenis' => $this->dataKegiatan['saluran_jenis'],
            'saluran_id' => $this->dataKegiatan['saluran_id'],
            'kelurahan_id' => $this->dataKegiatan['kelurahan_id'],
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
        $mess = 'RAB <span class="font-semibold">' . $rab->jenis_pekerjaan . '</span> membutuhkan persetujuan Anda.';

        $unit_id = $this->unit_id;
        $user = User::whereHas('unitKerja', function ($unit) use ($unit_id) {
            return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Seksi Perencanaan%');
        })->whereHas('roles', function ($role) {
            return $role->where('name', 'like', '%Kepala Seksi%');
        })->first();
        Notification::send($user, new UserNotification($mess, "/rab/{$rab->id}"));
        $this->dispatch('saveDokumen', kontrak_id: $rab->id, isRab: true);
    }
    public function render()
    {
        return view('livewire.list-rab');
    }
}
