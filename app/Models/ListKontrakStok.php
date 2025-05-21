<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListKontrakStok extends Model
{

    protected $guarded = ['id'];

    // Relasi opsional
    public function kontrak()
    {
        return $this->belongsTo(KontrakVendorStok::class, 'kontrak_id');
    }

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }

    public function getTotalHargaAttribute()
    {
        return $this->jumlah * $this->harga;
    }
}
