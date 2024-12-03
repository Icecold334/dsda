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

        // Apply filters
        $this->applyFilters($query, $request);

        // Apply sorting
        $this->applySorting($query, $request);

        // Execute the query and format the results
        $asets = $query->get()->map(function ($aset) {
            return $this->formatAset($aset);
        });

        // Proses setiap aset untuk mendapatkan data QR
        $asetqr = $asets->map(function ($aset) {
            return getAssetWithSettings($aset->id); // Menggunakan helper
        })->keyBy('id')->toArray(); // Gunakan keyBy untuk membuat key array berdasarkan ID aset

        // Data tambahan untuk dropdown filter
        $kategoris = Kategori::all();

        // Return to view with the necessary data
        return view('nonaktifaset.index', compact('asets', 'kategoris', 'asetqr'));
    }

    /**
     * Apply filters to the query based on the request parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     */
    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('sebab')) {
            $query->where('alasannonaktif', $request->sebab);
        }
    }

    /**
     * Apply sorting to the query based on the request parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     */
    protected function applySorting($query, Request $request)
    {
        $orderBy = $request->get('order_by', 'nama'); // Default to 'nama'
        $orderDirection = $request->get('order_direction', 'asc'); // Default to ascending

        $query->orderBy($orderBy, $orderDirection);
    }

    /**
     * Format the aset data to include calculated fields such as nilaiSekarang and totalPenyusutan.
     *
     * @param \App\Models\Aset $aset
     * @return \App\Models\Aset
     */
    protected function formatAset($aset)
    {
        $hargaTotal = floatval($aset->hargatotal);
        $nilaiSekarang = $this->nilaiSekarang($hargaTotal, $aset->tanggalbeli, $aset->umur);
        $totalPenyusutan = $hargaTotal - $nilaiSekarang;

        $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
        $aset->hargatotal = $this->rupiah($hargaTotal);
        $aset->totalpenyusutan = $this->rupiah(abs($totalPenyusutan));

        return $aset;
    }

    // Fungsi untuk mengambil aset dengan setting
    protected function getAssetWithSettings($asets)
    {
        // Proses setiap aset untuk mendapatkan setting yang diperlukan
        return $asets->map(function ($aset) {
            return getAssetWithSettings($aset->id); // Misal fungsi global helper yang sudah dibuat
        });
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
