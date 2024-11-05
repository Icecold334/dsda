<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiStok extends Model
{
    protected $table = 'transaksi_stok';

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }

    public function lokasiStok()
    {
        return $this->belongsTo(LokasiStok::class, 'lokasi_id');
    }

    public function pengirimanStok()
    {
        return $this->belongsTo(PengirimanStok::class, 'pengiriman_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
