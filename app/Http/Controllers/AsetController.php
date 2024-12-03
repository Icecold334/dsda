<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Merk;
use App\Models\Toko;
use App\Models\Lokasi;
use App\Models\Person;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\View\Components\Ask;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\History;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan query untuk aset aktif
        $query = $this->getAsetQuery();

        // Terapkan filter jika ada parameter request
        if ($request->hasAny(['nama', 'kategori_id', 'merk_id', 'toko_id', 'penanggung_jawab_id', 'lokasi_id'])) {
            $query = $this->applyFilters($query, $request);
        }

        // Terapkan sorting berdasarkan parameter request
        if ($request->hasAny(['order_by', 'order_direction'])) {
            // Gunakan query default atau query yang lebih kompleks jika sorting berdasarkan riwayat
            $query = $this->applySorting($query, $request);
        }

        // Ambil data hasil query
        $asets = $query->get();

        // Proses koleksi untuk menghitung nilaiSekarang dan totalPenyusutan
        $asets = $asets->map(function ($aset) {
            $hargaTotal = floatval($aset->hargatotal);
            $nilaiSekarang = $this->nilaiSekarang($hargaTotal, $aset->tanggalbeli, $aset->umur);
            $totalPenyusutan = $hargaTotal - $nilaiSekarang;

            $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
            $aset->hargatotal = $this->rupiah($hargaTotal);
            $aset->totalpenyusutan = $this->rupiah(abs($totalPenyusutan));

            return $aset;
        });

        // Data tambahan untuk dropdown filter
        $kategoris = Kategori::all();
        $merks = Merk::all();
        $tokos = Toko::all();
        $penanggungJawabs = Person::all();
        $lokasis = Lokasi::all();

        return view('aset.index', compact('asets', 'kategoris', 'merks', 'tokos', 'penanggungJawabs', 'lokasis'));
    }

    /**
     * Mendapatkan query dasar untuk aset aktif dengan nilai sekarang dan total penyusutan
     */
    private function getAsetQuery()
    {
        return Aset::where('status', true);  // Mengambil aset dengan status aktif (true)
    }

    /**
     * Menerapkan filter berdasarkan parameter request
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('merk_id')) {
            $query->where('merk_id', $request->merk_id);
        }

        if ($request->filled('toko_id')) {
            $query->where('toko_id', $request->toko_id);
        }

        if ($request->filled('penanggung_jawab_id')) {
            $query->whereHas('histories', function ($query) use ($request) {
                $query->where('person_id', $request->penanggung_jawab_id)
                    ->latest()  // Mengambil histori terakhir
                    ->limit(1);  // Hanya ambil histori terakhir
            });
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        return $query;
    }

    /**
     * Menerapkan sorting berdasarkan parameter request
     */
    private function applySorting($query, Request $request)
    {
        // Tentukan kolom yang ingin diurutkan
        $orderBy = $request->get('order_by', 'nama'); // Default urutkan berdasarkan nama
        $orderDirection = $request->get('order_direction', 'asc'); // Default urutan menaik

        // Menambahkan pengecekan untuk 'riwayat'
        if ($orderBy === 'riwayat') {
            // Jika pengurutan berdasarkan 'riwayat', lakukan LEFT JOIN dengan tabel history
            $query = Aset::query(); // Menggunakan Aset::query() karena ini membutuhkan join dengan history
            $query->leftJoin('history', 'history.aset_id', '=', 'aset.id')
                ->selectRaw('aset.*, COUNT(history.id) as history_count')  // Hitung jumlah riwayat untuk setiap aset
                ->where('aset.status', true)  // Menyaring berdasarkan status dari tabel 'aset'
                ->groupBy('aset.id');  // Kelompokkan berdasarkan aset_id

            // Urutkan berdasarkan jumlah history
            if ($orderDirection === 'asc') {
                // Urutkan dengan aset yang belum memiliki history di atas
                $query->orderByRaw('COUNT(history.id) ASC');
            } else {
                // Urutkan dengan aset yang memiliki banyak history di atas
                $query->orderByRaw('COUNT(history.id) DESC');
            }
        } else {
            // Urutkan berdasarkan kolom lain jika bukan berdasarkan riwayat
            $query->orderBy($orderBy, $orderDirection);
        }

        return $query;
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
