<?php

namespace App\Http\Controllers;

use App\Models\MerkStok;
use App\Models\BarangStok;
use Illuminate\Http\Request;

class BarangStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $barangs = BarangStok::all();
        return view('barang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $id)
    {
        if ($tipe === 'stok') {
            // Jika tipe adalah stok, dapatkan data BarangStok berdasarkan ID
            $stok = MerkStok::findOrFail($id);
            return view('barang.create', compact('tipe', 'id', 'stok'));
        }
        return view('barang.create', compact('tipe', 'id'));
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
        $barang = Barangstok::find($id);
        return view('barang.show', compact(var_name: 'barang'));
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
