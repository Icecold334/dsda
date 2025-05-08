<?php

namespace App\Providers;

use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->test();
        // Auth::loginUsingId(118); //kepala suku dinas
        // Auth::loginUsingId(16); //penanggung jawab
        // Auth::loginUsingId(16); //penanggung jawab
        // Auth::loginUsingId(194); // seksi 
        // Auth::loginUsingId(193); //sudin Sumber Daya Air Kota Administrasi Jakarta Timur
        // Auth::loginUsingId(217);
        // Auth::loginUsingId(245);

        // pusat
        // Auth::loginUsingId(1); //superadmin
        // Auth::loginUsingId(175); //kasudin
        // Auth::loginUsingId(176); //admin
        // Auth::loginUsingId(177); // pptk
        // Auth::loginUsingId(180); // ppk
        Auth::loginUsingId(178); // perencanaan
        // Auth::loginUsingId(179); // p3k
        // Auth::loginUsingId(561); // kasatpel
        // Auth::loginUsingId(191); // kasipemel drain
        // Auth::loginUsingId(187); // kasubag tu
        // Auth::loginUsingId(181); // pb


        // seribuu
        // Auth::loginUsingId(295); //kasudin
        // Auth::loginUsingId(296); //admin
        // Auth::loginUsingId(297); // pptk
        // Auth::loginUsingId(300); // ppk
        // Auth::loginUsingId(298); // perencanaan
        // Auth::loginUsingId(299); // p3k
        Auth::loginUsingId(601); // kasatpel
        // Auth::loginUsingId(315); // kasipemel drain
        // Auth::loginUsingId(309); // kasi perencanaan
        // Auth::loginUsingId(307); // kasubag tu
        // Auth::loginUsingId(181); // pb
        // Auth::loginUsingId(462);
    }

    // public function test()
    // {


    //     $Unit = UnitKerja::whereHas('user.kontrakVendor')->inRandomOrder()->first();
    //     // dd($Unit);

    //     // Pilih kontrak vendor yang terkait dengan unit kerja ini
    //     $kontrak = KontrakVendorStok::whereHas('user.unitKerja', function ($query) use ($Unit) {
    //         $query->where('parent_id', $Unit->id)->orWhere('id', $Unit->id);
    //     })->inRandomOrder()->first();

    //     // Pilih transaksi dan detail pengiriman yang terkait dengan kontrak ini
    //     $transaksi = TransaksiStok::where('kontrak_id', $kontrak->id)->inRandomOrder()->first();
    //     $detail_pengiriman = DetailPengirimanStok::whereHas('user.unitKerja', function ($unit) use ($Unit) {
    //         return $unit->where('parent_id', $Unit->id)->orWhere('id', $Unit->id);
    //     })->where('kontrak_id', $kontrak->id)->inRandomOrder()->first();

    //     // Ambil lokasi stok yang terkait dengan unit kerja
    //     $lokasi = LokasiStok::where('unit_id', $Unit->parent_id ?? $Unit->id)->inRandomOrder()->first();

    //     // dd($lokasi, $kontrak, $detail_pengiriman);
    // }
}
