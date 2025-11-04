<?php

use App\Models\Aset;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\Persetujuan;
use Illuminate\Support\Str;
use App\Livewire\DataDriver;
use App\Models\PermintaanStok;
use App\Livewire\AssetCalendar;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPermintaanStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RabController;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserController;
use App\Models\DetailPermintaanMaterial;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\DriverController;
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
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisStokController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\MerekStokController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\BagianStokController;
use App\Http\Controllers\BarangStokController;
use App\Http\Controllers\KonfirmasiController;
use App\Http\Controllers\LokasiStokController;
use App\Http\Controllers\PosisiStokController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\VendorStokController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\AsetNonAktifController;
use App\Http\Controllers\KategoriStokController;
use App\Http\Controllers\KontrakVendorController;
use App\Http\Controllers\MasterProgramController;
use App\Http\Controllers\TransaksiStokController;
use App\Http\Controllers\PengirimanStokController;
use App\Http\Controllers\PermintaanStokController;
use App\Http\Controllers\KontrakVendorStokController;
use App\Http\Controllers\TransaksiDaruratStokController;
use App\Http\Controllers\PengaturanPersetujuanController;
use App\Http\Controllers\KontrakRetrospektifStokController;

Route::get('/debug-gcs', function () {
    $keyPath = base_path(env('GOOGLE_CLOUD_KEY_FILE'));
    return [
        'key_exists' => file_exists($keyPath),
        'path' => $keyPath,
        'readable' => is_readable($keyPath),
    ];
});
Route::get('/gcs-sdk-test', function () {
    try {
        $client = new StorageClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'keyFilePath' => base_path(env('GOOGLE_CLOUD_KEY_FILE')),
        ]);

        $bucket = $client->bucket(env('GOOGLE_CLOUD_STORAGE_BUCKET'));

        // coba upload langsung
        $object = $bucket->upload('halo langsung via SDK', [
            'name' => 'direct-sdk-test.txt',
        ]);

        return ['success' => true, 'message' => 'SDK upload success ✅'];
    } catch (\Throwable $e) {
        // kirim pesan error asli dari SDK
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => substr($e->getTraceAsString(), 0, 500) // biar gak panjang
        ];
    }
});
Route::get('/debug-env', function () {
    return [
        'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
        'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET'),
        'key_file_env' => env('GOOGLE_CLOUD_KEY_FILE'),
        'key_file_resolved' => base_path(env('GOOGLE_CLOUD_KEY_FILE')),
        'config_disk_gcs' => config('filesystems.disks.gcs'),
    ];
});

Route::get('/gcs-upload-test', function () {
    $success = Storage::disk('gcs')->put('test-from-web.txt', 'Halo dari web Laravel!');
    return ['success' => $success];
});

Route::get('/', function () {
    return redirect()->to('/login');
});
Route::get('/logout', function () {
    Auth::guard('web')->logout();

    Session::invalidate();
    Session::regenerateToken();

    return redirect()->to('/login');
});

Route::get('/qr/permintaan/{user_id}/{kode}', function ($user_id, $kode) {
    // Cari permintaan berdasarkan kode_permintaan
    $permintaan = DetailPermintaanStok::where('kode_permintaan', $kode)->first();

    // Jika permintaan tidak ditemukan, redirect ke halaman home atau tampilkan pesan error
    if (!$permintaan) {
        return redirect()->route('home')->with('error', 'Permintaan tidak ditemukan.');
    }

    // Gunakan user_id = 3 sebagai guest, tidak tergantung pada user yang sedang login
    $user = User::find(3); // Selalu menggunakan user dengan ID 3 (guest)
    // Auth::loginUsingId(3);
    $tipe = 'permintaan';
    // Jika user dengan ID 3 tidak ditemukan, tampilkan pesan error
    if (!$user) {
        return redirect()->route('home')->with('error', 'User Guest tidak ditemukan.');
    }

    // Kembalikan view dengan data permintaan dan user guest
    return view('scan_permintaan', compact('permintaan', 'user', 'tipe'));
})->name('scan_permintaan');

Route::get('/qr/material/{user_id}/{kode}', function ($user_id, $kode) {
    // Cari permintaan material berdasarkan kode_permintaan
    $permintaan = \App\Models\DetailPermintaanMaterial::where('kode_permintaan', $kode)->first();

    // Jika permintaan tidak ditemukan, redirect ke halaman home atau tampilkan pesan error
    if (!$permintaan) {
        return redirect()->route('home')->with('error', 'Permintaan material tidak ditemukan.');
    }

    // Mapping status untuk mendapatkan status_teks
    $statusMap = [
        null => ['label' => 'Diproses', 'color' => 'warning'],
        0 => ['label' => 'Ditolak', 'color' => 'danger'],
        1 => ['label' => 'Disetujui', 'color' => 'success'],
        2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
        3 => ['label' => 'Selesai', 'color' => 'primary'],
    ];

    $statusTeks = $statusMap[$permintaan->status]['label'] ?? 'Tidak diketahui';

    // Cek status permintaan dan redirect sesuai logika
    switch ($permintaan->status) {
        case 1: // Disetujui - tampilkan PDF
            return downloadGabunganPdf($permintaan->id);

        case 2: // Sedang Dikirim - redirect ke halaman show dengan alert
            return redirect()->route('showPermintaan', ['tipe' => 'material', 'id' => $permintaan->id])
                ->with('alert', [
                    'type' => 'info',
                    'message' => 'Permintaan sedang dalam proses pengiriman.'
                ]);

        case 3: // Selesai - redirect ke halaman show dengan alert
            return redirect()->route('showPermintaan', ['tipe' => 'material', 'id' => $permintaan->id])
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Permintaan telah selesai diproses.'
                ]);

        default: // Status lain - redirect dengan pesan error
            return redirect()->route('showPermintaan', ['tipe' => 'material', 'id' => $permintaan->id])
                ->with('alert', [
                    'type' => 'warning',
                    'message' => "Permintaan belum dapat diakses. Status: {$statusTeks}"
                ]);
    }
})->name('scan_material');
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

Route::get('/material/{id}/qrDownload', function ($id) {
    $permintaan = \App\Models\DetailPermintaanMaterial::findOrFail($id);

    // Mapping status untuk mendapatkan status_teks
    $statusMap = [
        null => ['label' => 'Diproses', 'color' => 'warning'],
        0 => ['label' => 'Ditolak', 'color' => 'danger'],
        1 => ['label' => 'Disetujui', 'color' => 'success'],
        2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
        3 => ['label' => 'Selesai', 'color' => 'primary'],
    ];

    $statusTeks = $statusMap[$permintaan->status]['label'] ?? 'Tidak diketahui';

    // Cek status permintaan
    switch ($permintaan->status) {
        case 1: // Disetujui - tampilkan PDF
            return downloadGabunganPdf($id);

        case 2: // Sedang Dikirim - redirect ke halaman show dengan alert
            return redirect()->route('showPermintaan', ['tipe' => 'material', 'id' => $id])
                ->with('alert', [
                    'type' => 'info',
                    'message' => 'Permintaan sedang dalam proses pengiriman.'
                ]);

        case 3: // Selesai - redirect ke halaman show dengan alert
            return redirect()->route('showPermintaan', ['tipe' => 'material', 'id' => $id])
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Permintaan telah selesai diproses.'
                ]);

        default: // Status lain - redirect dengan pesan error
            return redirect()->route('showPermintaan', ['tipe' => 'material', 'id' => $id])
                ->with('alert', [
                    'type' => 'warning',
                    'message' => "Permintaan belum dapat diakses. Status: {$statusTeks}"
                ]);
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('agenda', AgendaController::class);
    Route::get('/nonaktifaset/export', [AsetNonAktifController::class, 'exportExcel'])->name('nonaktifaset.export');
    // Route::get('/material/{id}/qrDownload', function ($id) {
    //     $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    //     $pdf->SetMargins(20, 5, 20);
    //     $pdf->SetCreator('Sistem Permintaan');
    //     $pdf->SetAuthor('Dinas SDA');
    //     $pdf->SetTitle('SPB dan SPPB');
    //     $pdf->SetFont('helvetica', '', 10);

    //     // ========== Data Umum ==========
    //     // dd($id);
    //     $permintaan = DetailPermintaanMaterial::findOrFail($id);
    //     $unit_id = $permintaan->user->unitKerja->id;
    //     $permintaan->unit = UnitKerja::find($unit_id);
    //     $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

    //     $kasatpel = User::whereHas('unitKerja', fn($q) => $q->where('id', $unit_id))
    //         ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Kepala Satuan Pelaksana%'))
    //         ->first();

    //     $pemel = User::whereHas('unitKerja', fn($q) => $q->where('parent_id', $unit_id)->where('nama', 'like', '%Pemeliharaan%'))
    //         ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Kepala Seksi%'))
    //         ->first();

    //     $penjaga = User::whereHas('unitKerja', fn($q) => $q->where('id', $unit_id))
    //         ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Penjaga Gudang%'))
    //         ->where('lokasi_id', $permintaan->gudang_id)
    //         ->first();

    //     $pengurus = User::whereHas('unitKerja', fn($q) => $q->where('id', $unit_id))
    //         ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Pengurus Barang%'))
    //         ->first();

    //     $kasubag = User::whereHas('unitKerja', fn($q) => $q->where('parent_id', $unit_id)->where('nama', 'like', '%Tata Usaha%'))
    //         ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Kepala Subbagian%'))
    //         ->first();

    //     $Rkb = 1;
    //     $RKB = 1;
    //     $sign = true;
    //     $sudin = Str::contains($permintaan->unit->nama, 'Kepulauan')
    //         ? 'Kepulauan Seribu'
    //         : Str::of($permintaan->unit->nama)->after('Administrasi ');
    //     $isSeribu = 0;
    //     $withRab = $isSeribu ? $permintaan->permintaanMaterial->first()->rab_id : $permintaan->rab_id;

    //     // ========== Halaman 1: SPB ==========
    //     $htmlSpb = view(!$withRab ? 'pdf.nodin' : ($isSeribu ? 'pdf.spb1000' : 'pdf.spb'), compact(
    //         'ttdPath',
    //         'permintaan',
    //         'kasatpel',
    //         'pemel',
    //         'Rkb',
    //         'RKB',
    //         'sudin',
    //         'isSeribu',
    //         'sign'
    //     ))->render();

    //     $pdf->AddPage();
    //     $pdf->writeHTML($htmlSpb, true, false, true, false, '');

    //     // ========== Halaman 2: SPPB ==========
    //     $htmlSppb = view('pdf.sppb', compact(
    //         'ttdPath',
    //         'permintaan',
    //         'kasatpel',
    //         'penjaga',
    //         'pengurus',
    //         'kasubag',
    //         'Rkb',
    //         'RKB',
    //         'sudin',
    //         'isSeribu',
    //         'sign'
    //     ))->render();
    //     // dd('asd');

    //     $pdf->AddPage();
    //     $pdf->writeHTML($htmlSppb, true, false, true, false, '');
    //     // Output gabungan
    //     return response($pdf->Output('', 'S'))
    //         ->header('Content-Type', 'application/pdf')
    //         ->header('Content-Disposition', 'attachment; filename="SPB_SPPB.pdf"');
    // });

    Route::get('/nonaktifaset/downlaod-qr/{assetId}', [AsetNonAktifController::class, 'downloadQrImage'])->name('nonaktifaset.downloadQrImage');
    Route::resource('nonaktifaset', AsetNonAktifController::class);
    // Route::patch('/nonaktifaset/{nonaktifaset}/activate', [AsetNonAktifController::class, 'activate'])->name('nonaktifaset.activate');
    Route::get('/aset/export', [AsetController::class, 'exportExcel'])->name('aset.export');
    Route::get('/aset/{id}/export-pdf', [AsetController::class, 'exportPdf'])->name('aset.export-pdf');
    Route::get('/aset/downlaod-qr/{assetId}', [AsetController::class, 'downloadQrImage'])->name('aset.downloadQrImage');
    Route::get('/permintaan/downlaod-qr/{tipe}/{kode}', [PermintaanStokController::class, 'downloadQrImage'])->name('permintaan.downloadQrImage');
    Route::resource('aset', AsetController::class);
    Route::put('/aset/{id}/nonaktif', [AsetController::class, 'nonaktif'])->name('show.nonaktif');
    Route::resource('history', HistoryController::class);
    Route::resource('jurnal', JurnalController::class);
    Route::get('kalender-aset', function () {
        $peminjaman = DB::table('peminjaman_aset')
            ->join('detail_peminjaman_aset', 'peminjaman_aset.detail_peminjaman_id', '=', 'detail_peminjaman_aset.id')
            ->join('aset', 'peminjaman_aset.aset_id', '=', 'aset.id')
            ->where('detail_peminjaman_aset.status', 1)
            ->select('peminjaman_aset.*', 'detail_peminjaman_aset.tanggal_peminjaman', 'aset.nama')
            ->get();
        return view('peminjaman.index', ['peminjaman' => $peminjaman]);
    });
    Route::get('driver', [DriverController::class, 'index']);
    Route::get('security', [SecurityController::class, 'index']);
    Route::get('kategori/{tipe}', [KategoriController::class, 'create']);
    Route::get('kategori/{tipe}/{kategori}', [KategoriController::class, 'create'])->middleware('can:data_kategori');
    Route::resource('kategori', KategoriController::class)->middleware('can:data_kategori');
    Route::resource('keuangan', KeuanganController::class);
    Route::resource('lampiran', LampiranController::class);
    Route::get('lokasi/{tipe}/{lokasi}', [LokasiController::class, 'create'])->middleware('can:data_lokasi');
    Route::resource('lokasi', LokasiController::class)->middleware('can:data_lokasi');
    Route::get('merk/{tipe}/{merk}', [MerkController::class, 'create'])->middleware('can:data_merk');
    Route::resource('merk', MerkController::class)->middleware('can:data_merk');
    Route::get('ruang/{tipe}/{ruang}', [RuangController::class, 'create'])->middleware('can:data_ruang');
    Route::resource('ruang', RuangController::class)->middleware('can:data_ruang');
    Route::get('person/{tipe}/{person}', [PersonController::class, 'create'])->middleware('can:data_penanggung_jawab');
    Route::resource('person', PersonController::class)->middleware('can:data_penanggung_jawab');

    // Unit Kerja routes with new permissions
    Route::get('unit-kerja/{tipe}', [UnitKerjaController::class, 'create'])->middleware('can:unit_kerja.create');
    Route::get('unit-kerja/{tipe}/{id}', [UnitKerjaController::class, 'create'])->middleware('can:unit_kerja.create');
    Route::resource('unit-kerja', UnitKerjaController::class)->except(['create', 'store', 'edit', 'update', 'destroy'])->middleware('can:unit_kerja.read');
    Route::get('unit-kerja/create', [UnitKerjaController::class, 'create'])->middleware('can:unit_kerja.create')->name('unit-kerja.create');
    Route::post('unit-kerja', [UnitKerjaController::class, 'store'])->middleware('can:unit_kerja.create')->name('unit-kerja.store');
    Route::get('unit-kerja/{unit_kerja}/edit', [UnitKerjaController::class, 'edit'])->middleware('can:unit_kerja.update')->name('unit-kerja.edit');
    Route::put('unit-kerja/{unit_kerja}', [UnitKerjaController::class, 'update'])->middleware('can:unit_kerja.update')->name('unit-kerja.update');
    Route::patch('unit-kerja/{unit_kerja}', [UnitKerjaController::class, 'update'])->middleware('can:unit_kerja.update');
    Route::delete('unit-kerja/{unit_kerja}', [UnitKerjaController::class, 'destroy'])->middleware('can:unit_kerja.delete')->name('unit-kerja.destroy');

    // Kecamatan routes - protection via @can in views only
    Route::resource('kecamatan', KecamatanController::class);

    // Kelurahan routes - protection via @can in views only
    Route::resource('kelurahan', KelurahanController::class);

    // User Management routes - superadmin only
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('users/{user}/toggle-email-verification', [UserController::class, 'toggleEmailVerification'])->name('users.toggle-email-verification');
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');

        // Master Program routes - superadmin only (update only, no create/delete)
        Route::resource('master-program', MasterProgramController::class)->except(['create', 'store', 'destroy']);
    });

    Route::get('profil/{tipe}', [ProfilController::class, 'create']);
    Route::get('profil/{tipe}/{profil}', [ProfilController::class, 'create']);
    Route::resource('profil', ProfilController::class);
    Route::get('toko/{tipe}/{toko}', [TokoController::class, 'create'])->middleware('can:data_toko');
    Route::resource('toko', TokoController::class)->middleware('can:data_toko');
    Route::resource('bagian-stok', BagianStokController::class);
    Route::get('barang/{tipe}/{barang}', [BarangStokController::class, 'create']);
    // ->middleware('can:data_barang');
    Route::resource('barang', BarangStokController::class);
    // ->middleware('can:data_barang');
    // Route::resource('kontrak-vendor', KontrakVendorController::class);
    Route::get('lokasi-stok/{tipe}', [LokasiStokController::class, 'create'])->middleware('can:data_lokasi_gudang');
    Route::get('lokasi-stok/{tipe}/{id}', [LokasiStokController::class, 'create'])->middleware('can:data_lokasi_gudang');
    Route::resource('lokasi-stok', LokasiStokController::class)->middleware('can:data_lokasi_gudang');
    Route::get('kategori-stok/{tipe}/{id}', [KategoriStokController::class, 'create'])->middleware('can:data_kategori_stok');
    Route::resource('kategori-stok', KategoriStokController::class)->middleware('can:data_kategori_stok');
    Route::resource('merek-stok', MerekStokController::class);
    Route::resource('posisi-stok', PosisiStokController::class);
    Route::resource('vendor-stok', VendorStokController::class);
    Route::resource('bank', BankController::class);
    Route::resource('diskon', DiskonController::class);
    Route::resource('harga', HargaController::class);
    Route::resource('konfirmasi', KonfirmasiController::class);
    Route::resource('kota', KotaController::class);
    Route::resource('option', OptionController::class)->middleware('can:pengaturan');
    Route::resource('qrprint', QrPrintController::class)->middleware('can:qr_print');
    Route::resource('order', OrderController::class);
    Route::resource('provinsi', ProvinsiController::class);
    Route::resource('kalender', KalenderController::class);
    Route::get('permintaan/add/{tipe}/{kategori}', [PermintaanStokController::class, 'create']);
    Route::get('permintaan/add/{tipe}/{kategori}/{next}', [PermintaanStokController::class, 'create']);
    Route::get('permintaan/{tipe}', [PermintaanStokController::class, 'index']);
    Route::get('option-approval', [PengaturanPersetujuanController::class, 'index']);
    Route::get('option-approval/{tipe}/{jenis}', [PengaturanPersetujuanController::class, 'edit']);
    Route::get('permintaan/edit/{id}', [PermintaanStokController::class, 'edit'])->name('editPermintaan');
    Route::get('permintaan/material/edit/{id}', [PermintaanStokController::class, 'editMaterial'])->name('editPermintaanMaterial');
    Route::get('permintaan/{tipe}/{id}', [PermintaanStokController::class, 'show'])->name('showPermintaan');
    Route::get('/log-barang', [StokController::class, 'logBarang'])->name('log-index');
    Route::get('/stok/sudin/{sudin}', [StokController::class, 'index']);
    Route::get('/stok/template-opname', [\App\Http\Controllers\StokOpnameController::class, 'downloadTemplate'])->name('stok.template-opname');

    Route::resources([
        'jenis-stok' => JenisStokController::class,
        'barang-stok' => BarangStokController::class,
        'merk-stok' => MerkStokController::class,
        'vendor-stok' => VendorStokController::class,
        'lokasi-stok' => LokasiStokController::class,
        'kategori-stok' => KategoriStokController::class,
        'bagian-stok' => BagianStokController::class,
        'posisi-stok' => PosisiStokController::class,
        'kontrak-vendor-stok' => KontrakVendorStokController::class,
        'pengiriman-stok' => PengirimanStokController::class,
        'transaksi-stok' => TransaksiStokController::class,
        'transaksi-darurat-stok' => TransaksiDaruratStokController::class,
        'kontrak-retrospektif-stok' => KontrakRetrospektifStokController::class,
        'stok' => StokController::class,
        'rab' => RabController::class,
        'permintaan-stok' => PermintaanStokController::class,
    ]);

    // Admin routes for permintaan - no restrictions (superadmin only)
    Route::prefix('admin/permintaan')->group(function () {
        Route::get('{id}/edit', [PermintaanStokController::class, 'adminEdit'])->name('permintaan.admin-edit');
        Route::put('{id}', [PermintaanStokController::class, 'adminUpdate'])->name('permintaan.admin-update');
        Route::delete('{id}', [PermintaanStokController::class, 'adminDestroy'])->name('permintaan.admin-destroy');
    });
});

function downloadGabunganPdf($id)
{
    $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(20, 5, 20);
    $pdf->SetCreator('Sistem Permintaan');
    $pdf->SetAuthor('Dinas SDA');
    $pdf->SetTitle('SPB dan SPPB');
    $pdf->SetFont('helvetica', '', 10);

    $permintaan = DetailPermintaanMaterial::findOrFail($id);
    $unit_id = $permintaan->user->unitKerja->id;
    $permintaan->unit = UnitKerja::find($unit_id);
    $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

    $pemohon = $permintaan->user;
    $isKasatpel = $pemohon->hasRole('Kepala Satuan Pelaksana') || $pemohon->roles->contains(function ($role) {
        return str_contains($role->name, 'Kepala Satuan Pelaksana') || str_contains($role->name, 'Ketua Satuan Pelaksana');
    });
    $kepalaSeksiPemeliharaan = User::whereHas('unitKerja', function ($unit) use ($unit_id) {
        return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Pemeliharaan%');
    })->whereHas('roles', function ($role) {
        return $role->where('name', 'like', '%Kepala Seksi%');
    })->first();
    $pemohonRole = $pemohon->roles->pluck('name')->first();

    $kasatpel = User::whereHas('unitKerja', fn($q) => $q->where('id', $unit_id))
        ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Kepala Satuan Pelaksana%'))
        ->first();

    $pemel = User::whereHas('unitKerja', fn($q) => $q->where('parent_id', $unit_id)->where('nama', 'like', '%Pemeliharaan%'))
        ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Kepala Seksi%'))
        ->first();

    $penjaga = User::whereHas('unitKerja', fn($q) => $q->where('id', $unit_id))
        ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Penjaga Gudang%'))
        ->where('lokasi_id', $permintaan->gudang_id)
        ->first();

    $pengurus = User::whereHas('unitKerja', fn($q) => $q->where('id', $unit_id))
        ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Pengurus Barang%'))
        ->first();

    $kasubag = User::whereHas('unitKerja', fn($q) => $q->where('parent_id', $unit_id)->where('nama', 'like', '%Tata Usaha%'))
        ->whereHas('roles', fn($q) => $q->where('name', 'like', '%Kepala Subbagian%'))
        ->first();

    $Rkb = 1;
    $RKB = 1;
    $sign = true;
    $sudin = Str::contains($permintaan->unit->nama, 'Kepulauan')
        ? 'Kepulauan Seribu'
        : Str::of($permintaan->unit->nama)->after('Administrasi ');
    $isSeribu = 0;
    $withRab = $isSeribu ? $permintaan->permintaanMaterial->first()->rab_id : $permintaan->rab_id;

    $approvedUsers = $permintaan->persetujuan()
        ->where('is_approved', 1)
        ->get()
        ->pluck('user_id')
        ->unique();

    $usersWithRoles = \App\Models\User::whereIn('id', $approvedUsers)->with('roles')->get();
    $pemelDone = $usersWithRoles->contains(function ($user) {
        return $user->hasRole('Kepala Seksi');
    });
    // ========== Halaman 1: SPB ==========
    $htmlSpb = view(!$withRab ? 'pdf.nodin' : ($isSeribu ? 'pdf.spb1000' : 'pdf.spb'), compact(
        'ttdPath',
        'permintaan',
        'kasatpel',
        'pemelDone',
        'pemel',
        'Rkb',
        'RKB',
        'sudin',
        'isSeribu',
        'sign',
        'pemohon',
        'pemohonRole'
    ))->render();

    $pdf->AddPage();
    $pdf->writeHTML($htmlSpb, true, false, true, false, '');

    // ========== Halaman 2: SPPB ==========
    $htmlSppb = view('pdf.sppb', compact(
        'ttdPath',
        'permintaan',
        'isKasatpel',
        'kepalaSeksiPemeliharaan',
        'kasatpel',
        'penjaga',
        'pengurus',
        'kasubag',
        'Rkb',
        'RKB',
        'sudin',
        'isSeribu',
        'sign',
        'pemohon',
        'pemohonRole'
    ))->render();

    $pdf->AddPage();
    $pdf->writeHTML($htmlSppb, true, false, true, false, '');

    return response($pdf->Output('', 'S'))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="SPB_SPPB.pdf"');
}


Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

require __DIR__ . '/auth.php';
