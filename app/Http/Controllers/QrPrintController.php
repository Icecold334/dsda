<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use Illuminate\Http\Request;

class QrPrintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::whereNull('parent_id') // Hanya kategori utama
            ->with('children') // Ambil sub-kategori
            ->get();
        $assets = Aset::where('status', true)->get();
        return view('qrprint.index', compact('assets', 'kategoris'));
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
    public function show(Aset $aset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aset $aset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aset $aset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $aset)
    {
        //
    }
}
