<?php

namespace App\Http\Controllers;

use App\Models\BarangStok;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TransaksiDaruratStok;
use App\Models\VendorStok;

class KontrakVendorStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get transactions with a filled kontrak_id
        $transaksi = TransaksiStok::whereNotNull('kontrak_id')->whereHas('kontrakStok', function ($kontrak) {
            return $kontrak->whereNotNull('status');
        })->get();
        $waiting = KontrakVendorStok::where('type', false)->whereNull('status')->get();
        $sortedTransaksi = $transaksi->sortByDesc('tanggal');

        // Group the sorted transactions by vendor_id, then by kontrak_id
        $groupedTransactions = $sortedTransaksi->groupBy('vendor_id')->map(function ($vendorGroup) {
            return $vendorGroup->groupBy('kontrak_id')->map(function ($kontrakGroup) {
                return $kontrakGroup->groupBy('id'); // Group by transaction ID for distinction
            });
        });
        return view('rekam.index', compact('groupedTransactions', 'waiting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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

        // Return the view with the contract data
        return view('rekam.show', compact('kontrak'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
