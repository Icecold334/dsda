<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Security extends Model
{
    /** @use HasFactory<\Database\Factories\SecurityFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }
}
