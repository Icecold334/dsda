<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListRab extends Model
{
    protected $table = 'list_rab';
    protected $guarded = ['id'];

    public function rab()
    {
        return $this->belongsTo(Rab::class, 'rab_id');
    }
    public function barangStok()
    {
        return $this->belongsTo(BarangStok::class, 'barang_id');
    }
}
