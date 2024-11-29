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


        $query = Aset::query();

        // Filter berdasarkan nama aset
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan merk
        if ($request->filled('merk_id')) {
            $query->where('merk_id', $request->merk_id);
        }

        // Filter berdasarkan toko
        if ($request->filled('toko_id')) {
            $query->where('toko_id', $request->toko_id);
        }

        // Filter berdasarkan penanggung jawab dari histori terakhir
        if ($request->filled('penanggung_jawab_id')) {
            $query->whereHas('histories', function ($query) use ($request) {
                $query->where('person_id', $request->penanggung_jawab_id)
                    ->latest() // Mengambil histori terakhir
                    ->limit(1); // Hanya ambil histori terakhir
            });
        }

        // Filter berdasarkan lokasi
        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        // Urutkan data
        $orderField = $request->get('order_field', 'riwayat');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderField, $orderDirection);

        // Tentukan kolom yang ingin diurutkan
        $orderBy = $request->get('order_by', 'nama'); // Default urutkan berdasarkan nama
        $orderDirection = $request->get('order_direction', 'asc'); // Default urutan menaik

        // Urutkan berdasarkan riwayat terakhir berdasarkan tanggal
        if ($orderBy == 'riwayat') {
            $query->addSelect([
                'last_history_aset' => History::select('aset_id')
                    ->whereColumn('aset_id', 'aset.id')
                    ->orderBy('aset_id', 'desc')
                    ->limit(1)
            ]);
            $query->orderBy('last_history_aset', $orderDirection); // Urutkan berdasarkan tanggal riwayat terakhir
        } else {
            $query->orderBy($orderBy, $orderDirection); // Urutkan berdasarkan kolom lain yang dipilih
        }

        // Ambil data hasil query
        $asets = $query->get();

        // Data tambahan untuk dropdown filter
        $kategoris = Kategori::all();
        $merks = Merk::all();
        $tokos = Toko::all();
        $penanggungJawabs = Person::all();
        $lokasis = Lokasi::all();

        $asets = Aset::where('status', true)->get()->map(function ($aset) {
            $nilaiSekarang = $this->nilaiSekarang($aset->hargatotal, $aset->tanggalbeli, $aset->umur);
            $aset->nilaiSekarang = $this->rupiah($nilaiSekarang);
            $hargaTotal = $aset->hargatotal;
            $aset->hargatotal = $this->rupiah($hargaTotal);
            $totalPenyusutan = $hargaTotal - $nilaiSekarang;
            $aset->totalpenyusutan = $this->rupiah(abs($totalPenyusutan));
            return $aset;
        });
        return view('aset.index', compact('asets', 'kategoris', 'merks', 'tokos', 'penanggungJawabs', 'lokasis'));
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
