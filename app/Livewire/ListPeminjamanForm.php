<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Kategori;
use App\Models\WaktuPeminjaman;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class ListPeminjamanForm extends Component
{


    public $tipe;
    public $newWaktu;
    public $waktus;
    public $unit_id;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $keterangan;
    public $permintaan;
    public $list = [];
    public $newAsetId;
    public $newMerkJenis;
    public $newPeminjaman = 1;
    public $newDisetujui;
    public $newPeserta;
    public $newKeterangan;
    public $newBarangId; // Input for new barang
    public $newBarang; // Input for new barang
    public $newJumlah; // Input for new jumlah
    public $newDokumen; // Input for new dokumen
    public $barangSuggestions = []; // Suggestions for barang
    public $assetSuggestions = [];
    public $asets = [];
    public $suggestions = [
        'barang' => [],
        'aset' => []
    ];

    public function fetchSuggestions($field, $value = '')
    {
        $this->suggestions[$field] = [];
        // if ($value) {
        $key = Str::slug($value);

        if ($field === 'aset') {
            $tipe = 'KDO';
            $this->suggestions[$field] = Aset::whereHas('kategori', function ($kategori) use ($tipe) {
                return $kategori->where('nama', $tipe);
            })->where('slug', 'like', '%' . $key . '%')
                ->pluck('nama')->toArray();
        }
        // }
    }
    public function selectSuggestion($field, $value)
    {
        // if ($field === 'aset') {
        //     $this->newAset = $value;
        // }
        $this->suggestions[$field] = [];
    }
    public function blurSpecification($key)
    {
        $this->suggestions[$key] = [];
    }

    public function addToList()
    {
        // $this->validate([
        //     'newAset' => 'required',
        //     'newPermintaan' => 'required|integer|min:1',
        //     'newTanggalPeminjaman' => 'required|date',
        //     'newTanggalPengembalian' => 'required|date|after:newTanggalPeminjaman',
        //     'newKeterangan' => 'nullable|string',
        // ]);

        $this->list[] = [
            'aset_id' => $this->newAsetId,
            'aset_name' => Aset::find($this->newAsetId)->nama,
            'waktu_id' => $this->newWaktu,
            'waktu' => WaktuPeminjaman::find($this->newWaktu),
            'jumlah' => $this->newJumlah,
            'jumlah_peserta' => $this->newPeserta,
            'keterangan' => $this->newKeterangan,
            'img' => $this->newDokumen,
            // 'aset_name' => $this->newAsetId,
            // 'merk_jenis' => $this->newMerkJenis,
            // 'disetujui' => $this->newDisetujui,
        ];

        $this->reset(['newAsetId', 'newJumlah', 'newPeserta', 'newDokumen', 'newWaktu', 'newKeterangan']);
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
    }

    #[On('unit_id')]
    public function fillUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }
    #[On('peminjaman')]
    public function fillTipe($peminjaman)
    {
        $this->tipe = $peminjaman;
        $tipe = $this->tipe;
        $kategori = Kategori::where('nama', $tipe)->first();
        $cond = false;
        $this->asets =
            Aset::when($cond, function ($query) {
                $query->whereHas('user', function ($query) {
                    return $query->whereHas('unitKerja', function ($query) {
                        return $query->where('parent_id', $this->unit_id)
                            ->orWhere('id', $this->unit_id);
                    });
                });
            })->whereHas('kategori', function ($query) use ($kategori) {
                return $query->where('parent_id', $kategori->id)->orWhere('id', $kategori->id);
            })->get();
    }

    #[On('sub_unit_id')]
    public function fillSubUnitId($sub_unit_id)
    {
        $this->sub_unit_id = $sub_unit_id;
    }
    #[On('keterangan')]
    public function fillKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
    }

    #[On('tanggal_permintaan')]
    public function fillTanggalPermintaan($tanggal_permintaan)
    {
        $this->tanggal_permintaan = $tanggal_permintaan;
    }

    public function mount()
    {
        $this->waktus = WaktuPeminjaman::all();

        $this->tanggal_permintaan = Carbon::now()->format('Y-m-d');
    }
    public function render()
    {
        return view('livewire.list-peminjaman-form');
    }
}
