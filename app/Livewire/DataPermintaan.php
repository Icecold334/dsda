<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Ruang;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use App\Models\PeminjamanAset;
use App\Models\PermintaanStok;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPeminjamanAset;
use App\Models\DetailPermintaanStok;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\DetailPermintaanMaterial;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DataPermintaan extends Component
{
    public $nonUmum;
    public $search; // Search term
    public $jenis; // Selected jenis
    public $lokasi; // Selected jenis
    public $tanggal; // Selected jenis
    public $selected_unit_id; // Selected jenis
    public $status; // Selected jenis
    public $unitOptions = [];
    public $jenisOptions = []; // List of jenis options
    public $lokasiOptions = []; // List of jenis options

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
                return $item['tanggal'] === $this->tanggal;
            });
        }


        if (!empty($this->status)) {
            $s = $this->status;
            $query = $query->filter(function ($item) use ($s) {
                if ($s === 'diproses') {
                    return is_null($item['cancel']) && is_null($item['proses']) && is_null($item['status']);
                } elseif ($s === 'disetujui') {
                    return is_null($item['cancel']) && is_null($item['proses']) && $item['status'] == 1;
                } elseif ($s === 'ditolak') {
                    return ($item['cancel'] == 1 && is_null($item['proses']))
                        || (is_null($item['cancel']) && is_null($item['proses']) && $item['status'] == 0);
                } elseif ($s === 'selesai') {
                    return $item['cancel'] == 0 && $item['proses'] == 1;
                } elseif ($s === 'siap diambil') {
                    return $item['cancel'] == 0 && is_null($item['proses']);
                } elseif ($s === 'dibatalkan') {
                    return $item['cancel'] == 1;
                }
                return false;
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

        if ($this->getJenisId() == 1) {
            $permintaan = DetailPermintaanMaterial::all()->map(function ($perm) {
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
        return [
            'id' => $item->id,
            'kode' => $tipe === 'permintaan' ? $item->kode_permintaan : $item->kode_peminjaman,
            'tanggal' => $tipe === 'permintaan' ? $item->tanggal_permintaan : $item->tanggal_peminjaman,
            'kategori_id' => $item->kategori_id,
            'kategori' => $tipe === 'permintaan' ? $item->kategoriStok : $item->kategori,
            'unit_id' => $item->unit_id,
            'unit' => $item->unit,
            'sub_unit_id' => $item->sub_unit_id,
            'sub_unit' => $item->subUnit,
            'status_warna' => $item->status_warna ?? null,
            'status_teks' => $item->status_teks ?? null,
            'status' => $item->status,
            'cancel' => $item->cancel,
            'proses' => $item->proses,
            'jenis_id' => $tipe === 'permintaan' ? $item->jenis_id : null,
            'tipe' => $tipe,
            'created_at' => $item->created_at->format('Y-m-d H:i:s')
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
        // $sheet->setCellValue('A7', 'KODE');
        // $sheet->setCellValue('B7', 'JENIS LAYANAN');
        // $sheet->setCellValue('C7', 'TANGGAL PENGGUNAAN');
        // $sheet->setCellValue('D7', 'UNIT KERJA');
        // $sheet->setCellValue('E7', 'STATUS');
        // $sheet->setCellValue('F7', 'BARANG / ASET');
        // $sheet->setCellValue('G7', 'JUMLAH');

        // Sub-header 
        // Detail Aset
        // $sheet->setCellValue('C8', 'MERK')
        //     ->setCellValue('D8', 'TIPE')
        //     ->setCellValue('E8', 'UKURAN');

        // Style header tabel

        // $sheet->getStyle('A7:G7')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        // $sheet->getStyle('A7:G7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF806000');

        //     ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->mergeCells('C7:E7');

        // $sheet->getStyle('C8:E8')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setARGB('FF000000');
        // foreach (range('A', 'G') as $col) {
        //     $sheet->getColumnDimension($col)->setAutoSize(true);
        // }

        // Header kolom
        $sheet->fromArray(['KODE', 'JENIS LAYANAN', 'TANGGAL PENGGUNAAN', 'UNIT KERJA', 'STATUS', 'BARANG / ASET', 'JUMLAH'], null, 'A7');
        $sheet->getStyle('A7:G7')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A7:G7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF806000');

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $row = 8;

        foreach ($data as $barang) {
            $details = collect();

            if ($barang['tipe'] === 'permintaan') {
                $details = PermintaanStok::where('detail_permintaan_id', $barang['id'])->get();
            } else {
                $details = PeminjamanAset::where('detail_peminjaman_id', $barang['id'])->get();
            }

            $detailCount = $details->count();
            $startRow = $row;
            if ($detailCount > 0) {
                foreach ($details as $detail) {
                    // Nama item
                    $namaItem = '-';

                    if ($barang['tipe'] === 'permintaan') {
                        $namaItem = $detail->barangStok?->nama ?? '-';
                    } elseif (strtolower($barang['kategori']?->nama) === 'ruangan') {
                        $namaItem = Ruang::find($detail->aset_id)?->nama ?? '-';
                    } elseif (strtolower($barang['kategori']?->nama) === 'kdo') {
                        $aset = Aset::find($detail->aset_id);
                        $namaItem = $aset ? "{$aset->merk->nama} {$aset->nama} - {$aset->noseri}" : '-';
                    } else {
                        $namaItem = $detail->aset?->nama ?? '-';
                    }

                    $sheet->setCellValue("F{$row}", $namaItem);
                    $sheet->setCellValue("G{$row}", $detail->jumlah ?? '-');
                    $row++;
                }

                // Merge kolom utama (A–E)
                foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
                    $sheet->mergeCells("{$col}{$startRow}:{$col}" . ($row - 1));
                    $sheet->setCellValue("{$col}{$startRow}", match ($col) {
                        'A' => $barang['kode'],
                        'B' => ucfirst($barang['tipe']) . ' ' . ($barang['kategori']?->nama ?? '-'),
                        'C' => date('j F Y', $barang['tanggal']),
                        'D' => $barang['sub_unit']?->nama ?? $barang['unit']?->nama,
                        'E' => $this->getStatusText($barang),
                    });
                }
            } else {
                $sheet->fromArray([
                    $barang['kode'],
                    ucfirst($barang['tipe']),
                    date('j F Y', $barang['tanggal']),
                    $barang['sub_unit']?->nama ?? $barang['unit']?->nama,
                    $this->getStatusText($barang),
                    '-',
                    '-',
                ], null, "A{$row}");
                $row++;
            }
        }

        // Set data utama barang (kolom A dan B)
        // $sheet->setCellValue('A' . $row, $barang['kode'])
        //     ->setCellValue('B' . $row, $barang['tipe']);

        // // Set data terkait barang (kolom C sampai E)
        // $sheet->setCellValue('C' . $row, date('j F Y', $barang['tanggal']))
        //     ->setCellValue('D' . $row, $barang['sub_unit']?->nama ?? $barang['unit']?->nama)
        //     ->setCellValue(
        //         'E' . $row,
        //         $barang['cancel'] === 1 ? 'dibatalkan' : ($barang['cancel'] === 0 && $barang['proses'] === 1 ? 'selesai' : ($barang['cancel'] === 0 && $barang['proses'] === null ? 'siap diambil' : ($barang['cancel'] === null && $barang['proses'] === null && $barang['status'] === null ? 'diproses' : ($barang['cancel'] === null && $barang['proses'] === null && $barang['status'] === 1 ? 'disetujui' : 'ditolak'))))
        //     );

        // Pindah ke baris berikutnya
        // $row++;


        // Terapkan alignment ke kolom tertentu (contoh kolom F)
        // $sheet->getStyle('A1:E' . ($row - 1))
        //     ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // $sheet->getColumnDimension('A')->setAutoSize(true);
        // $sheet->getColumnDimension('B')->setAutoSize(true);
        // $sheet->getColumnDimension('C')->setAutoSize(true);
        // $sheet->getColumnDimension('D')->setAutoSize(true);
        // $sheet->getColumnDimension('E')->setAutoSize(true);

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

    private function getStatusText($item)
    {
        return $item['cancel'] === 1
            ? 'dibatalkan'
            : ($item['cancel'] === 0 && $item['proses'] === 1
                ? 'selesai'
                : ($item['cancel'] === 0 && $item['proses'] === null
                    ? 'siap diambil'
                    : ($item['cancel'] === null && $item['proses'] === null && $item['status'] === null
                        ? 'diproses'
                        : ($item['cancel'] === null && $item['proses'] === null && $item['status'] === 1
                            ? 'disetujui'
                            : 'ditolak'))));
    }
    public function render()
    {
        $permintaans = $this->applyFilters();
        return view('livewire.data-permintaan', compact('permintaans'));
    }
}
