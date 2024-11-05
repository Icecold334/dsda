<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosisiStok extends Model
{
    protected $table = 'posisi_stok';

    public function bagianStok()
    {
        return $this->belongsTo(BagianStok::class, 'bagian_id');
    }
}
