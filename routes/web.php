<?php

use App\Models\Aset;
use App\Models\User;
use App\Models\PermintaanStok;
use App\Livewire\AssetCalendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrPrintController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LampiranController;
use App\Http\Controllers\MerkStokController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisStokController;
use App\Http\Controllers\MerekStokController;
use App\Http\Controllers\BagianStokController;
use App\Http\Controllers\BarangStokController;
use App\Http\Controllers\KonfirmasiController;
use App\Http\Controllers\LokasiStokController;
use App\Http\Controllers\PosisiStokController;
use App\Http\Controllers\VendorStokController;
use App\Http\Controllers\AsetNonAktifController;
use App\Http\Controllers\KontrakVendorController;
use App\Http\Controllers\TransaksiStokController;
use App\Http\Controllers\PengirimanStokController;
use App\Http\Controllers\PermintaanStokController;
use App\Http\Controllers\KontrakVendorStokController;
use App\Http\Controllers\TransaksiDaruratStokController;
use App\Http\Controllers\KontrakRetrospektifStokController;
use App\Http\Controllers\UnitKerjaController;
use App\Models\Persetujuan;

Route::get('/', function () {
    return redirect()->to('/login');
});
Route::get('/logout', function () {
    Auth::guard('web')->logout();

    Session::invalidate();
    Session::regenerateToken();

    return redirect()->to('/login');
});

Route::get('/scan/{user_id}/{systemcode}', function ($user_id, $systemcode) {
    // Cari aset berdasarkan systemcode
    $aset = Aset::with(['histories', 'keuangans', 'jurnals'])->where('systemcode', $systemcode)->first();

    // Jika aset tidak ditemukan, redirect ke halaman home atau tampilkan pesan error
    if (!$aset) {
        return redirect()->route('home')->with('error', 'Aset tidak ditemukan.');
    }

    // Gunakan user_id = 3 sebagai guest, tidak tergantung pada user yang sedang login
    $user = User::find(3); // Selalu menggunakan user dengan ID 3 (guest)
    // Auth::loginUsingId(3);

    // Jika user dengan ID 3 tidak ditemukan, tampilkan pesan error
    if (!$user) {
        return redirect()->route('home')->with('error', 'User Guest tidak ditemukan.');
    }

    // Kembalikan view dengan data aset dan user guest
    return view('scan', compact('aset', 'user'));
})->name('scan');


Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware(['auth'])->group(function () {
    Route::resource('agenda', AgendaController::class);
    Route::get('/nonaktifaset/downlaod-qr/{assetId}', [AsetNonAktifController::class, 'downloadQrImage'])->name('nonaktifaset.downloadQrImage');
    Route::resource('nonaktifaset', AsetNonAktifController::class);
    // Route::patch('/nonaktifaset/{nonaktifaset}/activate', [AsetNonAktifController::class, 'activate'])->name('nonaktifaset.activate');
    Route::get('/aset/{id}/export-pdf', [AsetController::class, 'exportPdf'])->name('aset.export-pdf');
    Route::get('/aset/downlaod-qr/{assetId}', [AsetController::class, 'downloadQrImage'])->name('aset.downloadQrImage');
    Route::resource('aset', AsetController::class);
    Route::put('/aset/{id}/nonaktif', [AsetController::class, 'nonaktif'])->name('show.nonaktif');
    Route::resource('history', HistoryController::class);
    Route::resource('jurnal', JurnalController::class);
    Route::get('kategori/{tipe}', [KategoriController::class, 'create']);
    Route::get('kategori/{tipe}/{kategori}', [KategoriController::class, 'create']);
    Route::resource('kategori', KategoriController::class);
    Route::resource('keuangan', KeuanganController::class);
    Route::resource('lampiran', LampiranController::class);
    Route::get('lokasi/{tipe}/{lokasi}', [LokasiController::class, 'create']);
    Route::resource('lokasi', LokasiController::class);
    Route::get('merk/{tipe}/{merk}', [MerkController::class, 'create']);
    Route::resource('merk', MerkController::class);
    Route::get('person/{tipe}/{person}', [PersonController::class, 'create']);
    Route::resource('person', PersonController::class);
    Route::get('unit-kerja/{tipe}', [UnitKerjaController::class, 'create']);
    Route::get('unit-kerja/{tipe}/{id}', [UnitKerjaController::class, 'create']);
    Route::resource('unit-kerja', UnitKerjaController::class);
    Route::get('profil/{tipe}', [ProfilController::class, 'create']);
    Route::get('profil/{tipe}/{profil}', [ProfilController::class, 'create']);
    Route::resource('profil', ProfilController::class);
    Route::get('toko/{tipe}/{toko}', [TokoController::class, 'create']);
    Route::resource('toko', TokoController::class);
    Route::resource('bagian-stok', BagianStokController::class);
    Route::resource('barang', BarangStokController::class);
    // Route::resource('kontrak-vendor', KontrakVendorController::class);
    Route::get('lokasi-stok/{tipe}', [LokasiStokController::class, 'create']);
    Route::get('lokasi-stok/{tipe}/{id}', [LokasiStokController::class, 'create']);
    Route::resource('lokasi-stok', LokasiStokController::class);
    Route::resource('merek-stok', MerekStokController::class);
    Route::resource('posisi-stok', PosisiStokController::class);
    Route::resource('vendor-stok', VendorStokController::class);
    Route::resource('bank', BankController::class);
    Route::resource('diskon', DiskonController::class);
    Route::resource('harga', HargaController::class);
    Route::resource('konfirmasi', KonfirmasiController::class);
    Route::resource('kota', KotaController::class);
    Route::resource('option', OptionController::class);
    Route::resource('qrprint', QrPrintController::class);
    Route::resource('order', OrderController::class);
    Route::resource('provinsi', ProvinsiController::class);
    Route::resource('kalender', KalenderController::class);
    Route::get('permintaan/add/{tipe}', [PermintaanStokController::class, 'create']);
    Route::get('permintaan/{tipe}', [PermintaanStokController::class, 'index']);

    Route::resources([
        'jenis-stok' => JenisStokController::class,
        'barang-stok' => BarangStokController::class,
        'merk-stok' => MerkStokController::class,
        'vendor-stok' => VendorStokController::class,
        'lokasi-stok' => LokasiStokController::class,
        'bagian-stok' => BagianStokController::class,
        'posisi-stok' => PosisiStokController::class,
        'kontrak-vendor-stok' => KontrakVendorStokController::class,
        'pengiriman-stok' => PengirimanStokController::class,
        'transaksi-stok' => TransaksiStokController::class,
        'transaksi-darurat-stok' => TransaksiDaruratStokController::class,
        'kontrak-retrospektif-stok' => KontrakRetrospektifStokController::class,
        'stok' => StokController::class,
        'permintaan-stok' => PermintaanStokController::class,
    ]);
});

// Register all resource routes


// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/testapprove', function () {
    return Persetujuan::all()->toArray();
});

require __DIR__ . '/auth.php';
