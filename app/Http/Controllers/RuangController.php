<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use App\Http\Requests\StoreRuangRequest;
use App\Http\Requests\UpdateRuangRequest;

class RuangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ruang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe, $ruang = 0)
    {
        return view('ruang.create', compact('tipe', 'ruang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRuangRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ruang $ruang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ruang $ruang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRuangRequest $request, Ruang $ruang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ruang $ruang)
    {
        //
    }
}
