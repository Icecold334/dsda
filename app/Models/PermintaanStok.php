<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanStok extends Model
{
    protected $table = 'permintaan_stok';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }

    public function lokasiStok()
    {
        return $this->belongsTo(LokasiStok::class, 'lokasi_id');
    }
}
