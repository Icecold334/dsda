<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bidang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
