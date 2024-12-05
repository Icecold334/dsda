<?php

namespace App\Http\Controllers;

use App\Models\DetailPermintaanStok;
use App\Models\PermintaanStok;
use Illuminate\Http\Request;

class PermintaanStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permintaans = DetailPermintaanStok::whereHas('user', function ($user) {
            return $user->whereHas('unitKerja', function ($unit) {
                return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });
        })->orderBy('id', 'desc')->get();
        return view('permintaan.index', compact('permintaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe)
    {
        return view('permintaan.create', compact('tipe'));
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
        $permintaan = DetailPermintaanStok::find($id);
        return view('permintaan.show', compact('permintaan'));
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
