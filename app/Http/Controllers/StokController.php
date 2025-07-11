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
    public function index($sudin = false)
    {
        return view('stok.index', ['sudin' => $sudin]);
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
        $barang = Barangstok::find($id);
        $stok = Stok::whereHas('merkStok', function ($stok) use ($id) {
            $stok->whereHas('barangStok', function ($barang) use ($id) {
                $barang->where('id', $id);
            });
        })->whereHas('lokasiStok', function ($stokQuery) {
            $stokQuery->whereHas('unitKerja', function ($unit) {
                return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });
        })->get();
        return view('stok.show', compact('barang', 'stok', 'id'));
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
    public function logBarang()
    {
        return view('stok.log');
    }
}
