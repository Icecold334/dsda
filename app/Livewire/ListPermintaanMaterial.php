<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Stok;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use App\Models\DetailPermintaanMaterial;
use App\Models\PermintaanMaterial;
use App\Models\Rab;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class ListPermintaanMaterial extends Component
{
    use WithFileUploads;
    public $permintaan, $tanggalPenggunaan, $keterangan, $isShow, $gudang_id, $withRab = 0, $lokasiMaterial, $nodin, $namaKegiatan, $isSeribu;
    public  $rabs, $rab_id,  $barangs = [], $merks = [], $dokumenCount, $newBarangId, $newRabId, $newMerkId, $newMerkMax, $newKeterangan, $newJumlah, $newUnit = 'Satuan', $showRule = false, $ruleAdd = false, $list = [], $dataKegiatan = [];

    public function mount()
    {
        // dd($this->isSeribu);
        $this->isShow = Request::routeIs('showPermintaan');
        $this->tanggalPenggunaan = Carbon::now()->format('Y-m-d'); // Format untuk date biasa
        $this->checkShow();
        $this->checkAdd();
        $this->rabs = Rab::where('status', 2)->whereHas('user.unitKerja', function ($unit) {
            $unit->where('parent_id', $this->unit_id)
                ->orWhere('id', $this->unit_id);
        })->orderBy('created_at', 'desc')->get();

        if ($this->permintaan) {
            if ($this->isSeribu) {
                $this->withRab = $this->permintaan->permintaanMaterial->first()->rab_id;
            }
            foreach ($this->permintaan->permintaanMaterial as $item) {
                $this->list[] = [
                    'id' => $item->id,
                    'rab_id' => $item->rab_id,
                    'merk' => $item->merkStok,
                    'img' => $item->img,
                    'jumlah' => $item->jumlah,
                    'keterangan' => $item->deskripsi,
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
    #[On('namaKegiatan')]
    public function fillNamaKegiatan($namaKegiatan)
    {
        $this->namaKegiatan = $namaKegiatan;
        // Cek jika ada nilai yang null atau kosong

        $this->checkShow();
    }
    #[On('nodin')]
    public function fillNodin($nodin)
    {
        $this->nodin = $nodin;
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
    #[On('withRab')]
    public function fillWithRab($withRab)
    {
        $this->withRab = $withRab;

        $this->fillBarangs();

        $this->checkShow();
    }
    #[On('lokasiMaterial')]
    public function fillLokasiMaterial($lokasiMaterial)
    {
        $this->lokasiMaterial = $lokasiMaterial;

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
        if ($this->withRab) {
            $this->showRule = $this->tanggalPenggunaan && $this->gudang_id && ($this->isSeribu && $this->withRab || $this->rab_id);
        } else {
            $this->showRule = $this->tanggalPenggunaan && $this->gudang_id && $this->lokasiMaterial && $this->keterangan && $this->nodin && $this->namaKegiatan;
        }
    }
    public function updated($field)
    {
        if (!$this->newMerkId) {
            $this->newJumlah = null;
            $this->newUnit = 'Satuan';
        } else {
            // $this->newUnit = MerkStok::find($this->newMerkId)->barangStok->satuanBesar->nama;
            $this->newMerkMax = Stok::where('merk_id', $this->newMerkId)->where('lokasi_id', $this->gudang_id)->sum('jumlah');
        }
        if ($field === 'newMerkId') {
            $this->newJumlah = null;
        } elseif ($field == 'newBarangId') {
            $rab_id = $this->rab_id;
            $gudang_id = $this->gudang_id;
            if ($this->newBarangId) {
                $this->newUnit = BarangStok::find($this->newBarangId)->satuanBesar->nama;

                if ($this->newRabId && $this->withRab) {
                    $rab_id = $this->newRabId;

                    $this->merks = BarangStok::find($this->newBarangId)->merkStok()->when($rab_id, function ($query) use ($rab_id) {
                        return $query->whereHas('listRab', function ($query) use ($rab_id) {
                            $query->where('rab_id', $rab_id);
                        });
                    })->whereHas('stok', function ($stok) use ($gudang_id) {
                        return $stok->where('lokasi_id', $gudang_id);
                    })->get();
                } else {
                    $this->merks = BarangStok::find($this->newBarangId)->merkStok()->when($rab_id, function ($query) use ($rab_id) {
                        return $query->whereHas('listRab', function ($query) use ($rab_id) {
                            $query->where('rab_id', $rab_id);
                        });
                    })->whereHas('stok', function ($stok) use ($gudang_id) {
                        return $stok->where('lokasi_id', $gudang_id);
                    })->get();
                }
            } else {
                $this->newUnit = 'Satuan';
                $this->newJumlah = null;
                $this->merks = [];
            }
        } elseif ($field == 'newRabId') {
            $rabId = $this->newRabId;
            $gudang_id = $this->gudang_id;
            $this->barangs = BarangStok::where('jenis_id', 1)->whereHas('merkStok.stok', function ($stok) use ($gudang_id) {
                return $stok->where('lokasi_id', $gudang_id);
            })->when($rabId > 0, function ($query) use ($rabId) { // Filter hanya jika $rabId > 0
                $query->whereHas('merkStok.listRab', function ($query) use ($rabId) {
                    $query->where('rab_id', $rabId);
                });
            })->get();
        }
        $this->checkAdd();
    }

    public function addToList()
    {
        $this->list[] = [
            'id' => null,
            'merk' => MerkStok::find($this->newMerkId),
            'img' => null,
            'rab_id' => $this->isSeribu ? $this->newRabId : null,
            'jumlah' => $this->newJumlah,
            'keterangan' => $this->newKeterangan ?? null
        ];
        $this->dispatch('listCount', count: count($this->list));
        $this->reset(['newMerkId', 'newJumlah', 'newUnit', 'newBarangId', 'newRabId', 'newKeterangan']);
        $this->checkAdd();
    }

    public function saveData()
    {


        $data = [];
        $user_id = Auth::id();


        $permintaan = DetailPermintaanMaterial::create([
            'kode_permintaan' => fake()->numerify('ABCD#######'),
            'user_id' => $user_id,
            'nodin' => $this->nodin,
            'gudang_id' => $this->gudang_id,
            'nama' => $this->namaKegiatan,
            'lokasi' => $this->lokasiMaterial,
            'keterangan' => $this->keterangan,
            'rab_id' => $this->rab_id ?? null,
            'tanggal_permintaan' => strtotime($this->tanggalPenggunaan)
        ]);

        foreach ($this->list as $item) {

            $data[] = [
                'detail_permintaan_id' => $permintaan->id,
                'user_id' => $user_id,
                'merk_id' => $item['merk']->id,
                'rab_id' => $this->isSeribu ? $item['rab_id']  : null,
                'jumlah' => $item['jumlah'],
                'deskripsi' => $item['keterangan'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            $stok = Stok::where('merk_id', $item['merk']->id)->where('lokasi_id', $this->gudang_id)->first();

            $stok->update(['jumlah' => $stok->jumlah - $item['jumlah']]);
        }



        PermintaanMaterial::insert($data);
        $this->reset('list');
        $this->dispatch('saveDokumen', kontrak_id: $permintaan->id, isRab: false, isMaterial: true);
        // return redirect()->to('permintaan/material');
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
