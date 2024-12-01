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
        // Auth::loginUsingId(1);
        Auth::loginUsingId(7);
        // Auth::loginUsingId(25);
        // Auth::loginUsingId(22);
        Auth::loginUsingId(11);
        // Auth::loginUsingId(1);
        // Auth::loginUsingId(9);

    }
}
