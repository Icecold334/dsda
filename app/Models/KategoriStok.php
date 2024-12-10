<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriStok extends Model
{
    protected $table = 'kategori_stok';

    protected $guarded = ['id'];
    public function barangStok()
    {
        return $this->hasMany(BarangStok::class, 'kategori_id');
    }

    public function merkStok()
    {
        return $this->hasMany(MerkStok::class, 'kategori_id');
    }
}
