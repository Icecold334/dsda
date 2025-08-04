<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
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
    public function index(Request $request)
    {
        $query = Kecamatan::with('unitKerja')->withCount('kelurahans');

        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('kecamatan', 'like', '%' . $search . '%');
        }

        $kecamatans = $query->paginate(10)->appends($request->query());
        return view('kecamatan.index', compact('kecamatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitKerjas = \App\Models\UnitKerja::orderBy('nama')->get();
        return view('kecamatan.create', compact('unitKerjas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string|max:255',
            'unit_id' => 'nullable|exists:unit_kerja,id'
        ]);

        Kecamatan::create([
            'kecamatan' => $request->kecamatan,
            'unit_id' => $request->unit_id
        ]);

        return redirect()->route('kecamatan.index')
            ->with('success', 'Kecamatan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kecamatan $kecamatan)
    {
        $kecamatan->load(['unitKerja', 'kelurahans']);
        return view('kecamatan.show', compact('kecamatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kecamatan $kecamatan)
    {
        $unitKerjas = \App\Models\UnitKerja::orderBy('nama')->get();
        return view('kecamatan.edit', compact('kecamatan', 'unitKerjas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'kecamatan' => 'required|string|max:255',
            'unit_id' => 'nullable|exists:unit_kerja,id'
        ]);

        $kecamatan->update([
            'kecamatan' => $request->kecamatan,
            'unit_id' => $request->unit_id
        ]);

        return redirect()->route('kecamatan.index')
            ->with('success', 'Kecamatan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kecamatan $kecamatan)
    {
        // Check if this kecamatan has kelurahans
        if ($kecamatan->kelurahans->count() > 0) {
            return back()->with('error', 'Kecamatan ini memiliki kelurahan terkait. Hapus kelurahan terlebih dahulu.');
        }

        $kecamatan->delete();
        return redirect()->route('kecamatan.index')
            ->with('success', 'Kecamatan berhasil dihapus');
    }
}
