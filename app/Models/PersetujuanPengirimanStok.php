<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersetujuanPengirimanStok extends Model
{
    protected $table = 'persetujuan_pengiriman_stok';
    protected $guarded = ['id'];

    public function detailPengirimanStok()
    {
        return $this->belongsTo(DetailPengirimanStok::class, 'detail_pengiriman_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
