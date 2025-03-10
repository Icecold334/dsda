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
use App\Models\PersetujuanPeminjamanAset;
use App\Models\Ruang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ListPeminjamanForm extends Component
{

    use WithFileUploads;
    public $approve_after;
    public $approvals;
    public $showNew;
    public $tipe;
    public $newWaktu;
    public $waktus;
    public $unit_id;
    public $sub_unit_id;
    public $tanggal_peminjaman;
    public $keterangan;
    public $last;
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
    public $ruangs = [];
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
        // dd($kategori->children);
        // if ($this->tipe == 'Ruangan') {
        //     $this->showAdd = $this->newAsetId && $this->newWaktu && $this->newPeserta && $this->newKeterangan && $this->newDokumen;
        // }
        $cond = true;
        // dd($tipe);
        if ($tipe == 'Ruangan') {
            $this->asets =  Ruang::when($cond, function ($query) {
                $query->whereHas('user', function ($query) {
                    return $query->whereHas('unitKerja', function ($query) {
                        return $query->where('parent_id', $this->unit_id)
                            ->orWhere('id', $this->unit_id);
                    });
                });
            })->get();
        } else {
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
                })->where('peminjaman', 1)->get();
        }
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
        $latestApprovalConfiguration = \App\Models\OpsiPersetujuan::where('jenis', Str::lower($this->tipe == 'Peralatan Kantor' ? 'alat' : $this->tipe))
            ->where('unit_id', $this->unit_id)
            ->where('created_at', '<=', now()) // Pastikan data sebelum waktu saat ini
            ->latest()
            ->first();
        $kodepeminjaman = Str::random(10); // Generate a unique code

        // Create Detail peminjaman Stok
        $detailPeminjaman = DetailPeminjamanAset::create([
            'kode_peminjaman' => $kodepeminjaman,
            'tanggal_peminjaman' => strtotime($this->tanggal_peminjaman),
            'unit_id' => $this->unit_id,
            'sub_unit_id' => $this->sub_unit_id ?? null,
            'user_id' => Auth::id(),
            'kategori_id' => Kategori::where('nama', $this->tipe)->first()->id,
            'approval_configuration_id' => $latestApprovalConfiguration->id,
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
        $this->showNew = Request::is('permintaan/add/peminjaman*');
        if ($this->last) {


            $this->keterangan = $this->last->keterangan;
            $this->dispatch('keterangan', keterangan: $this->keterangan);

            $this->sub_unit_id = $this->last->sub_unit_id;
            $this->dispatch('sub_unit_id', sub_unit_id: $this->sub_unit_id);

            $this->fillTipe(Kategori::find($this->last->kategori_id)->nama);
        }
        if ($this->peminjaman) {
            $this->fillTipe($this->peminjaman->kategori->nama);
            $this->tanggal_peminjaman = $this->peminjaman->tanggal_peminjaman;
            $this->keterangan = $this->peminjaman->keterangan;
            $this->unit_id = $this->peminjaman->unit_id;
            $this->sub_unit_id = $this->peminjaman->sub_unit_id;
            $this->tipe = Kategori::find($this->peminjaman->kategori_id)->nama;
            foreach ($this->peminjaman->peminjamanAset as $key => $value) {
                $this->list[] = [
                    'id' => $value->id,
                    'detail_peminjaman_id' => $value->detail_peminjaman_id,
                    'aset_id' => $value->aset_id,
                    'approved_aset_id' => $value->approved_aset_id ?? null,
                    'aset_name' => Aset::find($value->aset_id)->nama,
                    'approved_aset_name' => Aset::find($value->approved_aset_id)->nama ?? null,
                    'waktu_id' => $value->waktu_id,
                    'approved_waktu_id' => $value->approved_waktu_id ?? null,
                    'waktu' => WaktuPeminjaman::find($value->waktu_id),
                    'approved_waktu' => WaktuPeminjaman::find($value->approved_waktu_id) ?? null,
                    'jumlah' => $value->jumlah,
                    'approved_jumlah' => $value->jumlah_approve ?? null,
                    'jumlah_peserta' => $value->jumlah_orang,
                    'keterangan' => $value->deskripsi,
                    'img' => $value->img,
                    'fix' => $this->tipe == 'Ruangan' ? $value->approved_aset_id && $value->approved_waktu_id : ($this->tipe == 'KDO' ? $value->approved_aset_id && $value->approved_waktu_id : $value->approved_aset_id && $value->approved_waktu_id && $value->jumlah_approve)
                ];
            }
            $approve_after = $this->approve_after = $this->peminjaman->opsiPersetujuan->jabatanPersetujuan->pluck('jabatan.name')->toArray()[$this->peminjaman->opsiPersetujuan->urutan_persetujuan - 1];

            $this->approvals = PersetujuanPeminjamanAset::where('status', true)->where('detail_peminjaman_id', $this->peminjaman->id)
                ->whereHas('user', function ($query) use ($approve_after) {
                    $query->role($approve_after); // Muat hanya persetujuan dari kepala_seksi
                })
                ->pluck('detail_peminjaman_id') // Ambil hanya detail_permintaan_id yang sudah disetujui
                ->toArray();
        } else {
            $this->fillTipe($this->tipe);
        };
        $this->waktus = WaktuPeminjaman::all();

        $this->tanggal_peminjaman = Carbon::now()->format('Y-m-d');
    }

    public $availHours;

    public function updatedNewAsetId()
    {
        $selectedAsetId = $this->newAsetId;
        // Ambil waktu yang telah di-booking untuk aset yang dipilih pada hari ini
        $bookedTimes = PeminjamanAset::where('aset_id', $selectedAsetId)
            ->whereHas('detailPeminjaman', function ($query) {
                $todayStart = strtotime($this->tanggal_peminjaman); // Waktu mulai (00:00:00)
                $todayEnd = strtotime($this->tanggal_peminjaman . ' 23:59:59'); // Waktu akhir (23:59:59)


                $query->whereBetween('tanggal_peminjaman', [$todayStart, $todayEnd])
                    ->where(function ($query) {
                        $query->whereNull('status')->orWhere('status', '!=', 0);
                    });
            })
            ->pluck('waktu_id')
            ->toArray();
        $waktus = WaktuPeminjaman::all();
        $this->waktus = $waktus->reject(function ($item) use ($bookedTimes) {
            return in_array($item->id, $bookedTimes);
        });
    }

    public function approveItem($index, $message)
    {
        // // Validasi input
        // $this->validate([
        //     "list.$index.approved_aset_id" => 'required',
        //     "list.$index.approved_waktu_id" => 'required',
        // ], [
        //     "list.$index.approved_aset_id.required" => "Layanan untuk item ke-{$index} harus dipilih.",
        //     "list.$index.approved_waktu_id.required" => "Waktu untuk item ke-{$index} harus dipilih.",
        // ]);

        // Tandai item sebagai "fix"
        $this->list[$index]['fix'] = true;


        // Simpan perubahan ke database (misalnya, tabel PeminjamanAset)
        $peminjamanAset = PeminjamanAset::find($this->list[$index]['id']);
        // dd($peminjamanAset, $message);
        if ($peminjamanAset) {

            $data = $this->tipe == 'Peralatan Kantor' ? [
                'approved_aset_id' => $this->list[$index]['approved_aset_id'],
                'approved_waktu_id' => $this->list[$index]['approved_waktu_id'],
                'jumlah_approve' => $this->list[$index]['approved_jumlah'],
                'catatan_approved' => $message,
            ] : [
                'approved_aset_id' => $this->list[$index]['approved_aset_id'],
                'approved_waktu_id' => $this->list[$index]['approved_waktu_id'],
                'catatan_approved' => $message,
            ];
            $peminjamanAset->update($data);
        }
        $this->dispatch('success', "Peminjaman disetujui!");

        // session()->flash('message', "Item pada baris ke-{$index} berhasil disetujui.");
    }

    public function render()
    {
        return view('livewire.list-peminjaman-form');
    }
}
