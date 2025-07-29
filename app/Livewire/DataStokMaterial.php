<?php

namespace App\Livewire;

use Carbon\Unit;
use App\Models\Stok;
use Livewire\Component;
use App\Models\JenisStok;
use App\Models\UnitKerja;
use App\Models\BarangStok;
use App\Models\LokasiStok;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\TransaksiStok;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class DataStokMaterial extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = '', $all, $sudins; // Search term
    public $jenis = ''; // Selected jenis
    public $lokasi = ''; // Selected jenis
    public $unit_id, $isSeribu, $sudin; // Current user's unit ID
    // public $barangs = []; Filtered barangs
    public $stoks = [];
    public $jenisOptions = []; // List of jenis options
    public $lokasiOptions = []; // List of jenis options

    public function mount()
    {
        $this->sudins = UnitKerja::whereNull('parent_id')->where('hak', 0)->get();
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
    public function updatedUnitId()
    {
        $parent = UnitKerja::find($this->unit_id);
        $sudin = Str::contains($parent->nama, 'Kepulauan')
            ? 'Kepulauan Seribu'
            : Str::of($parent->nama)->after('Administrasi ');
        $this->sudin = $sudin;
        $this->applyFilters();
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


    public function fetchStoks()
    {
        $gudangs = LokasiStok::whereHas('unitKerja', function ($unit) {
            $unit->where('parent_id', $this->unit_id)
                ->orWhere('id', $this->unit_id);
        })->whereHas('transaksiStok', function ($trxQuery) {
            $trxQuery->whereHas('merkStok.barangStok', function ($barangQuery) {
                $barangQuery->where('jenis_id', 1);
            });
        })
            ->with([
                'transaksiStok.merkStok.barangStok' => function ($query) {
                    $query->where('jenis_id', 1);
                }
            ])
            ->get();

        $gudangs->filter(function ($lokasi) {
            $barangTotals = [];

            foreach ($lokasi->transaksiStok as $trx) {
                $barang = $trx->merkStok->barangStok ?? null;
                $merkId = $trx->merkStok->id ?? null;
                if (!$barang || $barang->jenis_id !== 1 || !$merkId)
                    continue;

                $barangId = $barang->id;

                // Hitung jumlah
                $jumlah = match ($trx->tipe) {
                    'Pemasukan' => (int) $trx->jumlah,
                    'Pengeluaran', 'Pengajuan' => -((int) $trx->jumlah),
                    'Penyesuaian' => (int) $trx->jumlah, // karena sudah string seperti '+50' atau '-30'
                    default => 0,
                };

                // Simpan berdasarkan barang + merk
                $barangTotals[$barangId][$merkId] = ($barangTotals[$barangId][$merkId] ?? 0) + $jumlah;
            }

            // Hitung total per barang ID hanya dari merk yang positif saja
            $stokPerBarang = [];
            foreach ($barangTotals as $barangId => $perMerk) {
                foreach ($perMerk as $jumlah) {
                    if ($jumlah > 0) {
                        $stokPerBarang[$barangId] = ($stokPerBarang[$barangId] ?? 0) + $jumlah;
                    }
                }
            }

            $lokasi->barangStokSisa = collect($stokPerBarang)->filter(fn($val) => $val > 0);
            return $lokasi->barangStokSisa->isNotEmpty();
        });

        return $gudangs;
    }


    public function applyFilters()
    {
        $this->fetchBarangs();
        $this->fetchStoks();
    }


    public function downloadExcel()
    {
        try {
            $gudangs = $this->fetchStoks();
            $unit = UnitKerja::find($this->unit_id);
            $unitName = $unit ? $unit->nama : 'N/A';

            $spreadsheet = new Spreadsheet();
            $filterInfo = sprintf(
                "Filter - Unit: %s",
                $unitName ?: 'Semua Unit'
            );

            // Properti dokumen
            $spreadsheet->getProperties()
                ->setCreator('www.inventa.id')
                ->setLastModifiedBy('www.inventa.id')
                ->setTitle('Stok Material')
                ->setSubject('Daftar Stok Material - Dinas Sumber Daya Air (DSDA)')
                ->setDescription('Laporan Stok Material')
                ->setKeywords('stok, material, laporan, excel')
                ->setCategory('Laporan Stok Material');

            $sheet = $spreadsheet->getActiveSheet();
            // Header judul
            $sheet->setCellValue('A2', 'DAFTAR STOK MATERIAL')
                ->mergeCells('A2:D2')
                ->getStyle('A2')->getFont()->setBold(true)->setSize(14);
            $sheet->setCellValue('A3', strtoupper('Dinas Sumber Daya Air (DSDA)'))
                ->mergeCells('A3:D3')
                ->getStyle('A3')->getFont()->setBold(true);
            $sheet->setCellValue('A4', $filterInfo)
                ->mergeCells('A4:D4')
                ->getStyle('A4')->getFont()->setItalic(true);
            $sheet->setCellValue('A5', 'Periode: ' . now()->format('d F Y'))
                ->mergeCells('A5:D5')
                ->getStyle('A5')->getFont()->setBold(true);

            // Atur rata tengah untuk header
            $sheet->getStyle('A2:A5')
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Header tabel
            $sheet->setCellValue('A7', 'LOKASI GUDANG');
            $sheet->setCellValue('B7', 'KODE BARANG');
            $sheet->setCellValue('C7', 'NAMA BARANG');
            $sheet->setCellValue('D7', 'JUMLAH STOK');

            // Style header tabel
            $sheet->getStyle('A7:D7')->getFont()->setBold(true);
            $sheet->getStyle('A7:D7')->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle('A7:D7')
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A7:D7')
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A7:D7')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF806000');

            $row = 8; // Mulai dari baris ke-8
            $totalLokasi = 0;
            $totalBarang = 0;
            $totalStok = 0;

            foreach ($gudangs as $gudang) {
                $totalLokasi++;
                $isFirstRow = true;

                foreach ($gudang->barangStokSisa as $barangId => $jumlah) {
                    $barang = \App\Models\BarangStok::find($barangId);
                    $totalBarang++;
                    $totalStok += $jumlah;

                    if ($isFirstRow) {
                        $sheet->setCellValue('A' . $row, $gudang->nama);
                        $isFirstRow = false;
                    } else {
                        $sheet->setCellValue('A' . $row, '');
                    }

                    $sheet->setCellValue('B' . $row, $barang->kode_barang ?? '-')
                        ->setCellValue('C' . $row, $barang->nama ?? '-')
                        ->setCellValue('D' . $row, $jumlah . ' ' . ($barang->satuanBesar->nama ?? 'Unit'));

                    $row++;
                }

                if ($gudang->barangStokSisa->isEmpty()) {
                    $sheet->setCellValue('A' . $row, $gudang->nama)
                        ->setCellValue('B' . $row, '-')
                        ->setCellValue('C' . $row, 'Tidak ada stok')
                        ->setCellValue('D' . $row, '0');
                    $row++;
                }
            }

            // Tambahkan ringkasan data
            $row += 2;
            $sheet->setCellValue('A' . $row, 'RINGKASAN:')
                ->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            $sheet->setCellValue('A' . $row, 'Total Lokasi Gudang: ' . $totalLokasi);
            $row++;
            $sheet->setCellValue('A' . $row, 'Total Jenis Barang: ' . $totalBarang);
            $row++;
            $sheet->setCellValue('A' . $row, 'Total Unit Stok: ' . $totalStok);
            $row++;
            $sheet->setCellValue('A' . $row, 'Tanggal Export: ' . now()->format('d F Y H:i:s'));

            // Auto-size kolom
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);

            // Generate filename berdasarkan filter yang aktif
            $timestamp = now()->format('Y-m-d_His');
            $filterName = '';
            if ($this->unit_id) {
                $unitFilter = str_replace(' ', '', $unitName);
                $filterName .= '_' . $unitFilter;
            }

            $fileName = "Daftar_Stok_Material_DSDA{$filterName}_{$timestamp}.xlsx";

            // Set header untuk file Excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header('Cache-Control: max-age=0');

            // Flash message sukses
            session()->flash('success', 'File Excel berhasil diunduh!');

            // Simpan file ke output
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
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
        if (in_array($propertyName, ['search', 'jenis'])) {
            $this->applyFilters();
        }
    }

    public function render()
    {
        $gudangs = $this->fetchStoks();
        return view('livewire.data-stok-material', compact('gudangs'));
    }
}
