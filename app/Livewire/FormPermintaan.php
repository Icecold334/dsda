<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Rab;
use App\Models\Aset;
use App\Models\Ruang;
use BaconQrCode\Writer;
use Livewire\Component;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\KategoriStok;
use Illuminate\Support\Facades\Auth;
use BaconQrCode\Renderer\GDLibRenderer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class FormPermintaan extends Component
{
    public $permintaan, $kategori;
    public $units;
    public $last, $withRab = 0, $lokasiMaterial, $namaKegiatan, $nodin, $isSeribu, $Rkb;
    public $unit_id, $rab_id, $rabs, $gudang_id, $gudangs;
    public $kategoris;
    public $kategori_id;
    public $tipePeminjaman;
    public $subUnits;
    public $tipe;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $tanggal_masuk;
    public $tanggal_keluar;
    public $keterangan;
    public $listCount;
    public $RuangId;
    public $ruangs;
    public $KDOId;
    public $kdos;
    public $peserta;
    public $LokasiLain;
    public $AlamatLokasi;
    public $KontakPerson;
    public $kecamatan_id, $kelurahan_id;
    public $kecamatans = [], $kelurahans = [];

    #[On('listCount')]
    public function updateListCount($count)
    {
        $this->listCount = $count;
    }

    public function updatedUnitId()
    {
        if ($this->unit_id) {
            $this->subUnits = UnitKerja::where('parent_id', $this->unit_id)->get();
        }
        $this->dispatch('unit_id', unit_id: $this->unit_id);
    }
    public function updatedTipePeminjaman()
    {
        $this->dispatch('peminjaman', peminjaman: $this->tipePeminjaman);
    }
    public function updatedKategoriId()
    {
        $this->dispatch('kategori_id', kategori_id: $this->kategori_id);
    }
    public function updatedRabId()
    {
        $this->dispatch('rab_id', rab_id: $this->rab_id);
    }
    public function updatedNodin()
    {
        $this->dispatch('nodin', nodin: $this->nodin);
    }
    public function updatedNamaKegiatan()
    {
        $this->dispatch('namaKegiatan', namaKegiatan: $this->namaKegiatan);
    }

    public function updatedLokasiMaterial()
    {
        $this->dispatch('lokasiMaterial', lokasiMaterial: $this->lokasiMaterial);
    }
    public function updatedTanggalPermintaan()
    {
        $this->dispatch('tanggal_permintaan', tanggal_permintaan: $this->tanggal_permintaan);
    }
    public function updatedKeterangan()
    {
        $this->dispatch('keterangan', keterangan: $this->keterangan);
    }

    public function updatedSubUnitId()
    {
        $this->dispatch('sub_unit_id', sub_unit_id: $this->sub_unit_id);
    }
    public function updatedPeserta()
    {
        $this->dispatch('peserta', peserta: $this->peserta);
    }
    public function updatedRuangId()
    {
        $this->dispatch('RuangId', RuangId: $this->RuangId);
    }
    public function updatedLokasiLain()
    {
        $this->dispatch('LokasiLain', LokasiLain: $this->LokasiLain);
    }
    public function updatedAlamatLokasi()
    {
        $this->dispatch('AlamatLokasi', AlamatLokasi: $this->AlamatLokasi);
    }
    public function updatedKontakPerson()
    {
        $this->dispatch('KontakPerson', KontakPerson: $this->KontakPerson);
    }
    public function updatedKDOId()
    {
        $this->dispatch('KDOId', KDOId: $this->KDOId);
    }
    public function updatedTanggalMasuk()
    {
        $this->dispatch('tanggal_masuk', tanggal_masuk: $this->tanggal_masuk);
    }
    public function updatedTanggalKeluar()
    {
        $this->dispatch('tanggal_keluar', tanggal_keluar: $this->tanggal_keluar);
    }
    public function updatedGudangId()
    {
        $this->dispatch('gudang_id', gudang_id: $this->gudang_id);
    }
    public function updatedWithRab()
    {
        if (!$this->withRab) {
            $this->rab_id = null;
        }
        $this->dispatch('withRab', withRab: $this->withRab);
    }

    public $showKategori;

    public function mount()
    {
        $kategori = Request::segment(4);
        $this->kategori = $kategori;
        $this->gudangs = LokasiStok::where('unit_id', $this->unit_id)
            ->whereHas('transaksiStok', function ($query) {
                $query->whereHas('merkStok.barangStok', function ($q) {
                    $q->where('jenis_id', 1);
                });
            })
            ->with(['transaksiStok.merkStok.barangStok' => function ($q) {
                $q->where('jenis_id', 1);
            }])
            ->get()
            ->filter(function ($lokasi) {
                $jumlah = 0;

                foreach ($lokasi->transaksiStok as $trx) {
                    $barang = $trx->merkStok->barangStok ?? null;
                    if (!$barang || $barang->jenis_id !== 1) continue;

                    $jumlah += match ($trx->tipe) {
                        'Pemasukan' => (int) $trx->jumlah,
                        'Pengeluaran', 'Pengajuan' => - ((int) $trx->jumlah),
                        'Penyesuaian' => (int) $trx->jumlah,
                        default => 0,
                    };
                }

                return $jumlah > 0;
            })
            ->values(); // optional: reindex collection


        // dd($this->last);
        // 2024 - 12 - 04
        if ($this->last) {
            $this->kategori_id = $this->last->kategori_id;

            if ($this->last->kategori_id === 4) {
                $this->tanggal_permintaan = Carbon::createFromTimestamp($this->last->{"tanggal_{$this->tipe}"})
                    ->format('Y-m-d\TH:i'); // Format untuk datetime-local
            } else {
                $this->tanggal_permintaan = Carbon::createFromTimestamp($this->last->{"tanggal_{$this->tipe}"})
                    ->format('Y-m-d'); // Format untuk date biasa
                $this->tanggal_masuk = Carbon::createFromTimestamp($this->last->tanggal_masuk)
                    ->format('Y-m-d'); // Format untuk date biasa
                $this->tanggal_keluar = Carbon::createFromTimestamp($this->last->tanggal_keluar)
                    ->addDay()->format('Y-m-d'); // Format untuk date biasa
            }
            $this->dispatch('tanggal_permintaan', tanggal_permintaan: $this->tanggal_permintaan);
            $this->dispatch('tanggal_masuk', tanggal_masuk: $this->tanggal_masuk);
            $this->dispatch('tanggal_keluar', tanggal_keluar: $this->tanggal_keluar);

            $this->keterangan = $this->last->keterangan;
            $this->dispatch('keterangan', keterangan: $this->keterangan);

            $this->sub_unit_id = $this->last->sub_unit_id;
            $this->dispatch('sub_unit_id', sub_unit_id: $this->sub_unit_id);

            $this->peserta = $this->last->peserta;
            $this->dispatch('peserta', peserta: $this->peserta);

            $this->RuangId = $this->last->RuangId;
            $this->dispatch('RuangId', RuangId: $this->RuangId);

            $this->LokasiLain = $this->last->LokasiLain;
            $this->dispatch('LokasiLain', LokasiLain: $this->LokasiLain);

            $this->AlamatLokasi = $this->last->AlamatLokasi;
            $this->dispatch('AlamatLokasi', AlamatLokasi: $this->AlamatLokasi);

            $this->KontakPerson = $this->last->KontakPerson;
            $this->dispatch('KontakPerson', KontakPerson: $this->KontakPerson);

            $this->KDOId = $this->last->KDOId;
            $this->dispatch('KDOId', KDOId: $this->KDOId);

            $this->tanggal_masuk = $this->last->tanggal_masuk;
            $this->dispatch('tanggal_masuk', tanggal_masuk: $this->tanggal_masuk);

            $this->tanggal_keluar = $this->last->tanggal_keluar;
            $this->dispatch('tanggal_keluar', tanggal_keluar: $this->tanggal_keluar);

            if ($this->tipe == 'peminjaman') {
                # code...
                $this->tipePeminjaman = Kategori::find($this->last->kategori_id)->nama;
                $this->dispatch('peminjaman', peminjaman: $this->tipePeminjaman);
            }
        } else {
            if ($kategori == 'konsumsi') {
                $this->tanggal_permintaan = Carbon::now()->format('Y-m-d\TH:i'); // Format datetime-local
            } else {
                $this->tanggal_permintaan = Carbon::now()->format('Y-m-d'); // Format date biasa
                $this->tanggal_masuk = Carbon::now()->format('Y-m-d'); // Format date biasa
                $this->tanggal_keluar = Carbon::tomorrow()->format('Y-m-d'); // Format date biasa
            }
        }

        $cond = true;
        $this->ruangs =  Ruang::when($cond, function ($query) {
            $query->whereHas('user', function ($query) {
                return $query->whereHas('unitKerja', function ($query) {
                    return $query->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                });
            });
        })->get();

        $KDO = 'KDO';
        $kategori_KDO = Kategori::where('nama', $KDO)->first();

        $this->kdos =
            Aset::when($cond, function ($query) {
                $query->whereHas('user', function ($query) {
                    return $query->whereHas('unitKerja', function ($query) {
                        return $query->where('parent_id', $this->unit_id)
                            ->orWhere('id', $this->unit_id);
                    });
                });
            })->whereHas('kategori', function ($query) use ($kategori_KDO) {
                return $query->where('parent_id', $kategori_KDO->id)->orWhere('id', $kategori_KDO->id);
            })->where('perbaikan', 1)->get();

        $this->showKategori = Request::is('permintaan/add/permintaan*');
        $this->units = UnitKerja::whereNull('parent_id')->whereHas('children', function ($sub) {
            return $sub;
        })->get();
        $this->tipePeminjaman = Kategori::find($kategori == 'kdo' ? 1 : ($kategori == 'ruangan' ? 2 : 8))->nama;
        $this->kategoris = KategoriStok::whereHas('barangStok', function ($barang) {
            return $barang->whereHas('merkStok', function ($merk) {
                return $merk;
            });
        })->get();
        if ($this->unit_id) {
            $this->subUnits = UnitKerja::where('parent_id', $this->unit_id)->get();
        }
        if ($this->permintaan) {
            $this->updatedUnitId();
            $this->dispatch('unit_id', unit_id: $this->unit_id);
            $this->dispatch(event: 'tanggal_permintaan', tanggal_permintaan: $this->tanggal_permintaan);
            $this->dispatch('keterangan', keterangan: $this->keterangan);
            $this->dispatch('sub_unit_id', sub_unit_id: $this->sub_unit_id);
            $this->dispatch('RuangId', RuangId: $this->RuangId);
            $this->dispatch('LokasiLain', LokasiLain: $this->LokasiLain);
            $this->dispatch('AlamatLokasi', AlamatLokasi: $this->AlamatLokasi);
            $this->dispatch('KontakPerson', KontakPerson: $this->KontakPerson);
            $this->dispatch('KDOId', KDOId: $this->KDOId);
            $this->dispatch('tanggal_masuk', tanggal_masuk: $this->tanggal_masuk);
            $this->dispatch('tanggal_keluar', tanggal_keluar: $this->tanggal_keluar);
        }

        $this->rabs = Rab::where('status', 2)->whereHas('user.unitKerja', function ($unit) {
            $unit->where('parent_id', $this->unit_id)
                ->orWhere('id', $this->unit_id);
        })->orderBy('created_at', 'desc')->get();

        $this->kecamatans = Kecamatan::where('unit_id', $this->unit_id)->get();
    }

    public function updatedKecamatanId()
    {
        $this->kelurahans = Kelurahan::where('kecamatan_id', $this->kecamatan_id)->get();
        $this->kelurahan_id = null;
    }

    public function updatedKelurahanId()
    {
        $kelurahanId = $this->kelurahan_id;
        $this->dispatch('kelurahanId', id: $kelurahanId);
    }



    public function render()
    {
        return view('livewire.form-permintaan');
    }
}
