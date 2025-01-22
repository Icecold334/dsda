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

        $this->test();
        // Auth::loginUsingId(1); //superadmin
        Auth::loginUsingId(3); //kepala unit
        // Auth::loginUsingId(7);
        // Auth::loginUsingId(118); //kepala suku dinas
        // Auth::loginUsingId(16); //penanggung jawab
        // Auth::loginUsingId(16); //penanggung jawab
        // Auth::loginUsingId(194); // seksi 
        // Auth::loginUsingId(193); //sudin Sumber Daya Air Kota Administrasi Jakarta Timur
    }

    public function test()
    {


        // $Unit = UnitKerja::whereHas('user.kontrakVendor')->inRandomOrder()->first();
        // $kontrak = KontrakVendorStok::whereHas('user.unitKerja')->whereHas('transaksiStok')->whereHas('detailPengiriman.user.unitKerja')->where('type', true)->inRandomOrder()->first();
        // $detail_pengiriman = DetailPengirimanStok::whereHas('user.UnitKerja', function ($unit) use ($Unit) {
        //     return $unit->where('parent_id', $Unit->parent_id)->orWhere('id', $Unit->id);
        // })->where('kontrak_id', $kontrak->id)->inRandomOrder()->first();
        // // Pilih transaksi yang terkait dengan kontrak yang dipilih
        // $transaksi = TransaksiStok::where('kontrak_id', $kontrak->id)->inRandomOrder()->first();

        // $lokasi = LokasiStok::where('unit_id', $Unit->parent_id ?? $Unit->id)->inRandomOrder()->first();
        // dd($lokasi, $kontrak);
    }
}
