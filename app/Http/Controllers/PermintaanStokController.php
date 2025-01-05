<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjamanAset;
use App\Models\DetailPermintaanStok;
use App\Models\Kategori;
use App\Models\KategoriStok;
use App\Models\PermintaanStok;
use Illuminate\Http\Request;

class PermintaanStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tipe = null)
    {
        $jenis_id = is_null($tipe) || $tipe === 'umum' ? 3 : ($tipe === 'spare-part' ? 2 : 1);


        $permintaan = DetailPermintaanStok::where('jenis_id', $jenis_id)->whereHas('unit', function ($unit) {
            return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
        })->orderBy('id', 'desc')->get();
        $peminjaman = DetailPeminjamanAset::whereHas('unit', function ($unit) {
            return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
        })->orderBy('id', 'desc')->get();

        $permintaans = is_null($tipe) || $tipe === 'umum' ?  $permintaan->merge($peminjaman) : $permintaan;
        $kategoris = KategoriStok::all();

        return view('permintaan.index', compact('permintaans', 'tipe', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $kategori, $next = 0)
    {
        $last = null;
        if ($next !== 0) {
            $model = $tipe === 'permintaan' ? '\App\Models\DetailPermintaanStok' : '\App\Models\DetailPeminjamanAset';
            $last = app($model)::latest()->first();
        }
        $kategori = KategoriStok::where('slug', $kategori)->first();

        return view('permintaan.create', compact('tipe', 'last', 'kategori'));
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
    public function show(string $tipe = '', string $id)
    {
        $permintaan = $tipe == 'permintaan' ? DetailPermintaanStok::find($id) : DetailPeminjamanAset::find($id);
        return view('permintaan.show', compact('permintaan', 'tipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permintaan = DetailPermintaanStok::find($id);
        return view('permintaan.edit', compact('permintaan'));
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
