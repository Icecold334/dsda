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

        // Get transactions with a filled kontrak_id
        $transaksi = TransaksiStok::whereNull('kontrak_id')->whereHas('user', function ($user) {
            return $user->whereHas('unitKerja', function ($unit) {
                return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });
        })->get();

        // Sort transactions by date (you could sort by any other attribute relevant to your context)
        $sortedTransaksi = $transaksi->sortByDesc('tanggal');

        // Group the sorted transactions by parent_id first, then by unit_id
        $groupedTransactions = $sortedTransaksi->groupBy(function ($transaksi) {
            // First group by parent_id, then unit_id to merge users with the same parent
            return $transaksi->user->unitKerja->parent_id . '-' . $transaksi->user->unitKerja->id;
        })->map(function ($group) {
            // Then group by kontrak_id and finally by transaction id
            return $group->groupBy('kontrak_id')->map(function ($kontrakGroup) {
                return $kontrakGroup->groupBy('id');
            });
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
            ->get();
        // dd($transaksis);


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
