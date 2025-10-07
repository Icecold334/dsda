<?php

namespace App\Models;

use App\Models\KategoriStok;
use Illuminate\Database\Eloquent\Model;

class MerkStok extends Model
{
    protected $table = 'merk_stok';
    protected $guarded = ['id'];

    protected $appends = ['stok_gudang'];


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
    public function permintaanMaterial()
    {
        return $this->hasMany(PermintaanMaterial::class, 'merk_id');
    }
    public function transaksiStok()
    {
        return $this->hasMany(TransaksiStok::class, 'merk_id');
    }
    public function listRab()
    {
        return $this->hasMany(ListRab::class, 'merk_id');
    }

    public function kontrak()
    {
        return $this->hasMany(KontrakVendorStok::class, 'merk_id');
    }

    /**
     * Get spesifikasi attribute (combination of nama, tipe, ukuran)
     */
    public function getSpesifikasiAttribute()
    {
        $parts = array_filter([
            $this->nama,
        ]);

        return implode(' - ', $parts);
    }

    public function getStokGudangAttribute()
    {
        // Ganti dengan logic yang sesuai untuk stok gudang
        return $this->attributes['stok_gudang'] ?? 0;
    }
}
