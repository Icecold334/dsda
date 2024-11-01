<?php

// app/Models/MerekStok.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerekStok extends Model
{
    use HasFactory;

    protected $table = 'merek_stok';

    protected $fillable = [
        'barang_id',
        'nama',
        'jumlah',
        'satuan',
        'lokasi_id',
        'bagian_id',
        'posisi_id',
        'vendor_id',
        'kontrak_id',
        'stok_awal',
        'stok_sisa'
    ];

    public function barang()
    {
        return $this->belongsTo(BarangStok::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiStok::class);
    }

    public function bagian()
    {
        return $this->belongsTo(BagianStok::class);
    }

    public function posisi()
    {
        return $this->belongsTo(PosisiStok::class);
    }

    public function vendor()
    {
        return $this->belongsTo(VendorStok::class);
    }

    public function kontrak()
    {
        return $this->belongsTo(KontrakVendor::class);
    }
}
