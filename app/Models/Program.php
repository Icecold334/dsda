<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(UnitKerja::class, 'bidang_id'); // UnitKerja = model pengganti Bidang
    }

    public function children()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
