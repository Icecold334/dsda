<?php

namespace App\Models;

use App\Models\StokDisetujui;
use App\Models\DetailPermintaanStok;
use Illuminate\Database\Eloquent\Model;

class PermintaanStok extends Model
{
    protected $table = 'permintaan_stok';
    protected $guarded = ['id'];

    public function detailPermintaan()
    {
        return $this->belongsTo(DetailPermintaanStok::class, 'detail_permintaan_id');
    }
    public function stokDisetujui()
    {
        return $this->hasMany(StokDisetujui::class, 'permintaan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }
    public function barangStok()
    {
        return $this->belongsTo(BarangStok::class, 'barang_id');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'lokasi_id');
}
}
