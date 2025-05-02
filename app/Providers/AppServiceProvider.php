<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

        // Auth::loginUsingId(1); //superadmin
        // Auth::loginUsingId(175); //kasudin
        // Auth::loginUsingId(177); // perencanaan
        // Auth::loginUsingId(553); // kasatpel
        // Auth::loginUsingId(190); // kasipemel drain
        // Auth::loginUsingId(186); // kasubag tu
        // Auth::loginUsingId(180); // pb
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
