<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakVendorStok extends Model
{
    protected $table = 'kontrak_vendor_stok';

    protected $guarded = ['id'];

    public function vendorStok()
    {
        return $this->belongsTo(VendorStok::class, 'vendor_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function merkStok()
    // {
    //     return $this->belongsTo(MerkStok::class, 'merk_id');
    // }
    // public function listKontrak()
    // {
    //     return $this->hasMany(ListKontrakStok::class, 'kontrak_id');
    // }

    public function pengirimanStok()
    {
        return $this->hasMany(PengirimanStok::class, 'kontrak_id');
    }
    public function transaksiStok()
    {
        return $this->hasMany(TransaksiStok::class, 'kontrak_id');
    }
}
