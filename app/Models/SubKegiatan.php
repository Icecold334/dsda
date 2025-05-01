<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubKegiatan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function children()
    {
        return $this->hasMany(AktivitasSubKegiatan::class);
    }
}
