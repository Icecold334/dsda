<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListKontrakStok extends Model
{
    protected $table = 'list_vendor_stok';
    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }
    public function kontrakStok()
    {
        return $this->belongsTo(KontrakVendorStok::class, 'kontrak_id');
    }
}
