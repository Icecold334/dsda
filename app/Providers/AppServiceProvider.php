<?php

namespace App\Providers;

use App\Models\User;
use Spatie\Permission\Models\Role;
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

        // Auth::loginUsingId(1); //superadmin
        // Auth::loginUsingId(4); //kepala unit

        // Auth::loginUsingId(1); //superadmin

        // Auth::loginUsingId(7);
        // Auth::loginUsingId(118); //kepala suku dinas
        // Auth::loginUsingId(7); //penanggung jawab
        // Auth::loginUsingId(16); //penanggung jawab
        // Auth::loginUsingId(194); // seksi 
        // Auth::loginUsingId(193); //sudin Sumber Daya Air Kota Administrasi Jakarta Timur
    }
}
