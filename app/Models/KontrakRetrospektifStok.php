<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakRetrospektifStok extends Model
{
    protected $table = 'kontrak_retrospektif_stok';

    public function vendorStok()
    {
        return $this->belongsTo(VendorStok::class, 'vendor_id');
    }

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }
}
