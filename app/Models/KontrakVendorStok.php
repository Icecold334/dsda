<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakVendorStok extends Model
{
    protected $table = 'kontrak_vendor_stok';

    public function vendorStok()
    {
        return $this->belongsTo(VendorStok::class, 'vendor_id');
    }

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }

    public function pengirimanStok()
    {
        return $this->hasMany(PengirimanStok::class, 'kontrak_id');
    }
}
