<?php

namespace App\Http\Controllers;

use App\Models\aset;
use App\Models\Merk;
use App\Models\Toko;
use App\Models\Lokasi;
use App\Models\Person;
use App\Models\Kategori;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsetNonAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Query awal untuk aset non-aktif
        $query = Aset::where('status', false)
            ->whereHas('user', function ($query) use ($parentUnitId) {
                // Cari aset yang terkait dengan unit parent user
                $query->whereHas('unitKerja', function ($unitQuery) use ($parentUnitId) {
                    // Pastikan kita selalu memfilter berdasarkan unit parent
                    $unitQuery->where('parent_id', $parentUnitId)
                        ->orWhere('id', $parentUnitId); // Menampilkan aset yang terkait dengan parent atau child
                });
            });
        // Apply filters
        $this->applyFilters($query, $request);

        // Apply sorting
        $this->applySorting($query, $request);

        // Execute the query and format the results
        $asets = $query->get()->map(function ($aset) {
            return $this->formatAset($aset);
        });

        // Proses setiap aset untuk mendapatkan data QR
        $asetqr = $asets->map(function ($aset) {
            return getAssetWithSettings($aset->id); // Menggunakan helper
        })->keyBy('id')->toArray(); // Gunakan keyBy untuk membuat key array berdasarkan ID aset

        // Data tambahan untuk dropdown filter
        $kategoris = Kategori::all();

        // Return to view with the necessary data
        return view('nonaktifaset.index', compact('asets', 'kategoris', 'asetqr'));
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
}
