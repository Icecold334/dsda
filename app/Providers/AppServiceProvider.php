<?php

namespace App\Providers;

use App\Models\User;
use App\Models\MerkStok;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use App\Models\TransaksiStok;
use App\Models\KontrakVendorStok;
use Spatie\Permission\Models\Role;
use App\Models\DetailPengirimanStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
        // Auth::loginUsingId(1); //superadmin
        // Auth::loginUsingId(3); //kepala unit
        Auth::loginUsingId(252);
        // Auth::loginUsingId(118); //kepala suku dinas
        // Auth::loginUsingId(16); //penanggung jawab
        // Auth::loginUsingId(16); //penanggung jawab
        // Auth::loginUsingId(194); // seksi 
        // Auth::loginUsingId(193); //sudin Sumber Daya Air Kota Administrasi Jakarta Timur
        // Auth::loginUsingId(4);
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
