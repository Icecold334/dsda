<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use App\Http\Requests\StoreRabRequest;
use App\Http\Requests\UpdateRabRequest;

class RabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rab.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rab.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRabRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rab $rab)
    {
        $statusMap = [
            null => ['label' => 'Diproses', 'color' => 'warning'],
            0 => ['label' => 'Ditolak', 'color' => 'danger'],
            1 => ['label' => 'Dibatalkan', 'color' => 'secondary'],
            2 => ['label' => 'Disetujui', 'color' => 'success'],
            3 => ['label' => 'Selesai', 'color' => 'primary'],
        ];

        // Tambahkan properti dinamis ke dalam object
        $rab->status_teks = $statusMap[$rab->status]['label'] ?? 'Tidak diketahui';
        $rab->status_warna = $statusMap[$rab->status]['color'] ?? 'gray';

        // Hitung selisih hari
        $totalHari = $rab->mulai->diffInDays($rab->selesai);

        // Hitung bulan dan sisa hari
        $bulan = floor($totalHari / 30);
        $hari  = $totalHari % 30;

        // Buat string durasi
        $lamaPengerjaan = '';
        if ($bulan > 0) {
            $lamaPengerjaan .= $bulan . ' bulan';
        }
        if ($hari > 0) {
            $lamaPengerjaan .= ($lamaPengerjaan ? ' ' : '') . $hari . ' hari';
        }

        $rab->lamaPengerjaan = $lamaPengerjaan ?: '0 hari';

        return view('rab.show', compact('rab'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rab $rab)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRabRequest $request, Rab $rab)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rab $rab)
    {
        //
    }
}
