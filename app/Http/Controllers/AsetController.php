<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\View\Components\Ask;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



        $asets = Aset::where('status', true)->get()->map(function ($aset) {
            $nilaiSekarang = $this->nilaiSekarang($aset->hargatotal, strtotime($aset->tanggalbeli), $aset->umur);
            $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
            $hargaTotal = $aset->hargatotal;
            $aset->hargatotal = $this->rupiah($hargaTotal);
            $aset->totalpenyusutan = $this->rupiah($hargaTotal - $nilaiSekarang);
            return $aset;
        });
        return view('aset.index', compact('asets'));
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
        $aset->hargasatuan = $this->rupiah($aset->hargasatuan);
        $aset->hargatotal = $this->rupiah($aset->hargatotal);
        return view('aset.show', compact('aset'));
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
