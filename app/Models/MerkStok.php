<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerkStok extends Model
{
    protected $table = 'merk_stok';

    public function barangStok()
    {
        return $this->belongsTo(BarangStok::class, 'barang_id');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class, 'merk_id');
    }

    public function kontrakVendorStok()
    {
        return $this->hasMany(KontrakVendorStok::class, 'merk_id');
    }

    public function pengirimanStok()
    {
        return $this->hasMany(PengirimanStok::class, 'merk_id');
    }
}
