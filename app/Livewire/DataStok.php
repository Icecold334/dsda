<?php

namespace App\Livewire;

use App\Models\Stok;
use Livewire\Component;
use App\Models\JenisStok;
use App\Models\UnitKerja;
use App\Models\BarangStok;
use App\Models\LokasiStok;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
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
    public $unit_id, $isSeribu, $sudin; // Current user's unit ID
    // public $barangs = []; Filtered barangs
    public $stoks = [];
    public $jenisOptions = []; // List of jenis options
    public $lokasiOptions = []; // List of jenis options

    public function mount()
    {
        $this->unit_id = Auth::user()->unit_id;
        $unit = UnitKerja::find($this->unit_id);
        $this->sudin = Str::contains($unit->nama, 'Kepulauan')
            ? 'Kepulauan Seribu'
            : Str::of($unit->nama)->after('Administrasi ');

        $this->jenisOptions = JenisStok::pluck('nama')->toArray(); // Fetch all available jenis
        if (!Auth::user()->unitKerja->hak) {
            $this->jenis = "Material";
        }
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

    public function fetchBarangs($excel = false)
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
            });


        return $excel ? $barang->get() : $barang->paginate(10);
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

    public function applyFilters()
    {
        $this->fetchBarangs();
        $this->fetchStoks();
    }


    public function downloadExcel()
    {
        try {
            // Pastikan data stok ter-update
            $this->fetchStoks();
            $data = $this->fetchBarangs(true);

            $unit = UnitKerja::find($this->unit_id);
            $unitName = $unit ? $unit->nama : 'N/A';
            $spreadsheet = new Spreadsheet();
            $filterInfo = sprintf(
                "Filter - Jenis: %s, Lokasi: %s, Unit: %s",
                $this->jenis ?: 'Semua',
                $this->lokasi ?: 'Semua',
                $unitName ?: 'N/A'
            );

            // Properti dokumen
            $spreadsheet->getProperties()
                ->setCreator('www.inventa.id')
                ->setLastModifiedBy('www.inventa.id')
                ->setTitle('Stok')
                ->setSubject('Daftar Stok - Dinas Sumber Daya Air (DSDA)')
                ->setDescription('Laporan Stok')
                ->setKeywords('stok, laporan, excel')
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
            $sheet->setCellValue('A4', $filterInfo)
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
            $sheet->setCellValue('F7', 'STOK TERSEDIA');
            $sheet->setCellValue('G7', 'LOKASI PENYIMPANAN');

            // Sub-header spesifikasi
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
            $totalBarang = 0;
            $totalStok = 0;

            $stoks = $this->stoks;
            foreach ($data as $barang) {
                $totalBarang++;
                // Set data utama barang (kolom A dan B)
                $sheet->setCellValue('A' . $row, $barang->kode_barang)
                    ->setCellValue('B' . $row, $barang->nama);

                // Periksa apakah barang memiliki stok terkait
                if (isset($stoks[$barang->id]) && count($stoks[$barang->id]) > 0) {
                    foreach ($stoks[$barang->id] as $stok) {
                        $totalStok += $stok['jumlah'] ?? 0;
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
            }

            // Tambahkan ringkasan data
            $row += 2;
            $sheet->setCellValue('A' . $row, 'RINGKASAN:')
                ->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            $sheet->setCellValue('A' . $row, 'Total Jenis Barang: ' . $totalBarang);
            $row++;
            $sheet->setCellValue('A' . $row, 'Total Unit Stok: ' . $totalStok);
            $row++;
            $sheet->setCellValue('A' . $row, 'Tanggal Export: ' . now()->format('d F Y H:i:s'));

            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);

            // Generate filename berdasarkan filter yang aktif
            $timestamp = now()->format('Y-m-d_His');
            $filterName = '';
            if ($this->jenis)
                $filterName .= '_' . str_replace(' ', '', $this->jenis);
            if ($this->lokasi)
                $filterName .= '_' . str_replace(' ', '', $this->lokasi);

            $fileName = "Daftar_Stok_DSDA{$filterName}_{$timestamp}.xlsx";

            // Set header untuk file Excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header('Cache-Control: max-age=0');

            // Simpan file ke output
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            // Flash message sukses
            session()->flash('success', 'File Excel berhasil diunduh!');

            return Response::streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $fileName);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengunduh file Excel: ' . $e->getMessage());
            return;
        }
    }



    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'jenis', 'lokasi'])) {
            $this->applyFilters();
        }
    }

    public function render()
    {
        $barangs = $this->fetchBarangs();
        $stoks = $this->stoks;
        return view('livewire.data-stok', compact('barangs', 'stoks'));
    }
}
