<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListRab extends Model
{
    protected $table = 'list_rab';
    protected $fillable = [
        'rab_id',
        'merk_id',
        'jumlah'
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
