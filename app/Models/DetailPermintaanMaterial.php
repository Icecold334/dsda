<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Persetujuan;


class DetailPermintaanMaterial extends Model
{
    protected $table = 'detail_permintaan_material';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function lokasiStok()
    {
        return $this->belongsTo(LokasiStok::class, 'gudang_id');
    }

    public function permintaanMaterial()
    {
        return $this->hasMany(PermintaanMaterial::class, 'detail_permintaan_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }
    public function lampiran()
    {
        return $this->hasMany(FotoPermintaanMaterial::class, 'detail_permintaan_id');
    }
    public function lampiranDokumen()
    {
        return $this->hasMany(LampiranPermintaan::class, 'permintaan_id');
    }

    public function persetujuan()
    {
        return $this->morphMany(Persetujuan::class, 'approvable');
    }

    public function rab()
    {
        return $this->belongsTo(Rab::class, 'rab_id');
    }
}
