<?php

namespace App\Http\Controllers;

use App\Models\BarangStok;
use App\Models\VendorStok;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TransaksiDaruratStok;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class KontrakVendorStokController extends Controller
{
    public $unit_id;

    private function setUnitId()
    {
        $user = Auth::user();

        // Jika user tidak memiliki unitKerja atau adalah superadmin, biarkan melihat semua data
        if (!$user || !$user->unitKerja || ($user->unitKerja->hak ?? 0) == 1 || $user->hasRole('superadmin')) {
            $this->unit_id = null;
        } else {
            // User biasa dengan unit kerja dan hak = 0
            $this->unit_id = $user->unitKerja->id;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setUnitId();
        // if ($this->unit_id) {
        //     $transaksi = TransaksiStok::whereNotNull('kontrak_id')->whereHas('kontrakStok', function ($kontrak) {
        //         return $kontrak->whereNotNull('status')->whereHas('user', function ($user) {
        //             return $user->whereHas('unitKerja', function ($unit) {
        //                 return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
        //             });
        //         });
        //     })->get();
        // } else {
        //     $transaksi = TransaksiStok::whereNotNull('kontrak_id')->whereHas('kontrakStok', function ($kontrak) {
        //         return $kontrak->whereNotNull('status');
        //     })->get();
        // }
        // // Get transactions with a filled kontrak_id

        // $sortedTransaksi = $transaksi->sortByDesc('tanggal');

        // // Group the sorted transactions by vendor_id, then by kontrak_id
        // $groupedTransactions = $sortedTransaksi->groupBy('vendor_id')->map(function ($vendorGroup) {
        //     return $vendorGroup->groupBy('kontrak_id')->map(function ($kontrakGroup) {
        //         return $kontrakGroup->groupBy('id'); // Group by transaction ID for distinction
        //     });
        // });


        $groupedTransactions = KontrakVendorStok::whereHas('transaksiStok')->when($this->unit_id, function ($kontrak) {
            return $kontrak->whereHas('user', function ($user) {
                return $user->whereHas('unitKerja', function ($unit) {
                    return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            });
        })->get()->sortByDesc('tanggal');
        return view('rekam.index', compact('groupedTransactions', ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gate::authorize('kontrak_tambah_kontrak_baru');
        $vendors = VendorStok::all();
        return view('rekam.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Retrieve the contract details with related data (such as vendor and items)
        $kontrak = KontrakVendorStok::findOrFail($id);
        $total = $kontrak->listKontrak->sum(function ($item) {
            $subtotal = $item->harga * $item->jumlah;
            $ppn = $item->ppn ? ($subtotal * ((int) $item->ppn) / 100) : 0;
            return $subtotal + $ppn;
        });



        // Return the view with the contract data
        return view('rekam.show', compact('id'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Gate::authorize('kontrak_tambah_kontrak_baru');
        // $vendors = VendorStok::all();
        return view('rekam.create', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
