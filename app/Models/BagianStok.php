<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BagianStok extends Model
{
    protected $table = 'bagian_stok';
    protected $guarded = ['id'];
    public function lokasiStok()
    {
        return $this->belongsTo(LokasiStok::class, 'lokasi_id');
    }

    public function posisiStok()
    {
        return $this->hasMany(PosisiStok::class, 'bagian_id');
    }
}
