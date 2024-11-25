<?php

namespace App\Http\Controllers;

use App\Models\aset;
use Illuminate\Http\Request;

class AsetNonAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asets = Aset::where('status', false)->get()->map(function ($aset) {
            $nilaiSekarang = $this->nilaiSekarang($aset->hargatotal, $aset->tanggalbeli, $aset->umur);
            $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
            $hargaTotal = $aset->hargatotal;
            $aset->hargatotal = $this->rupiah($hargaTotal);
            $totalPenyusutan = $hargaTotal - $nilaiSekarang;
            $aset->totalpenyusutan = $this->rupiah(abs($totalPenyusutan));
            return $aset;
        });
        return view('nonaktifaset.index', compact('asets'));
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
    public function show(Aset $nonaktifaset)
    {
        $nonaktifaset->hargasatuan = $this->rupiah($nonaktifaset->hargasatuan);
        $nonaktifaset->hargatotal = $this->rupiah($nonaktifaset->hargatotal);
        // dd($nonaktifaset);
        return view('nonaktifaset.show', compact('nonaktifaset'));
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

    // public function activate($id)
    // {
    //     // Cari aset berdasarkan ID
    //     $nonaktifAset = Aset::findOrFail($id);

    //     // Perbarui status aset menjadi aktif (status = 1)
    //     $nonaktifAset->update(['status' => 1]);

    //     // Redirect atau respon JSON jika menggunakan fetch
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Aset berhasil diaktifkan.',
    //     ]);
    // }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $id)
    {
        // Pastikan data ditemukan
        $nonaktif_aset = Aset::findOrFail($id->id);

        // Hapus data
        $nonaktif_aset->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('nonaktifaset.index')->with('success', 'Aset berhasil dihapus.');
    }
}
