<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\DB;

class KontrakVendorStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kontrakVendorsAll = KontrakVendorStok::all();

        // Use a collection to filter distinct records based on tanggal_kontrak and vendor_id
        $kontrakVendors = $kontrakVendorsAll->unique(function ($item) {
            return $item->tanggal_kontrak . '-' . $item->vendor_id;
        });


        return view('rekam.index', compact('kontrakVendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
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
