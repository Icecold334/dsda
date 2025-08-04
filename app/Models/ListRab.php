<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListRab extends Model
{
    protected $table = 'list_rab';
    protected $fillable = [
        'rab_id',
        'merk_id',
        'jumlah',
        'nama_barang_override',
        'spesifikasi_override',
        'satuan_override',
        'keterangan'
    ];

    public function rab()
    {
        return $this->belongsTo(Rab::class, 'rab_id');
    }

    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }
}
