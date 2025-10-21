<?php

namespace App\Http\Controllers;

use App\Models\DetailPengirimanStok;
use App\Models\VendorStok;
use Illuminate\Http\Request;
use App\Models\PengirimanStok;
use Illuminate\Support\Facades\DB;

class PengirimanStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $datangs = PengirimanStok::orderBy('id', 'desc')->get()->groupBy('kode_pengiriman_stok');
        $datangs = DetailPengirimanStok::whereHas('user', function ($user) {
            return $user->whereHas('unitKerja', function ($unit) {
                return $unit->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
            });
        })->orderBy('id', 'desc')->get();
        return view('pengiriman.index', compact('datangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = VendorStok::whereHas('kontrakVendorStok', function ($query) {
            $query->where('type', true); // Adjust 'tipe' column as needed
        })->get();
        return view('pengiriman.create', compact('vendors'));
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
    public function show(string $id)
    {
        // dd($id);
        // Ambil detail pengiriman berdasarkan ID yang diberikan
        $pengiriman = DetailPengirimanStok::findOrFail($id);

        // Kirim data pengiriman ke view
        return view('pengiriman.show', compact('pengiriman'));
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
        // Memulai transaksi database untuk memastikan semua operasi berhasil atau dibatalkan
        DB::beginTransaction();

        try {
            // 1. Cari data induk yang akan dihapus
            $pengiriman = DetailPengirimanStok::findOrFail($id);

            // 2. HAPUS SEMUA DATA ANAK YANG TERHUBUNG (berdasarkan model)
            // Menghapus data dari tabel 'pengiriman_stok'
            $pengiriman->pengirimanStok()->delete();

            // Menghapus data dari tabel 'persetujuan_pengiriman_stok'
            $pengiriman->persetujuan()->delete();

            // Menghapus data persetujuan polimorfik
            $pengiriman->persetujuanMorph()->delete();

            // Menghapus file foto yang terhubung
            // NOTE: Ini hanya menghapus record database, bukan file fisiknya.
            // Penanganan file fisik memerlukan logika tambahan jika diperlukan.
            $pengiriman->fotoPengirimanMaterial()->delete();

            // Menghapus file BAP polimorfik
            $pengiriman->bapfile()->delete();

            // 3. Setelah semua data anak dihapus, baru hapus data induknya
            $pengiriman->delete();

            // 4. Jika semua berhasil, simpan perubahan ke database
            DB::commit();

            return redirect()->route('pengiriman-stok.index')->with('success', 'Data pengiriman dan semua data terkait berhasil dihapus.');
        } catch (\Exception $e) {
            // 5. Jika terjadi error di salah satu langkah, batalkan semua perubahan
            DB::rollBack();

            // Kembali dengan pesan error
            return redirect()->route('pengiriman-stok.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}