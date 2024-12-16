<?php

namespace App\Http\Controllers;

use TCPDF;
use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Merk;
use App\Models\Toko;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Person;
use App\Models\History;
use App\Models\Kategori;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Console\View\Components\Ask;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan query untuk aset aktif
        $query = $this->getAsetQuery();

        // Terapkan filter jika ada parameter request
        if ($request->hasAny(['nama', 'kategori_id', 'merk_id', 'toko_id', 'penanggung_jawab_id', 'lokasi_id'])) {
            $query = $this->applyFilters($query, $request);
        }

        // Terapkan sorting berdasarkan parameter request
        if ($request->hasAny(['order_by', 'order_direction'])) {
            // Gunakan query default atau query yang lebih kompleks jika sorting berdasarkan riwayat
            $query = $this->applySorting($query, $request);
        }

        // Ambil data hasil query
        // $asets = $query->get();
        $asets = $query->paginate(20); // 20 item per halaman
        // Proses koleksi untuk menghitung nilaiSekarang dan totalPenyusutan
        $asets->getCollection()->transform(function ($aset) {
            $hargaTotal = floatval($aset->hargatotal);
            $nilaiSekarang = $this->nilaiSekarang($hargaTotal, $aset->tanggalbeli, $aset->umur);
            $totalPenyusutan = $hargaTotal - $nilaiSekarang;

            $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
            $aset->hargatotal = $this->rupiah($hargaTotal);
            $aset->totalpenyusutan = $this->rupiah(abs($totalPenyusutan));

            return $aset;
        });

        // dd($asets);

        // Proses setiap aset untuk mendapatkan data QR
        $asetqr = $asets->getCollection()->map(function ($aset) {
            return getAssetWithSettings($aset->id);
        })->keyBy('id')->toArray();

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
        $merks = Merk::all();
        // when($this->unit_id, function ($query) use ($parentUnitId) {
        //     $query->whereHas('user', function ($query) use ($parentUnitId) {
        //         filterByParentUnit($query, $parentUnitId);
        //     });
        // })->get();
        $tokos = Toko::all();
        // when($this->unit_id, function ($query) use ($parentUnitId) {
        //     $query->whereHas('user', function ($query) use ($parentUnitId) {
        //         filterByParentUnit($query, $parentUnitId);
        //     });
        // })->get();
        $penanggungJawabs = Person::when($this->unit_id, function ($query) use ($parentUnitId) {
            $query->whereHas('user', function ($query) use ($parentUnitId) {
                filterByParentUnit($query, $parentUnitId);
            });
        })->get();
        $lokasis = Lokasi::when($this->unit_id, function ($query) use ($parentUnitId) {
            $query->whereHas('user', function ($query) use ($parentUnitId) {
                filterByParentUnit($query, $parentUnitId);
            });
        })->get();

        // dd($merks);
        return view('aset.index', compact('asets', 'kategoris', 'merks', 'tokos', 'penanggungJawabs', 'lokasis', 'asetqr'));
    }

    /**
     * Mendapatkan query dasar untuk aset aktif dengan nilai sekarang dan total penyusutan
     */
    // private function getAsetQuery()
    // {
    //     // Ambil unit_id user yang sedang login
    //     $userUnitId = Auth::user()->unit_id;

    //     // Cari parent unit, jika ada
    //     $unit = UnitKerja::find($userUnitId);
    //     // dd($unit);
    //     // Jika unit ditemukan dan memiliki parent, ambil parent_id, jika tidak gunakan unit_id itu sendiri
    //     $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;
    //     // Query untuk mendapatkan aset berdasarkan unit parent
    //     return Aset::where('status', true)
    //         ->whereHas('user', function ($user) use ($parentUnitId) {
    //             $user->whereHas('unitKerja', function ($unit) use ($parentUnitId) {
    //                 return $unit->where('id', 1)->dd();
    //             });
    //         });
    // }

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
        return Aset::where('status', true)
            ->when($this->unit_id, function ($query) use ($parentUnitId) {
                $query->whereHas('user', function ($query) use ($parentUnitId) {
                    filterByParentUnit($query, $parentUnitId);
                });
            });
    }

    /**
     * Menerapkan filter berdasarkan parameter request
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('merk_id')) {
            $query->where('merk_id', $request->merk_id);
        }

        if ($request->filled('toko_id')) {
            $query->where('toko_id', $request->toko_id);
        }

        if ($request->filled('penanggung_jawab_id')) {
            $query->whereHas('histories', function ($query) use ($request) {
                $query->where('person_id', $request->penanggung_jawab_id)
                    ->latest()  // Mengambil histori terakhir
                    ->limit(1);  // Hanya ambil histori terakhir
            });
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        return $query;
    }

    /**
     * Menerapkan sorting berdasarkan parameter request
     */
    private function applySorting($query, Request $request)
    {
        // Tentukan kolom yang ingin diurutkan
        $orderBy = $request->get('order_by', 'nama'); // Default urutkan berdasarkan nama
        $orderDirection = $request->get('order_direction', 'asc'); // Default urutan menaik

        // Menambahkan pengecekan untuk 'riwayat'
        if ($orderBy === 'riwayat') {
            // Jika pengurutan berdasarkan 'riwayat', lakukan LEFT JOIN dengan tabel history
            $query = Aset::query(); // Menggunakan Aset::query() karena ini membutuhkan join dengan history
            $query->leftJoin('history', 'history.aset_id', '=', 'aset.id')
                ->selectRaw('aset.*, COUNT(history.id) as history_count')  // Hitung jumlah riwayat untuk setiap aset
                ->where('aset.status', true)  // Menyaring berdasarkan status dari tabel 'aset'
                ->groupBy('aset.id');  // Kelompokkan berdasarkan aset_id

            // Urutkan berdasarkan jumlah history
            if ($orderDirection === 'asc') {
                // Urutkan dengan aset yang belum memiliki history di atas
                $query->orderByRaw('COUNT(history.id) ASC');
            } else {
                // Urutkan dengan aset yang memiliki banyak history di atas
                $query->orderByRaw('COUNT(history.id) DESC');
            }
        } else {
            // Urutkan berdasarkan kolom lain jika bukan berdasarkan riwayat
            $query->orderBy($orderBy, $orderDirection);
        }

        return $query;
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
        // $data = QrCode::size(512)
        //     ->format('png')
        //     ->errorCorrection('M')
        //     ->generate(
        //         'https://twitter.com/HarryKir',
        //     );

        // return response($data)
        //     ->header('Content-type', 'image/png');
        return view('aset.create');
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
    public function show(Aset $aset)
    {
        $aset->hargasatuan = $this->rupiah($aset->hargasatuan);
        $aset->hargatotal = $this->rupiah($aset->hargatotal);
        return view('aset.show', compact('aset'));
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
        return view('aset.edit', compact('aset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function nonaktif(Request $request, $id)
    {
        $request->validate([
            'tanggal_nonaktif' => 'required|date',
            'sebab_nonaktif' => 'required|string',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $aset = Aset::findOrFail($id);

        // Update status aset
        $aset->update([
            'status' => 0,
            'tglnonaktif' => strtotime($request->tanggal_nonaktif),
            'alasannonaktif' => $request->sebab_nonaktif,
            'ketnonaktif' => $request->keterangan,
        ]);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil dinonaktifkan.');
    }

    public function exportPdf_($id)
    {
        // Retrieve the asset details
        $aset = Aset::findOrFail($id);

        // Create a new PDF instance
        $pdf = new \TCPDF();

        // Set document information
        $pdf->SetCreator('Inventa');
        $pdf->SetAuthor('Dinas Sumber Daya Air (DSDA)');
        $pdf->SetTitle('Kartu Aset - Dinas Sumber Daya Air (DSDA)');
        $pdf->SetSubject($aset->nama);

        // Disable header and set footer
        $pdf->SetPrintHeader(false);
        $pdf->SetFooterData([0, 64, 0], [0, 64, 128]);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // QR Code Path
        $qrCodePath = public_path('storage/qr/' . $aset->systemcode . '.png');

        // Check if the QR code exists
        if (file_exists($qrCodePath)) {
            // Add QR code image to the right top of the page (adjust X and Y as necessary)
            $pdf->Image($qrCodePath, 160, 40, 40, 40, 'PNG');  // X=160, Y=40 (can be adjusted)
        } else {
            // Fallback if QR code doesn't exist
            $pdf->SetXY(160, 0);  // Set position for fallback text
            $pdf->Cell(40, 40, 'QR tidak tersedia', 0, 0, 'C');
        }


        // Create the content (HTML)
        $html = view('aset.pdf', compact('aset'))->render();

        // Write the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        // Output PDF
        return $pdf->Output($aset->nama . '.pdf', 'I');
    }

    public function exportPdf($id)
    {
        // Ambil data aset
        $aset = Aset::with(['kategori', 'merk', 'toko', 'lokasi'])->findOrFail($id);

        // Dapatkan informasi tambahan seperti perusahaan
        $perusahaan = strtoupper('Dinas Sumber Daya Air (DSDA)');
        $tanggalCetak = date("j M Y, H:i");

        // Perhitungan penyusutan
        $umurEkonomi = $aset->umur ?? 0; // Umur ekonomi dalam tahun
        $hargaTotal = $aset->hargatotal ?? 0; // Harga total aset
        $tanggalBeli = Carbon::parse($aset->tanggalbeli); //Konversi timestamp menjadi Carbon
        $usiaAset = (int) $tanggalBeli->diffInMonths(Carbon::now()); // Usia aset dalam tahun

        $penyusutanPerBulan = $umurEkonomi > 0 ? ($hargaTotal / ($umurEkonomi * 12)) : 0; // Penyusutan bulanan
        $totalPenyusutan = $umurEkonomi > 0 ? $penyusutanPerBulan * ($usiaAset * 12) : 0; // Total penyusutan
        $nilaiSekarang = $umurEkonomi > 0 ? ($hargaTotal - $totalPenyusutan) : $hargaTotal; // Nilai sekarang

        // Buat instance TCPDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Informasi dokumen
        $pdf->SetCreator('www.inventa.id');
        $pdf->SetAuthor(Auth::user()->name);
        $pdf->SetTitle('Kartu Aset - ' . $perusahaan);
        $pdf->SetSubject($aset->nama);
        $pdf->SetKeywords($aset->nama . ', PDF, ' . $perusahaan);

        // Atur margin, header, dan footer
        $pdf->SetMargins(10, 20, 10); // Margin kiri, atas, kanan
        // $pdf->SetHeaderMargin(0); // Nonaktifkan margin header
        $pdf->SetFooterMargin(10); // Margin footer
        // $pdf->SetAutoPageBreak(true, 20); // Tambahkan page break otomatis

        // Nonaktifkan header
        $pdf->SetPrintHeader(false);

        // Posisi QR Code
        $qrX = 160; // Posisi X (horizontal)
        $qrY = 40;  // Posisi Y (vertical)

        // Tambahkan halaman baru
        $pdf->AddPage();

        // Isi konten PDF
        // Header Kartu Aset
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, strtoupper('Dinas Sumber Daya Air (DSDA)'), 0, 1, 'L');

        // Posisi teks "Kartu Aset" di atas QR Code
        $pdf->SetTextColor(3, 146, 222); // Warna biru
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetXY($qrX, $qrY - 20); // Atur posisi teks (di atas QR code)
        $pdf->Cell(40, 5, strtoupper('Kartu Aset'), 0, 1, 'L');

        // Nama Aset
        $pdf->SetTextColor(3, 146, 222);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, strtoupper($aset->nama), 0, 1, 'L');

        // QR Code (jika ada)
        $qrUrl =  public_path('storage/qr/' . $aset->systemcode . '.png');
        if (file_exists($qrUrl)) {
            $pdf->Image($qrUrl, $qrX, $qrY,  30, 30, 'PNG');
        }

        // Informasi aset
        $pdf->Ln(5);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 10);

        $pdf->Cell(40, 5, 'Kode Aset', 0, 0, 'L');
        $pdf->Cell(0, 5, $aset->kode, 0, 1, 'L');

        $pdf->Cell(40, 5, 'Kode Sistem', 0, 0, 'L');
        $pdf->Cell(0, 5, $aset->systemcode, 0, 1, 'L');

        $pdf->Cell(40, 5, 'Kategori', 0, 0, 'L');
        $pdf->Cell(0, 5, $aset->kategori->nama ?? '-', 0, 1, 'L');

        $pdf->Cell(40, 5, 'Keterangan', 0, 1, 'L');
        $pdf->Cell(40, 5, $aset->keterangan ?? '-', 0, 'L');

        // Tambahkan bagian lain seperti Detil Aset, Pembelian, Penyusutan
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Detil Aset', 0, 1);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(40, 5, 'Merk', 0, 0);
        $pdf->Cell(0, 5, $aset->merk->nama ?? '-', 0, 1);

        $pdf->Cell(40, 5, 'Produsen', 0, 0);
        $pdf->Cell(0, 5, $aset->produsen ?? '-', 0, 1);

        $pdf->Cell(40, 5, 'No. Seri', 0, 0);
        $pdf->Cell(0, 5, $aset->noseri ?? '-', 0, 1);

        $pdf->Cell(40, 5, 'Deskripsi', 0, 1, 'L');
        $pdf->Cell(40, 5, $aset->description ?? '-', 0, 'L');

        // Bagian Pembelian
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Pembelian', 0, 1);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(40, 5, 'Tanggal Pembelian', 0, 0);
        $pdf->Cell(0, 5, $aset->tanggalbeli ? date('j F Y', $aset->tanggalbeli) : '-', 0, 1);

        $pdf->Cell(40, 5, 'Toko', 0, 0);
        $pdf->Cell(0, 5, $aset->toko->nama ?? '-', 0, 1);

        $pdf->Cell(40, 5, 'No. Invoice', 0, 0);
        $pdf->Cell(0, 5, $aset->invoice ?? '-', 0, 1);

        $pdf->Cell(40, 5, 'Jumlah', 0, 0); // Label "Jumlah"
        $pdf->Cell(20, 5, $aset->jumlah  . ' Unit' ?? '-', 0, 1, 'L'); // Jumlah aset

        $pdf->Cell(40, 5, 'Harga Satuan', 0, 0);
        $pdf->Cell(0, 5, rupiah($aset->hargasatuan), 0, 1);

        $pdf->Cell(40, 5, 'Harga Total', 0, 0);
        $pdf->Cell(0, 5, rupiah($aset->hargatotal), 0, 1);

        $pdf->Ln(5);
        // Header Penyusutan
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'PENYUSUTAN', 0, 1, 'L');

        // Konten Penyusutan
        $pdf->SetFont('helvetica', '', 10);

        if ($aset->aktif && $umurEkonomi > 0) {
            $pdf->Cell(40, 5, 'Umur Ekonomi:', 0, 0, 'L');
            $pdf->Cell(0, 5, $umurEkonomi . ' Tahun', 0, 1, 'L');

            $pdf->Cell(40, 5, 'Usia Aset:', 0, 0, 'L');
            $pdf->Cell(0, 5, usia_aset($aset->tanggalbeli), 0, 1, 'L');

            $pdf->Cell(40, 5, 'Penyusutan / Bulan:', 0, 0, 'L');
            $pdf->Cell(0, 5, rupiah($penyusutanPerBulan), 0, 1, 'L');

            $pdf->Cell(40, 5, 'Total Penyusutan:', 0, 0, 'L');
            $pdf->Cell(0, 5, rupiah($totalPenyusutan), 0, 1, 'L');

            $pdf->Cell(40, 5, 'Nilai Sekarang:', 0, 0, 'L');
            $pdf->Cell(0, 5, rupiah($nilaiSekarang), 0, 1, 'L');
        } else {
            $pdf->Cell(40, 5, 'Umur Ekonomi:', 0, 0, 'L');
            $pdf->Cell(0, 5, 'Tanpa Penyusutan', 0, 1, 'L');

            $pdf->Cell(40, 5, 'Usia Aset:', 0, 0, 'L');
            $pdf->Cell(0, 5, usia_aset($aset->tanggalbeli), 0, 1, 'L');
        }

        // STATUS
        if (!$aset->status) { // Jika status aktif = 0 (Non-Aktif)
            // Tambahkan spasi sebelum header
            $pdf->Cell(0, 6, '', 0, 1, 'L');

            // Header STATUS
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'STATUS', 0, 1, 'L');

            // Konten STATUS
            $pdf->SetFont('helvetica', '', 10);

            // Baris Status Aset
            $pdf->Cell(40, 5, "Status Aset", 0, 0, 'L');
            $pdf->Cell(0, 5, "Non-Aktif", 0, 1, 'L');

            // Baris Tanggal Non-Aktif
            $pdf->Cell(40, 5, "Tgl Non-Aktif", 0, 0, 'L');
            $pdf->Cell(0, 5, $aset->tglnonaktif ? date("j F Y", strtotime($aset->tglnonaktif)) : '-', 0, 1, 'L');

            // Baris Sebab Non-Aktif
            $pdf->Cell(40, 5, "Sebab Non-Aktif", 0, 0, 'L');
            $pdf->Cell(0, 5, $aset->alasannonaktif ?? '-', 0, 1, 'L');

            // Baris Keterangan
            $pdf->Cell(40, 5, "Keterangan", 0, 1, 'L');
            $pdf->Cell(40, 5, $aset->ketnonaktif ?? '-', 0, 'L');

            $pdf->Cell(0, 6, '', 0, 1, 'L');
        }

        // RIWAYAT
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Riwayat', 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 10);

        // Cek apakah aset memiliki data riwayat
        if ($aset->histories->isNotEmpty()) {
            foreach ($aset->histories as $history) {
                // Tampilkan riwayat
                $pdf->Cell(40, 5, "Sejak Tanggal", 0, 0, 'L');
                $pdf->Cell(0, 5, date("j F Y", ($history->tanggal)), 0, 1, 'L');

                $pdf->Cell(40, 5, "Penanggung Jawab", 0, 0, 'L');
                $pdf->Cell(0, 5, $history->person->nama ?? '-', 0, 1, 'L');

                $pdf->Cell(40, 5, "Lokasi", 0, 0, 'L');
                $pdf->Cell(0, 5, $history->lokasi->nama ?? '-', 0, 1, 'L');

                $pdf->Cell(40, 5, "Jumlah", 0, 0, 'L');
                $pdf->Cell(0, 5, ($history->jumlah ?? 0) . " Unit", 0, 1, 'L');

                $pdf->Cell(40, 5, "Kondisi", 0, 0, 'L');
                $pdf->Cell(0, 5, ($history->kondisi ?? 0) . "%", 0, 1, 'L');

                $pdf->Cell(40, 5, "Kelengkapan", 0, 0, 'L');
                $pdf->Cell(0, 5, ($history->kelengkapan ?? 0) . "%", 0, 1, 'L');

                $pdf->Cell(40, 5, "Keterangan", 0, 1, 'L');
                $pdf->Cell(40, 5, $history->keterangan ?? '-', 0, 'L');

                // Tambahkan space setelah setiap riwayat
                $pdf->Cell(0, 3, '', 0, 1, 'L');
            }
        } else {
            // Jika tidak ada data riwayat
            $pdf->Cell(130, 8, "Tidak Ada Data", 0, 1, 'L');
        }

        // AGENDA
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Agenda', 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 10);

        $dayMap = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        // Cek apakah aset memiliki data agenda
        if ($aset->agendas->isNotEmpty()) {
            foreach ($aset->agendas as $agenda) {
                // Tampilkan data agenda berdasarkan tipe
                $tipeAgenda = '';
                if ($agenda->tipe === "mingguan") {
                    $tipeAgenda = "Mingguan: Setiap Hari " .  $dayMap[$agenda->hari];
                } elseif ($agenda->tipe === "bulanan") {
                    $tipeAgenda = "Bulanan: Setiap Tanggal " . $agenda->hari;
                } elseif ($agenda->tipe === "tahunan") {
                    $tipeAgenda = "Tahunan: Setiap " . date('j F', $agenda->tanggal);
                } else {
                    $tipeAgenda = "Tanggal: " . date('j F Y', $agenda->tanggal);
                }

                // Tampilkan tipe agenda
                // $pdf->Cell(40, 5, "Tipe Agenda", 0, 0, 'L');
                $pdf->Cell(40, 5, $tipeAgenda, 0, 1, 'L');

                // Tampilkan keterangan
                // $pdf->Cell(40, 5, "Keterangan", 0, 0, 'L');
                $pdf->Cell(40, 5, $agenda->keterangan ?? '-', 0, 1, 'L');

                // Tambahkan space setelah setiap agenda
                $pdf->Cell(0, 3, '', 0, 1, 'L');
            }
        } else {
            // Jika tidak ada data agenda
            $pdf->Cell(130, 8, "Tidak Ada Data", 0, 1, 'L');
        }

        // KEUANGAN
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Keuangan', 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 10);

        if ($aset->keuangans->isNotEmpty()) {
            $pemasukan = 0;
            $pengeluaran = 0;

            foreach ($aset->keuangans as $keuangan) {
                // Tampilkan detail keuangan
                $pdf->Cell(40, 5, date("d M Y", $keuangan->tanggal), 0, 0, 'L');
                $pdf->Cell(40, 5, $keuangan->tipe === 'in' ? 'Pemasukan' : ($keuangan->tipe === 'out' ? 'Pengeluaran' : 'Tidak Diketahui'), 0, 0, 'L');
                $pdf->Cell(40, 5, rupiah($keuangan->nominal), 0, 0, 'R');
                $pdf->MultiCell(60, 5, $keuangan->keterangan ?? '-', 0, 'L');

                // Hitung pemasukan dan pengeluaran
                if ($keuangan->tipe === 'in') {
                    $pemasukan += $keuangan->nominal;
                } else {
                    $pengeluaran += $keuangan->nominal;
                }
            }

            // Tambahkan total pemasukan, pengeluaran, dan selisih
            $pdf->Ln(3); // Space sebelum total
            // if ($pengeluaran > 0) {
            //     $pdf->Cell(40, 5, "Total Pengeluaran", 0, 0, 'L');
            //     $pdf->Cell(0, 5, rupiah($pengeluaran), 0, 1, 'R');
            // }
            // if ($pemasukan > 0) {
            //     $pdf->Cell(40, 5, "Total Pemasukan", 0, 0, 'L');
            //     $pdf->Cell(0, 5, rupiah($pemasukan), 0, 1, 'R');
            // }
            // if ($pengeluaran > 0 && $pemasukan > 0) {
            //     $selisih = $pemasukan - $pengeluaran;
            //     $pdf->Cell(40, 5, "Selisih", 0, 0, 'L');
            //     $pdf->Cell(0, 5, rupiah($selisih), 0, 1, 'R');
            // }
        } else {
            $pdf->Cell(130, 8, "Tidak Ada Data", 0, 1, 'L');
        }

        // JURNAL
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Jurnal', 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 10);

        if ($aset->jurnals->isNotEmpty()) {
            foreach ($aset->jurnals as $jurnal) {
                // Tampilkan detail jurnal
                $pdf->Cell(40, 5, date("d M Y", $jurnal->tanggal), 0, 1, 'L');
                $pdf->Cell(40, 5, $jurnal->keterangan ?? '-', 0, 'L');
                // Tambahkan space setelah setiap agenda
                $pdf->Cell(0, 3, '', 0, 1, 'L');
            }
        } else {
            $pdf->Cell(130, 8, "Tidak Ada Data", 0, 1, 'L');
        }


        // Output file PDF
        return response($pdf->Output($aset->nama . '.pdf', 'I'), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function exportExcel(Request $request)
    {
        // Ambil data aset
        // $asets = Aset::where('status', true)->get();

        // Ambil query dasar untuk aset
        $query = $this->getAsetQuery();

        // Terapkan filter jika ada parameter request
        if ($request->hasAny(['nama', 'kategori_id', 'merk_id', 'toko_id', 'penanggung_jawab_id', 'lokasi_id'])) {
            $query = $this->applyFilters($query, $request);
        }

        // Terapkan sorting berdasarkan parameter request
        if ($request->hasAny(['order_by', 'order_direction'])) {
            $query = $this->applySorting($query, $request);
        }

        // Ambil data hasil query
        $asets = $query->get();

        // Ambil nilai filter dari request
        $kategori = $request->filled('kategori_id') ? Kategori::find($request->kategori_id)->nama : 'Semua Kategori';
        $merk = $request->filled('merk_id') ? Merk::find($request->merk_id)->nama : 'Semua Merk';
        $toko = $request->filled('toko_id') ? Toko::find($request->toko_id)->nama : 'Semua Distributor';
        $penanggungJawab = $request->filled('penanggung_jawab_id') ? Person::find($request->penanggung_jawab_id)->nama : 'Semua Penanggung Jawab';
        $lokasi = $request->filled('lokasi_id') ? Lokasi::find($request->lokasi_id)->nama : 'Semua Lokasi';

        // Format deskripsi filter
        $filterInfo = "Kategori: $kategori, Merk: $merk, Distributor: $toko, Penanggung Jawab: $penanggungJawab, Lokasi: $lokasi";


        // Buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Properti dokumen
        $spreadsheet->getProperties()
            ->setCreator('www.inventa.id')
            ->setLastModifiedBy('www.inventa.id')
            ->setTitle('Aset Aktif')
            ->setSubject('Daftar Aset Aktif - Dinas Sumber Daya Air (DSDA)')
            ->setDescription('Laporan Aset Aktif')
            ->setKeywords('aset, laporan, excel')
            ->setCategory('Laporan Aset');

        // Header judul
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', 'ASET AKTIF')
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
        $sheet->setCellValue('R7', 'PENYUSUTAN');
        $sheet->setCellValue('W7', 'RIWAYAT TERAKHIR');
        $sheet->setCellValue('AD7', 'KETERANGAN');

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

        // Style header tabel
        // $sheet->getStyle('A7:AC8')->getFont()->setBold(true);
        // $sheet->getStyle('A7:AC8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A7:AC8')->getFill()
        //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        //     ->getStartColor()->setARGB('FF806000');
        $sheet->getStyle('A7:AD8')->getFont()->setBold(true);
        $sheet->getStyle('A7:AD8')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A7:AD8')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A7:AD8')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('E7:J7');
        $sheet->mergeCells('K7:Q7');
        $sheet->mergeCells('R7:V7');
        $sheet->mergeCells('W7:AC7');
        $sheet->mergeCells('A7:A8');
        $sheet->mergeCells('B7:B8');
        $sheet->mergeCells('C7:C8');
        $sheet->mergeCells('D7:D8');
        $sheet->mergeCells('AD7:AD8');

        $sheet->getStyle('E8:AC8')->getFill()
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
        $sheet->getStyle('W7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF1F4E78');
        $sheet->getStyle('AD7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF806000');



        // Data
        $row = 9; // Mulai baris data
        foreach ($asets as $aset) {
            $hargaTotal = floatval($aset->hargatotal);
            $nilaiSekarang = $this->nilaiSekarang($hargaTotal, $aset->tanggalbeli, $aset->umur);
            $totalPenyusutan = $hargaTotal - $nilaiSekarang;

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
                ->setCellValue('P' . $row, rupiah($hargaTotal))
                ->setCellValue('Q' . $row, $aset->umur)
                ->setCellValue('R' . $row, $aset->lama_garansi)
                ->setCellValue('S' . $row, usia_aset($aset->tanggalbeli))
                ->setCellValue('T' . $row, rupiah($aset->penyusutan))
                ->setCellValue('U' . $row, rupiah($totalPenyusutan))
                ->setCellValue('V' . $row, rupiah($nilaiSekarang))
                ->setCellValue('W' . $row, $lastHistory && $lastHistory->tanggal ? date('d M Y', $lastHistory->tanggal) : '--')
                ->setCellValue('X' . $row, $lastHistory && $lastHistory->person ? $lastHistory->person->nama : '--')
                ->setCellValue('Y' . $row, $lastHistory && $lastHistory->lokasi ? $lastHistory->lokasi->nama : '--')
                ->setCellValue('Z' . $row, $lastHistory ? $lastHistory->jumlah : '--')
                ->setCellValue('AA' . $row, $lastHistory ? $lastHistory->kondisi : '--')
                ->setCellValue('AB' . $row, $lastHistory ? $lastHistory->kelengkapan : '--')
                ->setCellValue('AC' . $row, $lastHistory ? $lastHistory->keterangan : '--')
                ->setCellValue('AD' . $row, $aset->keterangan);

            // Terapkan alignment ke kanan untuk kolom O dan P pada baris ini
            $sheet->getStyle('O' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('P' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('T' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('U' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('V' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

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
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);

        $fileName = 'Daftar Aset Aktif Dinas Sumber Daya Air (DSDA).xlsx';

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
