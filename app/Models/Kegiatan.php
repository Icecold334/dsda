<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function children()
    {
        return $this->hasMany(SubKegiatan::class);
    }
}
