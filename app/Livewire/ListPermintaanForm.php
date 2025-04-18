<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Stok;
use App\Models\User;
use App\Models\Ruang;
use BaconQrCode\Writer;
use Livewire\Component;
use App\Models\Kategori;
use App\Models\MerkStok;
use App\Models\UnitKerja;
use App\Models\BarangStok;
use App\Models\LokasiStok;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\StokDisetujui;
use Livewire\WithFileUploads;
use App\Models\PermintaanStok;
use App\Models\OpsiPersetujuan;
use App\Models\WaktuPeminjaman;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\DetailPermintaanStok;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use BaconQrCode\Renderer\GDLibRenderer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PersetujuanPermintaanStok;
use Illuminate\Support\Facades\Notification;

class ListPermintaanForm extends Component
{
    use WithFileUploads;

    public $unit_id;
    public $kategori_id;
    public $sub_unit_id;
    public $tanggal_permintaan;
    public $keterangan;
    public $permintaan, $rab_id;
    public $showAdd;
    public $kdos;
    public $newLokasiLain;
    public $newAlamatLokasi;
    public $newKontakPerson;
    public $newWaktu;
    public $atasanLangsung;
    public $waktus;
    public $availBarangs;
    public $list = []; // List of items
    public $newDeskripsi; // Input for new barang
    public $newCatatan; // Input for new barang
    public $newAset;
    public $newAsetId;
    public $newDriverId;
    public $newRuangId;
    public $newRuang;
    public $asetSuggestions = []; // Input for new barang
    public $lokasiSuggestions = []; // Input for new barang
    public $newBarangId; // Input for new barang
    public $newBarang;
    public $newLokasiId;
    public $newLokasi;
    public $newJumlah; // Input for new jumlah
    public $NoSeri;
    public $JenisKDO;
    public $NamaKDO;
    public $newDokumen; // Input for new dokumen
    public $newBukti; // Input for new dokumen
    public $newDone; // Input for new dokumen
    public $barangSuggestions = []; // Suggestions for barang
    public $showApprovalModal = false;
    public $ruleShow;
    public $ruleAdd;
    public $last;
    public $approve_after;
    public $selectedItemId; // ID dari item yang dipilih
    public $approvalData = []; // Data untuk lokasi dan stok
    public $catatan; // Catatan opsional
    public $noteModalVisible = false; // Untuk mengatur visibilitas modal catatan
    public $selectedItemNotes;
    public $requestIs;
    public $RuangId;
    public $peserta;
    public $LokasiLain;
    public $AlamatLokasi;
    public $KontakPerson;
    public $KDOId;
    public $tanggal_masuk;
    public $tanggal_keluar;
    public $approvals = [];
    public $asets = [];
    public $drivers = [];
    public $ruangs = [];

    public function openNoteModal($itemId)
    {
        $item = StokDisetujui::where('permintaan_id', $itemId)
            ->get();

        $this->selectedItemNotes = $item->map(function ($stok) {
            return [
                'merk' => $stok->merkStok,
                'lokasi' => $stok->lokasiStok->nama ?? '-',
                'bagian' => $stok->bagianStok->nama ?? '-',
                'posisi' => $stok->posisiStok->nama ?? '-',
                'jumlah_disetujui' => $stok->jumlah_disetujui,
                'catatan' => $stok->catatan ?? 'Tidak ada catatan',
            ];
        });

        $this->noteModalVisible = true; // Tampilkan modal
    }

    public function openApprovalModal($itemId)
    {
        $this->selectedItemId = $itemId;
        $this->loadApprovalData($itemId);
        $this->showApprovalModal = true;
    }

    public function loadApprovalData($itemId)
    {
        $item = PermintaanStok::find($itemId);
        $barang = $item->barangStok;
        // Ambil merk-merk yang tersedia untuk barang tersebut yang ada di stok
        $merkTersedia = MerkStok::where('barang_id', $barang->id) // Ambil merk berdasarkan barang yang dipilih
            ->whereHas('stok', function ($query) {
                $query->where('jumlah', '>', 0)->whereHas('lokasiStok', function ($stokQuery) {
                    $stokQuery->whereHas('unitKerja', function ($unit) {
                        return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                    });
                });
            })
            ->get();

        $this->approvalData = [
            'barang' => $barang, // Data barang yang dipilih
            'jumlah_permintaan' => $item->jumlah, // Jumlah yang diminta
            'stok' => $merkTersedia->map(function ($merk) use ($barang) {
                // Ambil stok yang tersedia untuk merk terkait
                return Stok::whereHas('lokasiStok', function ($stokQuery) {
                    $stokQuery->whereHas('unitKerja', function ($unit) {
                        return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                    });
                })->where('merk_id', $merk->id) // Ambil stok berdasarkan merk_id
                    ->get()
                    ->map(function ($stok) use ($merk) {
                        return [
                            'id' => $merk->id,
                            'nama' => $merk->nama, // Nama merk
                            'tipe' => $merk->tipe, // Tipe merk
                            'ukuran' => $merk->ukuran, // Ukuran merk
                            'lokasi' => $stok->lokasiStok->nama, // Lokasi terkait stok
                            'bagian' => $stok->bagianStok->nama ?? null, // Bagian jika ada
                            'posisi' => $stok->posisiStok->nama ?? null, // Posisi jika ada
                            'jumlah_tersedia' => $stok->jumlah, // Jumlah stok yang tersedia
                            'lokasi_id' => $stok->lokasi_id, // ID lokasi
                            'bagian_id' => $stok->bagian_id, // ID bagian
                            'posisi_id' => $stok->posisi_id, // ID posisi
                        ];
                    });
            })->flatten(1),
        ];
    }


    public function approveItems()
    {
        foreach ($this->approvalData['stok'] as $stok) {
            if (isset($stok['jumlah_disetujui']) && $stok['jumlah_disetujui'] > 0) {
                StokDisetujui::create([
                    'permintaan_id' => $this->selectedItemId,
                    'merk_id' => $stok['id'],
                    'lokasi_id' => $stok['lokasi_id'],
                    'bagian_id' => $stok['bagian_id'] ?? null,
                    'posisi_id' => $stok['posisi_id'] ?? null,
                    'catatan' => $stok['catatan'] ?? null, // Simpan catatan per stok
                    'jumlah_disetujui' => $stok['jumlah_disetujui'],
                ]);
                // Kurangi stok sesuai dengan lokasi, bagian, dan posisi (jika ada)
                // $stokModel = Stok::where('lokasi_id', $stok['lokasi_id'])
                //     ->when(isset($stok['bagian_id']), function ($query) use ($stok) {
                //         return $query->where('bagian_id', $stok['bagian_id']);
                //     })
                //     ->when(isset($stok['posisi_id']), function ($query) use ($stok) {
                //         return $query->where('posisi_id', $stok['posisi_id']);
                //     })
                //     ->where('merk_id', $this->approvalData['merk_id'])
                //     ->first();

                // if ($stokModel) {
                //     $stokModel->jumlah -= $stok['jumlah_disetujui'];
                //     $stokModel->save();
                // }
            }
        }


        $this->showApprovalModal = false; // Tutup modal
        $this->catatan = null; // Reset catatan
        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id);
    }



    public function removeNewDokumen()
    {
        if ($this->newDokumen && Storage::exists($this->newDokumen)) {
            Storage::delete($this->newDokumen);
        }
        $this->newDokumen = null; // Reset variable
    }

    public function focusLokasi()
    {
        $this->lokasiSuggestions = [];

        $suggest = UnitKerja::where('parent_id', $this->unit_id)->get();
        $this->lokasiSuggestions = $suggest;
    }
    public function focusAset()
    {
        $this->asetSuggestions = [];



        if ($this->requestIs === 'spare-part') {
            $suggest = Aset::whereHas('kategori', function ($kategori) {
                return $kategori->where('nama', 'KDO');
            });
        }
        $this->asetSuggestions = $suggest->where('nama', 'like', '%' . $this->newAset . '%')->get();
    }
    public function focusBarang()
    {

        $rabId = $this->rab_id;
        $this->barangSuggestions = [];
        $suggest = BarangStok::whereHas('merkStok', function ($merkQuery) {
            $merkQuery->where('nama', 'like', '%' . $this->newBarang . '%')
                ->orWhere('tipe', 'like', '%' . $this->newBarang . '%')
                ->orWhere('ukuran', 'like', '%' . $this->newBarang . '%')
                ->join('stok', 'merk_stok.id', '=', 'stok.merk_id')
                ->groupBy('merk_stok.id');
            // ->havingRaw('SUM(stok.jumlah) > 0') // Filter stok total > 0
        })->when($rabId > 0, function ($query) use ($rabId) { // Filter hanya jika $rabId > 0
            // dd('asdas');
            $query->whereHas('listRab', function ($query) use ($rabId) {
                $query->where('rab_id', $rabId);
            });
        })
            ->with([
                'merkStok' => function ($merkQuery) {
                    $merkQuery->join('stok', 'merk_stok.id', '=', 'stok.merk_id')
                        ->groupBy('merk_stok.id')
                        // ->havingRaw('SUM(stok.jumlah) > 0')
                        ->with(['stok' => function ($stokQuery) {
                            $stokQuery->select('merk_id', DB::raw('SUM(jumlah) as total_jumlah'))
                                ->groupBy('merk_id');
                        }]);
                }
            ]);
        if ($this->requestIs === 'permintaan') {
            // dd('asdads');
            $this->barangSuggestions = $suggest->where('kategori_id', $this->kategori_id)->get();
        } elseif ($this->requestIs === 'spare-part') {
            $this->barangSuggestions = $suggest->where('jenis_id', 2)->get();
        } elseif ($this->requestIs === 'material') {
            // dd($suggest->get());
            // $this->barangSuggestions = $suggest->where('jenis_id', 1)->get();
            $this->barangSuggestions = $suggest->get();
        }
    }
    #[On('rab_id')]
    public function fillRabId($rab_id)
    {
        $this->rab_id = $rab_id;
        $this->fillShowRule();
        $this->fillKategoriId();
    }
    #[On('unit_id')]
    public function fillUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
        $this->fillShowRule();
    }
    #[On('kategori_id')]
    public function fillKategoriId($kategori_id = null)
    {
        $rabId = $this->rab_id;
        $userUnitId = $this->unit_id;
        $this->availBarangs = [];
        $avai = BarangStok::whereHas(
            'merkStok'
            // , function ($merkQuery) use ($userUnitId) {
            //     $merkQuery->join('stok', 'merk_stok.id', '=', 'stok.merk_id') // Join tabel stok
            //         ->join('lokasi_stok', 'stok.lokasi_id', '=', 'lokasi_stok.id') // Join tabel lokasi
            //         ->join('unit_kerja', 'lokasi_stok.unit_id', '=', 'unit_kerja.id') // Join unit kerja
            //         ->where('unit_kerja.id', $userUnitId) // Filter berdasarkan unit kerja pengguna login
            //         ->select('merk_stok.*', DB::raw('SUM(stok.jumlah) as total_stok')) // Hitung stok total
            //         ->groupBy('merk_stok.id') // Kelompokkan berdasarkan merk
            //         ->havingRaw('total_stok > 0'); // Hanya stok dengan jumlah > 0
            // }
        )->when($rabId > 0, function ($query) use ($rabId) { // Filter hanya jika $rabId > 0
            $query->whereHas('listRab', function ($query) use ($rabId) {
                $query->where('rab_id', $rabId);
            });
        })
            ->with([
                'merkStok' => function ($merkQuery) {
                    $merkQuery->with(['stok' => function ($stokQuery) {
                        $stokQuery->select('merk_id', DB::raw('SUM(jumlah) as total_jumlah')) // Hitung total jumlah
                            ->groupBy('merk_id');
                    }]);
                }
            ]);
        if ($this->requestIs === 'permintaan') {
            $this->availBarangs = $avai->where('kategori_id', $kategori_id)->get();
        } elseif ($this->requestIs === 'spare-part') {
            $this->availBarangs = $avai->where('jenis_id', 2)->get();
        } elseif ($this->requestIs === 'material') {
            $this->availBarangs = $avai->where('jenis_id', 1)->get();
        }

        if ($kategori_id == 6) {
            $voucher = $this->availBarangs->first(function ($item) {
                return stripos($item->nama, 'voucher') !== false;
            });
            if ($voucher) {
                $this->newBarangId = $voucher->id;
            }
        }

        $this->kategori_id = $kategori_id;
        $this->fillShowRule();

        $KDO = 'KDO';
        $kategori = Kategori::where('nama', $KDO)->first();
        $cond = true;
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
            })->where('perbaikan', true)
            ->get();

        $this->drivers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Driver'); // Ambil user dengan role "Driver"
        })
            ->whereHas('unitKerja', function ($query) {
                return $query->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })
            ->get();
    }


    #[On('sub_unit_id')]
    public function fillSubUnitId($sub_unit_id)
    {
        $this->sub_unit_id = $sub_unit_id;
        $this->fillShowRule();
    }

    #[On('tanggal_permintaan')]
    public function fillTanggalPermintaan($tanggal_permintaan)
    {
        $this->tanggal_permintaan = $tanggal_permintaan;
        $this->fillShowRule();
    }
    #[On('peserta')]
    public function fillPeserta($peserta)
    {
        $this->peserta = $peserta;
        $this->fillShowRule();
    }

    #[On('RuangId')]
    public function fillRuangId($RuangId)
    {
        $this->RuangId = $RuangId;
        $this->fillShowRule();
    }

    #[On('LokasiLain')]
    public function fillLokasiLain($LokasiLain)
    {
        $this->LokasiLain = $LokasiLain;
        $this->fillShowRule();
    }

    #[On('AlamatLokasi')]
    public function fillAlamatLokasi($AlamatLokasi)
    {
        $this->AlamatLokasi = $AlamatLokasi;
        $this->fillShowRule();
    }

    #[On('KontakPerson')]
    public function fillKontakPerson($KontakPerson)
    {
        $this->KontakPerson = $KontakPerson;
        $this->fillShowRule();
    }
    #[On('KDOId')]
    public function fillKDOId($KDOId)
    {
        $this->KDOId = $KDOId;
        $this->fillShowRule();
    }
    #[On('tanggal_masuk')]
    public function fillTanggalMasuk($tanggal_masuk)
    {

        $this->tanggal_masuk = $tanggal_masuk;
        $this->fillShowRule();
    }
    #[On('tanggal_keluar')]
    public function fillTanggalKeluar($tanggal_keluar)
    {
        $this->tanggal_keluar = $tanggal_keluar;
        $this->fillShowRule();
    }


    public function saveData()
    {

        if ($this->kategori_id == 4) {
            $existing = DetailPermintaanStok::where('kategori_id', 4)
                ->where('user_id', Auth::id())
                ->whereNull('file') // file belum diupload
                ->latest()
                ->first();

            if ($existing) {
                $this->dispatch('error', 'Anda masih memiliki permintaan konsumsi yang belum selesai dengan cara melampirkan file SPJ.');
                return;
            }
        }

        $latestApprovalConfiguration = OpsiPersetujuan::where('jenis', $this->requestIs == 'permintaan' ? 'umum' : ($this->requestIs == 'spare-part' ? 'spare-part' : 'material'))
            ->where('unit_id', $this->unit_id)
            ->where('created_at', '<=', now()) // Pastikan data sebelum waktu saat ini
            ->latest()
            ->first();
        // dd($this->rab_id);
        // Create Detail Permintaan Stok
        $detailPermintaan = DetailPermintaanStok::create([
            'kode_permintaan' => $this->generateQRCode(),
            'tanggal_permintaan' => strtotime($this->tanggal_permintaan),
            'unit_id' => $this->unit_id,
            'rab_id' => $this->rab_id,
            'user_id' => Auth::id(),
            'jenis_id' => $this->requestIs == 'permintaan' ? 3 : ($this->requestIs == 'spare-part' ? 2 : 1),
            'kategori_id' => $this->kategori_id,
            'sub_unit_id' => $this->sub_unit_id ?? null,
            'keterangan' => $this->keterangan,
            'jumlah_peserta' => $this->peserta,
            'approval_configuration_id' => $latestApprovalConfiguration->id,
            'lokasi_id' => $this->RuangId == 0 ? null : $this->RuangId, // Pastikan null jika 0
            'lokasi_lain' => !empty($this->LokasiLain) ? $this->LokasiLain : null,
            'alamat_lokasi' => !empty($this->AlamatLokasi) ? $this->AlamatLokasi : null,
            'kontak_person' => !empty($this->KontakPerson) ? $this->KontakPerson : null,
            'aset_id' => $this->KDOId ?? null,
            'tanggal_masuk' => $this->tanggal_masuk,
            'tanggal_keluar' => $this->tanggal_keluar,
            'status' => null
        ]);
        $this->permintaan = $detailPermintaan;
        foreach ($this->list as $item) {
            $storedFilePath = $item['img'] ? str_replace('kondisiKdo/', '', $item['img']->storeAs(
                'kondisiKdo', // Directory
                $item['img']->getClientOriginalName(), // File name
                'public' // Storage disk
            )) : null;
            $storedFilePathBukti = $item['dokumen'] ? str_replace('buktikdo/', '', $item['dokumen']->storeAs(
                'buktikdo', // Directory
                $item['dokumen']->getClientOriginalName(), // File name
                'public' // Storage disk
            )) : null;
            PermintaanStok::create([
                'detail_permintaan_id' => $detailPermintaan->id,
                'user_id' => Auth::id(),
                // 'aset_id' => $item['aset_id'] ?? null,
                'aset_id' => isset($item['aset_id']) && $item['aset_id'] == 0 ? null : $item['aset_id'], // Pastikan NULL jika 0
                'deskripsi' => $item['deskripsi'] ?? null,
                'catatan' => $item['catatan'] ?? null,
                'img' => $storedFilePath ?? $storedFilePathBukti ?? null,
                'barang_id' => $item['barang_id'],
                'jumlah' => $item['jumlah'] ?? 1,
                'lokasi_id' => $item['lokasi_id'] ?? null,
                // 'driver_id' => $item['driver_id'] ?? null,
                'driver_name' => $item['driver_name'] ?? null,
                'noseri' => $item['noseri'] ?? null,
                'jenis_kdo' => $item['jenis_kdo'] ?? null,
                'nama_kdo' => $item['nama_kdo'] ?? null,
                // 'lokasi_id' => $this->lokasiId
            ]);
        }
        // $message = 'Permintaan ' . $detailPermintaan->jenisStok->nama . ' <span class="font-bold">' . $detailPermintaan->kode_permintaan . '</span> membutuhkan persetujuan Anda.';

        // $this->tipe = Str::contains($this->permintaan->getTable(), 'permintaan') ? 'permintaan' : 'peminjaman';

        // $user = Auth::user();
        // $roles = $this->permintaan->opsiPersetujuan->jabatanPersetujuan->pluck('jabatan.name')->toArray();
        // $roleLists = [];
        // $lastRoles = [];

        // $date = Carbon::parse($this->permintaan->created_at);

        // foreach ($roles as $role) {
        //     $users = User::whereHas('roles', function ($query) use ($role) {
        //         $query->where('name', 'LIKE', '%' . $role . '%');
        //     })
        //         ->where(function ($query) use ($date) {
        //             $query->whereHas('unitKerja', function ($subQuery) {
        //                 $subQuery->where('parent_id', $this->permintaan->unit_id);
        //             })
        //                 ->orWhere('unit_id', $this->permintaan->unit_id);
        //         })
        //         ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
        //         ->limit(1)
        //         ->get();

        //     $propertyKey = Str::slug($role); // Generate dynamic key for roles
        //     $roleLists[$propertyKey] = $users;
        //     $lastRoles[$propertyKey] = $users->search(fn($user) => $user->id == Auth::id()) === $users->count() - 1;
        // }

        // // Calculate listApproval dynamically
        // // $tipe = $this->permintaan->jenisStok->nama;
        // // $unit = UnitKerja::find($this->permintaan->unit_id);
        // $allApproval = collect();

        // // Hitung jumlah persetujuan yang dibutuhkan
        // $listApproval = collect($roleLists)->flatten(1)->count();

        // // Menggabungkan semua approval untuk pengecekan urutan
        // $allApproval = collect($roleLists)->flatten(1);
        // $currentApprovalIndex = $allApproval->filter(function ($user) {
        //     $approval = $user->{"persetujuan{$this->tipe}"}()
        //         ->where('detail_' . $this->tipe . '_id', $this->permintaan->id ?? 0)
        //         ->first();
        //     return $approval && $approval->status === 1; // Hanya hitung persetujuan yang berhasil
        // })->count();


        // // Pengecekan urutan user dalam daftar persetujuan
        // $index = $allApproval->search(fn($user) => $user->id == Auth::id());
        // // dd($allApproval);
        // $nextUser = $allApproval[$currentApprovalIndex];
        // if (collect($roles)->count() > 1) {
        //     if ($index === 0) {
        //         // Jika user adalah yang pertama dalam daftar
        //         $currentUser = $allApproval[$index];
        //     } else {
        //         // Jika user berada di tengah atau akhir
        //         $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
        //         $currentUser = $allApproval[$index];
        //         $previousApprovalStatus = optional(optional($previousUser)->{"persetujuan{$this->tipe}"}()
        //             ?->where('detail_' . $this->tipe . '_id', $this->permintaan->id ?? 0)
        //             ->first())->status;
        //     }
        // }
        // // $role_id = $latestApprovalConfiguration->jabatanPersetujuan->first()->jabatan->id;
        // // $user = Role::where('id', $role_id)->first()?->users->where('unit_id', $this->unit_id)->first();

        // Notification::send($nextUser, new UserNotification($message, "/permintaan/permintaan/{$detailPermintaan->id}"));

        if ($detailPermintaan->kategori_id == 6) {
            $csUsers = User::whereHas('roles', fn($q) => $q->where('name', 'LIKE', '%Customer Services%'))
                ->where('name', 'like', '%Insan%')
                ->get();

            $notifMessage = 'Permintaan ' . $detailPermintaan->jenisStok->nama . ' dengan kode <span class="font-bold">'
                . $detailPermintaan->kode_permintaan . '</span> telah diajukan dan membutuhkan perhatian CS.';

            Notification::send($csUsers, new UserNotification($notifMessage, "/permintaan/permintaan/{$detailPermintaan->id}"));
        } elseif ($detailPermintaan->kategori_id == 5) {
            $penanggungJawab = User::whereHas('roles', fn($q) => $q->where('name', 'LIKE', '%Penanggung Jawab%'))
                ->where('name', 'like', '%Sugi%')
                ->get();


            if ($penanggungJawab) {
                $notifPJ = 'Permintaan perbaikan dengan kode <span class="font-bold">'
                    . $detailPermintaan->kode_permintaan .
                    '</span> memerlukan persetujuan Anda sebagai Penanggung Jawab.';

                Notification::send($penanggungJawab, new UserNotification($notifPJ, "/permintaan/permintaan/{$detailPermintaan->id}"));
            }
        } elseif ($detailPermintaan->kategori_id == 4) {
            $csUsers = User::whereHas('roles', fn($q) => $q->where('name', 'LIKE', '%Customer Services%'))
                ->where('name', 'like', '%Nisya%')
                ->get();

            $notifMessage = 'Permintaan konsumsi dengan kode <span class="font-bold">'
                . $detailPermintaan->kode_permintaan . '</span> telah diajukan dan memerlukan perhatian Anda.';

            Notification::send($csUsers, new UserNotification($notifMessage, "/permintaan/permintaan/{$detailPermintaan->id}"));
        } else {
            $lokasiId = LokasiStok::where('nama', 'Gudang Umum')->value('id');

            $penjagaGudang = User::with(['roles', 'unitKerja', 'lokasiStok'])
                ->where('lokasi_id', $lokasiId)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'LIKE', '%Penjaga Gudang%');
                })
                ->first();
            if ($penjagaGudang) {
                $notifGudang = 'Permintaan ' . $detailPermintaan->jenisStok->nama . ' dengan kode <span class="font-bold">'
                    . $detailPermintaan->kode_permintaan .
                    '</span> telah diajukan dan perlu ditindaklanjuti oleh Penjaga Gudang.';

                Notification::send($penjagaGudang, new UserNotification(
                    $notifGudang,
                    "/permintaan/permintaan/{$detailPermintaan->id}"
                ));
            }
        }

        $messageAtasan = 'Permintaan ' . $detailPermintaan->kategoriStok->nama . ' <span class="font-bold">' . $detailPermintaan->kode_permintaan . '</span> telah diajukan oleh staf Anda dan memerlukan perhatian Anda.';
        $pemohon = $this->permintaan->user;

        // Reset atasan langsung
        $this->atasanLangsung = null;

        // 1. Jika pemohon adalah Kepala Unit → Atasan langsung null
        if ($pemohon->hasRole('Kepala Unit') && $this->permintaan->unit_id) {
            $this->atasanLangsung = null;
        }
        // 2. Jika pemohon adalah Kepala Subbagian, cari Kepala Unit di unit utama
        elseif ($pemohon->hasRole('Kepala Subbagian') && $this->permintaan->sub_unit_id) {
            $this->atasanLangsung = User::role('Kepala Unit')
                ->where('unit_id', $this->permintaan->unit->id) // Cari Kepala Unit di unit utama
                ->first();
        }
        // 3. Jika pemohon BUKAN Kepala Unit dan ada sub unit → Cari Kepala Subbagian di sub unit
        elseif ($this->permintaan->sub_unit_id) {
            $this->atasanLangsung = User::role('Kepala Subbagian')
                ->where('unit_id', $this->permintaan->sub_unit_id)
                ->first();
        }
        // Kirim notifikasi ke atasan langsung jika ditemukan
        if ($this->atasanLangsung) {
            // Kirim notifikasi ke atasan langsung
            Notification::send($this->atasanLangsung, new UserNotification($messageAtasan, "/permintaan/permintaan/{$detailPermintaan->id}"));
        }
        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id)->with('tanya', 'berhasil');
        // $this->reset(['list', 'detailPermintaan']);
        // session()->flash('message', 'Permintaan Stok successfully saved.');
    }



    #[On('keterangan')]
    public function fillKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
        $this->fillShowRule();
    }
    public $newUnit = 'Satuan'; // Default unit
    public $orang = 'Peserta'; // Default unit

    public function selectMerk()
    {
        $merk = BarangStok::find($this->newBarangId);
        $this->newBarang = $merk;
        // $this->newBarangId = null;
        // if ($merk) {
        //     $this->newBarangId = $merk->id;
        //     $this->newUnit = optional($merk->satuanBesar)->nama; // Set the new unit from the selected merk

        //     $this->resetBarangSuggestions();
        // }
        // if ($merk) {
        //     // Concatenate merk, tipe, and ukuran into one string, use '-' for any null values
        //     // $this->newBarang = collect([$merk->nama, $merk->tipe, $merk->ukuran])
        //     //     ->map(function ($value) {
        //     //         return $value ?? '-';
        //     //     })
        //     //     ->join(' | '); // Join the values with ' | ' as separator
        //     $this->newBarang = $merk->nama;

        //     $this->resetBarangSuggestions();
        // }
    }
    public function selectLokasi($merkId)
    {
        $lokasi = Aset::find($merkId);
        if ($lokasi) {
            $this->newLokasiId = $lokasi->id;

            $this->resetBarangSuggestions();
        }
        if ($lokasi) {

            $this->newLokasi = $lokasi->nama;

            $this->resetBarangSuggestions();
        }
    }
    public function selectAset($merkId)
    {
        $aset = Aset::find($merkId);
        if ($aset) {
            $this->newAsetId = $aset->id;

            $this->resetBarangSuggestions();
        }
        if ($aset) {

            $this->newAset = $aset->nama;

            $this->resetBarangSuggestions();
        }
    }
    private function resetBarangSuggestions()
    {
        $this->barangSuggestions = [];
    }
    public function addToList()
    {

        $this->validate([
            // 'newBarang' => 'required|string|max:255',
            'newJumlah' => 'nullable|integer|min:1',
            'newDokumen' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt',
        ]);
        $this->list[] = [
            'jumlah_approve' => $this->newJumlah,
            'status' => null,
            'id' => null,
            'aset_id' => $this->newAsetId ?? null,
            'aset_name' => $this->newAset ?? null,
            'deskripsi' => $this->newDeskripsi ?? null,
            'catatan' => $this->newCatatan ?? null,
            'img' => $this->newBukti,
            'img_done' => $this->newDone,
            'barang_id' => $this->newBarangId, // Assuming a dropdown for selecting existing barang
            // 'barang_name' => $this->newBarang,
            'barang' => $this->newBarang,
            'jumlah' => $this->newJumlah ?? null,
            'satuan' => $this->newUnit,
            // 'driver_id' => $this->newDriverId ?? null,
            'driver_name' => $this->newDriverId ?? null,
            'noseri' => $this->NoSeri ?? null,
            'jenis_kdo' => $this->JenisKDO ?? null,
            'nama_kdo' => $this->NamaKDO ?? null,
            'dokumen' => $this->newDokumen ?? null,
        ];
        $this->ruleAdd = false;
        $this->dispatch('listCount', count: count($this->list));
        // Reset inputs after adding to the list
        $this->reset(['newBarangId', 'newJumlah', 'newDokumen', 'newAset', 'newAsetId', 'newDriverId', 'newDeskripsi', 'newCatatan', 'newBukti', 'NoSeri', 'JenisKDO', 'NamaKDO']);
    }

    public function updateList($index, $field, $value)
    {
        $this->list[$index][$field] = $value;
    }

    public function fillShowRule()
    {
        // $this->ruleShow = Request::is('permintaan/add/permintaan') ? $this->tanggal_permintaan && $this->unit_id && $this->kategori_id : $this->tanggal_permintaan && $this->keterangan && $this->unit_id && $this->sub_unit_id;
        $this->ruleShow =
            $this->requestIs == 'permintaan'
            ? (($this->kategori_id == 5 || $this->kategori_id == 6)
                ? $this->tanggal_permintaan && $this->unit_id && $this->kategori_id && $this->keterangan
                : $this->tanggal_permintaan && $this->unit_id && $this->kategori_id)
            : ($this->requestIs == 'spare-part'
                ? $this->tanggal_permintaan && $this->unit_id && $this->kategori_id && $this->sub_unit_id && $this->keterangan
                : $this->tanggal_permintaan && $this->unit_id && $this->sub_unit_id && $this->keterangan);
    }
    public function updated()
    {

        // $this->ruleAdd = $this->requestIs == 'permintaan' ? $this->newBarang && $this->newJumlah : ($this->requestIs == 'spare-part' ? $this->newBarang && $this->newJumlah && $this->newAsetId && $this->newBukti && $this->newDeskripsi : $this->newBarang && $this->newJumlah  && $this->newDeskripsi);
        $this->ruleAdd =
            $this->requestIs == 'permintaan'
            ? ($this->kategori_id == 5
                ? $this->newBarang && $this->newDeskripsi
                : ($this->kategori_id == 6
                    ? $this->newDriverId
                    : $this->newBarang && $this->newJumlah))
            : ($this->requestIs == 'spare-part'
                ? $this->newBarang && $this->newJumlah && $this->newAsetId && $this->newBukti && $this->newDeskripsi
                : $this->newBarang && $this->newJumlah && $this->newDeskripsi);
    }
    public $tipe;
    public function mount()
    {


        $this->waktus = WaktuPeminjaman::all();

        $this->fillShowRule();
        $expl = explode('/', Request::getUri());
        $this->requestIs = (int)strlen(Request::segment(3)) > 3 ? Request::segment(3) : $expl[count($expl) - 2];
        // $this->focusBarang();

        $this->fillKategoriId($this->kategori_id ?? null);

        $this->showAdd = Request::is('permintaan/add/*');

        if ($this->requestIs == 'spare-part') {
            $this->kdos = Aset::all();
        }
        if ($this->permintaan) {
            $tipe = $this->permintaan->jenisStok->nama;
            $this->tipe = $tipe;

            foreach ($this->permintaan->permintaanStok as $key => $value) {
                $this->unit_id = $this->permintaan->unit_id;
                $this->keterangan = $this->permintaan->keterangan;
                $this->tanggal_permintaan = $this->permintaan->tanggal_permintaan;
                $this->fillKategoriId($this->permintaan->kategori_id);


                $this->list[] = [
                    'detail_permintaan_id' => $value->detail_permintaan_id,
                    'detail_permintaan_status' => optional($value->detailPermintaan)->status,
                    'detail_permintaan_cancel' => optional($value->detailPermintaan)->cancel,
                    'jumlah_approve' => $value->stokDisetujui->sum('jumlah_disetujui'),
                    'status' => $value->status,
                    'user_id' => $value->user_id,
                    'id' => $value->id,
                    'aset_id' => $value->aset_id ?? null,
                    'aset_name' => $value->aset->nama ?? null,
                    'deskripsi' => $value->deskripsi ?? null,
                    'catatan' => $value->catatan ?? null,
                    'img' => $value->img ?? null,
                    'barang_id' => $value->barangStok->id, // Assuming a dropdown for selecting existing barang
                    'barang_name' => $value->barangStok->nama,
                    'jumlah' => $value->jumlah,
                    'satuan' => $value->barangStok->satuanBesar->nama,
                    // 'driver_id' => $value->driver_id,
                    'driver_name' => $value->driver_name,
                    'voucher_name' => $value->voucher_name,
                    'noseri' => $value->noseri,
                    'jenis_kdo' => $value->jenis_kdo,
                    'nama_kdo' => $value->nama_kdo,
                    'dokumen' => $value->img ?? null,
                    'img_done' => $value->img_done ?? null,
                ];
            }
            // $role = $tipe == 'Umum' ? 'Kepala Seksi' : ($tipe == 'Spare Part' ? 'Kepala Subbagian' : 'Kepala Seksi Pemeliharaan');
            $approve_after = $this->approve_after = $this->permintaan->opsiPersetujuan->jabatanPersetujuan->pluck('jabatan.name')->toArray()[$this->permintaan->opsiPersetujuan->urutan_persetujuan - 1];

            $this->approvals = PersetujuanPermintaanStok::where('status', true)->where('detail_permintaan_id', $this->permintaan->id)
                ->whereHas('user', function ($query) use ($approve_after) {
                    $query->role($approve_after); // Muat hanya persetujuan dari kepala_seksi
                })
                ->pluck('detail_permintaan_id') // Ambil hanya detail_permintaan_id yang sudah disetujui
                ->toArray();
        }
        $this->tanggal_permintaan = Carbon::now()->format('Y-m-d');
        $this->tanggal_masuk = Carbon::now()->format('Y-m-d');
        $this->tanggal_keluar = Carbon::now()->addDay()->format('Y-m-d');
    }

    public function removeFromList($index)
    {
        if (isset($this->list[$index]['dokumen'])) {
            Storage::delete('public/' . $this->list[$index]['dokumen']);
        }
        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
        $this->dispatch('listCount', count: count($this->list));
    }


    public function blurLokasi()
    {
        // if ($this->newBarang) {
        //     $this->barangSuggestions = MerkStok::where('nama', 'like', '%' . $this->newBarang . '%')->get();
        // } else {
        $this->lokasiSuggestions = [];
        // }
    }
    public function blurAset()
    {
        // if ($this->newBarang) {
        //     $this->barangSuggestions = MerkStok::where('nama', 'like', '%' . $this->newBarang . '%')->get();
        // } else {
        $this->asetSuggestions = [];
        // }
    }
    public function blurBarang()
    {
        if ($this->newBarang) {
            $this->barangSuggestions = MerkStok::where('nama', 'like', '%' . $this->newBarang . '%')->get();
        } else {
            $this->barangSuggestions = [];
        }
    }

    public function selectBarang($barangId, $barangName)
    {

        $this->newBarang = $barangName;
        $this->barangSuggestions = [];
    }
    public function approveItem($index)
    {
        $item = $this->list[$index];
        $permintaan = PermintaanStok::find($item['id']);
        $this->list[$index]['status'] = true;
        $permintaan->update(['status' => true, 'jumlah_approve' => $this->list[$index]['jumlah_approve']]);
        // Optionally, remove the item from the list or mark it as approved
        // $this->list[$index]['jumlah_approve'] = true;

        // Provide feedback
        session()->flash('message', 'Item approved successfully!');
    }
    private function generateQRCode()
    {
        $userId = Auth::id(); // Dapatkan ID pengguna yang login
        $qrName = strtoupper(Str::random(16)); // Buat nama file acak untuk QR code

        // Tentukan folder dan path target file
        $qrFolder = "qr_permintaan";
        $qrTarget = "{$qrFolder}/{$qrName}.png";

        // Konten QR Code (contohnya URL)
        $qrContent = url("/qr/permintaan/{$userId}/{$qrName}");

        // Pastikan direktori untuk QR Code tersedia
        if (!Storage::disk('public')->exists($qrFolder)) {
            Storage::disk('public')->makeDirectory($qrFolder);
        }

        // Konfigurasi renderer untuk menggunakan GD dengan ukuran 400x400
        $renderer = new GDLibRenderer(500);
        $writer = new Writer($renderer);

        // Path absolut untuk menyimpan file
        $filePath = Storage::disk('public')->path($qrTarget);

        // Hasilkan QR Code ke file
        $writer->writeFile($qrContent, $filePath);

        // Periksa apakah file berhasil dibuat
        if (Storage::disk('public')->exists($qrTarget)) {
            return $qrName; // Kembalikan nama file QR
        } else {
            return "0"; // Kembalikan "0" jika gagal
        }
    }
    public function removePhoto($index = null)
    {
        if ($index) {
            dd('asdasd');
            $this->list[$index]['img'] = null;
        }
        $this->newBukti = null;
        $this->newDokumen = null;
    }


    public function removeBukti($index)
    {
        // Cek apakah index tersedia di list
        if (isset($this->list[$index]['img_done'])) {
            // Hapus foto pada index tertentu
            $this->list[$index]['img_done'] = null;
        }
    }



    public function removeDocument($index)
    {
        $item = $this->list[$index];

        // Optional: Delete the file from storage if necessary
        // Storage::delete($item['dokumen']);

        // Remove the document path from the item in the list
        $this->list[$index]['dokumen'] = null;
    }


    public function doneItem($index, $message)
    {
        // Simpan perubahan ke database (misalnya, tabel PeminjamanAset)
        $permintaanStok = PermintaanStok::find($this->list[$index]['id']);
        // dd($permintaanStok, $message);
        if ($permintaanStok) {
            $uploadedFile = $this->list[$index]['img_done'];

            $storedFilePath = $uploadedFile
                ? $uploadedFile->storeAs(
                    'kondisiKdo',
                    time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension(),
                    'public'
                )
                : null;

            // Simpan hanya nama file-nya saja, tanpa path folder
            $fileNameOnly = $storedFilePath ? basename($storedFilePath) : null;
            $data = $this->tipe == 'Umum' ? [
                'img_done' => $fileNameOnly,
                'catatan_done' => $message,
            ] : [
                'img_done' => $fileNameOnly,
                'catatan_done' => $message,
            ];
            $permintaanStok->update($data);
        }

        // Update juga ke tabel detail_permintaan_stok melalui relasi
        if ($permintaanStok->detailPermintaan) {
            $detail = $permintaanStok->detailPermintaan;

            if ($detail->kategori_id == 6) {
                $detail->update([
                    'proses' => 1,
                    'keterangan_done' => $message,
                ]);

                $csUsers = User::whereHas('roles', fn($q) => $q->where('name', 'LIKE', '%Customer Services%'))
                    ->where('name', 'like', '%Insan%')
                    ->get();

                $notifMessage = 'Permintaan ' . $detail->jenisStok->nama . ' dengan kode <span class="font-bold">'
                    . $detail->kode_permintaan . '</span> telah Selesai dan membutuhkan perhatian CS.';

                Notification::send($csUsers, new UserNotification($notifMessage, "/permintaan/permintaan/{$detail->id}"));
            } elseif ($detail->kategori_id == 5) {
                // Ambil semua permintaan stok untuk detail ini
                $relatedPermintaan = $detail->permintaanStok;

                // Cek apakah ada permintaan dengan status
                $adaYangSudahDisetujui = $relatedPermintaan->contains(function ($item) {
                    return $item->status === 1;
                });

                if ($adaYangSudahDisetujui) {
                    // Update detail jadi selesai
                    $detail->update([
                        'proses' => 1,
                        'cancel' => 0,
                        'keterangan_done' => $message,
                    ]);

                    // Update aset jika ada
                    if ($detail->aset) {
                        $detail->aset->update([
                            'perbaikan' => true
                        ]);
                    }

                    // Kirim notifikasi ke Penanggung Jawab & User
                    $notifPJ = 'Permintaan perbaikan dengan kode <span class="font-bold">'
                        . $detail->kode_permintaan .
                        '</span> selesai dengan keterangan: ' . $message;

                    $penanggungJawab = User::whereHas('roles', fn($q) => $q->where('name', 'LIKE', '%Penanggung Jawab%'))
                        ->where('name', 'like', '%Sugi%')
                        ->get();

                    Notification::send($penanggungJawab, new UserNotification($notifPJ, "/permintaan/permintaan/{$detail->id}"));

                    $user = $detail->user;
                    Notification::send($user, new UserNotification(
                        $notifPJ,
                        "/permintaan/permintaan/{$detail->id}"
                    ));
                } else {
                    // Gunakan logika lama (berdasarkan index ke-2)
                    if ($relatedPermintaan && $relatedPermintaan->count() >= 2) {
                        $secondItem = $relatedPermintaan[1];
                        if ($secondItem->id == $permintaanStok->id) {
                            $detail->update([
                                'proses' => 1,
                                'cancel' => 0,
                                'keterangan_done' => $message,
                            ]);

                            if ($detail->aset) {
                                $detail->aset->update([
                                    'perbaikan' => true
                                ]);
                            }

                            $notifPJ = 'Permintaan perbaikan dengan kode <span class="font-bold">'
                                . $detail->kode_permintaan .
                                '</span> selesai dengan keterangan: ' . $message;

                            $penanggungJawab = User::whereHas('roles', fn($q) => $q->where('name', 'LIKE', '%Penanggung Jawab%'))
                                ->where('name', 'like', '%Sugi%')
                                ->get();

                            Notification::send($penanggungJawab, new UserNotification($notifPJ, "/permintaan/permintaan/{$detail->id}"));

                            $user = $detail->user;
                            Notification::send($user, new UserNotification(
                                $notifPJ,
                                "/permintaan/permintaan/{$detail->id}"
                            ));
                        }
                    }
                }
            } else {
                $detail->update([
                    'proses' => 1,
                    'keterangan_done' => $message,
                ]);

                $permintaanItems = $this->permintaan->permintaanStok;
                foreach ($permintaanItems as $merk) {
                    foreach ($merk->stokDisetujui as  $item) {
                        $this->adjustStockForApproval($item);
                    }
                }

                $mess = "Permintaan dengan kode {$detail->kode_permintaan} Selesai dan sudah diambil dengan keterangan {$message}.";
                $user = $detail->user;
                Notification::send($user, new UserNotification(
                    $mess,
                    "/permintaan/permintaan/{$detail->id}"
                ));
            }
        }

        // $this->dispatch('success', "Upload Bukti Berhasil!");
        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id)->with('success', 'Upload Berhasil!');
    }
    public function VoucherNameAction($index)
    {
        // Simpan perubahan ke database (misalnya, tabel PeminjamanAset)
        $permintaanStok = PermintaanStok::find($this->list[$index]['id']);
        // dd($permintaanStok, $message);
        if ($permintaanStok) {
            $voucherName = $this->list[$index]['voucher_name'];

            $permintaanStok->update([
                'voucher_name' => $voucherName,
            ]);
        }

        // $this->dispatch('success', "Upload Bukti Berhasil!");
        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id)->with('success', 'Voucher Nama Diberikan!');
    }
    public function TakeVoucherName($index)
    {
        // Simpan perubahan ke database (misalnya, tabel PeminjamanAset)
        $permintaanStok = PermintaanStok::find($this->list[$index]['id']);
        if ($permintaanStok->detailPermintaan) {
            $detail = $permintaanStok->detailPermintaan;

            $detail->update([
                'cancel' => 0,
            ]);
        }

        // $this->dispatch('success', "Upload Bukti Berhasil!");
        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id)->with('success', 'Voucher Sudah Diambil!');
    }

    public function uploadimg($index)
    {
        $item = $this->list[$index];

        $img = str_replace('kondisiKdo/', '', $item['img']->storeAs(
            'kondisiKdo', // Directory
            $item['img']->getClientOriginalName(), // File name
            'public'
        ));

        $this->list[$index]['img'] = $img;
        $this->permintaan->permintaanStok()->where('id', $item['id'])->update(['img' => $img]);
        $allUploaded = collect($this->list)->every(function ($item) {
            return is_string($item['img']);
        });
        // dd($allUploaded);
        if ($allUploaded) {
            // Jika semua img sudah berupa string → update status proses
            $this->permintaan->update(['proses' => 1]);
            return redirect()->to('/permintaan/permintaan/' . $this->permintaan->id);
        }
    }

    public function ApproveItemKDO($index, $message, $status = true)
    {
        $permintaanStok = PermintaanStok::find($this->list[$index]['id']);

        if ($permintaanStok) {
            $permintaanStok->update([
                'catatan' => $message,
                'status' => $status,
            ]);
        }

        // $this->dispatch('success', "Upload Bukti Berhasil!");
        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id)->with('success', 'Persetujuan Berhasil!');
    }

    protected function adjustStockForApproval($merk)
    {
        // Ambil stok berdasarkan merk_id, diurutkan berdasarkan lokasi atau logika lainnya
        $stocks =
            Stok::where('merk_id', $merk->merk_id)
            ->where('lokasi_id', $merk->lokasi_id)
            ->where('bagian_id', $merk->bagian_id)
            ->where('posisi_id', $merk->posisi_id)
            // ->where('jumlah', '>', 0)
            ->get();

        $remaining = $merk->jumlah_disetujui; // Jumlah yang harus dikurangi

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break; // Hentikan jika jumlah sudah terpenuhi

            if ($stock->jumlah >= $remaining) {
                // Jika stok di lokasi ini cukup atau lebih dari jumlah yang dibutuhkan
                $stock->jumlah -= $remaining;
                $stock->save(); // Simpan perubahan stok
                $remaining = 0;
            } else {
                // Jika stok di lokasi ini kurang dari jumlah yang dibutuhkan
                $remaining -= $stock->jumlah; // Kurangi jumlah stok dari sisa yang diperlukan
                $stock->jumlah = 0;
                $stock->save(); // Simpan stok sebagai 0
            }
        }

        // Jika stok tidak mencukupi
        // if ($remaining > 0) {
        //     Log::warning("Stok tidak mencukupi untuk merk_id {$merkId}. Dibutuhkan {$jumlahApprove}, namun kekurangan {$remaining}.");
        //     // Tambahkan logika untuk menangani kekurangan stok, seperti pemberitahuan atau aksi lain
        // }
    }


    public function render()
    {
        return view('livewire.list-permintaan-form', [
            'barangs' => MerkStok::all(), // Assuming you have a Barang model
        ]);
    }
}
