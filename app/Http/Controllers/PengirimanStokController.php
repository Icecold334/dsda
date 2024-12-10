<?php

namespace App\Http\Controllers;

use App\Models\DetailPengirimanStok;
use App\Models\VendorStok;
use Illuminate\Http\Request;
use App\Models\PengirimanStok;

class PengirimanStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $datangs = PengirimanStok::orderBy('id', 'desc')->get()->groupBy('kode_pengiriman_stok');
        $datangs = DetailPengirimanStok::whereHas('user', function ($user) {
            return $user->whereHas('unitKerja', function ($unit) {
                return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });
        })->orderBy('id', 'desc')->get();
        return view('pengiriman.index', compact('datangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = VendorStok::whereHas('kontrakVendorStok', function ($query) {
            $query->where('type', true); // Adjust 'tipe' column as needed
        })->get();
        return view('pengiriman.create', compact('vendors'));
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
        // Ambil detail pengiriman berdasarkan ID yang diberikan
        $pengiriman = DetailPengirimanStok::findOrFail($id);

        // Kirim data pengiriman ke view
        return view('pengiriman.show', compact('pengiriman'));
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
