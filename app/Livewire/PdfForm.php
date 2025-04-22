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
            if ($kategori == 4) {
                $dataItems = [];

                foreach ($permintaanStoks as $permintaanStok) {
                    $dataItems[] = [
                        'nama_barang' => optional($permintaanStok->barangStok)->nama ?? 'N/A',
                        'jumlah' => $permintaanStok->jumlah . ' ' . $permintaanStok->barangStok->satuanBesar->nama,
                    ];
                }
                // === Ambil user berdasarkan role ===
                $this->roles = ['Customer Services', 'Penanggung Jawab'];
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
                        ->when($role === 'Penanggung Jawab', function ($query) {
                            $query->where('name', 'like', '%Halimah%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();
                }
                $CSkonsumsi = optional($this->roleLists['Customer Services']->first())->name ?? 'N/A';
                $PJkonsumsi = optional($this->roleLists['Penanggung Jawab']->first())->name ?? 'N/A';

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
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Penanggung Jawab']->first())->ttd),
                    'items' => $dataItems,
                ];
            } elseif ($kategori == 5) {
                $dataItems = [];
                foreach ($permintaanStoks as $permintaanStok) {
                    $status = $permintaanStok->status;
                    $dataItems[] = [
                        'nama_barang' => $permintaanStok->barangStok->nama ?? 'N/A',
                        'keterangan' => $permintaanStok->deskripsi ?? '',
                        'status' => $status === 1 ? 'Disetujui' : ($status === 0 ? 'Ditolak' : '-'),
                    ];
                }

                $this->roles = ['Penanggung Jawab'];
                $this->roleLists = [];

                foreach ($this->roles as $role) {
                    $baseQuery = User::role($role)
                        ->whereHas(
                            'unitKerja',
                            fn($q) =>
                            $q->where('id', $this->permintaan->unit_id)
                                ->orWhere('parent_id', $this->permintaan->unit_id)
                        )
                        ->when($role === 'Penanggung Jawab', function ($query) {
                            $query->where('name', 'like', '%Sugi%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();
                }

                $PJ = optional($this->roleLists['Penanggung Jawab']->first())->name ?? 'N/A';
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
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Penanggung Jawab']->first())->ttd),
                    'ttd_persetujuan2' => '',
                    'items' => $dataItems,
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
                    'items' => collect([
                        [
                            'nama_aset' => $permintaanStok->aset->merk->nama . ' ' . $permintaanStok->aset->nama . '-' .  $permintaanStok->aset->noseri  ?? '',
                            'driver_name' => $permintaanStok->driver_name ?? '',
                            'voucher_name' => $permintaanStok->voucher_name ?? '',
                        ]
                    ]),
                ];
            } else {
                $dataItems = [];

                foreach ($permintaanStoks as $permintaanStok) {
                    $dataItems[] = [
                        'nama_barang' => optional($permintaanStok->barangStok)->nama ?? 'N/A',
                        'nama_aset' => optional($permintaanStok->aset)->nama ?? '',
                        'jumlah' => $permintaanStok->jumlah . ' ' . $permintaanStok->barangStok->satuanBesar->nama,
                        'jumlah_approve' => $permintaanStok->stokDisetujui->sum('jumlah_disetujui') . ' ' . $permintaanStok->barangStok->satuanBesar->nama,
                    ];
                }
                // === Ambil user berdasarkan role ===
                $this->roles = ['Penjaga Gudang', 'Pengurus Barang'];
                $this->roleLists = [];

                foreach ($this->roles as $role) {
                    $baseQuery = User::role($role)
                        ->whereHas(
                            'unitKerja',
                            fn($q) =>
                            $q->where('id', $this->permintaan->unit_id)
                                ->orWhere('parent_id', $this->permintaan->unit_id)
                        )
                        ->when($role === 'Penjaga Gudang', function ($query) {
                            $query->whereHas('lokasiStok', function ($lokasi) {
                                $lokasi->where('nama', 'Gudang Umum');
                            });
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
                $penjagaGudang = optional($this->roleLists['Penjaga Gudang']->first())->name ?? 'N/A';
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
                    'persetujuan1' => $penjagaGudang,
                    'persetujuan2' => $pengurusBarang,
                    'ttd_pemohon' => $this->getTTDPath(optional($this->permintaan->user)->ttd),
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Penjaga Gudang']->first())->ttd),
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Pengurus Barang']->first())->ttd),
                    'items' => $dataItems,
                ];
            }
        } elseif ($this->permintaan instanceof \App\Models\DetailPeminjamanAset) {
            $peminjamanAset = $this->permintaan->peminjamanAset;
            $namaFile = "Form-Umum-Peminjaman-{$this->permintaan->kategori->nama}.pdf";
            $kategori = Str::lower($this->permintaan->kategori->nama);

            if ($kategori == 'kdo') {
                $permintaanAset = $peminjamanAset->first();
                $this->roles = ['Customer Services', 'Penanggung Jawab'];
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
                        ->when($role === 'Penanggung Jawab', function ($query) {
                            $query->where('name', 'like', '%Sugi%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();
                }

                $CSkdo = optional($this->roleLists['Customer Services']->first())->name ?? 'N/A';
                $PJkdo = optional($this->roleLists['Penanggung Jawab']->first())->name ?? 'N/A';
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
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Penanggung Jawab']->first())->ttd),
                    'items' => collect([
                        [
                            'nama_aset' => $permintaanAset->aset->merk->nama . ' ' . $permintaanAset->aset->nama . '-' .  $permintaanAset->aset->noseri  ?? '',
                            'approved_aset_id' => $permintaanAset->approved_aset_id ?? null,
                            'approved_aset_name' => optional(Aset::with('merk')->find($permintaanAset->approved_aset_id), function ($aset) {
                                return $aset->merk->nama . ' ' . $aset->nama . '-' . $aset->noseri;
                            }) ?? '-',
                            'jumlah_orang' => $permintaanAset->jumlah_orang ?? '',
                            'waktu' => $permintaanAset->waktu->waktu . ' ' . $permintaanAset->waktu->mulai . '-' . $permintaanAset->waktu->selesai  ?? '',
                        ]
                    ]),
                ];
            } elseif ($kategori == 'ruangan') {
                $permintaanAset = $peminjamanAset->first();
                $this->roles = ['Customer Services', 'Penanggung Jawab'];
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
                        ->when($role === 'Penanggung Jawab', function ($query) {
                            $query->where('name', 'like', '%Halimah%');
                        });
                    $this->roleLists[$role] = $baseQuery->get();
                }

                $CSkdo = optional($this->roleLists['Customer Services']->first())->name ?? 'N/A';
                $PJkdo = optional($this->roleLists['Penanggung Jawab']->first())->name ?? 'N/A';
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
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Penanggung Jawab']->first())->ttd),
                    'items' => collect([
                        [
                            'nama_aset' => $permintaanAset->ruang->nama,
                            'approved_aset_name' => Ruang::find($permintaanAset->approved_aset_id)?->nama,
                            'jumlah_orang' => $permintaanAset->jumlah_orang ?? '',
                            'waktu' => $permintaanAset->waktu->waktu . ' ' . $permintaanAset->waktu->mulai . '-' . $permintaanAset->waktu->selesai  ?? '',
                            'approved_waktu' => optional(WaktuPeminjaman::find($permintaanAset->approved_waktu_id), function ($waktu) {
                                return "{$waktu->waktu} {$waktu->mulai}-{$waktu->selesai}";
                            }) ?? '-',
                        ]
                    ]),
                ];
            } else {
                $dataItems = [];

                foreach ($peminjamanAset as $permintaanAset) {
                    $dataItems[] = [
                        'nama_aset' => $permintaanAset->aset->nama ?? '',
                        'jumlah' => $permintaanAset->jumlah,
                        'jumlah_approve' => $permintaanAset->jumlah_approve,
                        'waktu' => $permintaanAset->waktu->waktu . ' ' . $permintaanAset->waktu->mulai . '-' . $permintaanAset->waktu->selesai  ?? '',
                    ];
                }
                // === Ambil user berdasarkan role ===
                $this->roles = ['Customer Services', 'Penanggung Jawab'];
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
                        });

                    // === Penanggung Jawab logika khusus ===
                    if ($role === 'Penanggung Jawab') {
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
                $CSperalatan = optional($this->roleLists['Customer Services']->first())->name ?? 'N/A';
                $PJperalatan = optional($this->roleLists['Penanggung Jawab']->first())->name ?? 'N/A';

                $data = [
                    'judul' => 'FORMULIR PEMINJAMAN UMUM',
                    'no_surat' => $this->permintaan->kode_peminjaman,
                    'lokasi' => $this->permintaan->kategoriStok->nama ?? '',
                    'unit' => $this->permintaan->unit->nama ?? '',
                    'sub_unit' => $this->permintaan->subUnit->nama ?? '',
                    'keterangan' => $this->permintaan->keterangan ?? '',
                    'tanggal' => Carbon::createFromTimestamp($this->permintaan->tanggal_peminjaman)->format('d-m-Y'),
                    'pemohon' => $this->permintaan->user->name ?? 'N/A',
                    'persetujuan1' => $CSperalatan,
                    'persetujuan2' => $PJperalatan,
                    'ttd_pemohon' => $this->getTTDPath(optional($this->permintaan->user)->ttd),
                    'ttd_persetujuan1' => $this->getTTDPath(optional($this->roleLists['Customer Services']->first())->ttd),
                    'ttd_persetujuan2' => $this->getTTDPath(optional($this->roleLists['Penanggung Jawab']->first())->ttd),
                    'items' => $dataItems,
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

    public function render()
    {
        return view('livewire.pdf-form');
    }
}
