<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AktivitasSubKegiatan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(SubKegiatan::class, 'sub_kegiatan_id');
    }

    public function children()
    {
        return $this->hasMany(UraianRekening::class);
    }
}
