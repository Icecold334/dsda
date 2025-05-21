<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\Persetujuan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPeminjamanAset;
use App\Models\DetailPermintaanStok;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\DetailPermintaanMaterial;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DataPermintaanMaterial extends Component
{
    public $nonUmum, $isSeribu;
    public $search; // Search term
    public $jenis; // Selected jenis
    public $lokasi; // Selected jenis
    public $tanggal; // Selected jenis
    public $selected_unit_id; // Selected jenis
    public $status; // Selected jenis
    public $unitOptions = [];
    public $jenisOptions = []; // List of jenis options
    public $lokasiOptions = []; // List of jenis options

    public $approvalTimeline = [], $roleList, $selectedId;
    public $showTimelineModal = false;
    public $tipe;
    // public $permintaans;



    public function mount()
    {
        $this->tipe = Request::segment(2);
        $this->unitOptions = $this->unit_id ? UnitKerja::where('id', $this->unit_id)->get() : UnitKerja::whereNull('parent_id')->get();
        $this->nonUmum = request()->is('permintaan/spare-part') || request()->is('permintaan/material');
        $this->applyFilters();
    }

    public function applyFilters()
    {
        // Ambil data permintaan dan peminjaman
        $permintaanQuery = $this->getPermintaanQuery();
        $peminjamanQuery = $this->getPeminjamanQuery();


        // Gabungkan data berdasarkan tipe
        $query = $this->tipe
            ? $permintaanQuery->sortByDesc('created_at')
            : $permintaanQuery->merge($peminjamanQuery)->sortByDesc('created_at');


        // Terapkan filter pencarian
        if (!empty($this->search)) {
            $query = $query->filter(function ($item) {
                return stripos($item['kode'], $this->search) !== false;
            });
        }

        // Terapkan filter jenis
        if (!empty($this->jenis)) {
            $query = $query->filter(function ($item) {
                return $item['tipe'] === $this->jenis;
            });
        }
        // Apply unit_id filter if selected
        // Terapkan filter unit_id
        if ($this->selected_unit_id) {
            $query = $query->filter(function ($item) {
                return $item['sub_unit_id'] == $this->selected_unit_id;
            });
        }

        // Terapkan filter tanggal
        if (!empty($this->tanggal)) {
            $query = $query->filter(function ($item) {
                return $item['tanggal'] === strtotime($this->tanggal);
            });
        }


        if (!empty($this->status)) {
            $query = $query->filter(function ($item) {
                $statusFilter = $this->status;

                if ($statusFilter === 'diproses') {
                    return is_null($item['status']);
                }

                $statusMap = [
                    'ditolak' => 0,
                    'disetujui' => 1,
                    'sedang dikirim' => 2,
                    'selesai' => 3,
                ];

                return isset($statusMap[$statusFilter]) && $item['status'] === $statusMap[$statusFilter];
            });
        }




        // Fetch filtered data
        $permintaans = $query->values();
        return $permintaans;
    }


    /**
     * Ambil data permintaan dengan filter.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getPermintaanQuery()
    {

        $permintaan = DetailPermintaanStok::where('jenis_id', $this->getJenisId())
            ->when($this->unit_id, function ($query) {
                $query->whereHas('unit', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            })->get();
        // dd($this->unit_id);

        if ($this->getJenisId() == 1) {

            $permintaan = DetailPermintaanMaterial::when($this->unit_id, function ($query) {
                $query->whereHas('user.unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            })->get()->map(function ($perm) {
                $statusMap = [
                    null => ['label' => 'Diproses', 'color' => 'warning'],
                    0 => ['label' => 'Ditolak', 'color' => 'danger'],
                    1 => ['label' => 'Disetujui', 'color' => 'success'],
                    2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
                    3 => ['label' => 'Selesai', 'color' => 'primary'],
                ];

                // Menambahkan properti dinamis
                $perm->status_teks = $statusMap[$perm->status]['label'] ?? 'Tidak diketahui';
                $perm->status_warna = $statusMap[$perm->status]['color'] ?? 'gray';

                return $perm;
            });
        }

        return $permintaan->isNotEmpty() ? $permintaan->map(function ($item) {
            return $this->mapData($item, 'permintaan');
        }) : collect([]);
    }

    /**
     * Ambil data peminjaman dengan filter.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getPeminjamanQuery()
    {
        $peminjaman = DetailPeminjamanAset::when($this->unit_id, function ($query) {
            $query->whereHas('unit', function ($unit) {
                $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });
        })->get();

        return $peminjaman->isNotEmpty() ? $peminjaman->map(function ($item) {
            return
                $this->mapData($item, 'peminjaman');
        }) : collect([]);
    }

    /**
     * Mapping data permintaan atau peminjaman.
     *
     * @param  object $item
     * @param  string $tipe
     * @return array
     */
    private function mapData($item, $tipe)
    {
        if ($this->isSeribu) {
            # code...
            $withRab = $item->permintaanMaterial->first()->rab_id;
        } else {
            $withRab = $item->rab_id;
        }

        return [
            'id' => $item->id,
            'kode' => $item->nodin,
            'nomor_rab' => $withRab ? 'Dengan ' . $this->Rkb :  'Tanpa ' . $this->Rkb,
            'tanggal' => $item->tanggal_permintaan,
            'kategori_id' => $item->kategori_id,
            'kategori' => $tipe === 'permintaan' ? $item->kategoriStok : $item->kategori,
            'unit_id' => $item->unit_id,
            'lokasi' => $item->rab_id ? $item->rab->lokasi :  $item->lokasi,
            'unit' => $item->unit,
            'sub_unit_id' => $item->sub_unit_id,
            'jenis_pekerjaan' => $item->rab_id ? $item->rab->jenis_pekerjaan : $item->nama,
            'sub_unit' => $item->subUnit,
            'status_warna' => $item->status_warna ?? null,
            'status_teks' => $item->status_teks ?? null,
            'status' => $item->status,
            'cancel' => $item->cancel,
            'proses' => $item->proses,
            'jenis_id' => $tipe === 'permintaan' ? $item->jenis_id : null,
            'tipe' => $tipe,
            'created_at' => $item->created_at->format('Y ')
        ];
    }

    /**
     * Tentukan jenis ID berdasarkan tipe.
     *
     * @return int
     */
    private function getJenisId()
    {
        return $this->tipe === 'material' ? 1 : ($this->tipe === 'spare-part' ? 2 : 3);
    }

    public function tambahPermintaan()
    {
        $href = "/permintaan/add/material/material";
        $data = $this->getPermintaanQuery();
        $query = $data->filter(function ($item) {
            $statusFilter = 'sedang dikirim';

            if ($statusFilter === 'diproses') {
                return is_null($item['status']);
            }

            $statusMap = [
                'ditolak' => 0,
                'disetujui' => 1,
                'sedang dikirim' => 2,
                'selesai' => 3,
            ];

            return isset($statusMap[$statusFilter]) && $item['status'] === $statusMap[$statusFilter];
        });
        if ($query->count()) {
            # code...
            return $this->dispatch('gagal', pesan: 'Anda masih memiliki permintaan dengan status "Sedang Dikirim". Harap selesaikan terlebih dahulu.');
        } else {
            return redirect()->to($href);
        }
    }
    public function updated($propertyName)
    {
        $this->applyFilters();
    }

    public function downloadExcel()
    {

        $data = $this->applyFilters();
        $unit = UnitKerja::find($this->selected_unit_id)?->nama;
        $spreadsheet = new Spreadsheet();
        $filterInfo = sprintf(
            "Jenis: %s, Lokasi: %s, Unit: %s",
            $this->jenis ?? '-',
            $this->lokasi ?? '-',
            $unit ?? '-'
        );

        // dd($data);

        // Properti dokumen
        $spreadsheet->getProperties()
            ->setCreator('www.inventa.id')
            ->setLastModifiedBy('www.inventa.id')
            ->setTitle('Permintaan')
            ->setSubject('Daftar Permintaan - Dinas Sumber Daya Air (DSDA)')
            ->setDescription('Daftar Permintaan')
            ->setKeywords('aset, laporan, excel')
            ->setCategory('Daftar Permintaan');

        $sheet = $spreadsheet->getActiveSheet();
        // Header judul
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', 'DAFTAR PERMINTAAN')
            ->mergeCells('A2:E2')
            ->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->setCellValue('A3', strtoupper('Dinas Sumber Daya Air (DSDA)'))
            ->mergeCells('A3:E3')
            ->getStyle('A3')->getFont()->setBold(true);
        $sheet->setCellValue('A4',  $filterInfo)
            ->mergeCells('A4:E4')
            ->getStyle('A4')->getFont()->setItalic(true);
        $sheet->setCellValue('A5', 'Periode: ' . now()->format('d F Y'))
            ->mergeCells('A5:E5')
            ->getStyle('A5')->getFont()->setBold(true);

        // Atur rata tengah untuk header
        $sheet->getStyle('A2:A5')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $sheet->setCellValue('A7', 'KODE');
        $sheet->setCellValue('B7', 'JENIS LAYANAN');
        $sheet->setCellValue('C7', 'TANGGAL PENGGUNAAN');
        $sheet->setCellValue('D7', 'UNIT KERJA');
        $sheet->setCellValue('E7', 'STATUS');

        // Sub-header 
        // Detail Aset
        // $sheet->setCellValue('C8', 'MERK')
        //     ->setCellValue('D8', 'TIPE')
        //     ->setCellValue('E8', 'UKURAN');

        // Style header tabel

        $sheet->getStyle('A7:E7')->getFont()->setBold(true);
        $sheet->getStyle('A7:E7')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A7:E7')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A7:E7');
        //     ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->mergeCells('C7:E7');

        // $sheet->getStyle('C8:E8')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setARGB('FF000000');
        $sheet->getStyle('A7:E7')->getFill() // E26B0A
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF806000');
        $row = 8; // Mulai dari baris ke-9

        foreach ($data as $barang) {
            // Set data utama barang (kolom A dan B)
            $sheet->setCellValue('A' . $row, $barang['kode'])
                ->setCellValue('B' . $row, $barang['tipe']);

            // Set data terkait barang (kolom C sampai E)
            $sheet->setCellValue('C' . $row, date('j F Y', $barang['tanggal']))
                ->setCellValue('D' . $row, $barang['sub_unit']?->nama ?? $barang['unit']?->nama)
                ->setCellValue(
                    'E' . $row,
                    $barang['cancel'] === 1 ? 'dibatalkan' : ($barang['cancel'] === 0 && $barang['proses'] === 1 ? 'selesai' : ($barang['cancel'] === 0 && $barang['proses'] === null ? 'siap diambil' : ($barang['cancel'] === null && $barang['proses'] === null && $barang['status'] === null ? 'diproses' : ($barang['cancel'] === null && $barang['proses'] === null && $barang['status'] === 1 ? 'disetujui' : 'ditolak'))))
                );

            // Pindah ke baris berikutnya
            $row++;
        }

        // Terapkan alignment ke kolom tertentu (contoh kolom F)
        // $sheet->getStyle('A1:E' . ($row - 1))
        //     ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);



        $fileName = 'Daftar Pelayanan Umum Dinas Sumber Daya Air (DSDA).xlsx';

        // Set header untuk file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        return Response::streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }

    public function openApprovalTimeline($id, $tipe)
    {
        $this->selectedId = $id;
        $model =  \App\Models\DetailPermintaanMaterial::class;
        $permintaan = DetailPermintaanMaterial::find($id);
        $roles = ['Kepala Seksi', 'Kepala Subbagian', 'Pengurus Barang'];


        $date = Carbon::parse($permintaan->created_at);

        foreach ($roles as $role) {
            // dd($this->permintaan->user->unit_id);
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', 'LIKE', '%' . $role . '%');
            })
                ->where(function ($query) {
                    $query->whereHas('unitKerja', function ($subQuery) {
                        $subQuery->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                    });
                })
                ->where(function ($query) use ($role) {
                    $query->whereHas('unitKerja', function ($subQuery) use ($role) {
                        $subQuery->when($role === 'Kepala Seksi', function ($query) {
                            return $query->where('nama', 'like', '%Pemeliharaan%');
                        })->when($role === 'Kepala Subbagian', function ($query) {
                            return $query->where('nama', 'like', '%Tata Usaha%');
                        });
                    });
                })->when($role === 'Penjaga Gudang', function ($query) {
                    return $query->where('lokasi_id', $this->permintaan->gudang_id);
                })
                ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
                ->limit(1)
                ->get();


            $propertyKey = Str::slug($role); // Generate dynamic key for roles
            $this->roleList[$propertyKey] = $users;
        }

        $this->approvalTimeline = Persetujuan::where('approvable_id', $id)
            ->where('approvable_type', $model)
            ->with('user')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($item) {
                $role = '';
                $desc = '';
                switch ($item->user->roles->first()->name) {
                    case 'Kepala Seksi':
                        $role = 'Kepala Seksi Pemeliharaan';
                        $desc = '';
                        break;
                    case 'Kepala Subbagian':
                        $role = 'Kepala Subbagian Tata Usaha';
                        $desc = 'SPPB & QR-Code';
                        break;
                    case 'Pengurus Barang':
                        $role = 'Pengurus Barang';
                        $desc = 'Barang dalam pengiriman';
                        break;

                    default:
                        $role = 'Admin';
                        break;
                }
                $isApproved = $item->is_approved;

                $status = match (true) {
                    is_null($isApproved) => 'Diproses',
                    $isApproved == true => 'Disetujui',
                    $isApproved == false => 'Ditolak',
                };


                if ($status === 'Disetujui') {
                    $desc = $desc; // ini nilai catatan approval
                } elseif ($status === 'Ditolak') {
                    $desc = $item->approvable->keterangan_ditolak ?? 'Tidak ada keterangan';
                }

                return [
                    'user' => $item->user->name,
                    'role' => $role,
                    'desc' => $desc,
                    'status' => is_null($item->is_approved)
                        ? 'Diproses'
                        : ($item->is_approved
                            ? 'Disetujui'
                            : 'Ditolak'),
                    'status_warna' => is_null($item->is_approved)
                        ? 'yellow'
                        : ($item->is_approved
                            ? 'green'
                            : 'red'),

                    'img' => $item->img,
                    'tanggal' => $item->created_at->format('d M Y H:i'),
                ];
            })
            ->toArray();

        $this->showTimelineModal = true;
    }
    public function render()
    {
        $permintaans = $this->applyFilters();
        return view('livewire.data-permintaan-material', compact('permintaans'));
    }
}
