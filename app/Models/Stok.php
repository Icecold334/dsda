<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }

    public function lokasiStok()
    {
        return $this->belongsTo(LokasiStok::class, 'lokasi_id');
    }
}
