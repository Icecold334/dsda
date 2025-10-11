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
        $user = Auth::user();
        $this->sudins = UnitKerja::whereNull('parent_id')->where('hak', 0)->get();
        $this->jenisOptions = JenisStok::pluck('nama')->toArray(); // Fetch all available jenis

        // Cek apakah user memiliki unitKerja dan bukan superadmin
        if ($user && $user->unitKerja && !($user->unitKerja->hak ?? 0)) {
            $this->jenis = "Material";
        }

        // Set lokasiOptions berdasarkan apakah superadmin atau tidak
        if (!$user || !$user->unitKerja || ($user->unitKerja->hak ?? 0) == 1) {
            // Superadmin - tampilkan semua lokasi
            $this->lokasiOptions = LokasiStok::pluck('nama')->toArray();
        } else {
            // User biasa - hanya lokasi di unit mereka
            $this->lokasiOptions = LokasiStok::whereHas('unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })
                ->pluck('nama')
                ->toArray();
        }

        $this->applyFilters(); // Fetch initial data
    }
    public function updatedUnitId()
    {
        if ($this->unit_id) {
            $parent = UnitKerja::find($this->unit_id);
            $sudin = Str::contains($parent->nama, 'Kepulauan')
                ? 'Kepulauan Seribu'
                : Str::of($parent->nama)->after('Administrasi ');
            $this->sudin = $sudin;

            // Update lokasi options berdasarkan unit yang dipilih
            $this->lokasiOptions = LokasiStok::whereHas('unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            })
                ->pluck('nama')
                ->toArray();
        } else {
            $this->sudin = 'Semua Unit Kerja';
            // Jika tidak ada unit dipilih, tampilkan semua lokasi
            $this->lokasiOptions = LokasiStok::pluck('nama')->toArray();
        }

        $this->applyFilters();
    }

    public function fetchBarangs($excel = false)
    {
        $user = Auth::user();
        $isSuperadmin = !$user || !$user->unitKerja || ($user->unitKerja->hak ?? 0) == 1;

        $barang = BarangStok::whereHas('merkStok', function ($merkQuery) use ($isSuperadmin) {
            $merkQuery->whereHas('stok', function ($stokQuery) use ($isSuperadmin) {
                $stokQuery->where('jumlah', '>', 0);

                // Filter unit kerja hanya jika bukan superadmin
                if (!$isSuperadmin && $this->unit_id) {
                    $stokQuery->whereHas('lokasiStok.unitKerja', function ($unit) {
                        $unit->where('parent_id', $this->unit_id)
                            ->orWhere('id', $this->unit_id);
                    });
                }

                // Filter lokasi jika dipilih
                $stokQuery->when($this->lokasi, function ($query) {
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
        $user = Auth::user();
        $isSuperadmin = !$user || !$user->unitKerja || ($user->unitKerja->hak ?? 0) == 1;

        $gudangsQuery = LokasiStok::query();

        // Filter unit kerja hanya jika bukan superadmin
        if ($this->unit_id) {
            $gudangsQuery->whereHas('unitKerja', function ($unit) {
                $unit->where('parent_id', $this->unit_id)
                    ->orWhere('id', $this->unit_id);
            });
        }

        // $gudangs = $gudangsQuery ->whereHas('transaksiStok', function ($trxQuery) {
        //     $trxQuery->whereHas('merkStok.barangStok', function ($barangQuery) {
        //         $barangQuery->where('jenis_id', 1);
        //     });
        // })
        //     ->with([
        //         'transaksiStok.merkStok.barangStok' => function ($query) {
        //             $query->where('jenis_id', 1);
        //         }
        //     ])
        //     ->get();

        $gudangs = $gudangsQuery
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
            $unit = $this->unit_id ? UnitKerja::find($this->unit_id) : null;
            $unitName = $unit ? $unit->nama : 'Semua Unit Kerja';

            $spreadsheet = new Spreadsheet();
            $filterInfo = sprintf(
                "Filter - Unit: %s",
                $unitName
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
                ->mergeCells('A2:E2')
                ->getStyle('A2')->getFont()->setBold(true)->setSize(14);
            $sheet->setCellValue('A3', strtoupper('Dinas Sumber Daya Air (DSDA)'))
                ->mergeCells('A3:E3')
                ->getStyle('A3')->getFont()->setBold(true);
            $sheet->setCellValue('A4', $filterInfo)
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
            $sheet->setCellValue('A7', 'UNIT KERJA');
            $sheet->setCellValue('B7', 'LOKASI GUDANG');
            $sheet->setCellValue('C7', 'KODE BARANG');
            $sheet->setCellValue('D7', 'NAMA BARANG');
            $sheet->setCellValue('E7', 'JUMLAH STOK');

            // Style header tabel
            $sheet->getStyle('A7:E7')->getFont()->setBold(true);
            $sheet->getStyle('A7:E7')->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle('A7:E7')
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A7:E7')
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A7:E7')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF806000');

            $row = 8; // Mulai dari baris ke-8
            $totalLokasi = 0;
            $totalBarang = 0;
            $totalStok = 0;

            foreach ($gudangs as $gudang) {
                $totalLokasi++;
                $isFirstRow = true;
                $unitKerjaName = $gudang->unitKerja->nama ?? 'Unit Tidak Diketahui';

                foreach ($gudang->barangStokSisa as $barangId => $jumlah) {
                    $barang = \App\Models\BarangStok::find($barangId);
                    $totalBarang++;
                    $totalStok += $jumlah;

                    if ($isFirstRow) {
                        $sheet->setCellValue('A' . $row, $unitKerjaName);
                        $sheet->setCellValue('B' . $row, $gudang->nama);
                        $isFirstRow = false;
                    } else {
                        $sheet->setCellValue('A' . $row, '');
                        $sheet->setCellValue('B' . $row, '');
                    }

                    $sheet->setCellValue('C' . $row, $barang->kode_barang ?? '-')
                        ->setCellValue('D' . $row, $barang->nama ?? '-')
                        ->setCellValue('E' . $row, $jumlah . ' ' . ($barang->satuanBesar->nama ?? 'Unit'));

                    $row++;
                }

                if ($gudang->barangStokSisa->isEmpty()) {
                    $sheet->setCellValue('A' . $row, $unitKerjaName)
                        ->setCellValue('B' . $row, $gudang->nama)
                        ->setCellValue('C' . $row, '-')
                        ->setCellValue('D' . $row, 'Tidak ada stok')
                        ->setCellValue('E' . $row, '0');
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
            $sheet->getColumnDimension('E')->setAutoSize(true);

            // Generate filename berdasarkan filter yang aktif
            $timestamp = now()->format('Y-m-d_His');
            $filterName = '';
            if ($this->unit_id && $unit) {
                $unitFilter = str_replace(' ', '', $unit->nama);
                $filterName .= '_' . $unitFilter;
            } else {
                $filterName .= '_SemuaUnit';
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
        // dd('test');
        $gudangs = $this->fetchStoks();
        return view('livewire.data-stok-material', compact('gudangs'));
    }
}
