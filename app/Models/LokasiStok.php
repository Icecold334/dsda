<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiStok extends Model
{
    protected $table = 'lokasi_stok';
    protected $guarded = ['id'];

    public function bagianStok()
    {
        return $this->hasMany(BagianStok::class, 'lokasi_id');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class, 'lokasi_id');
    }
}
