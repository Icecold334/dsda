<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KelurahanController extends Controller
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
        $query = Kelurahan::with('kecamatan');

        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhereHas('kecamatan', function ($q) use ($search) {
                        $q->where('kecamatan', 'like', '%' . $search . '%');
                    });
            });
        }

        $kelurahans = $query->paginate(10)->appends($request->query());
        return view('kelurahan.index', compact('kelurahans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kecamatans = Kecamatan::all();
        return view('kelurahan.create', compact('kecamatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id'
        ]);

        Kelurahan::create($request->all());

        return redirect()->route('kelurahan.index')
            ->with('success', 'Kelurahan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelurahan $kelurahan)
    {
        return view('kelurahan.show', compact('kelurahan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelurahan $kelurahan)
    {
        $kecamatans = Kecamatan::all();
        return view('kelurahan.edit', compact('kelurahan', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelurahan $kelurahan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id'
        ]);

        $kelurahan->update($request->all());

        return redirect()->route('kelurahan.index')
            ->with('success', 'Kelurahan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelurahan $kelurahan)
    {
        $kelurahan->delete();

        return redirect()->route('kelurahan.index')
            ->with('success', 'Kelurahan berhasil dihapus!');
    }
}
