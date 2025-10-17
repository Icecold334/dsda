<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class StokOpnameController extends Controller
{
    public function downloadTemplate()
    {
        $headers = ['kode', 'nama_barang', 'satuan', 'stok', 'merk'];
        $csv = implode(',', $headers) . "\n";

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="template_stok_opname.csv"');
    }
}