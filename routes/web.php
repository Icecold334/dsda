<?php

use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\HistoryController;
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
use App\Http\Controllers\KontrakVendorController;
use App\Http\Controllers\TransaksiStokController;
use App\Http\Controllers\PengirimanStokController;
use App\Http\Controllers\PermintaanStokController;
use App\Http\Controllers\KontrakVendorStokController;
use App\Http\Controllers\TransaksiDaruratStokController;
use App\Http\Controllers\KontrakRetrospektifStokController;

Route::view('/', 'welcome');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware(['auth'])->group(function () {
    Route::resource('agenda', AgendaController::class);
    Route::resource('aset', AsetController::class);
    Route::resource('history', HistoryController::class);
    Route::resource('jurnal', JurnalController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('keuangan', KeuanganController::class);
    Route::resource('lampiran', LampiranController::class);
    Route::resource('lokasi', LokasiController::class);
    Route::resource('merk', MerkController::class);
    Route::resource('person', PersonController::class);
    Route::resource('toko', TokoController::class);
    Route::resource('bagian-stok', BagianStokController::class);
    Route::resource('barang-stok', BarangStokController::class);
    // Route::resource('kontrak-vendor', KontrakVendorController::class);
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
    Route::resource('order', OrderController::class);
    Route::resource('provinsi', ProvinsiController::class);
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

require __DIR__ . '/auth.php';
