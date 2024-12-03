<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';
    protected $guarded = ['guarded'];

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
