<?php

namespace App\Http\Controllers;

use App\Models\BarangStok;
use App\Models\Stok;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = BarangStok::whereHas('merkStok', function ($merkQuery) {
            $merkQuery->whereHas('stok', function ($stokQuery) {
                $stokQuery->where('jumlah', '>', 0);
            });
        })->get();

        $stoks = Stok::where('jumlah', '>', 0)
            ->with('merkStok') // Ensure we load related merk information for display
            ->get()
            ->groupBy('merkStok.barang_id'); // Group stocks by barang_id

        return view('stok.index', compact('barangs', 'stoks'));
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
