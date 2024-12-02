<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisStok extends Model
{
    protected $table = 'jenis_stok';

    public function barangStok()
    {
        return $this->hasMany(BarangStok::class, 'jenis_id');
    }
}
