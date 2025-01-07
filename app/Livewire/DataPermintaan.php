<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPeminjamanAset;
use App\Models\DetailPermintaanStok;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DataPermintaan extends Component
{
    public $search; // Search term
    public $jenis; // Selected jenis
    public $lokasi; // Selected jenis
    public $tanggal; // Selected jenis
    public $unit_id; // Selected jenis
    public $status; // Selected jenis
    public $unitOptions = [];
    public $jenisOptions = []; // List of jenis options
    public $lokasiOptions = []; // List of jenis options

    public $permintaans;

    public function mount()
    {
        $this->unitOptions = UnitKerja::find($this->unit_id);

        $this->applyFilters();
    }

    public function applyFilters()
    {





        $permintaanQuery = DetailPermintaanStok::select('id', 'kode_permintaan as kode', 'tanggal_permintaan as tanggal', 'kategori_id', 'unit_id', 'status', 'cancel', 'proses', 'jenis_id', DB::raw('"permintaan" as tipe'), 'created_at')
            ->where('jenis_id', 3)
            ->whereHas('unit', function ($unit) {
                $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });

        $peminjamanQuery = DetailPeminjamanAset::select('id', 'kode_peminjaman as kode', 'tanggal_peminjaman as tanggal', 'kategori_id', 'unit_id', 'status', 'cancel', 'proses', DB::raw('"peminjaman" as tipe'), DB::raw('NULL as jenis_id'), 'created_at')
            ->whereHas('unit', function ($unit) {
                $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });

        // Gabungkan kedua query menggunakan union
        $query = $permintaanQuery->union($peminjamanQuery);

        // Tambahkan kondisi tambahan pada query gabungan
        $query->orderBy('id', 'desc');
        // dd($query->where('jenis_id', 4));
        // Apply search filter if present
        if (!empty($this->search)) {
            $query->where('kode_permintaan', 'like', '%' . $this->search . '%');
        }

        // Apply jenis filter if selected
        if (!empty($this->jenis)) {
            $query->where('tipe', $this->jenis);
        }

        // Apply unit_id filter if selected
        if (!empty($this->unit_id)) {
            $query->where('unit_id', $this->unit_id);
        }
        if (!empty($this->tanggal)) {
            $tanggalFormatted = $this->tanggal; // Contoh: '2025-01-02'

            // Konversi tanggal input ke rentang waktu (awal dan akhir hari)
            $tanggalStart = strtotime($tanggalFormatted . ' 00:00:00');
            $tanggalEnd = strtotime($tanggalFormatted . ' 23:59:59');
            // dd($tanggalStart, $tanggalEnd);

            // Filter berdasarkan rentang timestamp
            $query->whereBetween('tanggal', [$tanggalStart, $tanggalEnd]);
        }

        // Apply status filter if selected
        if (!empty($this->status)) {
            $s = $this->status;

            $query->where(function ($query) use ($s) {
                if ($s === 'diproses') {
                    $query->whereNull('cancel')
                        ->whereNull('proses')
                        ->whereNull('status');
                } elseif ($s === 'disetujui') {
                    $query->whereNull('cancel')
                        ->whereNull('proses')
                        ->where('status', 1);
                } elseif ($s === 'ditolak') {
                    $query->where('cancel', 1)
                        ->whereNull('proses')
                        ->orWhere(function ($query) {
                            $query->whereNull('cancel')
                                ->whereNull('proses')
                                ->where('status', 0);
                        });
                } elseif ($s === 'selesai') {
                    $query->where('cancel', 0)
                        ->where('proses', 1);
                } elseif ($s === 'siap diambil') {
                    $query->where('cancel', 0)
                        ->whereNull('proses');
                } elseif ($s === 'dibatalkan') {
                    $query->where('cancel', 1);
                }
            });
        }


        // Fetch filtered data
        $this->permintaans = $query->get();
    }


    public function updated($propertyName)
    {
        $this->applyFilters();
    }

    public function downloadExcel()
    {
        $data = $this->permintaans;
        $unit = UnitKerja::find($this->unit_id)->nama;
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

        $sheet->getStyle('A7:E8')->getFont()->setBold(true);
        $sheet->getStyle('A7:E8')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A7:E8')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A7:E8');
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
            $sheet->setCellValue('A' . $row, $barang->kode)
                ->setCellValue('B' . $row, $barang->tipe);

            // // Periksa apakah barang memiliki stok terkait
            // if (isset($stoks[$barang->id]) && count($stoks[$barang->id]) > 0) {
            //     foreach ($stoks[$barang->id] as $stok) {
            //         // Set data stok terkait barang (kolom C sampai G)
            $sheet->setCellValue('C' . $row, $barang->tanggal)
                ->setCellValue('D' . $row, $barang->unit->nama);
            // ->setCellValue('E' . $row, $stok['ukuran'] ?? '-')

            //         $row++; // Pindah ke baris berikutnya
            //     }
            // } else {
            //     // Jika tidak ada stok, kosongkan kolom C-G untuk barang ini
            //     $sheet->setCellValue('C' . $row, '-')
            //         ->setCellValue('D' . $row, '-')
            //         ->setCellValue('E' . $row, '-')
            //         ->setCellValue('F' . $row, '0')
            //         ->setCellValue('G' . $row, '-');

            //     $row++; // Pindah ke baris berikutnya
            // }

            // Terapkan alignment ke kanan untuk kolom tertentu
            // $sheet->getStyle('F' . ($row - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);



        $fileName = 'Daftar Stok Dinas Sumber Daya Air (DSDA).xlsx';

        // Set header untuk file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        return Response::streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Daftar_Stok_DSDA.xlsx');
    }
    public function render()
    {
        return view('livewire.data-permintaan');
    }
}
