<?php

namespace App\Models;

use App\Models\SatuanBesar;
use App\Models\SatuanKecil;
use Illuminate\Database\Eloquent\Model;

class BarangStok extends Model
{
    protected $table = 'barang_stok';

    protected $guarded = ['$id'];

    public function jenisStok()
    {
        return $this->belongsTo(JenisStok::class, 'jenis_id');
    }

    public function satuanBesar()
    {
        return $this->belongsTo(SatuanBesar::class);
    }

    public function satuanKecil()
    {
        return $this->belongsTo(SatuanKecil::class);
    }

    public function merkStok()
    {
        return $this->hasMany(MerkStok::class, 'barang_id');
    }
}