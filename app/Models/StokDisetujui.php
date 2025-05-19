<?php

namespace App\Models;

use App\Models\LokasiStok;
use App\Models\PermintaanStok;
use Illuminate\Database\Eloquent\Model;

class StokDisetujui extends Model
{
    protected $table = 'stok_disetujui';
    protected $guarded = ['id'];
    public function lokasiStok()
    {
        return $this->belongsTo(LokasiStok::class, 'lokasi_id');
    }
    public function bagianStok()
    {
        return $this->belongsTo(BagianStok::class, 'bagian_id');
    }
    public function posisiStok()
    {
        return $this->belongsTo(PosisiStok::class, 'posisi_id');
    }
    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }
    public function permintaan()
    {
        return $this->belongsTo(PermintaanStok::class, 'permintaan_id');
    }
    public function permintaanMaterial()
    {
        return $this->belongsTo(PermintaanMaterial::class, 'permintaan_id');
    }
}
