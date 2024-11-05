<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangStok extends Model
{
    protected $table = 'barang_stok';

    public function jenisStok()
    {
        return $this->belongsTo(JenisStok::class, 'jenis_id');
    }

    public function merkStok()
    {
        return $this->hasMany(MerkStok::class, 'barang_id');
    }
}
