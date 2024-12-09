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
// <<<<<<< support
//         Auth::loginUsingId(2);
//         // Auth::loginUsingId(7);
// =======
// >>>>>>> main
//         // Auth::loginUsingId(25);
//         Auth::loginUsingId(22);
    }
}
