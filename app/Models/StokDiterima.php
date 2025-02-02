<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokDiterima extends Model
{
    protected $table = 'stok_diterima';
    protected $guarded = ['id'];

    public function pengiriman()
    {
        return $this->belongsTo(PengirimanStok::class, 'pengiriman_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
