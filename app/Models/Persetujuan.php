<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    protected $table = 'approvals';
    protected $fillable = ['user_id', 'role', 'is_approved', 'approvable_id', 'approvable_type'];

    // Relasi ke User (PPK atau PPTK)
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
