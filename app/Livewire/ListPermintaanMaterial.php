<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Stok;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use App\Models\DetailPermintaanMaterial;
use App\Models\PermintaanMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class ListPermintaanMaterial extends Component
{
    use WithFileUploads;
    public $permintaan, $tanggalPenggunaan, $keterangan, $isShow, $gudang_id;
    public  $rab_id,  $barangs, $dokumenCount, $newMerkId, $newMerkMax, $newJumlah, $newUnit = 'Satuan', $showRule = false, $ruleAdd = false, $list = [], $dataKegiatan = [];

    public function mount()
    {
        $this->isShow = Request::routeIs('showPermintaan');
        $this->tanggalPenggunaan = Carbon::now()->format('Y-m-d'); // Format untuk date biasa
        $this->checkShow();
        $this->checkAdd();

        if ($this->permintaan) {
            foreach ($this->permintaan->permintaanMaterial as $item) {
                $this->list[] = [
                    'id' => $item->id,
                    'merk' => $item->merkStok,
                    'img' => $item->img,
                    'jumlah' => $item->jumlah,
                    'editable' => is_null($item->img),
                ];
            }
        }

        $this->fillBarangs();
    }


    private function fillBarangs()
    {
        $rabId = $this->rab_id;
        $gudang_id = $this->gudang_id;
        $this->barangs = BarangStok::where('jenis_id', 1)->whereHas('merkStok.stok', function ($stok) use ($gudang_id) {
            return $stok->where('lokasi_id', $gudang_id);
        })->when($rabId > 0, function ($query) use ($rabId) { // Filter hanya jika $rabId > 0
            $query->whereHas('merkStok.listRab', function ($query) use ($rabId) {
                $query->where('rab_id', $rabId);
            });
        })->get();
    }

    #[On('tanggal_permintaan')]
    public function fillTanggalPenggunaan($tanggal_permintaan)
    {
        $this->tanggalPenggunaan = $tanggal_permintaan;
        // Cek jika ada nilai yang null atau kosong

        $this->checkShow();
    }
    #[On('keterangan')]
    public function fillKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
        // Cek jika ada nilai yang null atau kosong

        $this->checkShow();
    }
    public function resetImage($index)
    {
        $this->list[$index]['img'] = null;
    }

    public function saveItemPic($index)
    {
        // Pastikan data ada dan berupa file upload
        if (!isset($this->list[$index]['img']) || !is_object($this->list[$index]['img'])) {
            return;
        }

        // Simpan file ke storage
        $file = $this->list[$index]['img'];
        $filename = $file->getClientOriginalExtension();
        $file->storeAs('fotoPerBarang', $filename, 'public');

        // Simpan ke database sesuai model terkait
        $itemId = $this->list[$index]['id'] ?? null;
        if ($itemId) {
            $this->permintaan->permintaanMaterial()->where('id', $itemId)->update([
                'img' => $filename,
            ]);
        }

        // Update list agar sekarang nilai img jadi string (bukan file)
        $this->list[$index]['img'] = $filename;

        $allSaved = collect($this->list)->every(function ($item) {
            return isset($item['img']) && is_string($item['img']);
        });

        if ($allSaved) {
            $this->permintaan->update(['status' => 3]);
            return redirect()->to('permintaan/permintaan/' . $this->permintaan->id);
        }
    }
    #[On('rab_id')]
    public function fillRabId($rab_id)
    {
        $this->rab_id = $rab_id;

        $this->fillBarangs();

        $this->checkShow();
    }
    #[On('gudang_id')]
    public function fillGudangId($gudang_id)
    {
        $this->gudang_id = $gudang_id;

        $this->newJumlah = null;
        $this->newMerkId = null;


        $this->fillBarangs();

        $this->checkShow();
    }

    public function checkShow()
    {
        $this->showRule = $this->tanggalPenggunaan && $this->gudang_id;
    }
    public function updated($field)
    {
        if (!$this->newMerkId) {
            $this->newJumlah = null;
            $this->newUnit = 'Satuan';
        } else {
            $this->newUnit = MerkStok::find($this->newMerkId)->barangStok->satuanBesar->nama;
            $this->newMerkMax = Stok::where('merk_id', $this->newMerkId)->where('lokasi_id', $this->gudang_id)->sum('jumlah');
        }
        if ($field === 'newMerkId') {
            $this->newJumlah = null;
        }
        $this->checkAdd();
    }

    public function addToList()
    {
        $this->list[] = [
            'id' => null,
            'merk' => MerkStok::find($this->newMerkId),
            'img' => null,
            'jumlah' => $this->newJumlah
        ];
        $this->dispatch('listCount', count: count($this->list));
        $this->reset(['newMerkId', 'newJumlah', 'newUnit']);
        $this->checkAdd();
    }

    public function saveData()
    {


        $data = [];
        $user_id = Auth::id();


        $permintaan = DetailPermintaanMaterial::create([
            'kode_permintaan' => fake()->numerify('ABCD#######'),
            'user_id' => $user_id,
            'gudang_id' => $this->gudang_id,
            'keterangan' => $this->keterangan,
            'rab_id' => $this->rab_id,
            'tanggal_permintaan' => strtotime($this->tanggalPenggunaan)
        ]);

        foreach ($this->list as $item) {

            $data[] = [
                'detail_permintaan_id' => $permintaan->id,
                'user_id' => $user_id,
                'merk_id' => $item['merk']->id,
                'jumlah' => $item['jumlah'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            $stok = Stok::where('merk_id', $item['merk']->id)->where('lokasi_id', $this->gudang_id)->first();

            $stok->update(['jumlah' => $stok->jumlah - $item['jumlah']]);
        }



        PermintaanMaterial::insert($data);
        $this->reset('list');

        return redirect()->to('permintaan/material');
    }

    public function removeFromList($index)
    {

        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
        $this->dispatch('listCount', count: count($this->list));
    }
    public function checkAdd()
    {
        $this->ruleAdd = $this->newMerkId && $this->newJumlah && $this->newJumlah <= $this->newMerkMax;
    }
    public function render()
    {
        return view('livewire.list-permintaan-material');
    }
}
