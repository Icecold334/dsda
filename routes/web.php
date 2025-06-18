<?php

use App\Models\Aset;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\Persetujuan;
use Illuminate\Support\Str;
use App\Models\PermintaanStok;
use App\Livewire\AssetCalendar;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPermintaanStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RabController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\TokoController;
use App\Models\DetailPermintaanMaterial;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RuangController;
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
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\BagianStokController;
use App\Http\Controllers\BarangStokController;
use App\Http\Controllers\KonfirmasiController;
use App\Http\Controllers\LokasiStokController;
use App\Http\Controllers\PosisiStokController;
use App\Http\Controllers\VendorStokController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\AsetNonAktifController;
use App\Http\Controllers\KategoriStokController;
use App\Http\Controllers\KontrakVendorController;
use App\Http\Controllers\TransaksiStokController;
use App\Http\Controllers\PengirimanStokController;
use App\Http\Controllers\PermintaanStokController;
use App\Http\Controllers\KontrakVendorStokController;
use App\Http\Controllers\TransaksiDaruratStokController;
use App\Http\Controllers\PengaturanPersetujuanController;
use App\Http\Controllers\KontrakRetrospektifStokController;

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
    // Cari aset berdasarkan systemcode
    $permintaan = DetailPermintaanStok::where('kode_permintaan', $kode)->first();

    // Jika permintaan tidak ditemukan, redirect ke halaman home atau tampilkan pesan error
    if (!$permintaan) {
        return redirect()->route('home')->with('error', 'Aset tidak ditemukan.');
    }

    // Gunakan user_id = 3 sebagai guest, tidak tergantung pada user yang sedang login
    $user = User::find(3); // Selalu menggunakan user dengan ID 3 (guest)
    // Auth::loginUsingId(3);
    $tipe = 'permintaan';
    // Jika user dengan ID 3 tidak ditemukan, tampilkan pesan error
    if (!$user) {
        return redirect()->route('home')->with('error', 'User Guest tidak ditemukan.');
    }

    // Kembalikan view dengan data aset dan user guest
    return view('scan_permintaan', compact('permintaan', 'user', 'tipe'));
})->name('scan_permintaan');
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



Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('agenda', AgendaController::class);
    Route::get('/nonaktifaset/export', [AsetNonAktifController::class, 'exportExcel'])->name('nonaktifaset.export');
    Route::get('/material/{id}/qrDownload', function ($id) {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(20, 5, 20);
        $pdf->SetCreator('Sistem Permintaan');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('SPB dan SPPB');
        $pdf->SetFont('helvetica', '', 10);

        // ========== Data Umum ==========
        dd($id);
        $permintaan = DetailPermintaanMaterial::findOrFail($id);
        $unit_id = $permintaan->user->unitKerja->id;
        $permintaan->unit = UnitKerja::find($unit_id);
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

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

        // ========== Halaman 1: SPB ==========
        $htmlSpb = view(!$withRab ? 'pdf.nodin' : ($isSeribu ? 'pdf.spb1000' : 'pdf.spb'), compact(
            'ttdPath',
            'permintaan',
            'kasatpel',
            'pemel',
            'Rkb',
            'RKB',
            'sudin',
            'isSeribu',
            'sign'
        ))->render();

        $pdf->AddPage();
        $pdf->writeHTML($htmlSpb, true, false, true, false, '');

        // ========== Halaman 2: SPPB ==========
        $htmlSppb = view('pdf.sppb', compact(
            'ttdPath',
            'permintaan',
            'kasatpel',
            'penjaga',
            'pengurus',
            'kasubag',
            'Rkb',
            'RKB',
            'sudin',
            'isSeribu',
            'sign'
        ))->render();
        dd('asd');

        $pdf->AddPage();
        $pdf->writeHTML($htmlSppb, true, false, true, false, '');
        // Output gabungan
        return response($pdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="SPB_SPPB.pdf"');
    });
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
    Route::get('unit-kerja/{tipe}', [UnitKerjaController::class, 'create'])->middleware('can:data_unit_kerja');
    Route::get('unit-kerja/{tipe}/{id}', [UnitKerjaController::class, 'create'])->middleware('can:data_unit_kerja');
    Route::resource('unit-kerja', UnitKerjaController::class)->middleware('can:data_unit_kerja');
    Route::get('profil/{tipe}', [ProfilController::class, 'create']);
    Route::get('profil/{tipe}/{profil}', [ProfilController::class, 'create']);
    Route::resource('profil', ProfilController::class);
    Route::get('toko/{tipe}/{toko}', [TokoController::class, 'create'])->middleware('can:data_toko');
    Route::resource('toko', TokoController::class)->middleware('can:data_toko');
    Route::resource('bagian-stok', BagianStokController::class);
    Route::get('barang/{tipe}/{barang}', [BarangStokController::class, 'create'])->middleware('can:data_barang');
    Route::resource('barang', BarangStokController::class)->middleware('can:data_barang');
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
    Route::get('permintaan/{tipe}/{id}', [PermintaanStokController::class, 'show'])->name('showPermintaan');
    Route::get('/log-barang', [StokController::class, 'logBarang'])->name('log-index');
    Route::get('/stok/sudin/{sudin}', [StokController::class, 'index']);

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
});


Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

require __DIR__ . '/auth.php';
