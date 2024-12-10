<?php

namespace App\Models;

use App\Models\KategoriStok;
use Illuminate\Database\Eloquent\Model;

class MerkStok extends Model
{
    protected $table = 'merk_stok';
    protected $guarded = ['id'];

    public function barangStok()
    {
        return $this->belongsTo(BarangStok::class, 'barang_id');
    }

    public function kategoriStok()
    {
        return $this->belongsTo(KategoriStok::class, 'kategori_id');  // Optional relationship
    }
    public function stok()
    {
        return $this->hasMany(Stok::class, 'merk_id');
    }

    public function kontrakVendorStok()
    {
        return $this->hasMany(KontrakVendorStok::class, 'merk_id');
    }

    public function pengirimanStok()
    {
        return $this->hasMany(PengirimanStok::class, 'merk_id');
    }
    public function transaksiStok()
    {
        return $this->hasMany(TransaksiStok::class, 'merk_id');
    }
}
