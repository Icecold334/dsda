<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class MasterProgramController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Check akan dilakukan di routes
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master-program.index');
    }

    /**
     * Show the form for creating a new resource.
     * 
     * Note: Create method disabled - only updates allowed
     */
    public function create()
    {
        abort(403, 'Penambahan program tidak diizinkan dari halaman ini.');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * Note: Store method disabled - only updates allowed
     */
    public function store(Request $request)
    {
        abort(403, 'Penambahan program tidak diizinkan dari halaman ini.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        $unitKerjas = UnitKerja::orderBy('nama')->get();

        return view('master-program.show', compact('program', 'unitKerjas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        $unitKerjas = UnitKerja::orderBy('nama')->get();

        return view('master-program.edit', compact('program', 'unitKerjas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $request->validate([
            'bidang_id' => 'required|exists:unit_kerja,id'
        ], [
            'bidang_id.required' => 'Unit kerja harus dipilih.',
            'bidang_id.exists' => 'Unit kerja yang dipilih tidak valid.'
        ]);

        $oldUnit = $program->parent ? $program->parent->nama : 'Belum Ditentukan';
        $newUnit = UnitKerja::find($request->bidang_id)->nama;

        $program->update([
            'bidang_id' => $request->bidang_id
        ]);

        return redirect()->route('master-program.index')
            ->with('success', "Program '{$program->program}' berhasil dipindahkan dari '{$oldUnit}' ke '{$newUnit}'");
    }

    /**
     * Remove the specified resource from storage.
     * 
     * Note: Delete method disabled - only updates allowed
     */
    public function destroy(Program $program)
    {
        abort(403, 'Penghapusan program tidak diizinkan dari halaman ini.');
    }
}
