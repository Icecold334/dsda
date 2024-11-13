<?php

namespace App\Models;

use App\Models\User;
use App\Models\PengirimanStok;
use App\Models\KontrakVendorStok;
use Illuminate\Database\Eloquent\Model;

class DetailPengirimanStok extends Model
{
    protected $table = 'detail_pengiriman_stok';


    protected $guarded = ['id'];

    public function kontrakVendorStok()
    {
        return $this->belongsTo(KontrakVendorStok::class, 'kontrak_id');
    }
    public function pengirimanStok()
    {
        return $this->hasMany(PengirimanStok::class, 'detail_pengiriman_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function super()
    {
        return $this->belongsTo(User::class, 'super_id');
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}