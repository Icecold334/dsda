<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    /** @use HasFactory<\Database\Factories\RuangFactory> */
    use HasFactory;

    protected $table = 'ruangs';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function penanggungjawab()
    {
        return $this->belongsTo(User::class, 'pj_id');
    }

    public function detailpermintaanstok()
    {
        return $this->hasMany(DetailPermintaanStok::class, 'lokasi_id');
    }
}
