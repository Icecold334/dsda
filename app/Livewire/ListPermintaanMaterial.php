<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Rab;
use App\Models\Stok;
use App\Models\User;
use Livewire\Component;
use App\Models\MerkStok;
use App\Models\BarangStok;
use Livewire\Attributes\On;
use App\Models\StokDisetujui;
use App\Models\TransaksiStok;
use Livewire\WithFileUploads;
use App\Models\PermintaanMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\DetailPermintaanMaterial;
use App\Helpers\StokHelper;
use App\Models\Merk;

class ListPermintaanMaterial extends Component
{
    use WithFileUploads;
    public $readonlyAlokasiMerkId = null;
    public $readonlyAlokasiIndex = null;
    public $permintaan, $tanggalPenggunaan, $keterangan, $isShow, $gudang_id, $withRab = 0, $kelurahanId, $lokasiMaterial, $nodin, $namaKegiatan, $isSeribu, $saluran_jenis, $saluran_id;
    public $distribusiModalIndex = null;
    public $alokasiInput = []; // key: posisi_id, value: jumlah
    public $alokasiSisa = 0;
    public $stokDistribusiList = [];

    public $rabs, $rab_id, $vol = [], $barangs = [], $merks = [], $dokumenCount, $newBarangId, $newRabId, $newMerkId, $newMerkMax, $newKeterangan, $newJumlah, $newUnit = 'Satuan', $showRule = false, $ruleAdd = false, $list = [], $dataKegiatan = [];

    public function mount($permintaan)
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
                    'merk_id' => $item->merkStok->id,
                    'img' => $item->img,
                    'jumlah' => $item->jumlah,
                    'keterangan' => $item->deskripsi,
                    'editable' => true, // Selalu bisa diedit untuk foto barang diterima
                ];
            }
        }

        $this->fillBarangs();

        // logic form pengurus barang
        if ($permintaan && isset($permintaan->gudang_id)) {
            $gudangId = $permintaan->gudang_id;
            $barangIds = collect($this->list)->pluck('merk.barang_id')->unique()->toArray();

            $this->merks = \App\Models\MerkStok::whereIn('barang_id', $barangIds)
                ->whereHas('transaksiStok', function ($q) use ($gudangId) {
                    $q->where('lokasi_id', $gudangId);
                })->get();
        } else {
            $this->merks = collect();
        }
    }

    #[On('saluranJenis')]
    public function setSaluranJenis($saluran_jenis)
    {
        $this->saluran_jenis = $saluran_jenis;
    }
    #[On('vol')]
    public function setVol($vol)
    {
        $this->vol = $vol;
        $this->checkShow();
    }
    #[On('saluran_id')]
    public function setSaluranId($saluran_id)
    {
        // dd($saluran_id);

        $this->saluran_id = $saluran_id;
    }
    public function openReadonlyAlokasiModal($merkId, $index)
    {
        $this->readonlyAlokasiMerkId = $merkId;
        $this->readonlyAlokasiIndex = $index;
    }

    private function fillBarangs()
    {
        $rabId = $this->rab_id;
        $gudang_id = $this->gudang_id;

        if ($this->withRab && $rabId > 0) {
            // Jika menggunakan RAB, ambil semua barang dari RAB
            // tanpa peduli stok di gudang (validasi stok dilakukan di level input)
            $rabItems = \App\Models\ListRab::with(['merkStok.barangStok'])
                ->where('rab_id', $rabId)
                ->whereHas('merkStok.barangStok', function ($q) {
                    $q->where('jenis_id', 1); // hanya material
                })
                ->get();

            $barangIds = $rabItems->pluck('merkStok.barangStok.id')->unique()->filter();
            $this->barangs = \App\Models\BarangStok::whereIn('id', $barangIds)->get();
        } else {
            // Jika tidak menggunakan RAB, gunakan logika lama berdasarkan stok gudang
            $transaksis = TransaksiStok::with(['merkStok.barangStok'])
                ->where(function ($q) use ($gudang_id) {
                    $q->where('lokasi_id', $gudang_id)
                        ->orWhereHas('bagianStok', fn($q) => $q->where('lokasi_id', $gudang_id))
                        ->orWhereHas('posisiStok.bagianStok', fn($q) => $q->where('lokasi_id', $gudang_id));
                })
                ->whereHas('merkStok.barangStok', function ($q) {
                    $q->where('jenis_id', 1); // hanya material
                })
                ->get();

            // Hitung total per barang ID
            $barangTotals = [];

            foreach ($transaksis as $trx) {
                $barang = $trx->merkStok->barangStok;
                if (!$barang)
                    continue;

                $barangId = $barang->id;

                $jumlah = match ($trx->tipe) {
                    'Penyesuaian' => (int) $trx->jumlah,
                    'Pemasukan' => (int) $trx->jumlah,
                    'Pengeluaran' => -(int) $trx->jumlah,
                    default => 0,
                };

                $barangTotals[$barangId] = ($barangTotals[$barangId] ?? 0) + $jumlah;
            }

            // Ambil hanya barang yang stok totalnya > 0
            $ids = collect($barangTotals)->filter(fn($val) => $val > 0)->keys();
            $this->barangs = \App\Models\BarangStok::whereIn('id', $ids)->get();
        }
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
    #[On('kelurahanId')]
    public function fillKelurahanID($id)
    {
        $this->kelurahanId = $id;
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
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
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
            // Gunakan StokHelper untuk menghitung maksimal yang bisa diminta
            // Tentukan RAB ID yang benar berdasarkan mode
            $rabIdForValidation = null;
            if ($this->withRab) {
                if ($this->isSeribu && $this->newRabId) {
                    // Untuk mode Seribu, gunakan RAB per-item
                    $rabIdForValidation = $this->newRabId;
                } elseif (!$this->isSeribu && $this->rab_id) {
                    // Untuk mode normal, gunakan RAB dari form utama
                    $rabIdForValidation = $this->rab_id;
                }
            }

            $this->newMerkMax = \App\Helpers\StokHelper::calculateMaxPermintaan(
                $this->newMerkId,
                $rabIdForValidation,
                $this->gudang_id
            );
        }
        if ($field === 'newMerkId') {
            $this->newJumlah = null;
        } elseif ($field == 'newBarangId') {
            $this->newJumlah = null;
            $this->newMerkMax = null;
            $this->newMerkId = null;
            $barang = BarangStok::find($this->newBarangId);
            $this->newUnit = $barang?->satuanBesar->nama ?? 'Satuan';

            // Gunakan rab_id dari form utama, bukan newRabId
            $rab_id = $this->rab_id;
            $gudang_id = $this->gudang_id;

            if ($this->withRab && $rab_id) {
                // Jika menggunakan RAB, ambil merk dari RAB untuk barang yang dipilih
                $this->merks = MerkStok::where('barang_id', $this->newBarangId)
                    ->whereHas('listRab', function ($qr) use ($rab_id) {
                        $qr->where('rab_id', $rab_id);
                    })->get();
            } else {
                // Jika tidak menggunakan RAB, gunakan logika lama berdasarkan stok gudang
                // Ambil semua transaksi untuk barang terpilih
                $transaksis = \App\Models\TransaksiStok::with('merkStok')
                    ->whereHas('merkStok', function ($q) {
                        $q->whereHas('barangStok', function ($b) {
                            $b->where('jenis_id', 1); // hanya material
                        });
                    })
                    ->where(function ($q) use ($gudang_id) {
                        $q->where('lokasi_id', $gudang_id)
                            ->orWhereHas('bagianStok', fn($q) => $q->where('lokasi_id', $gudang_id))
                            ->orWhereHas('posisiStok.bagianStok', fn($q) => $q->where('lokasi_id', $gudang_id));
                    })
                    ->get();

                $merkTotals = [];

                foreach ($transaksis as $trx) {
                    $merkId = $trx->merk_id;
                    $jumlah = match ($trx->tipe) {
                        'Penyesuaian' => (int) $trx->jumlah,
                        'Pemasukan' => (int) $trx->jumlah,
                        'Pengeluaran', 'Pengajuan' => -(int) $trx->jumlah,
                        default => 0,
                    };
                    $merkTotals[$merkId] = ($merkTotals[$merkId] ?? 0) + $jumlah;
                }

                $availableMerkIds = collect($merkTotals)->filter(fn($val) => $val > 0)->keys();

                $this->merks = MerkStok::whereIn('id', $availableMerkIds)
                    ->where('barang_id', $this->newBarangId)
                    ->get();
            }
        } elseif ($field == 'newRabId') {
            // Field newRabId hanya digunakan untuk kasus isSeribu
            // Dimana setiap item bisa memiliki RAB yang berbeda
            $rabId = $this->newRabId;
            $gudang_id = $this->gudang_id;

            if ($this->isSeribu && $this->withRab && $rabId > 0) {
                // Jika menggunakan RAB per-item (kasus Seribu), ambil barang dari RAB
                $rabItems = \App\Models\ListRab::with(['merkStok.barangStok'])
                    ->where('rab_id', $rabId)
                    ->whereHas('merkStok.barangStok', function ($q) {
                        $q->where('jenis_id', 1); // hanya material
                    })
                    ->get();

                $barangIds = $rabItems->pluck('merkStok.barangStok.id')->unique()->filter();
                $this->barangs = \App\Models\BarangStok::whereIn('id', $barangIds)->get();
            } else {
                // Jika tidak menggunakan RAB, gunakan logika berdasarkan stok gudang
                $transaksis = \App\Models\TransaksiStok::with(['merkStok.barangStok'])
                    ->where(function ($q) use ($gudang_id) {
                        $q->where('lokasi_id', $gudang_id)
                            ->orWhereHas('bagianStok', fn($q) => $q->where('lokasi_id', $gudang_id))
                            ->orWhereHas('posisiStok.bagianStok', fn($q) => $q->where('lokasi_id', $gudang_id));
                    })
                    ->whereHas('merkStok.barangStok', function ($q) {
                        $q->where('jenis_id', 1); // hanya material
                    })
                    ->get();

                // Hitung total per barang ID
                $barangTotals = [];

                foreach ($transaksis as $trx) {
                    $barang = $trx->merkStok->barangStok;
                    if (!$barang)
                        continue;

                    $barangId = $barang->id;

                    $jumlah = match ($trx->tipe) {
                        'Penyesuaian' => (int) $trx->jumlah,
                        'Pemasukan' => (int) $trx->jumlah,
                        'Pengeluaran' => -(int) $trx->jumlah,
                        default => 0,
                    };

                    $barangTotals[$barangId] = ($barangTotals[$barangId] ?? 0) + $jumlah;
                }

                // Ambil hanya barang yang stok totalnya > 0
                $ids = collect($barangTotals)->filter(fn($val) => $val > 0)->keys();
                $this->barangs = \App\Models\BarangStok::whereIn('id', $ids)->get();
            }
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
        $this->reset(['newMerkId', 'newJumlah', 'newUnit', 'newBarangId', 'newKeterangan']);
        // Reset newRabId hanya jika isSeribu (karena setiap item bisa beda RAB)
        if ($this->isSeribu) {
            $this->reset(['newRabId']);
        }
        $this->checkAdd();
    }


    public function saveData()
    {
        $user_id = Auth::id();

        $permintaan = DetailPermintaanMaterial::create([
            'kode_permintaan' => fake()->numerify('ABCD#######'),
            'user_id' => $user_id,
            'nodin' => $this->nodin,
            'gudang_id' => $this->gudang_id,
            'saluran_jenis' => $this->saluran_jenis,
            'saluran_id' => $this->saluran_id,
            'p' => $this->vol['p'] ?? null,
            'l' => $this->vol['l'] ?? null,
            'k' => $this->vol['k'] ?? null,
            'nama' => $this->namaKegiatan,
            'kelurahan_id' => $this->kelurahanId,
            'lokasi' => $this->lokasiMaterial,
            'keterangan' => $this->keterangan,
            'rab_id' => $this->rab_id ?? null,
            'tanggal_permintaan' => strtotime($this->tanggalPenggunaan)
        ]);

        foreach ($this->list as $item) {
            $merkId = $item['merk']->id;

            // 1. Simpan ke permintaan_material
            $pm = PermintaanMaterial::create([
                'detail_permintaan_id' => $permintaan->id,
                'user_id' => $user_id,
                'merk_id' => $merkId,
                'rab_id' => $this->isSeribu ? $item['rab_id'] : null,
                'jumlah' => $item['jumlah'],
                'alocated' => !$this->isMerkTersebar($merkId) ? 1 : 0,
                'deskripsi' => $item['keterangan'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 2. Simpan ke transaksi_stok (tipe: Pengajuan)
            \App\Models\TransaksiStok::create([
                'kode_transaksi_stok' => fake()->unique()->numerify('TRX#####'),
                'permintaan_id' => $pm->id,
                'tipe' => 'Pengajuan',
                'merk_id' => $merkId,
                'jumlah' => $item['jumlah'],
                'lokasi_id' => $this->gudang_id,
                'bagian_id' => null,
                'posisi_id' => null,
                'user_id' => $user_id,
                'tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Cek jika stok tidak tersebar → langsung buat StokDisetujui
            if (!$this->isMerkTersebar($merkId)) {
                StokDisetujui::create([
                    'permintaan_id' => $pm->id,
                    'merk_id' => $merkId,
                    'lokasi_id' => $this->gudang_id,
                    'bagian_id' => null,
                    'posisi_id' => null,
                    'catatan' => 'Stok hanya tersedia di lokasi utama',
                    'jumlah_disetujui' => $item['jumlah'],
                ]);
            }
        }

        $this->reset('list');
        $pemohon = Auth::user();
        $creatorRoles = $pemohon->roles->pluck('name')->toArray();
        $hasRab = $permintaan->rab_id !== null;
        $isKasatpel = in_array('Kepala Satuan Pelaksana', $creatorRoles);

        // Tentukan role approval pertama
        if ($hasRab && $isKasatpel) {
            $firstRole = 'Kepala Seksi';
        } elseif ($hasRab && !$isKasatpel) {
            $firstRole = 'Kepala Suku Dinas';
        } elseif (!$hasRab && $isKasatpel) {
            $firstRole = 'Kepala Seksi';
        } else {
            $firstRole = 'Kepala Subbagian';
        }
        $date = Carbon::parse($permintaan->created_at);

        // Ambil user pertama dari role yang ditentukan dalam unit yang relevan
        $approvalUser = User::whereHas('roles', function ($query) use ($firstRole) {
            $query->where('name', 'LIKE', '%' . $firstRole . '%');
        })
            ->where(function ($query) {
                $query->whereHas('unitKerja', function ($subQuery) {
                    $subQuery->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            })
            ->where(function ($query) use ($firstRole) {
                $query->whereHas('unitKerja', function ($subQuery) use ($firstRole) {
                    $subQuery->when($firstRole === 'Kepala Seksi', function ($query) {
                        return $query->where('nama', 'like', '%Pemeliharaan%');
                    })->when($firstRole === 'Kepala Subbagian', function ($query) {
                        return $query->where('nama', 'like', '%Tata Usaha%');
                    });
                });
            })->when($firstRole === 'Penjaga Gudang', function ($query) use ($permintaan) {
                return $query->where('lokasi_id', $permintaan->gudang_id);
            })
            ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->first();

        // Kirim notifikasi kalau ketemu
        if ($approvalUser) {
            $pesan = "Permintaan dengan kode {$permintaan->kode_permintaan} membutuhkan persetujuan Anda.";
            \Illuminate\Support\Facades\Notification::send($approvalUser, new \App\Notifications\UserNotification(
                $pesan,
                "/permintaan/permintaan/{$permintaan->id}"
            ));
        }
        $this->dispatch('saveDokumen', kontrak_id: $permintaan->id, isRab: false, isMaterial: true);
    }



    public function removeFromList($index)
    {

        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
        $this->dispatch('listCount', count: count($this->list));
    }
    public function checkAdd()
    {
        if (!$this->newMerkId || !$this->newJumlah) {
            $this->ruleAdd = false;
            return;
        }

        // Tentukan RAB ID yang benar berdasarkan mode
        $rabIdForValidation = null;
        if ($this->withRab) {
            if ($this->isSeribu && $this->newRabId) {
                // Untuk mode Seribu, gunakan RAB per-item
                $rabIdForValidation = $this->newRabId;
            } elseif (!$this->isSeribu && $this->rab_id) {
                // Untuk mode normal, gunakan RAB dari form utama
                $rabIdForValidation = $this->rab_id;
            }
        }

        // Validasi menggunakan StokHelper
        $validation = \App\Helpers\StokHelper::validateJumlahPermintaan(
            $this->newMerkId,
            $this->newJumlah,
            $rabIdForValidation,
            $this->gudang_id
        );

        $this->ruleAdd = $validation['valid'];

        // Set error message jika tidak valid
        if (!$validation['valid']) {
            $this->addError('newJumlah', $validation['error_message']);
        } else {
            $this->resetErrorBag('newJumlah');
        }
    }
    public function isVolFilled()
    {
        $requiredKeys = ['p', 'l', 'k'];

        // Pastikan semua key ada dan nilainya tidak kosong
        foreach ($requiredKeys as $key) {
            // dump($this->vol[$key]);
            if (!array_key_exists($key, $this->vol) || $this->vol[$key] === null || $this->vol[$key] === '') {
                return false;
            }
        }

        return true;
    }
    public function openDistribusiModal($index)
    {
        $item = $this->list[$index];
        $this->distribusiModalIndex = $index;

        $merkId = $item['merk']->id;
        $jumlahDiminta = $item['jumlah'];
        $lokasiId = $this->permintaan->gudang_id;

        $transaksis = \App\Models\TransaksiStok::where('merk_id', $merkId)
            ->where(function ($q) use ($lokasiId) {
                $q->where('lokasi_id', $lokasiId)
                    ->orWhereHas('bagianStok', fn($q) => $q->where('lokasi_id', $lokasiId))
                    ->orWhereHas('posisiStok.bagianStok', fn($q) => $q->where('lokasi_id', $lokasiId));
            })
            ->get();

        $alokasiData = [];

        foreach ($transaksis as $trx) {
            $jumlah = match ($trx->tipe) {
                'Pemasukan' => (int) $trx->jumlah,
                'Pengeluaran' => - ((int) $trx->jumlah),
                'Penyesuaian' => (int) $trx->jumlah,
                default => 0,
            };

            $key = null;

            if ($trx->posisi_id) {
                $key = 'posisi:' . $trx->posisi_id;
            } elseif ($trx->bagian_id) {
                $key = 'bagian:' . $trx->bagian_id;
            } elseif ($trx->lokasi_id) {
                $key = 'lokasi:' . $trx->lokasi_id;
            }

            if ($key) {
                $alokasiData[$key] = ($alokasiData[$key] ?? 0) + $jumlah;
            }
        }

        $this->stokDistribusiList = collect($alokasiData)->filter(fn($val) => $val > 0);
        $this->alokasiInput = [];
        $this->alokasiSisa = $jumlahDiminta;
    }



    public function updatedAlokasiInput()
    {
        $total = array_sum(array_map('intval', $this->alokasiInput));
        $this->alokasiSisa = $this->list[$this->distribusiModalIndex]['jumlah'] - $total;
    }
    public function submitDistribusi()
    {
        if ($this->alokasiSisa != 0)
            return;

        $item = $this->list[$this->distribusiModalIndex];
        $user_id = Auth::id();

        foreach ($this->alokasiInput as $key => $jumlah) {
            if ((int) $jumlah <= 0)
                continue;

            [$tipe, $id] = explode(':', $key);

            $bagian_id = null;
            $posisi_id = null;

            if ($tipe === 'posisi') {
                $posisi = \App\Models\PosisiStok::find($id);
                if (!$posisi)
                    continue;
                $posisi_id = $posisi->id;
                $bagian_id = $posisi->bagian_id;
            } elseif ($tipe === 'bagian') {
                $bagian = \App\Models\BagianStok::find($id);
                if (!$bagian)
                    continue;
                $bagian_id = $bagian->id;
            }

            StokDisetujui::create([
                'permintaan_id' => $item['id'],
                'merk_id' => $item['merk']->id,
                'lokasi_id' => $this->permintaan->gudang_id,
                'bagian_id' => $bagian_id,
                'posisi_id' => $posisi_id,
                'catatan' => null,
                'jumlah_disetujui' => (int) $jumlah,
            ]);
        }
        // Ambil id permintaan_material berdasarkan merk
        $merkId = $this->list[$this->distribusiModalIndex]['merk']->id;

        $pm = PermintaanMaterial::where('detail_permintaan_id', $this->permintaan->id)
            ->where('merk_id', $merkId)
            ->first();

        if ($pm) {
            $pm->update(['alocated' => 1]);
        }

        $this->distribusiModalIndex = null;
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Alokasi disimpan ke stok_disetujui.'
        ]);
    }

    public function isMerkTersebar($merkId)
    {
        if ($this->isShow) {
            # code...
            $lokasiId = $this->permintaan->gudang_id;
        } else {
            $lokasiId = $this->gudang_id;
        }

        $transaksis = \App\Models\TransaksiStok::where('merk_id', $merkId)
            ->where(function ($q) use ($lokasiId) {
                $q->where('lokasi_id', $lokasiId)
                    ->orWhereHas('bagianStok', fn($q) => $q->where('lokasi_id', $lokasiId))
                    ->orWhereHas('posisiStok.bagianStok', fn($q) => $q->where('lokasi_id', $lokasiId));
            })
            ->get();

        $stokBagian = $transaksis->whereNull('posisi_id')->whereNotNull('bagian_id');
        $stokPosisi = $transaksis->whereNotNull('posisi_id');

        $jumlahBagian = $stokBagian->reduce(function ($carry, $trx) {
            return $carry + match ($trx->tipe) {
                'Pemasukan' => (int) $trx->jumlah,
                'Pengeluaran' => -(int) $trx->jumlah,
                'Penyesuaian' => (int) $trx->jumlah,
                default => 0,
            };
        }, 0);

        $jumlahPosisi = $stokPosisi->reduce(function ($carry, $trx) {
            return $carry + match ($trx->tipe) {
                'Pemasukan' => (int) $trx->jumlah,
                'Pengeluaran', 'Pengajuan' => -(int) $trx->jumlah,
                'Penyesuaian' => (int) $trx->jumlah,
                default => 0,
            };
        }, 0);

        return ($jumlahBagian + $jumlahPosisi) > 0;
    }

    public function getAlokasiByMerk($merkId, $index)
    {
        $item = $this->list[$index];
        return \App\Models\StokDisetujui::with(['lokasiStok', 'bagianStok', 'posisiStok'])
            ->where('permintaan_id', $item['id'])
            ->where('merk_id', $merkId)
            ->get();
    }

    public function updatedList($value, $name)
    {
        $parts = explode('.', $name);

        if (isset($parts[2]) && $parts[2] === 'merk') {
            $index = $parts[1];
            $this->list[$index]['merk'] = \App\Models\MerkStok::find($value);
        }
    }

    public function render()
    {
        return view('livewire.list-permintaan-material');
    }
}
