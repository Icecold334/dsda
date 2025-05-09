<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanMaterial extends Model
{
    protected $table = 'permintaan_material';
    protected $guarded = ['id'];

    public function detailPermintaan()
    {
        return $this->belongsTo(DetailPermintaanMaterial::class, 'detail_permintaan_id');
    }
    public function rab()
    {
        return $this->belongsTo(Rab::class, 'rab_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }
}
