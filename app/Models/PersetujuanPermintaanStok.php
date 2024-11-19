<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersetujuanPermintaanStok extends Model
{
    protected $table = 'persetujuan_permintaan_stok';
    protected $guarded = ['id'];

    public function detailPermintaan()
    {
        return $this->belongsTo(DetailPermintaanStok::class, 'detail_permintaan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
