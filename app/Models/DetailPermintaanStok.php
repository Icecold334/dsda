<?php

namespace App\Models;

use App\Models\UnitKerja;
use App\Models\PermintaanStok;
use Illuminate\Database\Eloquent\Model;
use App\Models\PersetujuanPermintaanStok;

class DetailPermintaanStok extends Model
{
    protected $table = 'detail_permintaan_stok';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function unit()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }

    public function subUnit()
    {
        return $this->belongsTo(UnitKerja::class, 'sub_unit_id');
    }

    public function permintaanStok()
    {
        return $this->hasMany(PermintaanStok::class, 'detail_permintaan_id');
    }

    public function persetujuan()
    {
        return $this->hasMany(PersetujuanPermintaanStok::class, 'detail_permintaan_id');
    }
}
