<?php

namespace App\Models;

use App\Models\DetailPengirimanStok;
use Illuminate\Database\Eloquent\Model;

class LokasiStok extends Model
{
    protected $table = 'lokasi_stok';
    protected $guarded = ['id'];

    public function transaksiStok()
    {
        return $this->hasMany(TransaksiStok::class, 'lokasi_id');
    }
    public function bagianStok()
    {
        return $this->hasMany(BagianStok::class, 'lokasi_id');
    }
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }
    public function pengirimanStok()
    {
        return $this->hasMany(PengirimanStok::class, 'lokasi_id');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class, 'lokasi_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'lokasi_id');
    }
}
