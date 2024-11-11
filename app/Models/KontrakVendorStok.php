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
    public function super()
    {
        return $this->belongsTo(User::class, 'super_id');
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenKontrakStok::class, 'kontrak_id');
    }


    // public function merkStok()
    // {
    //     return $this->belongsTo(MerkStok::class, 'merk_id');
    // }
    // public function listKontrak()
    // {
    //     return $this->hasMany(ListKontrakStok::class, 'kontrak_id');
    // }

    public function detailPengirimanStok()
    {
        return $this->hasMany(DetailPengirimanStok::class, 'kontrak_id');
    }
    public function pengirimanStok()
    {
        return $this->hasMany(PengirimanStok::class, 'kontrak_id');
    }
    public function transaksiStok()
    {
        return $this->hasMany(TransaksiStok::class, 'kontrak_id');
    }
}
