<?php

namespace App\Http\Controllers;

use App\Models\aset;
use App\Models\Merk;
use App\Models\Toko;
use App\Models\Lokasi;
use App\Models\Person;
use App\Models\Kategori;
use Illuminate\Http\Request;

class AsetNonAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query awal untuk aset non-aktif
        $query = Aset::where('status', false);

        // Filter berdasarkan nama aset
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan sebab
        if ($request->filled('sebab')) {
            $query->where('alasannonaktif', $request->sebab);
        }

        // Tentukan kolom yang ingin diurutkan
        $orderBy = $request->get('order_by', 'nama'); // Default urutkan berdasarkan nama
        $orderDirection = $request->get('order_direction', 'asc'); // Default urutan menaik

        $asets = Aset::where('status', false)->get()->map(function ($aset) {
            $nilaiSekarang = $this->nilaiSekarang($aset->hargatotal, $aset->tanggalbeli, $aset->umur);
            $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
            $hargaTotal = $aset->hargatotal;
            $aset->hargatotal = $this->rupiah($hargaTotal);
            $totalPenyusutan = $hargaTotal - $nilaiSekarang;
            $aset->totalpenyusutan = $this->rupiah(abs($totalPenyusutan));
            return $aset;
        });

        $query->orderBy($orderBy, $orderDirection);

        // Ambil data hasil query
        $asets = $query->get();

        // Data tambahan untuk dropdown filter
        $kategoris = Kategori::all();

        // Return ke view dengan data filter dan hasil query
        return view('nonaktifaset.index', compact('asets', 'kategoris'));
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
