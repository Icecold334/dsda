<?php

namespace App\Livewire;

use App\Models\Rab;
use App\Models\DetailPermintaanMaterial;
use App\Models\PermintaanMaterial;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DataRab extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $Rkb, $RKB, $sudin, $unit_id;
    public $showHistoryModal = false;
    public $selectedRabId = null;
    public $historyData = [];
    public $searchSpb = '';
    public $loading = false;

    public function mount()
    {
        $this->unit_id = Auth::user()->unit_id;
    }

    public function fetchData()
    {
        $user = Auth::user();

        // Check if user is superadmin
        if ($user->hasRole('superadmin') || $user->unit_id === null) {
            // Superadmin dapat melihat semua RAB dari semua suku dinas
            $rabs = Rab::with(['user.unitKerja'])->orderBy('created_at', 'desc')->paginate(5);
        } else {
            // User biasa hanya bisa melihat RAB dari unit mereka/bawahan mereka
            $rabs = Rab::whereHas('user.unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })->orderBy('created_at', 'desc')->paginate(5);
        }

        $rabs->getCollection()->transform(function ($rab) {
            $statusMap = [
                null => ['label' => 'Diproses', 'color' => 'warning'],
                0 => ['label' => 'Ditolak', 'color' => 'danger'],
                1 => ['label' => 'Dibatalkan', 'color' => 'secondary'],
                2 => ['label' => 'Disetujui', 'color' => 'success'],
                3 => ['label' => 'Selesai', 'color' => 'primary'],
            ];

            // Tambahkan properti dinamis ke dalam object
            $rab->status_teks = $statusMap[$rab->status]['label'] ?? 'Tidak diketahui';
            $rab->status_warna = $statusMap[$rab->status]['color'] ?? 'gray';
            return $rab;
        });

        return $rabs;
    }

    public function showHistory($rabId)
    {
        $this->selectedRabId = $rabId;
        $this->searchSpb = '';
        $this->loadHistoryData();
        $this->showHistoryModal = true;
    }

    public function loadHistoryData()
    {
        if (!$this->selectedRabId)
            return;

        $query = collect();

        // Ambil permintaan material berdasarkan RAB dengan filter status
        $permintaanMaterial = DetailPermintaanMaterial::where('rab_id', $this->selectedRabId)
            ->whereIn('status', [2, 3]) // Filter hanya status 2 (Sedang Dikirim) dan 3 (Selesai)
            ->when($this->searchSpb, function ($q) {
                $q->where('nodin', 'like', '%' . $this->searchSpb . '%');
            })
            ->with(['user.kecamatan', 'permintaanMaterial'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil permintaan material individual (untuk kasus Seribu) dengan filter status
        $permintaanMaterialSeribu = PermintaanMaterial::where('rab_id', $this->selectedRabId)
            ->whereHas('detailPermintaan', function ($detail) {
                $detail->whereIn('status', [2, 3]); // Filter hanya status 2 dan 3
            })
            ->when($this->searchSpb, function ($q) {
                $q->whereHas('detailPermintaan', function ($detail) {
                    $detail->where('nodin', 'like', '%' . $this->searchSpb . '%');
                });
            })
            ->with(['detailPermintaan.user.kecamatan', 'detailPermintaan'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('detail_permintaan_id')
            ->map(function ($group) {
                return $group->first()->detailPermintaan;
            });

        // Gabungkan dan format data
        $allPermintaan = $permintaanMaterial->merge($permintaanMaterialSeribu)->unique('id');

        $this->historyData = $allPermintaan->map(function ($permintaan) {
            $statusMap = [
                null => ['label' => 'Diproses', 'color' => 'warning'],
                0 => ['label' => 'Ditolak', 'color' => 'danger'],
                1 => ['label' => 'Disetujui', 'color' => 'success'],
                2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
                3 => ['label' => 'Selesai', 'color' => 'primary'],
            ];

            return [
                'id' => $permintaan->id,
                'nodin' => $permintaan->nodin,
                'pemohon' => $permintaan->user->name ?? '-',
                'kecamatan' => $permintaan->user->kecamatan->kecamatan ?? '-',
                'tanggal' => $permintaan->created_at->format('d M Y'),
                'total_items' => $permintaan->permintaanMaterial->count(),
                'status' => $statusMap[$permintaan->status]['label'] ?? 'Tidak diketahui',
                'status_color' => $statusMap[$permintaan->status]['color'] ?? 'gray',
            ];
        })->toArray();
    }

    public function updatedSearchSpb()
    {
        $this->loadHistoryData();
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->selectedRabId = null;
        $this->historyData = [];
        $this->searchSpb = '';
    }

    public function downloadExcel()
    {
        $this->loading = true;

        try {
            $user = Auth::user();

            // Debug untuk memastikan data user dan unit
            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }

            // Ambil data RAB berdasarkan role user (sama seperti fetchData tapi tanpa pagination)
            if ($user->hasRole('superadmin') || $user->unit_id === null) {
                $rabs = Rab::with(['user.unitKerja'])->orderBy('created_at', 'desc')->get();
            } else {
                $rabs = Rab::whereHas('user.unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                })->orderBy('created_at', 'desc')->get();
            }

            // Transform data dengan status mapping
            $rabs->transform(function ($rab) {
                $statusMap = [
                    null => 'Diproses',
                    0 => 'Ditolak',
                    1 => 'Dibatalkan',
                    2 => 'Disetujui',
                    3 => 'Selesai',
                ];
                $rab->status_teks = $statusMap[$rab->status] ?? 'Tidak diketahui';
                return $rab;
            });

            // Buat spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set judul dokumen
            $sheet->setTitle('Data RAB');

            // Tentukan nama suku dinas untuk header dan filename
            $sukuDinasName = 'SEMUA SUKU DINAS';
            if (!($user->hasRole('superadmin') || $user->unit_id === null)) {
                // Ambil nama unit kerja dari user yang login
                $unitKerja = $user->unitKerja;
                if ($unitKerja) {
                    // Jika unit ini memiliki parent, gunakan nama parent
                    if ($unitKerja->parent_id) {
                        $parentUnit = $unitKerja->parent;
                        $sukuDinasName = $parentUnit ? strtoupper($parentUnit->nama) : strtoupper($unitKerja->nama);
                    } else {
                        // Jika ini adalah parent unit (suku dinas)
                        $sukuDinasName = strtoupper($unitKerja->nama);
                    }
                } else {
                    $sukuDinasName = 'SUKU DINAS TIDAK DIKETAHUI';
                }
            }

            // Tentukan periode data
            $oldestRab = $rabs->sortBy('created_at')->first();
            $newestRab = $rabs->sortByDesc('created_at')->first();

            $startDate = $oldestRab ? $oldestRab->created_at->format('d F Y') : date('01 F Y');
            $endDate = $newestRab ? $newestRab->created_at->format('d F Y') : date('d F Y');

            // Header informasi dokumen
            $sheet->setCellValue('A1', 'DAFTAR RENCANA ANGGARAN BIAYA (RAB)');
            $sheet->setCellValue('A2', 'DINAS SUMBER DAYA AIR (DSDA)');
            $sheet->setCellValue('A3', $sukuDinasName);
            $sheet->setCellValue('A4', 'Periode: ' . $startDate . ' - ' . $endDate);
            $sheet->setCellValue('A5', 'Tanggal Export: ' . date('d F Y'));

            // Style untuk header dokumen
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A4')->getFont()->setSize(10);
            $sheet->getStyle('A5')->getFont()->setSize(10);

            // Merge cells untuk header utama
            $sheet->mergeCells('A1:F1');
            $sheet->mergeCells('A2:F2');
            $sheet->mergeCells('A3:F3');
            $sheet->mergeCells('A4:F4');
            $sheet->mergeCells('A5:F5');

            // Center alignment untuk header dokumen
            $sheet->getStyle('A1:A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Header tabel (mulai dari baris 7)
            $headers = ['No', 'Jenis Pekerjaan', 'Tahun Anggaran', 'Lokasi', 'Tanggal Pelaksanaan', 'Status'];
            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
            foreach ($headers as $index => $header) {
                $sheet->setCellValue($columns[$index] . '7', $header);
            }

            // Style untuk header tabel
            $headerRange = 'A7:F7';
            $sheet->getStyle($headerRange)->getFont()->setBold(true);
            $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle($headerRange)->getFill()->getStartColor()->setRGB('4F46E5');
            $sheet->getStyle($headerRange)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Data (mulai dari baris 8)
            $row = 8;
            foreach ($rabs as $index => $rab) {
                $tanggalPelaksanaan = $rab->mulai->format('d/m/Y') . ' - ' . $rab->selesai->format('d/m/Y');

                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $rab->jenis_pekerjaan);
                $sheet->setCellValue('C' . $row, $rab->created_at->format('Y'));
                $sheet->setCellValue('D' . $row, $rab->lokasi);
                $sheet->setCellValue('E' . $row, $tanggalPelaksanaan);
                $sheet->setCellValue('F' . $row, $rab->status_teks);
                $row++;
            }

            // Style untuk data
            if ($row > 8) {
                $dataRange = 'A8:F' . ($row - 1);
                $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A8:A' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C8:C' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F8:F' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Auto width untuk semua kolom
            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Set minimum width untuk kolom tertentu
            $sheet->getColumnDimension('B')->setWidth(25); // Jenis Pekerjaan
            $sheet->getColumnDimension('D')->setWidth(30); // Lokasi
            $sheet->getColumnDimension('E')->setWidth(25); // Tanggal Pelaksanaan

            // Generate file dengan nama yang menyertakan suku dinas
            $sukuDinasSlug = strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $sukuDinasName));
            $filename = 'Data_RAB_' . $sukuDinasSlug . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            $writer = new Xlsx($spreadsheet);

            $tempFile = tempnam(sys_get_temp_dir(), 'rab_export');
            $writer->save($tempFile);

            $this->loading = false;

            // Download file
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            $this->loading = false;
            session()->flash('error', 'Terjadi kesalahan saat mengexport data: ' . $e->getMessage());
            return null;
        }
    }

    public function render()
    {
        $rabs = $this->fetchData();
        return view('livewire.data-rab', compact('rabs'));
    }
}
