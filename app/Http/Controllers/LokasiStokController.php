<?php

namespace App\Http\Controllers;

use App\Models\LokasiStok;
use Illuminate\Http\Request;

class LokasiStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all lokasi stok from the database, you could also add pagination here if needed
        $lokasiStok = LokasiStok::whereHas('unitKerja', function ($unit) {
            return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
        })->get(); // or ->paginate(10) if you prefer pagination

        // Pass lokasiStok to the view
        return view('lokasi_stok.index', compact('lokasiStok'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $id = 0)
    {
        return view('lokasi_stok.create', compact('tipe', 'id'));
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
