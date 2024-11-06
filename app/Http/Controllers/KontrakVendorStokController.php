<?php

namespace App\Http\Controllers;

use App\Models\BarangStok;
use Illuminate\Http\Request;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TransaksiDaruratStok;

class KontrakVendorStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



        $transaksiDarurat = TransaksiDaruratStok::all();
        $groupedTransactions = $transaksiDarurat->groupBy(function ($item) {
            return $item->vendor_id; // First level grouping by vendor_id
        })->map(function ($vendorGroup) {
            return $vendorGroup->groupBy(function ($item) {
                return $item->kontrak_retrospektif_id ?? 'null'; // Second level grouping by kontrak_retrospektif_id
            });
        });

        $result = [];
        foreach ($groupedTransactions as $vendorId => $kontrakGroups) {
            // Only include groups where the kontrak_id is not null
            foreach ($kontrakGroups as $kontrakId => $transactions) {
                if ($kontrakId !== 'null') {
                    $result[] = [
                        'vendor_id' => $vendorId,
                        'transactions' => $transactions,
                        'kontrak_id' => $kontrakId, // This will be the actual kontrak_retrospektif_id
                        'status' => true, // Indicate that it has a kontrak
                    ];
                }
            }
        }

        // Now $result will only contain entries with valid kontrak_retrospektif_id



        return view('rekam.index', compact('result'));
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
    public function show(string $id)
    {
        //
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
