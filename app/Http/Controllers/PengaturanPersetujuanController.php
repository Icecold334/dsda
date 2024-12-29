<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengaturanPersetujuanController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('persetujuan.index');
    }
    public function edit($tipe, $jenis)
    {
        return view('persetujuan.edit', compact('tipe', 'jenis'));
    }
}
