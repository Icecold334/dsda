<?php

namespace App\Http\Controllers;

use App\Models\VendorStok;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\TransaksiDaruratStok;
use App\Models\KontrakRetrospektifStok;

class TransaksiDaruratStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get transactions with a filled kontrak_id
        $transaksi = TransaksiStok::whereNull('kontrak_id')->get();

        // Sort transactions by date (you could sort by any other attribute relevant to your context)
        $sortedTransaksi = $transaksi->sortByDesc('tanggal');

        // Group the sorted transactions by vendor_id, then by kontrak_id
        $groupedTransactions = $sortedTransaksi->groupBy('vendor_id')->map(function ($vendorGroup) {
            return $vendorGroup->groupBy('kontrak_id')->map(function ($kontrakGroup) {
                return $kontrakGroup->groupBy('id'); // Group by transaction ID for distinction
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
        $transaksi = TransaksiStok::where('vendor_id', $id)
            ->whereNull('kontrak_id')
            ->get();


        // Pass the data to the view
        return view('darurat.edit', compact('transaksi'));
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
