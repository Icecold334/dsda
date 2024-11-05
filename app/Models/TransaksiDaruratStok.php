<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDaruratStok extends Model
{
    protected $table = 'transaksi_darurat_stok';

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }

    public function vendorStok()
    {
        return $this->belongsTo(VendorStok::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
