<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdendumListRab extends Model
{
    protected $table = 'adendum_list_rabs';
    protected $fillable = [
        'adendum_rab_id',
        'list_rab_id',
        'merk_id',
        'jumlah_lama',
        'jumlah_baru',
        'action',
    ];

    // Relasi ke Adendum RAB
    public function adendumRab()
    {
        return $this->belongsTo(AdendumRab::class, 'adendum_rab_id');
    }

    // Relasi ke List RAB asli (jika edit)
    public function listRab()
    {
        return $this->belongsTo(ListRab::class, 'list_rab_id');
    }

    // Relasi ke Merk Stok
    public function merkStok()
    {
        return $this->belongsTo(MerkStok::class, 'merk_id');
    }
}
