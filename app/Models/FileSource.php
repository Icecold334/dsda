<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileSource extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi polimorfis
    public function approvable()
    {
        return $this->morphTo();
    }
}
