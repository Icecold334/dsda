<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Merk;
use App\Models\Toko;
use App\Models\Lokasi;
use App\Models\Person;
use App\Models\Kategori;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AsetNonAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan query untuk aset aktif
        $query = $this->getAsetQuery();

        // Apply filters
        $this->applyFilters($query, $request);

        // Apply sorting
        $this->applySorting($query, $request);

        $asets = $query->paginate(5); // 20 item per halaman

        // Execute the query and format the results
        $asets->getCollection()->transform(function ($aset) {
            return $this->formatAset($aset);
        });

        // Proses setiap aset untuk mendapatkan data QR
        $asetqr = $asets->getCollection()->map(function ($aset) {
            return getAssetWithSettings($aset->id); // Menggunakan helper
        })->keyBy('id')->toArray(); // Gunakan keyBy untuk membuat key array berdasarkan ID aset
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Data tambahan untuk dropdown filter
        $kategoris = Kategori::all();
        // when($this->unit_id, function ($query) use ($parentUnitId) {
        //     $query->whereHas('user', function ($query) use ($parentUnitId) {
        //         filterByParentUnit($query, $parentUnitId);
        //     });
        // })->get();

        // Return to view with the necessary data
        return view('nonaktifaset.index', compact('asets', 'kategoris', 'asetqr'));
    }


    private function getAsetQuery()
    {
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Debugging: Tampilkan parentUnitId untuk verifikasi
        // Query untuk mendapatkan aset berdasarkan unit parent yang dimiliki oleh user
        return Aset::where('status', false)
            ->when($this->unit_id, function ($query) use ($parentUnitId) {
                $query->whereHas('user', function ($query) use ($parentUnitId) {
                    filterByParentUnit($query, $parentUnitId);
                });
            });
    }

    /**
     * Apply filters to the query based on the request parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     */
    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('sebab')) {
            $query->where('alasannonaktif', $request->sebab);
        }
    }

    /**
     * Apply sorting to the query based on the request parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     */
    protected function applySorting($query, Request $request)
    {
        $orderBy = $request->get('order_by', 'nama'); // Default to 'nama'
        $orderDirection = $request->get('order_direction', 'asc'); // Default to ascending

        $query->orderBy($orderBy, $orderDirection);
    }

    /**
     * Format the aset data to include calculated fields such as nilaiSekarang and totalPenyusutan.
     *
     * @param \App\Models\Aset $aset
     * @return \App\Models\Aset
     */
    protected function formatAset($aset)
    {
        $hargaTotal = floatval($aset->hargatotal);
        $nilaiSekarang = $this->nilaiSekarang($hargaTotal, $aset->tanggalbeli, $aset->umur);
        $totalPenyusutan = $hargaTotal - $nilaiSekarang;

        $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
        $aset->hargatotal = $this->rupiah($hargaTotal);
        $aset->totalpenyusutan = $this->rupiah(abs($totalPenyusutan));

        return $aset;
    }

    // Fungsi untuk mengambil aset dengan setting
    protected function getAssetWithSettings($asets)
    {
        // Proses setiap aset untuk mendapatkan setting yang diperlukan
        return $asets->map(function ($aset) {
            return getAssetWithSettings($aset->id); // Misal fungsi global helper yang sudah dibuat
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Aset $nonaktifaset)
    {
        $nonaktifaset->hargasatuan = $this->rupiah($nonaktifaset->hargasatuan);
        $nonaktifaset->hargatotal = $this->rupiah($nonaktifaset->hargatotal);
        // dd($nonaktifaset);
        return view('nonaktifaset.show', compact('nonaktifaset'));
    }

    public function downloadQrImage($assetId)
    {
        // Generate QR Image
        $outputPath = $this->generateQrImage($assetId);

        if (!$outputPath) {
            // Jika gambar tidak ditemukan atau gagal generate, kembalikan error
            return redirect()->back()->with('error', 'QR Code tidak ditemukan atau gagal dibuat.');
        }

        return $outputPath; // Return generated image directly to the browser
    }

    public function generateQrImage($assetId)
    {
        // Ambil aset dan data terkait dari helper
        $asset = getAssetWithSettings($assetId);

        if (!$asset) {
            return null; // Jika aset tidak ditemukan, return null
        }

        // Path ke template qrbase dan QR Code
        $qrBasePath = public_path('img/qrbase.png');
        $qrImagePath = $asset['qr_image'];

        // Periksa apakah file template dan QR Code ada
        if (!file_exists($qrBasePath) || !file_exists($qrImagePath)) {
            return null; // Jika salah satu file tidak ada, return null
        }

        // Load gambar qrbase dan QR Code
        $qrBase = imagecreatefrompng($qrBasePath);
        $qrImage = imagecreatefrompng($qrImagePath);

        // Set DPI untuk gambar (gunakan nilai DPI untuk kualitas tinggi)
        $dpi = 300; // Resolusi tinggi untuk kualitas yang lebih baik
        $scalingFactor = $dpi / 96; // Faktor skala untuk mengubah ukuran DPI default

        // Tentukan ukuran tetap untuk QR Code (menggunakan ukuran "medium")
        $qrWidth = 70 * $scalingFactor;  // Lebar QR Code dalam piksel
        $qrHeight = 75 * $scalingFactor; // Tinggi QR Code dalam piksel
        $padding = 10 * $scalingFactor;   // Padding di sekitar QR Code
        $fontSize = 4 * $scalingFactor;   // Ukuran font untuk teks

        // Buat kanvas baru untuk gambar yang dimodifikasi
        $baseWidth = $qrWidth + $padding * 2;
        $baseHeight = $qrHeight + $padding * 4; // Tambahan ruang untuk teks
        $newQrBase = imagecreatetruecolor($baseWidth, $baseHeight);

        // Isi latar belakang dengan warna putih
        $white = imagecolorallocate($newQrBase, 255, 255, 255);
        imagefill($newQrBase, 0, 0, $white);

        // Salin gambar qrbase ke dalam template baru
        imagecopyresampled(
            $newQrBase,
            $qrBase,
            0,
            0,
            0,
            0,
            $baseWidth,
            $baseHeight,
            imagesx($qrBase),
            imagesy($qrBase)
        );

        // Posisi QR Code dalam template
        $qrX = ($baseWidth - $qrWidth) / 2;
        $qrY = $padding + 6;

        // Gabungkan QR Code ke dalam template
        imagecopyresampled(
            $newQrBase,
            $qrImage,
            $qrX,
            $qrY,
            0,
            0,
            $qrWidth,
            $qrHeight,
            imagesx($qrImage),
            imagesy($qrImage)
        );

        // Warna teks
        $black = imagecolorallocate($newQrBase, 0, 0, 0);

        // Path font
        $fontPath = public_path('fonts/courbd.ttf');
        $fontPath_line2 = public_path('fonts/cour.ttf');

        // Tambahkan teks: judul dan baris 1, 2
        $judul = $asset['judul'] ?? 'QR Code Title';
        $judulX = ($baseWidth - imagettfbbox($fontSize, 0, $fontPath, $judul)[2]) / 2;
        imagettftext($newQrBase, $fontSize, 0, $judulX, $qrY - 15, $white, $fontPath, $judul);

        // Baris 1
        $baris1 = $asset['baris1'] ?? 'Line 1';
        $baris1X = ($baseWidth - imagettfbbox($fontSize, 0, $fontPath, $baris1)[2]) / 2;
        imagettftext($newQrBase, $fontSize, 0, $baris1X, $qrY + $qrHeight + $padding, $black, $fontPath_line2, $baris1);

        // Baris 2
        $line2Padding = 55;  // Padding untuk baris 2
        $baris2 = $asset['baris2'] ?? 'Line 2';
        $baris2X = ($baseWidth - imagettfbbox($fontSize, 0, $fontPath, $baris2)[2]) / 2;
        imagettftext($newQrBase, $fontSize, 0, $baris2X, $qrY + $qrHeight + $line2Padding, $black, $fontPath, $baris2);

        // Output gambar ke browser langsung
        ob_start(); // Start output buffering
        imagepng($newQrBase); // Output gambar PNG ke buffer
        $imageData = ob_get_contents(); // Ambil data gambar dari buffer
        ob_end_clean(); // Bersihkan buffer

        // Hapus gambar dari memori
        imagedestroy($newQrBase);
        imagedestroy($qrBase);
        imagedestroy($qrImage);

        // Kembalikan gambar sebagai stream untuk diunduh
        return response($imageData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $asset['nama'] . '.png"');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aset $aset)
    {
        //    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aset $aset)
    {
        //
    }

    // public function activate($id)
    // {
    //     // Cari aset berdasarkan ID
    //     $nonaktifAset = Aset::findOrFail($id);

    //     // Perbarui status aset menjadi aktif (status = 1)
    //     $nonaktifAset->update(['status' => 1]);

    //     // Redirect atau respon JSON jika menggunakan fetch
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Aset berhasil diaktifkan.',
    //     ]);
    // }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $id)
    {
        // Pastikan data ditemukan
        $nonaktif_aset = Aset::findOrFail($id->id);

        // Hapus data
        $nonaktif_aset->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('nonaktifaset.index')->with('success', 'Aset berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        // Ambil data aset
        // $asets = Aset::where('status', false)->get();

        // Ambil query dasar untuk aset
        $query = $this->getAsetQuery();

        // Apply filters
        $this->applyFilters($query, $request);

        // Apply sorting
        $this->applySorting($query, $request);

        // Ambil data hasil query
        $asets = $query->get();

        // Ambil nilai filter dari request
        $kategori = $request->filled('kategori_id') ? Kategori::find($request->kategori_id)->nama : 'Semua Kategori';
        $sebab = $request->filled('sebab') ? $request->sebab : 'Semua Sebab';

        // Format deskripsi filter
        $filterInfo = "Kategori: $kategori, Sebab : $sebab";

        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Properti dokumen
        $spreadsheet->getProperties()
            ->setCreator('www.inventa.id')
            ->setLastModifiedBy('www.inventa.id')
            ->setTitle('Aset Non Aktif')
            ->setSubject('Daftar Non Aset Aktif - Dinas Sumber Daya Air (DSDA)')
            ->setDescription('Laporan Aset Aktif')
            ->setKeywords('aset, laporan, excel')
            ->setCategory('Laporan Aset');

        // Header judul
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', 'ASET NON AKTIF')
            ->mergeCells('A2:AC2')
            ->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->setCellValue('A3', strtoupper('Dinas Sumber Daya Air (DSDA)'))
            ->mergeCells('A3:AC3')
            ->getStyle('A3')->getFont()->setBold(true);
        $sheet->setCellValue('A4',  $filterInfo)
            ->mergeCells('A4:AC4')
            ->getStyle('A4')->getFont()->setItalic(true);
        $sheet->setCellValue('A5', 'Periode: ' . now()->format('d F Y'))
            ->mergeCells('A5:AC5')
            ->getStyle('A5')->getFont()->setBold(true);

        // Atur rata tengah untuk header
        $sheet->getStyle('A2:A5')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $sheet->setCellValue('A7', 'KODE ASET');
        $sheet->setCellValue('B7', 'KODE SISTEM');
        $sheet->setCellValue('C7', 'NAMA ASET');
        $sheet->setCellValue('D7', 'KATEGORI');
        $sheet->setCellValue('E7', 'DETAIL ASET');
        $sheet->setCellValue('K7', 'PEMBELIAN');
        $sheet->setCellValue('R7', 'NON-AKTIF');
        $sheet->setCellValue('U7', 'RIWAYAT TERAKHIR');
        $sheet->setCellValue('AB7', 'KETERANGAN');

        // Sub-header 
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
        // NON-AKTIF
        $sheet->setCellValue('R8', 'TANGGAL')
            ->setCellValue('S8', 'SEBAB')
            ->setCellValue('T8', 'KETERANGAN');
        // Riwayat Terakhir
        $sheet->setCellValue('U8', 'SEJAK TANGGAL')
            ->setCellValue('V8', 'PENANGGUNG JAWAB')
            ->setCellValue('W8', 'LOKASI')
            ->setCellValue('X8', 'JUMLAH')
            ->setCellValue('Y8', 'KONDISI (%)')
            ->setCellValue('Z8', 'KELENGKAPAN (%)')
            ->setCellValue('AA8', 'KETERANGAN');

        // Style header tabel
        // $sheet->getStyle('A7:AC8')->getFont()->setBold(true);
        // $sheet->getStyle('A7:AC8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A7:AC8')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setARGB('FF806000');
        $sheet->getStyle('A7:AB8')->getFont()->setBold(true);
        $sheet->getStyle('A7:AB8')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A7:AB8')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A7:AB8')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('E7:J7');
        $sheet->mergeCells('K7:Q7');
        $sheet->mergeCells('R7:T7');
        $sheet->mergeCells('U7:AA7');
        $sheet->mergeCells('A7:A8');
        $sheet->mergeCells('B7:B8');
        $sheet->mergeCells('C7:C8');
        $sheet->mergeCells('D7:D8');
        $sheet->mergeCells('AB7:AB8');

        $sheet->getStyle('E8:AB8')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF000000');
        $sheet->getStyle('A7:D7')->getFill() // E26B0A
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
        $sheet->getStyle('U7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF1F4E78');
        $sheet->getStyle('AB7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF806000');



        // Data
        $row = 9; // Mulai baris data
        foreach ($asets as $aset) {
            $lastHistory = $aset->histories->last();
            $sheet->setCellValue('A' . $row, $aset->kode)
                ->setCellValue('B' . $row, $aset->nama)
                ->setCellValue('C' . $row, $aset->kategori->nama)
                ->setCellValue('D' . $row, $aset->merk->nama)
                ->setCellValue('E' . $row, $aset->tipe)
                ->setCellValue('F' . $row, $aset->produsen)
                ->setCellValue('G' . $row, $aset->noseri)
                ->setCellValue('I' . $row, $aset->thproduksi)
                ->setCellValue('J' . $row, $aset->deskripsi)
                ->setCellValue('K' . $row, date("d M Y", $aset->tanggalbeli))
                ->setCellValue('L' . $row, $aset->toko->nama)
                ->setCellValue('M' . $row, $aset->invoice)
                ->setCellValue('N' . $row, $aset->jumlah)
                ->setCellValue('O' . $row, rupiah($aset->hargasatuan))
                ->setCellValue('P' . $row, rupiah($aset->hargatotal))
                ->setCellValue('Q' . $row, $aset->lama_garansi)
                ->setCellValue('R' . $row, date("d M Y", $aset->tglnonaktif))
                ->setCellValue('S' . $row, $aset->alasannonaktif)
                ->setCellValue('T' . $row, $aset->ketnonaktif)
                ->setCellValue('U' . $row, $lastHistory && $lastHistory->tanggal ? date('d M Y', $lastHistory->tanggal) : '--')
                ->setCellValue('V' . $row, $lastHistory && $lastHistory->person ? $lastHistory->person->nama : '--')
                ->setCellValue('W' . $row, $lastHistory && $lastHistory->lokasi ? $lastHistory->lokasi->nama : '--')
                ->setCellValue('X' . $row, $lastHistory ? $lastHistory->jumlah : '--')
                ->setCellValue('Y' . $row, $lastHistory ? $lastHistory->kondisi : '--')
                ->setCellValue('Z' . $row, $lastHistory ? $lastHistory->kelengkapan : '--')
                ->setCellValue('AA' . $row, $lastHistory ? $lastHistory->keterangan : '--')
                ->setCellValue('AB' . $row, $aset->keterangan);

            // Terapkan alignment ke kanan untuk kolom O dan P pada baris ini
            $sheet->getStyle('O' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('P' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $row++; // Pindah ke baris berikutnya
        }


        // // Auto width untuk semua kolom
        // foreach (range('A', 'Z') as $columnID) {
        //     $sheet->getColumnDimension($columnID)->setAutoSize(true);
        // }

        // Auto Width
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
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);


        $fileName = 'Daftar Aset Non Aktif Dinas Sumber Daya Air (DSDA).xlsx';

        // Set header untuk file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
