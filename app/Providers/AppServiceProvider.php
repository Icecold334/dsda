<?php

namespace App\Providers;

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
        Auth::loginUsingId(3); //kepala unit
        // Auth::loginUsingId(118); //kepala suku dinas
        // Auth::loginUsingId(8); //penanggung jawab

    }
}
