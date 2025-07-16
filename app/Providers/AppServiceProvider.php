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

        // ini_set('max_execution_time', 240);

        // $this->app->singleton('JakartaDataset', function () {
        //     return Cache::remember('saluran_data', now()->addHours(6), function () {
        //         $endpoints = [
        //             // 'mikro' => 'https://portaldatadsda.jakarta.go.id/micro/list/get/0/namakec/ASC',
        //             'tersier' => 'https://portaldatadsda.jakarta.go.id/phb/list/get/0/namakec/ASC/0',
        //             'sekunder' => 'https://portaldatadsda.jakarta.go.id/aliran/list/get/namaSungai/ASC',
        //             'primer' => 'https://portaldatadsda.jakarta.go.id/primer/list/get/namaSungai/ASC',
        //         ];

        //         $data = [];

        //         foreach ($endpoints as $key => $url) {
        //             try {
        //                 $response = Http::timeout(180)->withOptions([
        //                     'verify' => public_path('cacert.pem'),
        //                 ])->get($url);

        //                 $data[$key] = $response->successful()
        //                     ? collect($response->json()['data'] ?? [])
        //                     : collect([]);
        //             } catch (\Exception $e) {
        //                 Log::error("Gagal fetch $key: " . $e->getMessage());
        //                 $data[$key] = collect([]);
        //             }
        //         }

        //         return $data;
        //     });
        // });
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // pusat
        // Auth::loginUsingId(1); //superadmin
        // Auth::loginUsingId(175); //kasudin
        // Auth::loginUsingId(176); //admin
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

    // public function generateInsightGemini($userId)
    // {


    //     // Ringkasan data budgeting 3 periode terakhir
    //     $summary = [
    //         [
    //             'periode' => '10 April 2025 - 9 Mei 2025',
    //             'total_spent' => 1720000,
    //             'by_category' => [
    //                 'makan' => 900000,
    //                 'bensin' => 500000,
    //                 'jajan' => 180000,
    //                 'lain-lain' => 140000,
    //             ],
    //         ],
    //         [
    //             'periode' => '10 Mei 2025 - 9 Juni 2025',
    //             'total_spent' => 1880000,
    //             'by_category' => [
    //                 'makan' => 1000000,
    //                 'bensin' => 480000,
    //                 'jajan' => 250000,
    //                 'lain-lain' => 150000,
    //             ],
    //         ],
    //         [
    //             'periode' => '10 Juni 2025 - 9 Juli 2025',
    //             'total_spent' => 1600000,
    //             'by_category' => [
    //                 'makan' => 850000,
    //                 'bensin' => 450000,
    //                 'jajan' => 200000,
    //                 'lain-lain' => 100000,
    //             ],
    //         ],
    //     ];


    //     // $prompt = "Berikan insight keuangan dari data budgeting berikut: " . json_encode($summary) . ". Insight bisa berupa pola pengeluaran, saran hemat, dan prediksi target saving yang cocok.";
    //     $prompt = "Kasih saran untuk data budgeting berikut: " . json_encode($summary) . ". buat dalam 1 kalimat 7-10 kata aja dalam bahasa yang agak santai";
    //     $key = 'AIzaSyCpHjrP3gDAlnMMmQS_QLj9c2z9E8Mm2y4';
    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //     ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $key, [
    //         'contents' => [[
    //             'parts' => [['text' => $prompt]]
    //         ]]
    //     ]);

    //     // dd($response->json());
    //     dd($response->json('candidates.0.content.parts.0.text') ?? 'Tidak ada insight ditemukan.');
    //     return $response->json('candidates.0.content.parts.0.text') ?? 'Tidak ada insight ditemukan.';
    // }
}
