<?php

namespace App\Livewire;

use App\Models\Aset;
use App\Models\Merk;
use App\Models\Toko;
use App\Models\Lokasi;
use App\Models\Person;
use Livewire\Component;
use App\Models\Kategori;
use App\Models\UnitKerja;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DataAsetAktif extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $nama, $kategori_id, $merk_id, $toko_id, $penanggung_jawab_id, $lokasi_id;
    public $orderBy = 'nama', $orderDirection = 'asc';
    public $perPage = 10;

    public $kategoris = [], $merks = [], $tokos = [], $penanggungJawabs = [], $lokasis = [];
    public $showFilters = false;

    public function mount()
    {
        $this->loadFilterOptions();
    }

    public function updated($property)
    {
        if (in_array($property, ['nama', 'kategori_id', 'merk_id', 'toko_id', 'penanggung_jawab_id', 'lokasi_id'])) {
            $this->resetPage();
        }
    }

    public function sortBy($field)
    {
        $this->orderDirection = ($this->orderBy === $field && $this->orderDirection === 'asc') ? 'desc' : 'asc';
        $this->orderBy = $field;
        $this->resetPage();
    }

    private function loadFilterOptions()
    {
        $unitId = Auth::user()->unit_id;
        $unit = UnitKerja::find($unitId);
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $unitId;

        $this->kategoris = Kategori::all();
        $this->merks = Merk::all();
        $this->tokos = Toko::all();

        $this->penanggungJawabs = Person::whereHas('user', function ($q) use ($parentUnitId) {
            $this->filterByParentUnit($q, $parentUnitId);
        })->get();

        $this->lokasis = Lokasi::whereHas('user', function ($q) use ($parentUnitId) {
            $this->filterByParentUnit($q, $parentUnitId);
        })->get();
    }


    public function fetchAsets()
    {
        $unitId = Auth::user()->unit_id;
        $unit = UnitKerja::find($unitId);
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $unitId;

        $query = Aset::where('status', true)
            ->when($this->nama, fn($q) => $q->where('nama', 'like', '%' . $this->nama . '%'))
            ->when($this->kategori_id, fn($q) => $q->where('kategori_id', $this->kategori_id))
            ->when($this->merk_id, fn($q) => $q->where('merk_id', $this->merk_id))
            ->when($this->toko_id, fn($q) => $q->where('toko_id', $this->toko_id))
            ->when($this->penanggung_jawab_id, fn($q) =>
            $q->whereHas('histories', fn($h) => $h->where('person_id', $this->penanggung_jawab_id)))
            ->when($this->lokasi_id, fn($q) =>
            $q->whereHas('histories', fn($h) => $h->where('lokasi_id', $this->lokasi_id)))
            ->whereHas('user', fn($q) => $this->filterByParentUnit($q, $parentUnitId));

        $paginated = $this->applySorting($query)->paginate($this->perPage);

        // Dapatkan data QR masing-masing aset
        $asetqr = $paginated->getCollection()->mapWithKeys(function ($aset) {
            return [$aset->id => getAssetWithSettings($aset->id)];
        });

        // Tambahkan properti tambahan ke tiap objek Aset
        $paginated->getCollection()->each(function ($aset) use ($asetqr) {
            $qr = $asetqr[$aset->id] ?? [];

            $aset->qr_code = [
                'qr_image' => isset($qr['qr_image']) ? asset($qr['qr_image']) : '',
                'judul' => $qr['judul'] ?? '',
                'baris1' => $qr['baris1'] ?? '',
                'baris2' => $qr['baris2'] ?? '',
            ];

            $aset->hargatotal_formatted = $this->rupiah($aset->hargatotal);
            $aset->nilaiSekarang = $this->rupiah($this->nilaiSekarang($aset->hargatotal, strtotime($aset->tanggalbeli), $aset->umur));
            $aset->totalpenyusutan = $this->rupiah($aset->hargatotal - $this->nilaiSekarang($aset->hargatotal, strtotime($aset->tanggalbeli), $aset->umur));
            // Mapping riwayat
            $aset->histories_mapped = $aset->histories->map(function ($history) {
                return (object)[
                    'tanggal' => $history->tanggal,
                    'person' => $history->person->nama ?? '-',
                    'lokasi' => $history->lokasi->nama ?? '-',
                ];
            });
        });

        return $paginated;
    }

    private function applySorting($query)
    {
        if ($this->orderBy === 'riwayat') {
            $query = Aset::query();
            $query->leftJoin('history', 'history.aset_id', '=', 'aset.id')
                ->selectRaw('aset.*, COUNT(history.id) as history_count')
                ->where('aset.status', true)
                ->groupBy('aset.id');

            return $this->orderDirection === 'asc'
                ? $query->orderByRaw('COUNT(history.id) ASC')
                : $query->orderByRaw('COUNT(history.id) DESC');
        }

        return $query->orderBy($this->orderBy, $this->orderDirection);
    }

    protected function getAssetWithSettings($asets)
    {
        return $asets->map(function ($aset) {
            return getAssetWithSettings($aset->id); // Fungsi global helper
        });
    }

    public function nilaiSekarang($harga, $tgl_beli, $umur, $tampil = true)
    {
        $sekarang = strtotime("now");

        // Hitung jumlah bulan dari tanggal beli dan sekarang
        $bulan_beli = date("n", $tgl_beli);
        $tahun_beli = date("Y", $tgl_beli);
        $jml_bulan_beli = ($tahun_beli * 12) + $bulan_beli;

        $bulan_sekarang = date("n", $sekarang);
        $tahun_sekarang = date("Y", $sekarang);
        $jml_bulan_sekarang = ($tahun_sekarang * 12) + $bulan_sekarang;

        $umur_bulan = $jml_bulan_sekarang - $jml_bulan_beli;
        $umurbulan_asli = $umur * 12;
        $invert_umurbulan = $umurbulan_asli - $umur_bulan;

        // Hitung nilai aset sekarang berdasarkan penyusutan
        $nilai_sekarang = ($umurbulan_asli > 0) ? ($invert_umurbulan / $umurbulan_asli) * $harga : $harga;
        $nilai_sekarang = max($nilai_sekarang, 0); // Pastikan tidak negatif

        return $tampil ? number_format((float)$nilai_sekarang, 2, '.', '') : $nilai_sekarang;
    }

    public function filterByParentUnit($query, $parentUnitId)
    {
        return $query->whereHas('unitKerja', function ($unitQuery) use ($parentUnitId) {
            $unitQuery->where('parent_id', $parentUnitId)
                ->orWhere('id', $parentUnitId);
        });
    }

    public function rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    public function exportExcel()
    {
        // Ambil unit kerja pengguna yang login
        $userUnitId = Auth::user()->unit_id;
        $unit = UnitKerja::find($userUnitId);
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        $query = Aset::where('status', true)
            ->when($this->nama, fn($q) => $q->where('nama', 'like', '%' . $this->nama . '%'))
            ->when($this->kategori_id, fn($q) => $q->where('kategori_id', $this->kategori_id))
            ->when($this->merk_id, fn($q) => $q->where('merk_id', $this->merk_id))
            ->when($this->toko_id, fn($q) => $q->where('toko_id', $this->toko_id))
            ->when($this->penanggung_jawab_id, fn($q) => $q->whereHas('histories', fn($q) => $q->where('person_id', $this->penanggung_jawab_id)))
            ->when($this->lokasi_id, fn($q) => $q->whereHas('histories', fn($q) => $q->where('lokasi_id', $this->lokasi_id)))
            ->whereHas('user', fn($q) => $this->filterByParentUnit($q, $parentUnitId)); //Tambahkan filter unit kerja

        // Terapkan sorting
        $query = $this->applySorting($query);

        $asets = $query->get();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //Set Properti Dokumen
        $spreadsheet->getProperties()
            ->setCreator('www.inventa.id')
            ->setLastModifiedBy('www.inventa.id')
            ->setTitle('Aset Aktif')
            ->setSubject('Daftar Aset Aktif - Dinas Sumber Daya Air (DSDA)')
            ->setDescription('Laporan Aset Aktif')
            ->setKeywords('aset, laporan, excel')
            ->setCategory('Laporan Aset');

        //Header Judul
        $sheet->setCellValue('A2', 'ASET AKTIF')
            ->mergeCells('A2:AC2')
            ->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->setCellValue('A3', strtoupper('Dinas Sumber Daya Air (DSDA)'))
            ->mergeCells('A3:AC3')
            ->getStyle('A3')->getFont()->setBold(true);
        $sheet->setCellValue('A4', 'Periode: ' . now()->format('d F Y'))
            ->mergeCells('A4:AC4')
            ->getStyle('A4')->getFont()->setItalic(true);

        //Atur rata tengah untuk header
        $sheet->getStyle('A2:A4')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        //Header Tabel
        $sheet->setCellValue('A7', 'KODE ASET');
        $sheet->setCellValue('B7', 'KODE SISTEM');
        $sheet->setCellValue('C7', 'NAMA ASET');
        $sheet->setCellValue('D7', 'KATEGORI');
        $sheet->setCellValue('E7', 'DETAIL ASET');
        $sheet->setCellValue('K7', 'PEMBELIAN');
        $sheet->setCellValue('R7', 'PENYUSUTAN');
        $sheet->setCellValue('W7', 'RIWAYAT TERAKHIR');
        $sheet->setCellValue('AD7', 'KETERANGAN');

        //Sub-header
        // Detail Aset
        $sheet->setCellValue('E8', 'MERK')
            ->setCellValue('F8', 'TIPE')
            ->setCellValue('G8', 'PRODUSEN')
            ->setCellValue('H8', 'KODE PRODUKSI')
            ->setCellValue('I8', 'TAHUN PRODUKSI')
            ->setCellValue('J8', 'DESKRIPSI');

        // Pembelian
        $sheet->setCellValue('K8', 'TANGGAL')
            ->setCellValue('L8', 'DISTRIBUTOR')
            ->setCellValue('M8', 'NO. INVOICE')
            ->setCellValue('N8', 'JUMLAH')
            ->setCellValue('O8', 'HARGA SATUAN (Rp)')
            ->setCellValue('P8', 'HARGA TOTAL (Rp)')
            ->setCellValue('Q8', 'LAMA GARANSI (TAHUN)');

        // Penyusutan
        $sheet->setCellValue('R8', 'UMUR EKONOMI (Tahun)')
            ->setCellValue('S8', 'USIA SEKARANG')
            ->setCellValue('T8', 'PENYUSUTAN PER BULAN (Rp)')
            ->setCellValue('U8', 'TOTAL PENYUSUTAN (Rp)')
            ->setCellValue('V8', 'NILAI SEKARANG (Rp)');

        // Riwayat Terakhir
        $sheet->setCellValue('W8', 'SEJAK TANGGAL')
            ->setCellValue('X8', 'PENANGGUNG JAWAB')
            ->setCellValue('Y8', 'LOKASI')
            ->setCellValue('Z8', 'JUMLAH')
            ->setCellValue('AA8', 'KONDISI (%)')
            ->setCellValue('AB8', 'KELENGKAPAN (%)')
            ->setCellValue('AC8', 'KETERANGAN');

        // Style Header
        $sheet->getStyle('A7:AD8')->getFont()->setBold(true);
        $sheet->getStyle('A7:AD8')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A7:AD8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Merge Header Cells
        $sheet->mergeCells('E7:J7');
        $sheet->mergeCells('K7:Q7');
        $sheet->mergeCells('R7:V7');
        $sheet->mergeCells('W7:AC7');
        $sheet->mergeCells('A7:A8');
        $sheet->mergeCells('B7:B8');
        $sheet->mergeCells('C7:C8');
        $sheet->mergeCells('D7:D8');
        $sheet->mergeCells('AD7:AD8');

        // Warna Header
        $sheet->getStyle('E8:AC8')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF000000');
        $sheet->getStyle('A7:D7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF806000');
        $sheet->getStyle('E7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE26B0A');
        $sheet->getStyle('K7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF375623');
        $sheet->getStyle('R7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF833C0C');
        $sheet->getStyle('W7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF1F4E78');
        $sheet->getStyle('AD7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF806000');

        // Isi Data
        $row = 9;
        foreach ($asets as $aset) {
            $hargaTotal = floatval($aset->hargatotal);
            $nilaiSekarang = $this->nilaiSekarang($hargaTotal, $aset->tanggalbeli, $aset->umur);
            $totalPenyusutan = $hargaTotal - $nilaiSekarang;

            $lastHistory = $aset->histories->last();
            $sheet->setCellValue('A' . $row, $aset->kode)
                ->setCellValue('B' . $row, $aset->systemcode)
                ->setCellValue('C' . $row, $aset->nama)
                ->setCellValue('D' . $row, $aset->kategori->nama ?? '-')
                ->setCellValue('E' . $row, $aset->merk->nama ?? '-')
                ->setCellValue('F' . $row, $aset->tipe)
                ->setCellValue('G' . $row, $aset->produsen ?? '-')
                ->setCellValue('H' . $row, $aset->noseri ?? '-')
                ->setCellValue('I' . $row, $aset->thproduksi ?? '-')
                ->setCellValue('J' . $row, $aset->deskripsi ?? '-')
                ->setCellValue('K' . $row, date("d M Y", strtotime($aset->tanggalbeli)))
                ->setCellValue('L' . $row, $aset->toko->nama ?? '-')
                ->setCellValue('M' . $row, $aset->invoice ?? '-')
                ->setCellValue('N' . $row, $aset->jumlah ?? '-')
                ->setCellValue('O' . $row, $this->rupiah($aset->hargasatuan))
                ->setCellValue('P' . $row, $this->rupiah($hargaTotal))
                ->setCellValue('Q' . $row, $aset->umur ?? '-')
                ->setCellValue('R' . $row, $aset->lama_garansi ?? '-')
                ->setCellValue('S' . $row, $this->usia_aset($aset->tanggalbeli))
                ->setCellValue('T' . $row, $this->rupiah($aset->penyusutan ?? 0))
                ->setCellValue('U' . $row, $this->rupiah($totalPenyusutan))
                ->setCellValue('V' . $row, $this->rupiah($nilaiSekarang))
                ->setCellValue('W' . $row, $lastHistory ? date('d M Y', strtotime($lastHistory->tanggal)) : '--')
                ->setCellValue('X' . $row, $lastHistory->person->nama ?? '--')
                ->setCellValue('Y' . $row, $lastHistory->lokasi->nama ?? '--')
                ->setCellValue('Z' . $row, $lastHistory->jumlah ?? '--')
                ->setCellValue('AA' . $row, $lastHistory->kondisi ?? '--')
                ->setCellValue('AB' . $row, $lastHistory->kelengkapan ?? '--')
                ->setCellValue('AC' . $row, $lastHistory->keterangan ?? '--')
                ->setCellValue('AD' . $row, $aset->keterangan ?? '-');

            $row++;
        }

        // Set Auto Width
        $columns = range('A', 'Z'); // Loop untuk A-Z
        array_push($columns, 'AA', 'AB', 'AC', 'AD'); // Tambahkan kolom lebih dari Z

        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Daftar Aset Aktif Dinas Sumber Daya Air (DSDA).xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName);
    }

    public function usia_aset($tgl_beli)
    {
        // Mendapatkan bulan dan tahun dari tanggal pembelian
        $bulan_beli = date("n", $tgl_beli);
        $tahun_beli = date("Y", $tgl_beli);

        // Menghitung jumlah bulan sejak tahun 0 untuk tanggal pembelian
        $jml_bulan_beli = ($tahun_beli * 12) + $bulan_beli;

        // Mendapatkan bulan dan tahun sekarang
        $sekarang = strtotime("now");
        $bulan_sekarang = date("n", $sekarang);
        $tahun_sekarang = date("Y", $sekarang);

        // Menghitung jumlah bulan sejak tahun 0 untuk tanggal sekarang
        $jml_bulan_sekarang = ($tahun_sekarang * 12) + $bulan_sekarang;

        // Menghitung umur aset dalam bulan
        $umur_bulan = $jml_bulan_sekarang - $jml_bulan_beli;

        // Konversi ke tahun dan bulan
        $tahun = floor($umur_bulan / 12);
        $bulan = $umur_bulan % 12;

        // Menentukan output
        if ($tahun == 0 && $bulan > 0) {
            return $bulan . " Bulan";
        } else if ($bulan == 0 && $tahun > 0) {
            return $tahun . " Tahun";
        } else if ($tahun == 0 && $bulan == 0) {
            return "Kurang dari 1 Bulan";
        } else {
            return $tahun . " Tahun " . $bulan . " Bulan";
        }
    }
    public function render()
    {
        $asets = $this->fetchAsets();
        // dd($asets);
        return view('livewire.data-aset-aktif', compact('asets')); // Kirim ke Blade
    }
}
