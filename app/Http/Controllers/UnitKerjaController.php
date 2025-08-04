<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied in routes/web.php
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $unitKerja = UnitKerja::with('children')->whereNull('parent_id')->get();
        return view('unit-kerja.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipe = null, $id = 0)
    {
        if ($tipe) {
            return view('unit-kerja.create', compact('tipe', 'id'));
        }

        // Get parent units for dropdown
        $parentUnits = UnitKerja::whereNull('parent_id')->get();
        return view('unit-kerja.create-new', compact('parentUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'parent_id' => 'nullable|exists:unit_kerjas,id'
        ]);

        UnitKerja::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->route('unit-kerja.index')
            ->with('success', 'Unit kerja berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitKerja $unitKerja)
    {
        return view('unit-kerja.show', compact('unitKerja'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitKerja $unitKerja)
    {
        $parentUnits = UnitKerja::whereNull('parent_id')
            ->where('id', '!=', $unitKerja->id)
            ->get();

        return view('unit-kerja.edit', compact('unitKerja', 'parentUnits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitKerja $unitKerja)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'parent_id' => 'nullable|exists:unit_kerjas,id'
        ]);

        // Check to prevent creating a loop in the hierarchy
        if ($request->parent_id && $request->parent_id == $unitKerja->id) {
            return back()->withErrors(['parent_id' => 'Unit kerja tidak dapat menjadi parent dari dirinya sendiri']);
        }

        $unitKerja->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->route('unit-kerja.index')
            ->with('success', 'Unit kerja berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitKerja $unitKerja)
    {
        // Check if this unit has children
        if ($unitKerja->children->count() > 0) {
            return back()->with('error', 'Unit kerja ini memiliki sub-unit. Hapus sub-unit terlebih dahulu.');
        }

        $unitKerja->delete();
        return redirect()->route('unit-kerja.index')
            ->with('success', 'Unit kerja berhasil dihapus');
    }
}
