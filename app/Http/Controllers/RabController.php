<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRabRequest;
use App\Http\Requests\UpdateRabRequest;

class RabController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if user can access specific RAB
     */
    private function canAccessRab(Rab $rab, $user)
    {
        // Superadmin can access all RABs
        if ($user->hasRole('superadmin') || $user->unit_id === null) {
            return true;
        }

        // Regular users can only access RABs from their unit or sub-units
        $rabUnitId = $rab->user->unitKerja->parent_id ?: $rab->user->unit_id;
        $userUnitId = $user->unitKerja->parent_id ?: $user->unit_id;

        return $rabUnitId === $userUnitId;
    }
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
        $hari = $totalHari % 30;

        // Buat string durasi
        $lamaPengerjaan = '';
        if ($bulan > 0) {
            $lamaPengerjaan .= $bulan . ' bulan';
        }
        if ($hari > 0) {
            $lamaPengerjaan .= ($lamaPengerjaan ? ' ' : '') . $hari . ' hari';
        }

        $rab->lamaPengerjaan = $lamaPengerjaan ?: '0 hari';
        switch ($rab->saluran_jenis) {
            case 'tersier':
                $keySaluran = 'idPhb';
                $namaSaluran = 'namaPhb';
                break;
            case 'sekunder':
                $keySaluran = 'idAliran';
                $namaSaluran = 'namaSungai';
                break;
            case 'primer':
                $keySaluran = 'idPrimer';
                $namaSaluran = 'namaSungai';
                break;

            default:
                $keySaluran = 'null';
                break;
        }

        if ($rab->saluran_jenis) {
            $rab->saluran_nama = collect($hasil[$rab->saluran_jenis])->where($keySaluran, $rab->saluran_id)->first()[$namaSaluran];
        }
        $RKB = $this->RKB;

        return view('rab.show', compact('rab', 'RKB'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rab $rab)
    {
        $user = Auth::user();

        // Check authorization
        if (!$this->canAccessRab($rab, $user)) {
            abort(403, 'Unauthorized access to this RAB');
        }

        return view('pages.rab.edit', compact('rab'));
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
