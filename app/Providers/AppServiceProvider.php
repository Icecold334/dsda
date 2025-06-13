<?php

namespace App\Providers;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        ini_set('max_execution_time', 240);

        $this->app->singleton('JakartaDataset', function () {
            return Cache::remember('saluran_data', now()->addHours(6), function () {
                $endpoints = [
                    'mikro' => 'https://portaldatadsda.jakarta.go.id/micro/list/get/0/namakec/ASC',
                    'tersier' => 'https://portaldatadsda.jakarta.go.id/phb/list/get/0/namakec/ASC/0',
                    'sekunder' => 'https://portaldatadsda.jakarta.go.id/aliran/list/get/namaSungai/ASC',
                    'primer' => 'https://portaldatadsda.jakarta.go.id/primer/list/get/namaSungai/ASC',
                ];

                $data = [];

                foreach ($endpoints as $key => $url) {
                    try {
                        $response = Http::timeout(180)->withOptions([
                            'verify' => public_path('cacert.pem'),
                        ])->get($url);

                        $data[$key] = $response->successful()
                            ? collect($response->json()['data'] ?? [])
                            : collect([]);
                    } catch (\Exception $e) {
                        Log::error("Gagal fetch $key: " . $e->getMessage());
                        $data[$key] = collect([]);
                    }
                }

                return $data;
            });
        });
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


        // pusat
        // Auth::loginUsingId(1); //superadmin
        // Auth::loginUsingId(175); //kasudin
        Auth::loginUsingId(176); //admin
        // Auth::loginUsingId(177); // pptk
        // Auth::loginUsingId(180); // ppk
        // Auth::loginUsingId(178); // perencanaan
        // Auth::loginUsingId(179); // p3k
        // Auth::loginUsingId(319); // kasatpel
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
