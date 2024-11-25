<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\View\Components\Ask;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



        $asets = Aset::where('status', true)->get()->map(function ($aset) {
            $nilaiSekarang = $this->nilaiSekarang($aset->hargatotal, $aset->tanggalbeli, $aset->umur);
            $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
            $hargaTotal = $aset->hargatotal;
            $aset->hargatotal = $this->rupiah($hargaTotal);
            $totalPenyusutan = $hargaTotal - $nilaiSekarang;
            $aset->totalpenyusutan = $this->rupiah(abs($totalPenyusutan));
            return $aset;
        });
        return view('aset.index', compact('asets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $data = QrCode::size(512)
        //     ->format('png')
        //     ->errorCorrection('M')
        //     ->generate(
        //         'https://twitter.com/HarryKir',
        //     );

        // return response($data)
        //     ->header('Content-type', 'image/png');
        return view('aset.create');
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
    public function edit(Aset $aset)
    {
        return view('aset.edit', compact('aset'));
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

    public function nonaktif(Request $request, $id)
    {
        $request->validate([
            'tanggal_nonaktif' => 'required|date',
            'sebab_nonaktif' => 'required|string',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $aset = Aset::findOrFail($id);

        // Update status aset
        $aset->update([
            'status' => 0,
            'tglnonaktif' => strtotime($request->tanggal_nonaktif),
            'alasannonaktif' => $request->sebab_nonaktif,
            'ketnonaktif' => $request->keterangan,
        ]);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil dinonaktifkan.');
    }
}
