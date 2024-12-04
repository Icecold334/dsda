<?php

namespace App\Http\Controllers;

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
use Illuminate\Console\View\Components\Ask;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $asets = $query->get();

        // Proses koleksi untuk menghitung nilaiSekarang dan totalPenyusutan
        $asets = $asets->map(function ($aset) {
            $hargaTotal = floatval($aset->hargatotal);
            $nilaiSekarang = $this->nilaiSekarang($hargaTotal, $aset->tanggalbeli, $aset->umur);
            $totalPenyusutan = $hargaTotal - $nilaiSekarang;

            $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
            $aset->hargatotal = $this->rupiah($hargaTotal);
            $aset->totalpenyusutan = $this->rupiah(abs($totalPenyusutan));

            return $aset;
        });

        // Proses setiap aset untuk mendapatkan data QR
        $asetqr = $asets->map(function ($aset) {
            return getAssetWithSettings($aset->id); // Menggunakan helper
        })->keyBy('id')->toArray(); // Gunakan keyBy untuk membuat key array berdasarkan ID aset

        // Data tambahan untuk dropdown filter
        $kategoris = Kategori::all();
        $merks = Merk::all();
        $tokos = Toko::all();
        $penanggungJawabs = Person::all();
        $lokasis = Lokasi::all();

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
        // dd($parentUnitId);

        // Query untuk mendapatkan aset berdasarkan unit parent yang dimiliki oleh user
        return Aset::where('status', true)
            ->whereHas('user', function ($query) use ($parentUnitId) {
                // Cari aset yang terkait dengan unit parent user
                $query->whereHas('unitKerja', function ($unitQuery) use ($parentUnitId) {
                    // Pastikan kita selalu memfilter berdasarkan unit parent
                    $unitQuery->where('parent_id', $parentUnitId)
                        ->orWhere('id', $parentUnitId); // Menampilkan aset yang terkait dengan parent atau child
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
}
