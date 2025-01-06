<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\VendorStok;
use App\Models\Persetujuan;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\TransaksiDaruratStok;
use Illuminate\Support\Facades\Auth;
use App\Models\KontrakRetrospektifStok;

class TransaksiDaruratStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($this->unit_id) {
            # code...
            $transaksi = TransaksiStok::whereNull('kontrak_id')
                ->whereHas('user', function ($user) {
                    return $user->whereHas('unitKerja', function ($unit) {
                        return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                    });
                })
                ->get();
        } else {
            $transaksi = TransaksiStok::whereNull('kontrak_id')
                ->whereHas('user', function ($user) {
                    return $user;
                })
                ->get();
        }

        // Sort transactions by date
        $sortedTransaksi = $transaksi->sortByDesc('tanggal');

        // Group transactions by vendor_id first, then by unit_id
        $groupedTransactions = $sortedTransaksi->groupBy('vendor_id')->map(function ($vendorGroup) {
            return $vendorGroup->groupBy('user.unit_id'); // Group by user unit_id
        });


        return view('darurat.index', compact('groupedTransactions'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = VendorStok::all();
        return view('darurat.create', compact('vendors'));
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
    public function show(string $id)
    {
        // Retrieve the transaction by ID with a null kontrak_id
        $transaksis = TransaksiStok::where('vendor_id', $id)
            ->whereNull('kontrak_id')
            ->whereHas('user', function ($user) {
                return $user->whereHas('unitKerja', function ($unit) {
                    return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            })
            ->get();


        // Pass the data to the view
        return view('darurat.show', compact('transaksis'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaksi = TransaksiStok::with('approvals')->where('vendor_id', $id)
            ->whereNull('kontrak_id')
            ->whereHas('user', function ($user) {
                return $user->whereHas('unitKerja', function ($unit) {
                    return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                });
            })
            ->get();

        $roles = Auth::user()->roles->pluck('name');

        $items = Persetujuan::where('approvable_id', $id)->where('approvable_type', TransaksiStok::class)->get();


        // Pass the data to the view
        return view('darurat.edit', compact('transaksi', 'roles', 'items'));
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
