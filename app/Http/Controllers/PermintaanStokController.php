<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjamanAset;
use App\Models\DetailPermintaanMaterial;
use App\Models\DetailPermintaanStok;
use App\Models\Kategori;
use App\Models\KategoriStok;
use App\Models\PermintaanMaterial;
use App\Models\PermintaanStok;
use Illuminate\Http\Request;

class PermintaanStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tipe = null)
    {
        $jenis_id = is_null($tipe) || $tipe === 'umum' ? 3 : ($tipe === 'spare-part' ? 2 : 1);


        $permintaan = DetailPermintaanStok::where('jenis_id', $jenis_id)->whereHas('unit', function ($unit) {
            return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
        })->orderBy('id', 'desc')->get();
        $peminjaman = DetailPeminjamanAset::whereHas('unit', function ($unit) {
            return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
        })->orderBy('id', 'desc')->get();

        $permintaans = is_null($tipe) || $tipe === 'umum' ?  $permintaan->merge($peminjaman) : $permintaan;
        $kategoris = KategoriStok::all();

        if ($jenis_id == 1) {
            $permintaans = PermintaanMaterial::all()->map(function ($permintaan) {
                $statusMap = [
                    null => ['label' => 'Diproses', 'color' => 'warning'],
                    0 => ['label' => 'Ditolak', 'color' => 'danger'],
                    1 => ['label' => 'Disetujui', 'color' => 'success'],
                    2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
                    3 => ['label' => 'Selesai', 'color' => 'primary'],
                ];

                // Menambahkan properti dinamis
                $permintaan->status_teks = $statusMap[$permintaan->status]['label'] ?? 'Tidak diketahui';
                $permintaan->status_warna = $statusMap[$permintaan->status]['color'] ?? 'gray';
            });
        }

        return view('permintaan.index', compact('permintaans', 'tipe', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $kategori, $next = 0)
    {
        $last = null;
        if ($next !== 0) {
            $model = $tipe === 'permintaan' ? '\App\Models\DetailPermintaanStok' : '\App\Models\DetailPeminjamanAset';
            $last = app($model)::latest()->first();
        }
        $kategori = KategoriStok::where('slug', $kategori)->first();

        return view('permintaan.create', compact('tipe', 'last', 'kategori'));
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
    public function show(string $tipe = '', string $id)
    {
        $permintaan = $tipe == 'permintaan' ? DetailPermintaanStok::find($id) : DetailPeminjamanAset::find($id);
        $permintaan_material = DetailPermintaanMaterial::find($id);
        if ($permintaan_material) {
            $permintaan = $permintaan_material;

            // Mapping status yang baru
            $statusMap = [
                null => ['label' => 'Diproses', 'color' => 'warning'],
                0 => ['label' => 'Ditolak', 'color' => 'danger'],
                1 => ['label' => 'Disetujui', 'color' => 'success'],
                2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
                3 => ['label' => 'Selesai', 'color' => 'primary'],
            ];

            // Menambahkan properti dinamis
            $permintaan->status_teks = $statusMap[$permintaan->status]['label'] ?? 'Tidak diketahui';
            $permintaan->status_warna = $statusMap[$permintaan->status]['color'] ?? 'gray';
        }
        return view('permintaan.show', compact('permintaan', 'tipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permintaan = DetailPermintaanStok::find($id);
        return view('permintaan.edit', compact('permintaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    public function downloadQrImage($kode)
    {
        // Generate QR Image
        // dd($kode);
        $outputPath = $this->generateQrImage($kode);
        if (!$outputPath) {
            // Jika gambar tidak ditemukan atau gagal generate, kembalikan error
            return redirect()->back()->with('error', 'QR Code tidak ditemukan atau gagal dibuat.');
        }

        return $outputPath; // Return generated image directly to the browser
    }

    public function generateQrImage($kode)
    {

        $asset = DetailPermintaanStok::find($kode);
        // Ambil aset dan data terkait dari helper
        $asset = [
            'id' => $asset->id,
            'nama' => $asset->kode_permintaan,
            'systemcode' => $asset->kode_permintaan,
            'kategori' => $asset->kategori->nama ?? 'Tidak Berkategori',
            'qr_image' => "storage/qr_permintaan/{$asset->kode_permintaan}.png",
            'judul' => 'Permintaan',
            'baris1' => '-',
            'baris2' => '-',
        ];

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
