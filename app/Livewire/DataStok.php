<?php

namespace App\Livewire;

use App\Models\Stok;
use Livewire\Component;
use App\Models\JenisStok;
use App\Models\UnitKerja;
use App\Models\BarangStok;
use App\Models\LokasiStok;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;


class DataStok extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = ''; // Search term
    public $jenis = ''; // Selected jenis
    public $lokasi = ''; // Selected jenis
    public $unit_id; // Current user's unit ID
    // public $barangs = []; Filtered barangs
    public $stoks  = [];
    public $jenisOptions = []; // List of jenis options
    public $lokasiOptions = []; // List of jenis options

    public function mount()
    {
        $this->jenisOptions = JenisStok::pluck('nama')->toArray(); // Fetch all available jenis
        $this->lokasiOptions = LokasiStok::whereHas('unitKerja', function ($unit) {
            $unit->where('parent_id', $this->unit_id)
                ->orWhere('id', $this->unit_id);
        })
            ->pluck('nama')
            ->toArray();
        $this->applyFilters(); // Fetch initial data
        // $this->fetchBarangs();
        // $this->fetchStoks();
    }

    public function fetchBarangs()
    {
        $barang = BarangStok::whereHas('merkStok', function ($merkQuery) {
            $merkQuery->whereHas('stok', function ($stokQuery) {
                $stokQuery->where('jumlah', '>', 0)
                    ->whereHas('lokasiStok.unitKerja', function ($unit) {
                        $unit->where('parent_id', $this->unit_id)
                            ->orWhere('id', $this->unit_id);
                    })
                    ->when($this->lokasi, function ($query) {
                        $query->whereHas('lokasiStok', function ($lokasiQuery) {
                            $lokasiQuery->where('nama', $this->lokasi);
                        });
                    });
            });
        })
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%') // Filter by name
                    ->orWhere('kode_barang', 'like', '%' . $this->search . '%'); // Filter by kode_barang
            })
            ->when($this->jenis, function ($query) {
                $query->whereHas('jenisStok', function ($jenisQuery) {
                    $jenisQuery->where('nama', $this->jenis);
                });
            })

            ->paginate(10);
            return $barang;
    }

    // public function fetchStoks()
    // {
    //     $this->stoks = Stok::where('jumlah', '>', 0)
    //         ->whereHas('lokasiStok.unitKerja', function ($unit) {
    //             $unit->where('parent_id', $this->unit_id)
    //                 ->orWhere('id', $this->unit_id);
    //         })
    //         ->when($this->search, function ($query) {
    //             $query->whereHas('merkStok.barangStok', function ($barangQuery) {
    //                 $barangQuery->where('nama', 'like', '%' . $this->search . '%')
    //                     ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
    //             });
    //         })
    //         ->when($this->jenis, function ($query) {
    //             $query->whereHas('merkStok.barangStok.jenisStok', function ($jenisQuery) {
    //                 $jenisQuery->where('nama', $this->jenis);
    //             });
    //         })
    //         ->with(['merkStok.barangStok'])
    //         ->get()
    //         ->groupBy('merkStok.barangStok.id');
    // }

    public function fetchStoks()
    {
        $stoks = Stok::where('jumlah', '>', 0)
            ->whereHas('lokasiStok.unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })
            ->when($this->lokasi, function ($query) {

                $query->whereHas('lokasiStok', function ($lokasiQuery) {
                    $lokasiQuery->where('nama', $this->lokasi);
                });
            })
            ->with(['merkStok', 'merkStok.barangStok']) // Eager load necessary relationships
            ->get();

        // Transform the collection into a grouped array
        $groupedStoks = [];
        foreach ($stoks as $stok) {
            if ($stok->merkStok && $stok->merkStok->barangStok) {
                $barangId = $stok->merkStok->barangStok->id;
                $groupedStoks[$barangId][] = [
                    'id' => $stok->id,
                    'jumlah' => $stok->jumlah,
                    'merk' => $stok->merkStok->nama ?? null,
                    'tipe' => $stok->merkStok->tipe ?? null,
                    'ukuran' => $stok->merkStok->ukuran ?? null,
                    'lokasi' => $stok->lokasiStok->nama ?? null,
                    'satuan' => $stok->merkStok->barangStok->satuanBesar->nama ?? null,
                ];
            }
        }

        $this->stoks = $groupedStoks;
    }

    public function downloadExcel()
    {
        $data = $this->barangs;
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
            ->setTitle('Stok')
            ->setSubject('Daftar Stok - Dinas Sumber Daya Air (DSDA)')
            ->setDescription('Laporan Stok')
            ->setKeywords('aset, laporan, excel')
            ->setCategory('Laporan Stok');

        $sheet = $spreadsheet->getActiveSheet();
        // Header judul
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', 'DAFTAR STOK')
            ->mergeCells('A2:G2')
            ->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->setCellValue('A3', strtoupper('Dinas Sumber Daya Air (DSDA)'))
            ->mergeCells('A3:G3')
            ->getStyle('A3')->getFont()->setBold(true);
        $sheet->setCellValue('A4',  $filterInfo)
            ->mergeCells('A4:G4')
            ->getStyle('A4')->getFont()->setItalic(true);
        $sheet->setCellValue('A5', 'Periode: ' . now()->format('d F Y'))
            ->mergeCells('A5:G5')
            ->getStyle('A5')->getFont()->setBold(true);

        // Atur rata tengah untuk header
        $sheet->getStyle('A2:A5')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $sheet->setCellValue('A7', 'KODE BARANG');
        $sheet->setCellValue('B7', 'NAMA BARANG');
        $sheet->setCellValue('C7', 'SPESIFIKASI');
        $sheet->setCellValue('F7', 'JUMLAH');
        $sheet->setCellValue('G7', 'LOKASI');

        // Sub-header 
        // Detail Aset
        $sheet->setCellValue('C8', 'MERK')
            ->setCellValue('D8', 'TIPE')
            ->setCellValue('E8', 'UKURAN');

        // Style header tabel

        $sheet->getStyle('A7:G8')->getFont()->setBold(true);
        $sheet->getStyle('A7:G8')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A7:G8')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A7:G8')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('C7:E7');

        $sheet->getStyle('C8:E8')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF000000');
        $sheet->getStyle('A7:G7')->getFill() // E26B0A
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF806000');
        $row = 9; // Mulai dari baris ke-9

        $stoks = $this->stoks;
        foreach ($data as $barang) {
            // Set data utama barang (kolom A dan B)
            $sheet->setCellValue('A' . $row, $barang->kode_barang)
                ->setCellValue('B' . $row, $barang->nama);

            // Periksa apakah barang memiliki stok terkait
            if (isset($stoks[$barang->id]) && count($stoks[$barang->id]) > 0) {
                foreach ($stoks[$barang->id] as $stok) {
                    // Set data stok terkait barang (kolom C sampai G)
                    $sheet->setCellValue('C' . $row, $stok['merk'] ?? '-')
                        ->setCellValue('D' . $row, $stok['tipe'] ?? '-')
                        ->setCellValue('E' . $row, $stok['ukuran'] ?? '-')
                        ->setCellValue('F' . $row, ($stok['jumlah'] ?? 0) . ' ' . ($stok['satuan'] ?? '-'))

                        ->setCellValue('G' . $row, $stok['lokasi'] ?? '-');

                    $row++; // Pindah ke baris berikutnya
                }
            } else {
                // Jika tidak ada stok, kosongkan kolom C-G untuk barang ini
                $sheet->setCellValue('C' . $row, '-')
                    ->setCellValue('D' . $row, '-')
                    ->setCellValue('E' . $row, '-')
                    ->setCellValue('F' . $row, '0')
                    ->setCellValue('G' . $row, '-');

                $row++; // Pindah ke baris berikutnya
            }

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

    public function applyFilters()
    {
        $this->fetchBarangs();
        $this->fetchStoks();
    }


    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'jenis'])) {
            $this->applyFilters();
        }
    }

    public function render()
    {
        $barangs = $this->fetchBarangs();
        $stoks = $this->stoks;
        return view('livewire.data-stok',compact('barangs', 'stoks'));
    }
}
