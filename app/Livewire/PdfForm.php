<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\User;
use App\Models\Ruang;
use App\Models\WaktuPeminjaman;
use Livewire\Component;
use Illuminate\Support\Str;

class PdfForm extends Component
{
    public $permintaan;
    public $kepalaSubbagian;
    public $penanggungjawab;
    public $roles = [];
    public $roleLists = [];


    public function mount($permintaan)
    {
        $this->permintaan = $permintaan;
    }

    public function UnduhPDF()
    {

        // Determine the type of permintaan and extract data accordingly
        if ($this->permintaan instanceof \App\Models\DetailPermintaanStok) {
            $permintaanStoks = $this->permintaan->permintaanStok;
            $kategori = $this->permintaan->kategoriStok->id;
            $namaFile = "Form-Umum-Permintaan-{$this->permintaan->kategoriStok->nama}.pdf";
            $this->kepalaSubbagian = User::whereHas('roles', function ($query) {
                $query->where('name', 'Kepala Subbagian');
            })
                ->where('unit_id', $this->permintaan->sub_unit_id)
                ->first();
            if ($kategori == 4) {
                $dataItems = [];

                foreach ($permintaanStoks as $permintaanStok) {
                    $dataItems[] = [
                        'nama_barang' => optional($permintaanStok->barangStok)->nama ?? 'N/A',
                        'jumlah' => $permintaanStok->jumlah . ' ' . $permintaanStok->barangStok->satuanBesar->nama,
                    ];
                }
                // === Ambil user berdasarkan role ===
                $this->roles = ['Customer Services', 'Koordinator Konsumsi'];
                $this->roleLists = [];

                foreach ($this->roles as $role) {
                    $baseQuery = User::role($role)
                        ->whereHas(
                            'unitKerja',
                            fn($q) =>
                            $q->where('id', $this->permintaan->unit_id)
                                ->orWhere('parent_id', $this->permintaan->unit_id)
                        )
                        ->when($role === 'Customer Services', function ($query) {
                            $query->where('name', 'like', '%Nisya%');
                        })
                        ->when($role === 'Koordinator Konsumsi', function ($query) {
                            $query->where('name', 'like', '%Halimah%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();
                }
                $CSkonsumsi = optional($this->roleLists['Customer Services']->first())->name ?? 'N/A';
                $PJkonsumsi = optional($this->roleLists['Koordinator Konsumsi']->first())->name ?? 'N/A';

                $data = [
                    'judul' => 'FORMULIR PERMINTAAN UMUM',
                    'no_surat' => $this->permintaan->kode_permintaan,
                    'lokasi' => $this->permintaan->kategoriStok->nama ?? '',
                    'unit' => $this->permintaan->unit->nama ?? '',
                    'sub_unit' => $this->permintaan->subUnit->nama ?? '',
                    'keterangan' => $this->permintaan->keterangan ?? '',
                    'jumlah_peserta' => $this->permintaan->jumlah_peserta ?? '',
                    'ruang' => $this->permintaan->ruang->nama ?? '',
                    'tanggal' => Carbon::createFromTimestamp($this->permintaan->tanggal_permintaan)->format('d-m-Y'),
                    'pemohon' => $this->permintaan->user->name ?? 'N/A',
                    'persetujuan1' => $CSkonsumsi,
                    'persetujuan2' => $PJkonsumsi,
                    'ttd_pemohon' => $this->getTTDPath(optional($this->permintaan->user)->ttd),
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Customer Services']->first())->ttd),
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Koordinator Konsumsi']->first())->ttd),
                    'jabatan_pemohon' => optional($this->permintaan->user->roles->first())->name ?? '',
                    'jabatan_persetujuan1' => 'Customer Services',
                    'jabatan_persetujuan2' => 'Koordinator Konsumsi',
                    'status' => $this->getStatusPermintaan($this->permintaan, 'permintaan'),
                    'items' => $dataItems,
                    'kepala_subbagian_umum' => optional($this->kepalaSubbagian)->name ?? 'N/A',
                    'ttd_kepala_subbagian_umum' => $this->getTTDPath(optional($this->kepalaSubbagian)->ttd),
                    'jabatan_kepala_subbagian_umum' => 'Kepala Subbagian Umum',
                ];
            } elseif ($kategori == 5) {
                $dataItems = [];
                foreach ($permintaanStoks as $permintaanStok) {
                    $status = $permintaanStok->status;
                    $dataItems[] = [
                        'nama_barang' => $permintaanStok->barangStok->nama ?? 'N/A',
                        'keterangan' => $permintaanStok->deskripsi ?? '',
                        'status_kdo' => $status === 1 ? 'Disetujui' : ($status === 0 ? 'Ditolak' : '-'),
                    ];
                }

                $this->roles = ['Koordinator KDO'];
                $this->roleLists = [];

                foreach ($this->roles as $role) {
                    $baseQuery = User::role($role)
                        ->whereHas(
                            'unitKerja',
                            fn($q) =>
                            $q->where('id', $this->permintaan->unit_id)
                                ->orWhere('parent_id', $this->permintaan->unit_id)
                        )
                        ->when($role === 'Koordinator KDO', function ($query) {
                            $query->where('name', 'like', '%Sugi%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();
                }

                $PJ = optional($this->roleLists['Koordinator KDO']->first())->name ?? 'N/A';
                $data = [
                    'judul' => 'FORMULIR PERMINTAAN UMUM',
                    'no_surat' => $this->permintaan->kode_permintaan,
                    'lokasi' => $this->permintaan->kategoriStok->nama ?? '',
                    'unit' => $this->permintaan->unit->nama ?? '',
                    'sub_unit' => $this->permintaan->subUnit->nama ?? '',
                    'keterangan' => $this->permintaan->keterangan ?? '',
                    'kdo_aset' => $this->permintaan->aset->merk->nama . ' ' . $this->permintaan->aset->nama . '-' .  $this->permintaan->aset->noseri ?? '',
                    'tanggal' => Carbon::createFromTimestamp($this->permintaan->tanggal_permintaan)->format('d-m-Y'),
                    'tanggal_masuk' => Carbon::parse($this->permintaan->tanggal_masuk)->format('d-m-Y'),
                    'tanggal_keluar' => Carbon::parse($this->permintaan->tanggal_keluar)->format('d-m-Y'),
                    'pemohon' => $this->permintaan->user->name ?? 'N/A',
                    'persetujuan1' => $PJ,
                    'persetujuan2' => '',
                    'ttd_pemohon' => $this->getTTDPath(optional($this->permintaan->user)->ttd),
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Koordinator KDO']->first())->ttd),
                    'ttd_persetujuan2' => '',
                    'jabatan_pemohon' => optional($this->permintaan->user->roles->first())->name ?? '',
                    'jabatan_persetujuan1' => 'Koordinator KDO',
                    'jabatan_persetujuan2' => '',
                    'status' => $this->getStatusPermintaan($this->permintaan, 'permintaan'),
                    'items' => $dataItems,
                    'kepala_subbagian_umum' => optional($this->kepalaSubbagian)->name ?? 'N/A',
                    'ttd_kepala_subbagian_umum' => $this->getTTDPath(optional($this->kepalaSubbagian)->ttd),
                    'jabatan_kepala_subbagian_umum' => 'Kepala Subbagian Umum',
                ];
            } elseif ($kategori === 6) {
                $permintaanStok = $permintaanStoks->first();
                $this->roles = ['Customer Services'];
                $this->roleLists = [];

                foreach ($this->roles as $role) {
                    $baseQuery = User::role($role)
                        ->whereHas(
                            'unitKerja',
                            fn($q) =>
                            $q->where('id', $this->permintaan->unit_id)
                                ->orWhere('parent_id', $this->permintaan->unit_id)
                        )
                        ->when($role === 'Customer Services', function ($query) {
                            $query->where('name', 'like', '%Insan%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();
                }

                $CS = optional($this->roleLists['Customer Services']->first())->name ?? 'N/A';
                $data = [
                    'judul' => 'FORMULIR PERMINTAAN UMUM',
                    'no_surat' => $this->permintaan->kode_permintaan,
                    'lokasi' => $this->permintaan->kategoriStok->nama ?? '',
                    'unit' => $this->permintaan->unit->nama ?? '',
                    'sub_unit' => $this->permintaan->subUnit->nama ?? '',
                    'keterangan' => $this->permintaan->keterangan ?? '',
                    'tanggal' => Carbon::createFromTimestamp($this->permintaan->tanggal_permintaan)->format('d-m-Y'),
                    'pemohon' => $this->permintaan->user->name ?? 'N/A',
                    'persetujuan1' => $CS,
                    'persetujuan2' => '',
                    'ttd_pemohon' => $this->getTTDPath(optional($this->permintaan->user)->ttd),
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Customer Services']->first())->ttd),
                    'ttd_persetujuan2' => '',
                    'jabatan_pemohon' => optional($this->permintaan->user->roles->first())->name ?? '',
                    'jabatan_persetujuan1' => 'Customer Services',
                    'jabatan_persetujuan2' => '',
                    'status' => $this->getStatusPermintaan($this->permintaan, 'permintaan'),
                    'items' => collect([
                        [
                            'nama_kdo' => $permintaanStok->aset->merk->nama . ' ' . $permintaanStok->aset->nama . '-' .  $permintaanStok->aset->noseri  ?? '',
                            'driver_name' => $permintaanStok->driver_name ?? '',
                            'voucher_name' => $permintaanStok->voucher_name ?? '',
                        ]
                    ]),
                    'kepala_subbagian_umum' => optional($this->kepalaSubbagian)->name ?? 'N/A',
                    'ttd_kepala_subbagian_umum' => $this->getTTDPath(optional($this->kepalaSubbagian)->ttd),
                    'jabatan_kepala_subbagian_umum' => 'Kepala Subbagian Umum',
                ];
            } else {
                $dataItems = [];

                foreach ($permintaanStoks as $permintaanStok) {
                    $dataItems[] = [
                        'nama_barang' => optional($permintaanStok->barangStok)->nama ?? 'N/A',
                        'nama_aset' => optional($permintaanStok->aset)->nama ?? '',
                        // 'jumlah' => $permintaanStok->jumlah . ' ' . $permintaanStok->barangStok->satuanBesar->nama,
                        'jumlah_approve' => $permintaanStok->stokDisetujui->sum('jumlah_disetujui') . ' ' . $permintaanStok->barangStok->satuanBesar->nama,
                    ];
                }
                // === Ambil user berdasarkan role ===
                $this->roles = ['Pengurus Barang', 'Koordinator Gudang'];
                $this->roleLists = [];

                foreach ($this->roles as $role) {
                    $baseQuery = User::role($role)
                        ->whereHas(
                            'unitKerja',
                            fn($q) =>
                            $q->where('id', $this->permintaan->unit_id)
                                ->orWhere('parent_id', $this->permintaan->unit_id)
                        )
                        // ->when($role === 'Penjaga Gudang', function ($query) {
                        //     $query->whereHas('lokasiStok', function ($lokasi) {
                        //         $lokasi->where('nama', 'Gudang Umum');
                        //     });
                        // });
                        ->when($role === 'Koordinator Gudang', function ($query) {
                            $query->where('name', 'like', '%Barkah%');
                        });

                    // === Pengurus Barang logika khusus ===
                    if ($role === 'Pengurus Barang') {
                        $user = $baseQuery
                            ->when(
                                optional($this->permintaan->unitKerja)->parent_id !== null,
                                fn($q) => $q->where('unit_id', $this->permintaan->unit_id),
                                fn($q) => $q->whereHas(
                                    'unitKerja',
                                    fn($q2) =>
                                    $q2->where('parent_id', $this->permintaan->unit_id)
                                )
                            )
                            ->first();

                        $this->roleLists[$role] = collect([$user])->filter(); // jika null, tetap aman
                    } else {
                        $this->roleLists[$role] = $baseQuery->get();
                    }
                }
                $penjagaGudang = optional($this->roleLists['Koordinator Gudang']->first())->name ?? 'N/A';
                $pengurusBarang = optional($this->roleLists['Pengurus Barang']->first())->name ?? 'N/A';

                $data = [
                    'judul' => 'FORMULIR PERMINTAAN UMUM',
                    'no_surat' => $this->permintaan->kode_permintaan,
                    'lokasi' => $this->permintaan->kategoriStok->nama ?? '',
                    'unit' => $this->permintaan->unit->nama ?? '',
                    'sub_unit' => $this->permintaan->subUnit->nama ?? '',
                    'keterangan' => $this->permintaan->keterangan ?? '',
                    'tanggal' => Carbon::createFromTimestamp($this->permintaan->tanggal_permintaan)->format('d-m-Y'),
                    'pemohon' => $this->permintaan->user->name ?? 'N/A',
                    'persetujuan2' => $penjagaGudang,
                    'persetujuan1' => $pengurusBarang,
                    'ttd_pemohon' => $this->getTTDPath(optional($this->permintaan->user)->ttd),
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Koordinator Gudang']->first())->ttd),
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Pengurus Barang']->first())->ttd),
                    'jabatan_pemohon' => optional($this->permintaan->user->roles->first())->name ?? '',
                    'jabatan_persetujuan2' => 'Koordinator Gudang',
                    'jabatan_persetujuan1' => 'Pengurus Barang',
                    'status' => $this->getStatusPermintaan($this->permintaan, 'permintaan'),
                    'items' => $dataItems,
                    'kepala_subbagian_umum' => optional($this->kepalaSubbagian)->name ?? 'N/A',
                    'ttd_kepala_subbagian_umum' => $this->getTTDPath(optional($this->kepalaSubbagian)->ttd),
                    'jabatan_kepala_subbagian_umum' => 'Kepala Subbagian Umum',
                ];
            }
        } elseif ($this->permintaan instanceof \App\Models\DetailPeminjamanAset) {
            $peminjamanAset = $this->permintaan->peminjamanAset;
            $namaFile = "Form-Umum-Peminjaman-{$this->permintaan->kategori->nama}.pdf";
            $kategori = Str::lower($this->permintaan->kategori->nama);
            $this->kepalaSubbagian = User::whereHas('roles', function ($query) {
                $query->where('name', 'Kepala Subbagian');
            })
                ->where('unit_id', $this->permintaan->sub_unit_id)
                ->first();

            if ($kategori == 'kdo') {
                $permintaanAset = $peminjamanAset->first();
                $this->roles = ['Customer Services', 'Koordinator KDO'];
                $this->roleLists = [];

                foreach ($this->roles as $role) {
                    $baseQuery = User::role($role)
                        ->whereHas(
                            'unitKerja',
                            fn($q) =>
                            $q->where('id', $this->permintaan->unit_id)
                                ->orWhere('parent_id', $this->permintaan->unit_id)
                        )
                        ->when($role === 'Customer Services', function ($query) {
                            $query->where('name', 'like', '%Nisya%');
                        })
                        ->when($role === 'Koordinator KDO', function ($query) {
                            $query->where('name', 'like', '%Sugi%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();
                }

                $CSkdo = optional($this->roleLists['Customer Services']->first())->name ?? 'N/A';
                $PJkdo = optional($this->roleLists['Koordinator KDO']->first())->name ?? 'N/A';
                $data = [
                    'judul' => 'FORMULIR PEMINJAMAN UMUM',
                    'no_surat' => $this->permintaan->kode_peminjaman,
                    'lokasi' => $this->permintaan->kategori->nama,
                    'unit' => $this->permintaan->unit->nama ?? '',
                    'sub_unit' => $this->permintaan->subUnit->nama ?? '',
                    'keterangan' => $this->permintaan->keterangan ?? '',
                    'tanggal' => Carbon::createFromTimestamp($this->permintaan->tanggal_peminjaman)->format('d-m-Y'),
                    'pemohon' => $this->permintaan->user->name ?? 'N/A',
                    'persetujuan1' => $CSkdo,
                    'persetujuan2' =>  $PJkdo,
                    'ttd_pemohon' => $this->getTTDPath(optional($this->permintaan->user)->ttd),
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Customer Services']->first())->ttd),
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Koordinator KDO']->first())->ttd),
                    'jabatan_pemohon' => optional($this->permintaan->user->roles->first())->name ?? '',
                    'jabatan_persetujuan1' => 'Customer Services',
                    'jabatan_persetujuan2' => 'Koordinator KDO',
                    'status' => $this->getStatusPermintaan($this->permintaan, 'peminjaman'),
                    'items' => collect([
                        [
                            // 'nama_aset' => $permintaanAset->aset->merk->nama . ' ' . $permintaanAset->aset->nama . '-' .  $permintaanAset->aset->noseri  ?? '',
                            'approved_kdo_name' => optional(Aset::with('merk')->find($permintaanAset->approved_aset_id), function ($aset) {
                                return $aset->merk->nama . ' ' . $aset->nama . '-' . $aset->noseri;
                            }) ?? '-',
                            'jumlah_orang' => $permintaanAset->jumlah_orang ?? '',
                            'waktu' => $permintaanAset->waktu->waktu . ' ' . $permintaanAset->waktu->mulai . '-' . $permintaanAset->waktu->selesai  ?? '',
                        ]
                    ]),
                    'kepala_subbagian_umum' => optional($this->kepalaSubbagian)->name ?? 'N/A',
                    'ttd_kepala_subbagian_umum' => $this->getTTDPath(optional($this->kepalaSubbagian)->ttd),
                    'jabatan_kepala_subbagian_umum' => 'Kepala Subbagian Umum',
                ];
            } elseif ($kategori == 'ruangan') {
                $permintaanAset = $peminjamanAset->first();
                $this->roles = ['Customer Services', 'Koordinator Konsumsi'];
                $this->roleLists = [];

                foreach ($this->roles as $role) {
                    $baseQuery = User::role($role)
                        ->whereHas(
                            'unitKerja',
                            fn($q) =>
                            $q->where('id', $this->permintaan->unit_id)
                                ->orWhere('parent_id', $this->permintaan->unit_id)
                        )
                        ->when($role === 'Customer Services', function ($query) {
                            $query->where('name', 'like', '%Nisya%');
                        })
                        ->when($role === 'Koordinator Konsumsi', function ($query) {
                            $query->where('name', 'like', '%Halimah%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();
                }

                $CSkdo = optional($this->roleLists['Customer Services']->first())->name ?? 'N/A';
                $PJkdo = optional($this->roleLists['Koordinator Konsumsi']->first())->name ?? 'N/A';
                $data = [
                    'judul' => 'FORMULIR PEMINJAMAN UMUM',
                    'no_surat' => $this->permintaan->kode_peminjaman,
                    'lokasi' => $this->permintaan->kategori->nama,
                    'unit' => $this->permintaan->unit->nama ?? '',
                    'sub_unit' => $this->permintaan->subUnit->nama ?? '',
                    'keterangan' => $this->permintaan->keterangan ?? '',
                    'tanggal' => Carbon::createFromTimestamp($this->permintaan->tanggal_peminjaman)->format('d-m-Y'),
                    'pemohon' => $this->permintaan->user->name ?? 'N/A',
                    'persetujuan1' => $CSkdo,
                    'persetujuan2' =>  $PJkdo,
                    'ttd_pemohon' => $this->getTTDPath(optional($this->permintaan->user)->ttd),
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Customer Services']->first())->ttd),
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Koordinator Konsumsi']->first())->ttd),
                    'jabatan_pemohon' => optional($this->permintaan->user->roles->first())->name ?? '',
                    'jabatan_persetujuan1' => 'Customer Services',
                    'jabatan_persetujuan2' => 'Koordinator Ruangan',
                    'status' => $this->getStatusPermintaan($this->permintaan, 'peminjaman'),
                    'items' => collect([
                        [
                            // 'nama_aset' => $permintaanAset->ruang->nama,
                            'approved_ruang_name' => Ruang::find($permintaanAset->approved_aset_id)?->nama,
                            'jumlah_orang' => $permintaanAset->jumlah_orang ?? '',
                            // 'waktu' => $permintaanAset->waktu->waktu . ' ' . $permintaanAset->waktu->mulai . '-' . $permintaanAset->waktu->selesai  ?? '',
                            'approved_waktu' => optional(WaktuPeminjaman::find($permintaanAset->approved_waktu_id), function ($waktu) {
                                return "{$waktu->waktu} {$waktu->mulai}-{$waktu->selesai}";
                            }) ?? '-',
                        ]
                    ]),
                    'kepala_subbagian_umum' => optional($this->kepalaSubbagian)->name ?? 'N/A',
                    'ttd_kepala_subbagian_umum' => $this->getTTDPath(optional($this->kepalaSubbagian)->ttd),
                    'jabatan_kepala_subbagian_umum' => 'Kepala Subbagian Umum',
                ];
            } else {
                $dataItems = [];

                foreach ($peminjamanAset as $permintaanAset) {
                    $dataItems[] = [
                        'nama_aset' => $permintaanAset->aset->nama ?? '',
                        // 'jumlah' => $permintaanAset->jumlah,
                        'jumlah_approve' => $permintaanAset->jumlah_approve,
                        'waktu' => $permintaanAset->waktu->waktu . ' ' . $permintaanAset->waktu->mulai . '-' . $permintaanAset->waktu->selesai  ?? '',
                    ];
                }
                // === Ambil user berdasarkan role ===
                $this->roles = ['Customer Services', 'Koordinator Gudang'];
                $this->roleLists = [];

                foreach ($this->roles as $role) {
                    $baseQuery = User::role($role)
                        ->whereHas(
                            'unitKerja',
                            fn($q) =>
                            $q->where('id', $this->permintaan->unit_id)
                                ->orWhere('parent_id', $this->permintaan->unit_id)
                        )
                        ->when($role === 'Customer Services', function ($query) {
                            $query->where('name', 'like', '%Nisya%');
                        })
                        ->when($role === 'Koordinator Gudang', function ($query) {
                            $query->where('name', 'like', '%Barkah%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();

                    // === Penanggung Jawab logika khusus ===
                    // if ($role === 'Penanggung Jawab') {
                    //     $user = $baseQuery
                    //         ->when(
                    //             optional($this->permintaan->unitKerja)->parent_id !== null,
                    //             fn($q) => $q->where('unit_id', $this->permintaan->unit_id),
                    //             fn($q) => $q->whereHas(
                    //                 'unitKerja',
                    //                 fn($q2) =>
                    //                 $q2->where('parent_id', $this->permintaan->unit_id)
                    //             )
                    //         )
                    //         ->first();

                    //     $this->roleLists[$role] = collect([$user])->filter(); // jika null, tetap aman
                    // } else {
                    //     $this->roleLists[$role] = $baseQuery->get();
                    // }
                }
                $CSperalatan = optional($this->roleLists['Customer Services']->first())->name ?? 'N/A';
                $PJperalatan = optional($this->roleLists['Koordinator Gudang']->first())->name ?? 'N/A';

                $data = [
                    'judul' => 'FORMULIR PEMINJAMAN UMUM',
                    'no_surat' => $this->permintaan->kode_peminjaman,
                    'lokasi' => $this->permintaan->kategori->nama ?? '',
                    'unit' => $this->permintaan->unit->nama ?? '',
                    'sub_unit' => $this->permintaan->subUnit->nama ?? '',
                    'keterangan' => $this->permintaan->keterangan ?? '',
                    'tanggal' => Carbon::createFromTimestamp($this->permintaan->tanggal_peminjaman)->format('d-m-Y'),
                    'pemohon' => $this->permintaan->user->name ?? 'N/A',
                    'persetujuan1' => $CSperalatan,
                    'persetujuan2' => $PJperalatan,
                    'ttd_pemohon' => $this->getTTDPath(optional($this->permintaan->user)->ttd),
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Customer Services']->first())->ttd),
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Koordinator Gudang']->first())->ttd),
                    'jabatan_pemohon' => optional($this->permintaan->user->roles->first())->name ?? '',
                    'jabatan_persetujuan1' => 'Customer Services',
                    'jabatan_persetujuan2' => 'Koordinator Gudang',
                    'status' => $this->getStatusPermintaan($this->permintaan, 'peminjaman'),
                    'items' => $dataItems,
                    'kepala_subbagian_umum' => optional($this->kepalaSubbagian)->name ?? 'N/A',
                    'ttd_kepala_subbagian_umum' => $this->getTTDPath(optional($this->kepalaSubbagian)->ttd),
                    'jabatan_kepala_subbagian_umum' => 'Kepala Subbagian Umum',
                ];
            }
        } else {
            abort(404, 'Jenis permintaan tidak dikenali.');
        }

        // Generate PDF
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Sistem Permintaan Umum');
        $pdf->SetAuthor('Dinas SDA Jakarta');
        $pdf->SetTitle('Form Permintaan');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);

        $html = view('pdf.form-umum', $data)->render();
        $pdf->writeHTML($html, true, false, true, false, '');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, $namaFile);
    }

    private function getTTDPath($filename)
    {
        return $filename ? "storage/usersTTD/{$filename}" : null;
    }

    private function getStatusPermintaan($permintaan, $tipe = null)
    {
        if ($permintaan->cancel === 1) {
            return 'dibatalkan';
        }

        if ($permintaan->cancel === 0 && $permintaan->proses === 1) {
            return 'selesai';
        }

        if ($permintaan->cancel === 0 && $permintaan->proses === null) {
            if ($tipe === 'permintaan' && $permintaan->kategori_id != 6) {
                return 'siap digunakan atau siap diambil';
            } elseif ($tipe === 'permintaan' && $permintaan->kategori_id == 6) {
                return 'sudah diambil';
            } elseif ($tipe === 'peminjaman') {
                return 'dipinjam';
            }
        }

        if ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null) {
            return 'diproses';
        }

        if ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1) {
            return 'disetujui';
        }

        return 'ditolak';
    }

    public function render()
    {
        return view('livewire.pdf-form');
    }
}
