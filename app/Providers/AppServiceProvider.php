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


        // pusat
        // Auth::loginUsingId(1); //superadmin
        Auth::loginUsingId(175); //kasudin
        // Auth::loginUsingId(176); //admin
        // Auth::loginUsingId(177); // pptk
        // Auth::loginUsingId(180); // ppk
        // Auth::loginUsingId(178); // perencanaan
        // Auth::loginUsingId(179); // p3k
        // Auth::loginUsingId(561); // kasatpel
        // Auth::loginUsingId(191); // kasipemel drain
        // Auth::loginUsingId(189); // kasi perencanaan
        // Auth::loginUsingId(187); // kasubag tu
        // Auth::loginUsingId(181); // pb
        // Auth::loginUsingId(476);


        // seribuu
        // Auth::loginUsingId(295); //kasudin
        // Auth::loginUsingId(296); //admin
        // Auth::loginUsingId(297); // pptk
        // Auth::loginUsingId(300); // ppk
        // Auth::loginUsingId(298); // perencanaan
        // Auth::loginUsingId(299); // p3k
        // Auth::loginUsingId(601); // kasatpel
        // Auth::loginUsingId(315); // kasipemel drain
        // Auth::loginUsingId(309); // kasi perencanaan
        // Auth::loginUsingId(307); // kasubag tu
        // Auth::loginUsingId(301); // pb
        // Auth::loginUsingId(462);
    }
}
