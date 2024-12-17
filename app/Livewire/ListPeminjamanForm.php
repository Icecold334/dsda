<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use Livewire\Component;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\PeminjamanAset;
use App\Models\WaktuPeminjaman;
use App\Models\DetailPeminjamanAset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ListPeminjamanForm extends Component
{

    use WithFileUploads;
    public $showNew;
    public $tipe;
    public $newWaktu;
    public $waktus;
    public $unit_id;
    public $sub_unit_id;
    public $tanggal_peminjaman;
    public $keterangan;
    public $peminjaman;
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
    public $showAdd; // Input for new dokumen
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
            'id' => null,
            'aset_id' => $this->newAsetId,
            'aset_name' => Aset::find($this->newAsetId)->nama,
            'waktu_id' => $this->newWaktu,
            'waktu' => WaktuPeminjaman::find($this->newWaktu),
            'jumlah' => $this->newJumlah,
            'jumlah_peserta' => $this->newPeserta,
            'keterangan' => $this->newKeterangan,
            'img' => $this->newDokumen,
        ];
        $this->dispatch('listCount', count: count($this->list));

        $this->reset(['newAsetId', 'newJumlah', 'newPeserta', 'newDokumen', 'newWaktu', 'newKeterangan']);
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
        $this->dispatch('listCount', count: count($this->list));
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
        // if ($this->tipe == 'Ruangan') {
        //     $this->showAdd = $this->newAsetId && $this->newWaktu && $this->newPeserta && $this->newKeterangan && $this->newDokumen;
        // }
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
        $this->tanggal_peminjaman = $tanggal_permintaan;
    }

    public function removePhoto()
    {
        $this->newDokumen = null;
    }

    public function saveData()
    {


        $kodepeminjaman = Str::random(10); // Generate a unique code

        // Create Detail peminjaman Stok
        $detailPeminjaman = DetailPeminjamanAset::create([
            'kode_peminjaman' => $kodepeminjaman,
            'tanggal_peminjaman' => strtotime($this->tanggal_peminjaman),
            'unit_id' => $this->unit_id,
            'sub_unit_id' => $this->sub_unit_id ?? null,
            'user_id' => Auth::id(),
            'kategori_id' => Kategori::where('nama', $this->tipe)->first()->id,
            'keterangan' => $this->keterangan,
            'status' => null
        ]);
        $this->peminjaman = $detailPeminjaman;
        foreach ($this->list as $item) {
            $storedFilePath = $item['img'] ? str_replace('undanganRapat/', '', $item['img']->storeAs(
                'undanganRapat', // Directory
                $item['img']->getClientOriginalName(), // File name
                'public' // Storage disk
            )) : null;
            PeminjamanAset::create([
                'detail_peminjaman_id' => $detailPeminjaman->id,
                'user_id' => Auth::id(),
                'aset_id' => $item['aset_id'] ?? null,
                'deskripsi' => $item['keterangan'] ?? null,
                // 'catatan' => $item['catatan'] ?? null,
                'img' => $storedFilePath,
                'waktu_id' => $item['waktu_id'],
                'jumlah_orang' => $item['jumlah_peserta'],
                'jumlah' => $item['jumlah'],
            ]);
        }
        return redirect()->to('permintaan/peminjaman/' . $this->peminjaman->id)->with('tanya', 'berhasil');
    }

    public function mount()
    {

        $this->showNew = Request::is('permintaan/add/peminjaman');

        if ($this->peminjaman) {
            $this->tanggal_peminjaman = $this->peminjaman->tanggal_peminjaman;
            $this->keterangan = $this->peminjaman->keterangan;
            $this->unit_id = $this->peminjaman->unit_id;
            $this->sub_unit_id = $this->peminjaman->sub_unit_id;
            $this->tipe = Kategori::find($this->peminjaman->kategori_id)->nama;
            foreach ($this->peminjaman->peminjamanAset as $key => $value) {
                // $this->unit_id = $this->permintaan->unit_id;
                // $this->keterangan = $this->permintaan->keterangan;
                // $this->tanggal_permintaan = $this->permintaan->tanggal_permintaan;

                $this->list[] = [
                    'id' => $value->id,
                    'aset_id' => $value->aset_id,
                    'aset_name' => Aset::find($value->aset_id)->nama,
                    'waktu_id' => $value->waktu_id,
                    'waktu' => WaktuPeminjaman::find($value->waktu_id),
                    'jumlah' => $value->jumlah,
                    'jumlah_peserta' => $value->jumlah_peserta,
                    'keterangan' => $value->deskripsi,
                    'img' => $value->img,
                ];
            }
        };
        $this->waktus = WaktuPeminjaman::all();

        $this->tanggal_peminjaman = Carbon::now()->format('Y-m-d');
    }
    public function render()
    {
        return view('livewire.list-peminjaman-form');
    }
}
