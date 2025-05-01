<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UraianRekening extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(AktivitasSubKegiatan::class, 'aktivitas_sub_kegiatan_id');
    }
}
