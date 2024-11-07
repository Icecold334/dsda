<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengirimanStok extends Model
{
    protected $table = 'pengiriman_stok';

    protected $guarded = ['id'];

    public function kontrakVendorStok()
    {
        return $this->belongsTo(KontrakVendorStok::class, 'kontrak_id');
    }

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }

    public function lokasiStok()
    {
        return $this->belongsTo(LokasiStok::class, 'lokasi_id');
    }

    public function bagianStok()
    {
        return $this->belongsTo(BagianStok::class, 'bagian_id');
    }

    public function posisiStok()
    {
        return $this->belongsTo(PosisiStok::class, 'posisi_id');
    }
}
