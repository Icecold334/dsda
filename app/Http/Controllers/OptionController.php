<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('option.index');  //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('option.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Jabatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $option)
    {
        $formattedRole = $this->formatRoleName($option->name);
        return view('option.show', compact('option', 'formattedRole'));
    }

    private function formatRoleName($role)
    {
        switch ($role) {
            case 'superadmin':
                return 'Super Admin';
            case 'admin':
                return 'Admin';
            case 'penanggungjawab':
                return 'Penanggung Jawab';
            case 'ppk':
                return 'Pejabat Pembuat Komitmen (PPK)';
            case 'pptk':
                return 'Pejabat Pelaksana Teknis Kegiatan (PPTK)';
            default:
                return ucfirst($role); // Default to capitalize the first letter
        }
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
    public function update(Request $request, string $option)
    {
        $role = Role::find($option);
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        // Update the role name
        $role->update([
            'name' => $validated['name'],
        ]);

        // Redirect back with success message
        return redirect()->route('option.index')
            ->with('success', 'Nama jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
