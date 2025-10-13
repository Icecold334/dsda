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
use App\Models\Kelurahan;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Livewire\WithPagination;

class DataPermintaanMaterial extends Component
{
    use WithPagination;

    public $nonUmum, $isSeribu;
    public $search; // Search term
    public $jenis; // Selected jenis
    public $lokasi; // Selected jenis
    public $tanggal; // Selected jenis
    public $selected_unit_id; // Selected jenis
    public $status; // Selected jenis
    public $sortBy = 'terbaru'; // Sorting option
    public $unitOptions = [];
    public $jenisOptions = []; // List of jenis options
    public $lokasiOptions = []; // List of jenis options
    public $perPage = 10; // Items per page
    public $unit_id; // User's unit ID for filtering
    public $Rkb = 'RKB'; // RKB label

    public $approvalTimeline = [], $roleList, $selectedId;
    public $showTimelineModal = false;
    public $tipe;

    // Admin properties
    public $isAdmin = false;
    public bool $isKasatpel = false;
    public ?int $kecamatanId = null;

    protected $paginationTheme = 'bootstrap'; // or 'tailwind'

    public function mount()
    {
        $this->tipe = Request::segment(2);

        // Initialize unit_id from authenticated user
        $user = Auth::user();
        $this->unit_id = $user->unit_id;

        // Initialize isSeribu based on unit name (like in Controller.php)
        if ($this->unit_id) {
            $parent = UnitKerja::find($this->unit_id);
            if ($parent) {
                $this->isSeribu = Str::contains($parent->nama, 'Suku Dinas Sumber Daya Air Kabupaten Administrasi Kepulauan Seribu');
            } else {
                $this->isSeribu = false;
            }
        } else {
            $this->isSeribu = false;
        }

        $this->unitOptions = $this->unit_id ? UnitKerja::where('id', $this->unit_id)->get() : UnitKerja::whereNull('parent_id')->get();
        $this->nonUmum = request()->is('permintaan/spare-part') || request()->is('permintaan/material');

        // Check if current user is admin (superadmin or unit_id null)
        $this->isAdmin = $user->hasRole('superadmin') || $user->unit_id === null;
        
        if (str_contains(strtolower($user->username), 'kasatpel')) {
            $this->isKasatpel = true;
            $this->kecamatanId = $user->kecamatan_id;
        }
    }

    public function updated($propertyName)
    {
        // Reset to page 1 when filters change
        if (in_array($propertyName, ['search', 'jenis', 'lokasi', 'tanggal', 'selected_unit_id', 'status', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getPermintaansProperty()
    {
        // Get filtered data
        $permintaanQuery = $this->getPermintaanQuery();
        $peminjamanQuery = $this->getPeminjamanQuery();

        // Combine data based on type
        $sortMethod = $this->sortBy === 'terlama' ? 'sortBy' : 'sortByDesc';
        $query = $this->tipe
            ? $permintaanQuery->$sortMethod('created_at')
            : $permintaanQuery->merge($peminjamanQuery)->$sortMethod('created_at');

        // Apply search filter
        if (!empty($this->search)) {
            $query = $query->filter(function ($item) {
                return stripos($item['kode'], $this->search) !== false;
            });
        }

        // Apply jenis filter
        if (!empty($this->jenis)) {
            $query = $query->filter(function ($item) {
                return $item['tipe'] === $this->jenis;
            });
        }

        // Apply unit_id filter
        if ($this->selected_unit_id) {
            $query = $query->filter(function ($item) {
                return $item['sub_unit_id'] == $this->selected_unit_id;
            });
        }

        // Apply date filter
        if (!empty($this->tanggal)) {
            $query = $query->filter(function ($item) {
                return $item['tanggal'] === strtotime($this->tanggal);
            });
        }

        // Apply status filter
        if (!empty($this->status)) {
            $query = $query->filter(function ($item) {
                $statusFilter = $this->status;

                if ($statusFilter === 'diproses') {
                    return is_null($item['status']);
                }

                if ($statusFilter === 'draft') {
                    return $item['status'] === 4;
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

        // Convert to paginated collection
        $allItems = $query->values();
        $currentPage = $this->getPage();
        $offset = ($currentPage - 1) * $this->perPage;

        return $allItems->slice($offset, $this->perPage);
    }

    public function getTotalProperty()
    {
        // Get total count for pagination
        $permintaanQuery = $this->getPermintaanQuery();
        $peminjamanQuery = $this->getPeminjamanQuery();

        $query = $this->tipe
            ? $permintaanQuery
            : $permintaanQuery->merge($peminjamanQuery);

        // Apply same filters as getPermintaansProperty
        if (!empty($this->search)) {
            $query = $query->filter(function ($item) {
                return stripos($item['kode'], $this->search) !== false;
            });
        }

        if (!empty($this->jenis)) {
            $query = $query->filter(function ($item) {
                return $item['tipe'] === $this->jenis;
            });
        }

        if ($this->selected_unit_id) {
            $query = $query->filter(function ($item) {
                return $item['sub_unit_id'] == $this->selected_unit_id;
            });
        }

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

                if ($statusFilter === 'draft') {
                    return $item['status'] === 4;
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

        return $query->count();
    }

    public function applyFilters()
    {
        // This method is now used for downloadExcel compatibility
        // Get request data and combined data
        $permintaanQuery = $this->getPermintaanQuery();
        $peminjamanQuery = $this->getPeminjamanQuery();

        // Combine data based on type
        $sortMethod = $this->sortBy === 'terlama' ? 'sortBy' : 'sortByDesc';
        $query = $this->tipe
            ? $permintaanQuery->$sortMethod('created_at')
            : $permintaanQuery->merge($peminjamanQuery)->$sortMethod('created_at');

        // Apply search filter
        if (!empty($this->search)) {
            $query = $query->filter(function ($item) {
                return stripos($item['kode'], $this->search) !== false;
            });
        }

        // Apply jenis filter
        if (!empty($this->jenis)) {
            $query = $query->filter(function ($item) {
                return $item['tipe'] === $this->jenis;
            });
        }

        // Apply unit_id filter if selected
        if ($this->selected_unit_id) {
            $query = $query->filter(function ($item) {
                return $item['sub_unit_id'] == $this->selected_unit_id;
            });
        }

        // Apply date filter
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

                if ($statusFilter === 'draft') {
                    return $item['status'] === 4;
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
        // === Query Pertama (DetailPermintaanStok) ===
        $stokQuery = DetailPermintaanStok::where('jenis_id', $this->getJenisId())
            ->when($this->unit_id, function ($query) {
                $query->whereHas('unit', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            });

        // --- TAMBAHAN FILTER KASATPEL DIMULAI ---
        if ($this->isKasatpel) {
            // 1. Ambil semua ID kelurahan yang berada di dalam kecamatan milik Kasatpel
            $kelurahanIds = Kelurahan::where('kecamatan_id', $this->kecamatanId)->pluck('id');
            
            // 2. Terapkan filter ke query SEBELUM ->get() dipanggil
            $stokQuery->whereIn('kelurahan_id', $kelurahanIds);
        }
        // --- TAMBAHAN SELESAI ---

        // Logika lama Anda untuk mengambil data tetap dipertahankan
        $permintaan = $stokQuery->get();


        // === Query Kedua (DetailPermintaanMaterial) jika jenis_id == 1 ===
        if ($this->getJenisId() == 1) {
            $materialQuery = DetailPermintaanMaterial::when($this->unit_id, function ($query) {
                $query->whereHas('user.unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            })
                // Filter draft items to only show to their creators, but allow all other statuses
                ->where(function ($query) {
                    $query->whereNull('status') // Include null status (diproses)
                        ->orWhere('status', '!=', 4) // Include all non-draft statuses
                        ->orWhere(function ($subQuery) {
                            $subQuery->where('status', 4) // Draft items only visible to creator
                                ->where('user_id', auth()->id());
                        });
                })
                ->get()->map(function ($perm) {
                    $statusMap = [
                        null => ['label' => 'Diproses', 'color' => 'warning'],
                        0 => ['label' => 'Ditolak', 'color' => 'danger'],
                        1 => ['label' => 'Disetujui', 'color' => 'success'],
                        2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
                        3 => ['label' => 'Selesai', 'color' => 'primary'],
                        4 => ['label' => 'Draft', 'color' => 'secondary'],
                    ];

                    // Add dynamic properties
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
        // Pisahkan query builder agar bisa disisipkan kondisi
        $peminjamanQuery = DetailPeminjamanAset::when($this->unit_id, function ($query) {
            $query->whereHas('unit', function ($unit) {
                $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });
        });

        // --- TAMBAHAN FILTER KASATPEL DIMULAI ---
        if ($this->isKasatpel) {
            // 1. Ambil semua ID kelurahan yang berada di dalam kecamatan milik Kasatpel
            $kelurahanIds = Kelurahan::where('kecamatan_id', $this->kecamatanId)->pluck('id');
            
            // 2. Terapkan filter ke query SEBELUM ->get() dipanggil
            $peminjamanQuery->whereIn('kelurahan_id', $kelurahanIds);
        }
        // --- TAMBAHAN SELESAI ---

        // Lanjutkan dengan logika lama Anda
        $peminjaman = $peminjamanQuery->get();

        return $peminjaman->isNotEmpty() ? $peminjaman->map(function ($item) {
            return $this->mapData($item, 'peminjaman');
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
            $withRab = $item->permintaanMaterial->first()->rab_id;
        } else {
            $withRab = $item->rab_id;
        }

        // Check if request can be deleted (only for permintaan type, not peminjaman)
        $canDelete = false;
        $canEdit = false;
        $canAdminEdit = false;
        $canAdminDelete = false;

        if ($tipe === 'permintaan') {
            $isOwner = $item->user_id === auth()->id();
            // Check if there's any approval at all (either approved or rejected)
            $hasAnyApproval = $item->persetujuan()->whereNotNull('is_approved')->exists();

            // Draft can be deleted and edited by owner
            $isDraft = $item->status === 4;

            // Regular user permissions
            $canDelete = $isOwner && (!$hasAnyApproval || $isDraft);
            $canEdit = $isOwner && (!$hasAnyApproval || $isDraft);

            // Admin permissions (no restrictions)
            $canAdminEdit = $this->isAdmin;
            $canAdminDelete = $this->isAdmin;
        }

        return [
            'id' => $item->id,
            'kode' => $item->nodin,
            'nomor_rab' => $withRab ? 'Dengan ' . $this->Rkb : 'Tanpa ' . $this->Rkb,
            'tanggal' => $item->tanggal_permintaan,
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
            'created_at' => $item->created_at, // Store original Carbon object
            'created_at_year' => $item->created_at->format('Y'),
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

    public function tambahPermintaan()
    {
        $href = "/permintaan/add/material/material";
        return redirect()->to($href);
    }

    public function downloadExcel()
    {
        $data = $this->applyFilters();
        $user = Auth::user();

        // Determine unit name for header
        $unitKerjaName = 'SEMUA UNIT KERJA';
        if (!($user->hasRole('superadmin') || $user->unit_id === null)) {
            $unitKerja = $user->unitKerja;
            if ($unitKerja) {
                // If this unit has a parent, use parent name
                if ($unitKerja->parent_id) {
                    $parentUnit = $unitKerja->parent;
                    $unitKerjaName = $parentUnit ? strtoupper($parentUnit->nama) : strtoupper($unitKerja->nama);
                } else {
                    // If this is a parent unit (suku dinas)
                    $unitKerjaName = strtoupper($unitKerja->nama);
                }
            } else {
                $unitKerjaName = 'UNIT KERJA TIDAK DIKETAHUI';
            }
        }

        // If there's a unit filter selected, use that unit's name
        if ($this->selected_unit_id) {
            $selectedUnit = UnitKerja::find($this->selected_unit_id);
            if ($selectedUnit) {
                $unitKerjaName = strtoupper($selectedUnit->nama);
            }
        }

        $spreadsheet = new Spreadsheet();

        // Document properties
        $spreadsheet->getProperties()
            ->setCreator('www.inventa.id')
            ->setLastModifiedBy('www.inventa.id')
            ->setTitle('Daftar Permintaan')
            ->setSubject('Daftar Permintaan - Dinas Sumber Daya Air (DSDA)')
            ->setDescription('Daftar Permintaan Material/Spare Part')
            ->setKeywords('permintaan, material, spare part, laporan, excel')
            ->setCategory('Daftar Permintaan');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Permintaan');

        // Document header
        $sheet->setCellValue('A1', 'DAFTAR PERMINTAAN');
        $sheet->setCellValue('A2', 'DINAS SUMBER DAYA AIR (DSDA)');
        $sheet->setCellValue('A3', $unitKerjaName);
        $sheet->setCellValue('A4', 'Periode: ' . date('d F Y'));
        $sheet->setCellValue('A5', 'Export pada: ' . date('d F Y H:i:s'));

        // Style for document header
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A4')->getFont()->setSize(10);
        $sheet->getStyle('A5')->getFont()->setSize(10);

        // Merge cells for main header
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $sheet->mergeCells('A4:E4');
        $sheet->mergeCells('A5:E5');

        // Center alignment for document header
        $sheet->getStyle('A1:A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Table header (starting from row 7)
        $sheet->setCellValue('A7', 'KODE');
        $sheet->setCellValue('B7', 'JENIS');
        $sheet->setCellValue('C7', 'TANGGAL PENGGUNAAN');
        $sheet->setCellValue('D7', 'TANGGAL PEMBUATAN');
        $sheet->setCellValue('E7', 'STATUS');

        // Table header style
        $sheet->getStyle('A7:E7')->getFont()->setBold(true);
        $sheet->getStyle('A7:E7')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A7:E7')
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A7:E7')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4F46E5'); // Primary color

        $row = 8; // Start from row 8

        foreach ($data as $item) {
            // Determine type (RAB/Without RAB)
            $jenis = $item['nomor_rab'] ?? 'Tanpa RAB';

            // Determine clearer status
            $status = 'Diproses';
            if ($item['cancel'] === 1) {
                $status = 'Dibatalkan';
            } elseif ($item['status'] === 0) {
                $status = 'Ditolak';
            } elseif ($item['status'] === 1) {
                $status = 'Disetujui';
            } elseif ($item['status'] === 2) {
                $status = 'Sedang Dikirim';
            } elseif ($item['status'] === 3 || $item['proses'] === 1) {
                $status = 'Selesai';
            } elseif ($item['status'] === 4) {
                $status = 'Draft';
            }

            // Format complete creation date
            $tanggalPembuatan = $item['created_at']->format('d F Y');

            // Set data
            $sheet->setCellValue('A' . $row, $item['kode']);
            $sheet->setCellValue('B' . $row, $jenis);
            $sheet->setCellValue('C' . $row, date('d/m/Y', $item['tanggal']));
            $sheet->setCellValue('D' . $row, $tanggalPembuatan);
            $sheet->setCellValue('E' . $row, $status);

            $row++;
        }

        // Border style for all data
        if ($row > 8) {
            $dataRange = 'A7:E' . ($row - 1);
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        // Auto width for all columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set minimum width for specific columns
        $sheet->getColumnDimension('A')->setWidth(15); // Code
        $sheet->getColumnDimension('B')->setWidth(20); // Type
        $sheet->getColumnDimension('C')->setWidth(18); // Usage Date
        $sheet->getColumnDimension('D')->setWidth(20); // Creation Date
        $sheet->getColumnDimension('E')->setWidth(15); // Status

        // Generate filename with unit name
        $unitSlug = strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $unitKerjaName));
        $fileName = 'Daftar_Permintaan_' . $unitSlug . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Set header for Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        // Save file to output
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

                // BYPASS: For period 12-19 August 2025, user 252 (Yusuf) who approves
                // will be displayed as "Kepala Seksi Pemeliharaan"
                // despite their actual role being different
                $isTransferPeriod = $item->created_at->between('2025-08-12', '2025-08-19 23:59:59');
                $isYusufTransfer = $item->user_id == 252;

                if ($isTransferPeriod && $isYusufTransfer) {
                    // Override role for Yusuf during transfer period
                    $role = 'Kepala Seksi Pemeliharaan';
                    $desc = '';
                } else {
                    // Normal logic based on user role
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
                }
                $isApproved = $item->is_approved;

                $status = match (true) {
                    is_null($isApproved) => 'Diproses',
                    $isApproved == true => 'Disetujui',
                    $isApproved == false => 'Ditolak',
                };

                if ($status === 'Disetujui') {
                    $desc = $item->keterangan ?? null; // approval notes
                } elseif ($status === 'Ditolak') {
                    $desc = $item->approvable->keterangan_ditolak ?? 'Tidak ada keterangan';
                }

                return [
                    'user' => $isTransferPeriod && $isYusufTransfer ? 'Yusuf Saut Pangibulan, ST, MPSDA' : $item->user->name,
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

            // Check if user is the requester
            if ($permintaan->user_id !== auth()->id()) {
                session()->flash('error', 'Anda hanya bisa menghapus permintaan yang Anda buat sendiri.');
                return;
            }

            // Check if request has been approved/rejected at all
            $hasAnyApproval = $permintaan->persetujuan()
                ->whereNotNull('is_approved')
                ->exists();

            // Allow deletion if it's a draft (status 4) or if no approval has been made
            $isDraft = $permintaan->status === 4;

            if ($hasAnyApproval && !$isDraft) {
                session()->flash('error', 'Permintaan yang sudah di-proses (disetujui/ditolak) tidak dapat dihapus.');
                return;
            }

            // Check if there's a specific status that cannot be deleted (except draft)
            if ($permintaan->status && $permintaan->status > 0 && $permintaan->status !== 4) {
                session()->flash('error', 'Permintaan dengan status ini tidak dapat dihapus.');
                return;
            }

            // Delete all related data in database transaction
            DB::transaction(function () use ($permintaan, $reason) {
                // Log user action with reason
                \Log::info('User deleted own permintaan', [
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'permintaan_id' => $permintaan->id,
                    'permintaan_nodin' => $permintaan->nodin,
                    'status' => $permintaan->status,
                    'reason' => $reason,
                    'deleted_at' => now()
                ]);

                // Delete pending approvals (no decision yet)
                $permintaan->persetujuan()->whereNull('is_approved')->delete();

                // Delete permintaan material details
                $permintaan->permintaanMaterial()->delete();

                // Delete photo attachments
                $lampiran = $permintaan->lampiran();
                foreach ($lampiran->get() as $foto) {
                    if ($foto->img && Storage::disk('public')->exists($foto->img)) {
                        Storage::disk('public')->delete($foto->img);
                    }
                }
                $lampiran->delete();

                // Delete document attachments
                $lampiranDokumen = $permintaan->lampiranDokumen();
                foreach ($lampiranDokumen->get() as $dokumen) {
                    if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                        Storage::disk('public')->delete($dokumen->file_path);
                    }
                }
                $lampiranDokumen->delete();

                // Delete main permintaan
                $permintaan->delete();
            });

            $successMessage = 'Permintaan berhasil dihapus.';
            if ($reason) {
                $successMessage .= ' Alasan: ' . $reason;
            }
            // Don't set session flash for delete - handled by event listener

            // Dispatch event to refresh page
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

                foreach ($itemsToDelete as $item) {
                    if ($item->merkStok && $item->merkStok->barang_id) {
                        $barang = BarangStok::find($item->merkStok->barang_id);
                        if ($barang) {
                            $barang->stok += $item->jumlah;
                            $barang->save();
                        }
                    }

                    TransaksiStok::where('permintaan_id', $item->id)
                        ->where('tipe', 'Pengajuan')
                        ->delete(); 
                }

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
            $this->resetPage();
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

    public function adminEditStatus($permintaanId, $newStatus, $reason = null)
    {
        if (!$this->isAdmin) {
            session()->flash('error', 'Anda tidak memiliki izin admin.');
            return;
        }

        try {
            DB::beginTransaction();

            // Find the permintaan
            $permintaan = DetailPermintaanMaterial::findOrFail($permintaanId);

            $oldStatus = $permintaan->status;
            $oldStatusText = match ($oldStatus) {
                null => 'Diproses',
                0 => 'Ditolak',
                1 => 'Disetujui',
                2 => 'Sedang Dikirim',
                3 => 'Selesai',
                4 => 'Draft',
                default => 'Tidak diketahui'
            };

            $newStatusText = match ($newStatus) {
                null => 'Diproses',
                0 => 'Ditolak',
                1 => 'Disetujui',
                2 => 'Sedang Dikirim',
                3 => 'Selesai',
                4 => 'Draft',
                default => 'Tidak diketahui'
            };

            // Update status
            $permintaan->status = $newStatus;
            $permintaan->save();

            // Log admin action
            $logMessage = "Status permintaan diubah oleh admin dari '{$oldStatusText}' menjadi '{$newStatusText}'";
            if ($reason) {
                $logMessage .= ". Alasan: {$reason}";
            }

            // Log to database if you have activity log
            // ActivityLog::create([
            //     'user_id' => Auth::id(),
            //     'action' => 'admin_status_change',
            //     'model_type' => 'DetailPermintaanMaterial',
            //     'model_id' => $permintaanId,
            //     'description' => $logMessage,
            //     'ip_address' => request()->ip(),
            //     'user_agent' => request()->userAgent()
            // ]);

            DB::commit();

            session()->flash('success', "Status permintaan berhasil diubah dari '{$oldStatusText}' menjadi '{$newStatusText}'");

            // Dispatch event to close modal and refresh
            $this->dispatch('admin-status-changed');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage());

            // Dispatch event to close loading modal even on error
            $this->dispatch('admin-status-change-completed');
        }
    }

    // Pagination methods
    public function previousPage()
    {
        $this->setPage(max(1, $this->getPage() - 1));
    }

    public function nextPage()
    {
        $totalPages = ceil($this->total / $this->perPage);
        $this->setPage(min($totalPages, $this->getPage() + 1));
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {
        return view('livewire.data-permintaan-material', [
            'permintaans' => $this->permintaans,
            'total' => $this->total,
            'currentPage' => $this->getPage(),
            'totalPages' => ceil($this->total / $this->perPage),
            'from' => (($this->getPage() - 1) * $this->perPage) + 1,
            'to' => min($this->getPage() * $this->perPage, $this->total)
        ]);
    }
}