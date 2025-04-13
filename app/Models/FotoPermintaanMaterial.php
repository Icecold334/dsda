<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoPermintaanMaterial extends Model
{

    protected $guarded = ['id'];

    protected $table = 'foto_permintaan_material';

    // Relationship to KontrakVendorStok
    public function DetailPermintaan()
    {
        return $this->belongsTo(DetailPermintaanMaterial::class, 'detail_permintaan_id');
    }
}
