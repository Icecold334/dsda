<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoPengirimanMaterial extends Model
{
    protected $table = 'foto_pengiriman_material';
    protected $guarded = ['id'];

    public function DetailPengiriman()
    {
        return $this->belongsTo(DetailPengirimanStok::class, 'detail_pengiriman_id');
    }
}
