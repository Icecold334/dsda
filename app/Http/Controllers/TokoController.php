<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil unit_id user yang sedang login
        // $userUnitId = Auth::user()->unit_id;

        // // Cari unit berdasarkan unit_id user
        // $unit = UnitKerja::find($userUnitId);

        // // Tentukan parentUnitId
        // // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        // $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        $tokos = Toko::all();
        // when($this->unit_id, function ($query) use ($parentUnitId) {
        //     $query->whereHas('user', function ($query) use ($parentUnitId) {
        //         filterByParentUnit($query, $parentUnitId);
        //     });
        // })->get();
        return view('toko.index', compact('tokos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $toko = 0)
    {
        return view('toko.create', compact('tipe', 'toko'));
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
