<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KontrakVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all kontrak_vendor grouped by tanggal_kontrak
        $kontrakVendors = KontrakVendorStok::all();
        dd($kontrakVendors);
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
