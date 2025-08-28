<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\Persetujuan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    // Admin properties
    public $isAdmin = false;

    // Additional properties
    public $unit_id;
    public $Rkb;

    // public $permintaans;



    public function mount()
    {
        $this->tipe = Request::segment(2);

        // Initialize unit_id from user's unit or default
        $this->unit_id = Auth::user()->unit_id ?? Auth::user()->unitKerja?->parent_id ?? null;

        // Initialize Rkb based on unit
        $this->Rkb = $this->unit_id == 1000 ? 'RAB' : 'RKB';

        $this->unitOptions = $this->unit_id ? UnitKerja::where('id', $this->unit_id)->get() : UnitKerja::whereNull('parent_id')->get();
        $this->nonUmum = request()->is('permintaan/spare-part') || request()->is('permintaan/material');

        // Check if current user is admin (superadmin or unit_id null)
        $user = Auth::user();
        $this->isAdmin = $user->hasRole('superadmin') || $user->unit_id === null;

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

        // Cek apakah permintaan bisa dihapus (hanya untuk tipe permintaan, bukan peminjaman)
        $canDelete = false;
        $canEdit = false;
        $canAdminEdit = false;
        $canAdminDelete = false;

        if ($tipe === 'permintaan') {
            $isOwner = $item->user_id === auth()->id();
            // Cek apakah sudah ada approval sama sekali (baik disetujui maupun ditolak)
            $hasAnyApproval = $item->persetujuan()->whereNotNull('is_approved')->exists();

            // Regular user permissions
            $canDelete = $isOwner && !$hasAnyApproval;
            $canEdit = $isOwner && !$hasAnyApproval;

            // Admin permissions (no restrictions)
            $canAdminEdit = $this->isAdmin;
            $canAdminDelete = $this->isAdmin;
        }

        return [
            'id' => $item->id,
            'kode' => $item->nodin,
            'nomor_rab' => $withRab ? 'Dengan RAB' : 'Tanpa RAB',
            'tanggal' => $item->tanggal_permintaan,
            'tanggal_dibuat' => $item->created_at->format('d/m/Y'),
            'jam_dibuat' => $item->created_at->format('H:i:s'),
            'kategori_id' => $item->kategori_id,
            'kategori' => $tipe === 'permintaan' ? $item->kategoriStok : $item->kategori,
            'unit_id' => $item->unit_id,
            'lokasi' => $item->rab_id ? $item->rab->lokasi : $item->lokasi,
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
            'created_at' => $item->created_at->format('Y '),
            'can_delete' => $canDelete,
            'can_edit' => $canEdit,
            'can_admin_edit' => $canAdminEdit,
            'can_admin_delete' => $canAdminDelete
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

    /**
     * Get approval information for a specific permintaan
     */
    private function getApprovalInfo($permintaanId, $tipe)
    {
        $approvals = [
            'kepala_seksi' => ['status' => '-', 'tanggal' => '-'],
            'kasudin' => ['status' => '-', 'tanggal' => '-'],
            'kepala_subbagian' => ['status' => '-', 'tanggal' => '-'],
            'pengurus_barang' => ['status' => '-', 'tanggal' => '-']
        ];

        if ($tipe === 'permintaan') {
            $model = DetailPermintaanMaterial::class;
            $persetujuanList = Persetujuan::where('approvable_id', $permintaanId)
                ->where('approvable_type', $model)
                ->with('user.roles')
                ->get();

            foreach ($persetujuanList as $persetujuan) {
                $userRoles = $persetujuan->user->roles->pluck('name');

                // Check for Kepala Seksi
                if (
                    $userRoles->contains(function ($role) {
                        return str_contains($role, 'Kepala Seksi');
                    })
                ) {
                    $approvals['kepala_seksi'] = [
                        'status' => $this->getApprovalStatus($persetujuan->is_approved),
                        'tanggal' => $persetujuan->created_at ? $persetujuan->created_at->format('d/m/Y H:i') : '-'
                    ];
                }

                // Check for Kasudin (Kepala Suku Dinas)
                if (
                    $userRoles->contains(function ($role) {
                        return str_contains($role, 'Kepala Suku Dinas') || str_contains($role, 'Kasudin');
                    })
                ) {
                    $approvals['kasudin'] = [
                        'status' => $this->getApprovalStatus($persetujuan->is_approved),
                        'tanggal' => $persetujuan->created_at ? $persetujuan->created_at->format('d/m/Y H:i') : '-'
                    ];
                }

                // Check for Kepala Subbagian
                if (
                    $userRoles->contains(function ($role) {
                        return str_contains($role, 'Kepala Subbagian');
                    })
                ) {
                    $approvals['kepala_subbagian'] = [
                        'status' => $this->getApprovalStatus($persetujuan->is_approved),
                        'tanggal' => $persetujuan->created_at ? $persetujuan->created_at->format('d/m/Y H:i') : '-'
                    ];
                }

                // Check for Pengurus Barang
                if (
                    $userRoles->contains(function ($role) {
                        return str_contains($role, 'Pengurus Barang');
                    })
                ) {
                    $approvals['pengurus_barang'] = [
                        'status' => $this->getApprovalStatus($persetujuan->is_approved),
                        'tanggal' => $persetujuan->created_at ? $persetujuan->created_at->format('d/m/Y H:i') : '-'
                    ];
                }
            }
        }

        return $approvals;
    }

    /**
     * Calculate speed from created_at to pengurus barang approval
     */
    private function calculateSpeed($permintaanId, $tipe)
    {
        if ($tipe !== 'permintaan') {
            return ['hari' => '-', 'jam' => '-'];
        }

        try {
            $permintaan = DetailPermintaanMaterial::find($permintaanId);
            if (!$permintaan) {
                return ['hari' => '-', 'jam' => '-'];
            }

            // Get pengurus barang approval
            $pengurusApproval = Persetujuan::where('approvable_id', $permintaanId)
                ->where('approvable_type', DetailPermintaanMaterial::class)
                ->whereHas('user.roles', function ($query) {
                    $query->where('name', 'like', '%Pengurus Barang%');
                })
                ->whereNotNull('is_approved')
                ->first();

            if (!$pengurusApproval) {
                return ['hari' => 'Belum diapprove', 'jam' => '-'];
            }

            $createdAt = $permintaan->created_at;
            $approvedAt = $pengurusApproval->created_at;
            
            $diff = $createdAt->diff($approvedAt);
            
            // Format days
            $hari = $diff->d > 0 ? $diff->d . ' hari' : '0 hari';
            
            // Format time as HH:MM:SS
            $jam = sprintf('%02d:%02d:%02d', $diff->h, $diff->i, $diff->s);
            
            return ['hari' => $hari, 'jam' => $jam];
            
        } catch (\Exception $e) {
            return ['hari' => 'Error', 'jam' => '-'];
        }
    }

    /**
     * Get approval status text
     */
    private function getApprovalStatus($isApproved)
    {
        if (is_null($isApproved)) {
            return 'Diproses';
        }
        return $isApproved ? 'Disetujui' : 'Ditolak';
    }

    public function tambahPermintaan()
    {
        $href = "/permintaan/add/material/material";
        return redirect()->to($href);
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
            ->mergeCells('A2:Q2')
            ->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->setCellValue('A3', strtoupper('Dinas Sumber Daya Air (DSDA)'))
            ->mergeCells('A3:Q3')
            ->getStyle('A3')->getFont()->setBold(true);
        $sheet->setCellValue('A4', $filterInfo)
            ->mergeCells('A4:Q4')
            ->getStyle('A4')->getFont()->setItalic(true);
        $sheet->setCellValue('A5', 'Periode: ' . now()->format('d F Y'))
            ->mergeCells('A5:Q5')
            ->getStyle('A5')->getFont()->setBold(true);

        // Atur rata tengah untuk header
        $sheet->getStyle('A2:A5')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $sheet->setCellValue('A7', 'KODE');
        $sheet->setCellValue('B7', 'RAB');
        $sheet->setCellValue('C7', 'JENIS LAYANAN');
        $sheet->setCellValue('D7', 'TANGGAL PENGGUNAAN');
        $sheet->setCellValue('E7', 'UNIT KERJA');
        $sheet->setCellValue('F7', 'STATUS');
        $sheet->setCellValue('G7', 'TANGGAL DIBUAT');
        $sheet->setCellValue('H7', 'JAM DIBUAT');
        $sheet->setCellValue('I7', 'KEPALA SEKSI');
        $sheet->setCellValue('J7', 'TGL KEPALA SEKSI');
        $sheet->setCellValue('K7', 'KASUDIN');
        $sheet->setCellValue('L7', 'TGL KASUDIN');
        $sheet->setCellValue('M7', 'KEPALA SUBBAGIAN');
        $sheet->setCellValue('N7', 'TGL KEPALA SUBBAGIAN');
        $sheet->setCellValue('O7', 'PENGURUS BARANG');
        $sheet->setCellValue('P7', 'TGL PENGURUS BARANG');
        $sheet->setCellValue('Q7', 'HARI');
        $sheet->setCellValue('R7', 'JAM');        // Sub-header 
        // Detail Aset
        // $sheet->setCellValue('C8', 'MERK')
        //     ->setCellValue('D8', 'TIPE')
        //     ->setCellValue('E8', 'UKURAN');

        // Style header tabel

        $sheet->getStyle('A7:R7')->getFont()->setBold(true);
        $sheet->getStyle('A7:R7')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A7:R7')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A7:R7');
        //     ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->mergeCells('C7:E7');

        // $sheet->getStyle('C8:E8')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setARGB('FF000000');

        $sheet->getStyle('A7:R7')->getFill() // E26B0A
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF806000');
        $row = 8; // Mulai dari baris ke-9

        foreach ($data as $barang) {
            // Get approval information for this permintaan
            $approvals = $this->getApprovalInfo($barang['id'], $barang['tipe']);
            
            // Calculate speed (kecepatan) from created_at to pengurus barang approval
            $kecepatan = $this->calculateSpeed($barang['id'], $barang['tipe']);
            
            // Set data utama barang (kolom A dan B)
            $sheet->setCellValue('A' . $row, $barang['kode'])
                ->setCellValue('B' . $row, $barang['nomor_rab'])
                ->setCellValue('C' . $row, $barang['tipe']);

            // Set data terkait barang (kolom D sampai R)
            $sheet->setCellValue('D' . $row, date('j F Y', $barang['tanggal']))
                ->setCellValue('E' . $row, $barang['sub_unit']?->nama ?? $barang['unit']?->nama)
                ->setCellValue('F' . $row, $barang['status_teks'] ?? '-')
                ->setCellValue('G' . $row, $barang['tanggal_dibuat'] ?? '-')
                ->setCellValue('H' . $row, $barang['jam_dibuat'] ?? '-')
                ->setCellValue('I' . $row, $approvals['kepala_seksi']['status'] ?? '-')
                ->setCellValue('J' . $row, $approvals['kepala_seksi']['tanggal'] ?? '-')
                ->setCellValue('K' . $row, $approvals['kasudin']['status'] ?? '-')
                ->setCellValue('L' . $row, $approvals['kasudin']['tanggal'] ?? '-')
                ->setCellValue('M' . $row, $approvals['kepala_subbagian']['status'] ?? '-')
                ->setCellValue('N' . $row, $approvals['kepala_subbagian']['tanggal'] ?? '-')
                ->setCellValue('O' . $row, $approvals['pengurus_barang']['status'] ?? '-')
                ->setCellValue('P' . $row, $approvals['pengurus_barang']['tanggal'] ?? '-')
                ->setCellValue('Q' . $row, $kecepatan['hari'])
                ->setCellValue('R' . $row, $kecepatan['jam']);

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
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);

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
        $model = \App\Models\DetailPermintaanMaterial::class;
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
            ->map(function ($item) use ($permintaan) {
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
                        $desc = 'Barang dalam pengiriman
                        <table class="text-sm text-gray-500 italic">
                            <tr>
                                <td>Nama Driver</td>
                                <td>' . $permintaan->driver . '</td>
                            </tr>
                            <tr>
                                <td>Nomor Polisi</td>
                                <td>' . $permintaan->nopol . '</td>
                            </tr>
                            <tr>
                                <td>Nama Security</td>
                                <td>' . $permintaan->security . '</td>
                            </tr>
                        </table>';
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
                    $desc = $item->keterangan ?? null; // catatan approval
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

    public function deletePermintaan($permintaanId, $reason = null)
    {
        try {
            $permintaan = DetailPermintaanMaterial::find($permintaanId);

            if (!$permintaan) {
                session()->flash('error', 'Permintaan tidak ditemukan.');
                return;
            }

            // Cek apakah user adalah pemohon
            if ($permintaan->user_id !== auth()->id()) {
                session()->flash('error', 'Anda hanya bisa menghapus permintaan yang Anda buat sendiri.');
                return;
            }

            // Cek apakah permintaan sudah di-approve/ditolak sama sekali
            $hasAnyApproval = $permintaan->persetujuan()
                ->whereNotNull('is_approved')
                ->exists();

            if ($hasAnyApproval) {
                session()->flash('error', 'Permintaan yang sudah di-proses (disetujui/ditolak) tidak dapat dihapus.');
                return;
            }

            // Cek apakah ada status tertentu yang tidak boleh dihapus
            if ($permintaan->status && $permintaan->status > 0) {
                session()->flash('error', 'Permintaan dengan status ini tidak dapat dihapus.');
                return;
            }

            // Hapus semua data terkait dalam transaksi database
            DB::transaction(function () use ($permintaan, $reason) {
                // Log user action with reason
                \Log::info('User deleted own permintaan', [
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'permintaan_id' => $permintaan->id,
                    'permintaan_nodin' => $permintaan->nodin,
                    'reason' => $reason,
                    'deleted_at' => now()
                ]);

                // Hapus persetujuan yang pending (belum ada keputusan)
                $permintaan->persetujuan()->whereNull('is_approved')->delete();

                // Hapus detail permintaan material
                $permintaan->permintaanMaterial()->delete();

                // Hapus lampiran foto
                $lampiran = $permintaan->lampiran();
                foreach ($lampiran->get() as $foto) {
                    if ($foto->img && Storage::disk('public')->exists($foto->img)) {
                        Storage::disk('public')->delete($foto->img);
                    }
                }
                $lampiran->delete();

                // Hapus lampiran dokumen
                $lampiranDokumen = $permintaan->lampiranDokumen();
                foreach ($lampiranDokumen->get() as $dokumen) {
                    if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                        Storage::disk('public')->delete($dokumen->file_path);
                    }
                }
                $lampiranDokumen->delete();

                // Hapus permintaan utama
                $permintaan->delete();
            });

            $successMessage = 'Permintaan berhasil dihapus.';
            if ($reason) {
                $successMessage .= ' Alasan: ' . $reason;
            }
            // Don't set session flash for delete - handled by event listener

            // Dispatch event untuk refresh halaman
            $this->dispatch('permintaan-deleted', ['message' => $successMessage]);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus permintaan: ' . $e->getMessage());

            // Dispatch event to close loading modal even on error
            $this->dispatch('permintaan-deleted');
        }
    }

    // Admin Methods - No restrictions

    public function adminDeletePermintaan($permintaanId, $reason = null)
    {
        if (!$this->isAdmin) {
            session()->flash('error', 'Anda tidak memiliki izin admin.');
            return;
        }

        if (!$reason) {
            session()->flash('error', 'Alasan hapus harus diisi untuk admin.');
            return;
        }

        try {
            $permintaan = DetailPermintaanMaterial::find($permintaanId);

            if (!$permintaan) {
                session()->flash('error', 'Permintaan tidak ditemukan.');
                return;
            }

            // Admin can delete regardless of status or ownership
            DB::transaction(function () use ($permintaan, $reason) {
                // Log admin action
                \Log::info('Admin deleted permintaan', [
                    'admin_id' => auth()->id(),
                    'admin_name' => auth()->user()->name,
                    'permintaan_id' => $permintaan->id,
                    'permintaan_nodin' => $permintaan->nodin,
                    'original_user_id' => $permintaan->user_id,
                    'reason' => $reason,
                    'deleted_at' => now()
                ]);

                // Delete all persetujuan records (regardless of status)
                $permintaan->persetujuan()->delete();

                // Delete permintaan material items
                $permintaan->permintaanMaterial()->delete();

                // Delete lampiran files and records
                $lampiran = $permintaan->lampiran();
                foreach ($lampiran->get() as $foto) {
                    if ($foto->img && Storage::disk('public')->exists($foto->img)) {
                        Storage::disk('public')->delete($foto->img);
                    }
                }
                $lampiran->delete();

                // Delete lampiran dokumen files and records
                $lampiranDokumen = $permintaan->lampiranDokumen();
                foreach ($lampiranDokumen->get() as $dokumen) {
                    if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                        Storage::disk('public')->delete($dokumen->file_path);
                    }
                }
                $lampiranDokumen->delete();

                // Finally delete the main permintaan
                $permintaan->delete();
            });

            $successMessage = 'Permintaan berhasil dihapus oleh admin. Alasan: ' . $reason;
            // Don't set session flash for delete - handled by event listener

            // Dispatch event to close loading modal and refresh data
            $this->dispatch('admin-delete-completed', ['message' => $successMessage]);

            // Refresh data
            $this->applyFilters();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus permintaan: ' . $e->getMessage());

            // Dispatch event to close loading modal even on error
            $this->dispatch('admin-delete-completed');
        }
    }

    public function adminEditPermintaan($permintaanId)
    {
        if (!$this->isAdmin) {
            session()->flash('error', 'Anda tidak memiliki izin admin.');
            return;
        }

        // Redirect to admin edit page
        return redirect()->route('permintaan.admin-edit', $permintaanId);
    }

    public function render()
    {
        $permintaans = $this->applyFilters();
        return view('livewire.data-permintaan-material', compact('permintaans'));
    }
}
