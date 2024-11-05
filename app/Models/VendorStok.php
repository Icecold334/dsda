<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorStok extends Model
{
    protected $table = 'vendor_stok';

    public function kontrakVendorStok()
    {
        return $this->hasMany(KontrakVendorStok::class, 'vendor_id');
    }

    public function transaksiDaruratStok()
    {
        return $this->hasMany(TransaksiDaruratStok::class, 'vendor_id');
    }
}
